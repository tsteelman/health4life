<?php

App::uses('AppModel', 'Model');

/**
 * City Model
 *
 * @property State $State
 */
class City extends AppModel {

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * 
     * @param type $requested_lat
     * @param type $requested_long
     * @param type $requested_redius in miles.
     * @param type $requested_limit
     * @return array
     */
    function getRadiusSearchResults($logged_in_user_city, $requested_redius = 1000, $requested_limit = 50) {
        $lat_long = $this->getCityLatLongPoints($logged_in_user_city);
        if (isset($lat_long) && ($lat_long[0]['City']['latitude'] != NULL) && ($lat_long[0]['City']['longitude'] != NULL)) {
            $requested_lat = $lat_long[0]['City']['latitude'];
            $requested_long = $lat_long[0]['City']['longitude'];
            $db = $this->getDataSource();
//         To search by kilometers instead of miles, replace 3959 with 6371.
            $query = "SELECT id, ( 3959 * acos( cos( radians({$requested_lat}) ) * cos( radians( `latitude` ) ) * cos( radians( `longitude` ) - radians({$requested_long}) ) + sin( radians({$requested_lat}) ) * sin( radians( `latitude` ) ) ) ) AS distance FROM `cities` HAVING distance < {$requested_redius} ORDER BY distance LIMIT 0 , {$requested_limit}";
            $nearByCites = $db->fetchAll($query);
        } else {
            $nearByCites[]['cities'] ['id'] = $logged_in_user_city;
        }
        return $nearByCites;
    }
    
    /**
     * Function to get latitide & longitude of a city
     * 
     * @param type $cityId
     * @return array
     */
    function getCityLatLongPoints($city_id) {
        $cityLatLongPoints = $this->find('all', array(
            'conditions' => array(
                "City.id" => $city_id
            ),
            'fields' => array('City.latitude', 'City.longitude', 'City.modified_date')
        ));
        return $cityLatLongPoints;
    }
    
    /**
     * Function to update the json weather data of a city
     * 
     * @param type $cityId
     * @param type $weatherInfo
     */
    public function updateWeatherData($cityId, $weatherInfo) {
        $this->id = $cityId;
        $this->set('content', $weatherInfo['content']);
        $this->set('modified_date', $weatherInfo['modifiedDate']);
        $this->save();
    }
    
    /**
     * Function to get weather data of a city in JSON format
     * 
     * @param type $cityId
     * @return string
     */
    public function getWeather($cityId) {
        $params = array(
            'conditions' => array(
                'City.id' => $cityId
            ),
            'fields' => array('content')
        );
        $json = $this->find('list', $params);
        return $json[$cityId];
    }
    
    /**
     * Function to get state & country of a city
     * 
     * @param type $cityId
     * @return array
     */ 
    public function getCityLocation($cityId) {
        $data = $this->find ( "first", array (
				'joins' => array (
						array (
								'table' => 'states',
								'alias' => 'States',
								'type' => 'LEFT',
								'conditions' => 'States.id  = City.state_id' 
						),
						array (
								'table' => 'countries',
								'alias' => 'Country',
								'type' => 'LEFT',
								'conditions' => 'Country.id = States.country_id' 
						)
						
            ),
            'conditions' => array(
                'City.id' => $cityId,
            ),
            'fields' => array('City.description', 'States.description', 'Country.short_name'),
                )
        );
        return $data;
	}

	/**
	 * Function to get the location name of a city
	 * 
	 * @param type $cityId
	 * @return string
	 */
	public function getCityLocationName($cityId) {
		$locationName = '';
		$locationRecord = $this->getCityLocation($cityId);
		if (!empty($locationRecord)) {
			$location['city'] = $locationRecord['City']['description'];
			$location['state'] = $locationRecord['States']['description'];
			$location['country'] = $locationRecord['Country']['short_name'];
			$locationName = join(', ', $location);
		}
		return $locationName;
	}

	/**
	 * Function to search the city, state, and country of all the locations 
	 * 
	 * @param string $searchTerm
	 * @return array
	 */
	public function searchLocations($searchTerm = '') {
		$this->virtualFields = array(
			'location' => "CONCAT_WS(', ', `City.description`, `States.description`, `Country.short_name`)"
		);
		$fields = array('City.id', 'location');
		$query = array(
			'joins' => array(
				array(
					'table' => 'states',
					'alias' => 'States',
					'type' => 'LEFT',
					'conditions' => 'States.id  = City.state_id'
				),
				array(
					'table' => 'countries',
					'alias' => 'Country',
					'type' => 'LEFT',
					'conditions' => 'Country.id = States.country_id'
				)
			),
			'fields' => $fields,
		);

		// search condition
		if ($searchTerm !== '' && $searchTerm !== null) {
			$searchQuery = "$searchTerm%";
			$query['conditions'] = array(
				'OR' => array(
					'City.description LIKE' => $searchQuery,
					'States.description LIKE' => $searchQuery,
					'Country.short_name LIKE' => $searchQuery,
					'location LIKE' => $searchQuery,
					"CONCAT_WS(', ', `States.description`, `Country.short_name`) LIKE" => $searchQuery
				)
			);
		}

		// return location list matching the criteria
		$locations = $this->find('list', $query);
		return $locations;
	}

	/**
	 * Function to search for city and get location
	 * 
	 * @param string $searchTerm
	 * @return array
	 */
	public function searchCityLocations($searchTerm = '') {
		$locations = array();
		if ($searchTerm !== '' && $searchTerm !== null) {
			$searchQuery = "$searchTerm%";
			$this->virtualFields = array(
				'location' => "CONCAT_WS(', ', `City.description`, `States.description`, `Country.short_name`)"
			);
			$query = array(
				'joins' => array(
					array(
						'table' => 'states',
						'alias' => 'States',
						'type' => 'INNER',
						'conditions' => 'States.id  = City.state_id'
					),
					array(
						'table' => 'countries',
						'alias' => 'Country',
						'type' => 'INNER',
						'conditions' => 'Country.id = States.country_id'
					)
				),
				'fields' => array('City.id', 'location'),
				'conditions' => array(
					'City.description LIKE' => $searchQuery
				)
			);
			$locations = $this->find('list', $query);
		}

		return $locations;
	}
}
<?php

/**
 * RadiusSearchComponent class file.
 * 
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('User', 'Model');
App::uses('Event', 'Model');
App::uses('Community', 'Model');
App::uses('City', 'Model');

/**
 * RadiusSearchComponent for Recommended friends, events, communities.
 * 
 *
 * @package 	Controller.Component
 * @category	Component 
 */
class RadiusSearchComponent extends Component {

    public function __construct() {
        $this->User = ClassRegistry::init('User');
        $this->Event = ClassRegistry::init('Event');
        $this->Community = ClassRegistry::init('Community');
        $this->City = ClassRegistry::init('City');
    }

//    public function getLatLongPoints($country = NULL, $state = NULL, $city = NULL) {
//        $address = $country."+".$state."+".$city;
//        $result = NULL;
////        $address = "India+kerala+alappuzha";
//        $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false";
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
////        curl_setopt($ch, CURLOPT_PROXYPORT, 80);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//        $response = curl_exec($ch);
//        curl_close($ch);
//        $response_a = json_decode($response);
//        $lat = $response_a->results[0]->geometry->location->lat;
//        $long = $response_a->results[0]->geometry->location->lng;
//        if($response_a->status == 'OK'){
//            $result = array(
//            'lat' => $lat,
//            'long' => $long
//        );
//        }
//        return $result;
//    }


    public function getNearByCities($logged_in_user_id = NULL, $requested_redius = 300, $requested_limit = 1000) {
		$logged_in_user_city = $this->User->getFullUserDetails($logged_in_user_id, 'id');
		$logged_in_user_city = $logged_in_user_city[0]['User']['city'];
		$cityArray = array();
		if (!is_null($logged_in_user_city) && $logged_in_user_city > 0) {
			$nearByCities = $this->City->getRadiusSearchResults($logged_in_user_city, $requested_redius, $requested_limit);
			foreach ($nearByCities as $city) {
				$cityArray[] = $city['cities'] ['id'];
			}
			if ($cityArray == NULL) {
				$cityArray[] = $logged_in_user_city;
			}
		}
		
		return $cityArray;
	}

    function getNearByUsers($logged_in_user_id = NULL, $requested_redius = 300, $requested_limit = 500) {
        $cityArray = $this->getNearByCites($logged_in_user_id, $requested_redius, $requested_limit);
        $nearByUsers = $this->User->find('all', array(
            'conditions' => array(
                "User.city" => $cityArray
            )
//            'fields' => array('User.id')
        ));
        return $nearByUsers;
    }

    function getNearByEvents($logged_in_user_id = NULL, $requested_redius = 300, $requested_limit = 500) {
        $cityArray = $this->getNearByCites($logged_in_user_id, $requested_redius, $requested_limit);
        $nearByEvents = $this->Event->find('all', array(
            'conditions' => array(
                "Event.city" => $cityArray
            )
//            'fields' => array('Event.id')
        ));
        return $nearByEvents;
    }

    function getNearByCommunities($logged_in_user_id = NULL, $requested_redius = 1000, $requested_limit = 100) {
        $cityArray = $this->getNearByCites($logged_in_user_id, $requested_redius, $requested_limit);
        $nearByCommunities = $this->Community->find('all', array(
            'conditions' => array(
                "Community.city" => $cityArray
            )
//            'fields' => array('Community.id')
        ));
        return $nearByCommunities;
    }

}

?>

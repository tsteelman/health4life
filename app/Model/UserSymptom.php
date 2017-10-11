<?php

App::uses('AppModel', 'Model');
App::import('Model', 'DiseaseSymptom');

/**
 * UserSymptom Model
 *
 */
class UserSymptom extends AppModel {

	const SEVERITY_NONE = 1;
	const SEVERITY_MILD = 2;
	const SEVERITY_MODERATE = 3;
	const SEVERITY_SEVERE = 4;
	
	public $timezone = null;
	
	public $belongsTo = array(
		'Symptom' => array(
			'className' => 'Symptom',
			'foreignKey' => 'id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/**
     * When adding a disease or updating a disease adding
     * related symptoms
     * 
     * @param type $patientId int user id
     * @param type $diseaseId int disease id
     */
    public function addPatientDiseaseSymptoms($patientId, $diseaseId) {
        $DiseaseSymptom = new DiseaseSymptom();
        $symptoms = $DiseaseSymptom->findByDiseaseId($diseaseId);

        if (!empty($symptoms['DiseaseSymptom']['symptom_ids'])) {
            $symptomsIds = explode(',', $symptoms['DiseaseSymptom']['symptom_ids']);


            foreach ($symptomsIds as $symptomsId ) {
				
				$userSymptomCount = $this->find ( 'count', array (
						'conditions' => array (
								'user_id' => $patientId,
								'symptom_id' => $symptomsId
                    )
                ));

                if ($userSymptomCount == 0) {
                    $year = Date::getCurrentYear();
                    $this->create();
                    $this->set(array(
                        'user_id' => $patientId,
                        'symptom_id' => $symptomsId,
                        'record_year' => $year
                    ));

                    $this->save(null, array('callbacks' => false, 'validate' => false));
                }
            }
        }
    }
    
    /**
     * Add a symptom to a user.
     * 
     * 
     * @param type $patientId int user id
     * @param type $symptomId int symptom Id
     */
    public function addPatientSymptoms($patientId, $symptomId, $severity = 0, $timestamp = NULL) {
                $year = Date::getCurrentYear();
                $userSymptomCount = $this->find('count', array(
                    'conditions' => array(
                        'user_id' => $patientId,
                        'symptom_id' => $symptomId,
                        'record_year' => $year
                    )
                ));

                if ($userSymptomCount == 0) {
                    
                    $this->create();
                    $this->set(array(
                        'user_id' => $patientId,
                        'symptom_id' => $symptomId,
                        'record_year' => $year
                    ));
                    
                    if ($this->save(null, array('callbacks' => false, 'validate' => false))) {
                        if ( $severity != 0 ) {                            
                            $this->addSymptomSeverity($patientId, $symptomId, $timestamp, $severity);
                        }
                        return TRUE;
                    }
                    else {
                        return FALSE;
                    }
                }
                else {
                   return FALSE; 
                }

    }
    
    /**
     * Function to add new severity reading of a symptom
     * @param int $userId
     * @param int $symptomId
     * @param int $severity
     */


    public function addSymptomSeverity($userId, $symptomId, $timestamp, $severity) {
        
        $is_value_present = false;
        
        /*
         * Remove time from timestamp
         */
        //$timestamp = strtotime(date("Y-m-d", $timestamp));
        
        /*
         * Get year from the timestamp
         * if timestamp is not defined then set current year as record year
         */          
        $yearFromDate = getdate($timestamp);
        $year = $yearFromDate['year'];

        /*
         * Get record with the specified year
         */
        $records = $this->getUserSymptomSeverity($userId, $symptomId, $year);

        /*
         * If record present then json decode its record_value
         */
        if (!empty($records)) {
            $severityRecords = json_decode($records['UserSymptom']['record_value'], TRUE);
        } else {
            $severityRecords = array();
        }        
        
        $severityRecords [$timestamp] = $severity;
        
        /*
         * Json encode the record values and last record value
         */
        $severityRecordsJson = json_encode($severityRecords);
        $latestRecordKey = max(array_keys($severityRecords));
        $latestRecordJson = json_encode(array(
            $latestRecordKey => $severityRecords [$latestRecordKey]
        ));

        $conditions = array(
            'user_id' => $userId,
            'record_year' => $year,
            'symptom_id' => $symptomId
        );

        /*
         * if id present for specified conditions, update the record
         */
        if ($this->hasAny($conditions)) {
            $id = $this->find('first', array(
                'conditions' => $conditions,
                'fields' => array(
                    'id'
                )
            ));
            $id = $id ['UserSymptom'] ['id'];

            $result = $this->updateSymptomSeverityRow($id, $severityRecordsJson, $latestRecordJson);

            // if no id present then create a new record
        } else {
            $result = $this->saveNewSymptomSeverityRow($userId, $severityRecordsJson, $symptomId, $year, $latestRecordJson);
        }

        return $result;
        
        
        
 
    }

    /**
	 * Function to update the symptom serverity with new value
	 * @param int $id
	 * @param string JSON $recordsJSON
	 * @param string JSON $latestRecordJson
	 * @return boolean
	 */
	function updateSymptomSeverityRow($id, $recordsJSON, $latestRecordJson) {
		$data = array(
				'id' => $id,
				'record_value' => $recordsJSON,
				'latest_record_value' => $latestRecordJson
		);
		if ($this->save ( $data )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Function to create a new row for symptom severity with specified year
	 *  and insert a value in that record
	 *  
	 * @param int $user_id
	 * @param json $recordsJSON :record_value
	 * @param int $symptomId
	 * @param year $record_year
	 * @param json $latestRecordJson :last updated value in that year
	 * @return boolean
	 */
	function saveNewSymptomSeverityRow($user_id, $recordsJSON, $symptomId, $record_year, $latestRecordJson) {
		$this->create();
		$data = array(
				'user_id' => $user_id,
				'symptom_id' => $symptomId,
				'record_year' => $record_year,
				'record_value' => $recordsJSON,
				'latest_record_value' => $latestRecordJson
		);
		if ($this->save ( $data )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Function to get user Symptom severity of a specified year
	 */
	public function getUserSymptomSeverity($userId, $symptomId, $year = NULL){
		
		/*
		 * If year is not specified use the current year
		 */
		if ($year == NULL) {
			$year = Date::getCurrentYear();
		}
		
		$record = $this->find('first', array(
				'conditions' => array(
						'UserSymptom.user_id' => $userId,
						'record_year' => $year,
						'symptom_id' => $symptomId
				),
				'fields' => array('record_value')
		)
		);
		return $record;
	}
	
	public function getSymptomSeverityInADay($userId, $symptomId, $time = NULL) {

        $yearFromDate = getdate($time);

        $record = $this->find('first', array(
            'conditions' => array(
                'UserSymptom.user_id' => $userId,
                'record_year' => $yearFromDate['year'],
                'symptom_id' => $symptomId
            ),
            'fields' => array('record_value')
        ));

        if (!empty($record ['UserSymptom'] ['record_value'])) {

            $recordValue = json_decode($record ['UserSymptom'] ['record_value'], TRUE);
            $newDate = date('Y-m-d', $time); //current date based on time - user local time                       

            foreach ($recordValue as $key => $record) {

               // $recTime = CakeTime::convert($key, new DateTimeZone($this->timezone));

                $recordDate = date('Y-m-d', $key);
               
                if ($recordDate == $newDate) {
                    return $record;
                }
            }
        }

        return null;
    }

    /**
	 * Function to get  user symptom ids
	 * @param int $userId
	 * @return array
	 */
	public function getUserSymptomIds($userId) {
		$userSymptoms = $this->find ( 'all', array (
				'conditions' => array (
						'UserSymptom.user_id' => $userId 
				),
				'fields' => array (
						'UserSymptom.symptom_id' 
				),
				'group' => array (
						'UserSymptom.symptom_id' 
				),
				'order' => array(
						'UserSymptom.id DESC'
				)
		) );
		
		return $userSymptoms;
	}
	
	/**
	 * Function to get symptom ids with record value
	 * @param int $userId
	 * @return array : userId, record_value
	 */
	public function getSymptomIdsWithvalue($userId){
		$userSymptoms = $this->find ( 'all', array (
				'conditions' => array (
						'UserSymptom.user_id' => $userId						
				),
				'fields' => array (
						'UserSymptom.symptom_id',
						'UserSymptom.record_value' 
				),
				'group' => array (
						'UserSymptom.symptom_id' 
				),
				'order' => array(
						'UserSymptom.id DESC'
				)
		) );
		
		return $userSymptoms;
	}
        
           /**
	 * Function to get symptom ids with record value
	 * @param int $userId
	 * @return array : userId, record_value
	 */
	public function getSymptomIdsWithLatestValue($userId){
		$userSymptoms = $this->find ( 'all', array (
				'conditions' => array (
						'UserSymptom.user_id' => $userId						
				),
				'fields' => array (
						'UserSymptom.symptom_id',
						'UserSymptom.latest_record_value' 
				),
				'group' => array (
						'UserSymptom.symptom_id' 
				),
				'order' => array(
						'UserSymptom.id DESC'
				)
		) );
		
		return $userSymptoms;
	}

	/**
	 * Function to check  record is present in a given time
	 * @param int $userId
	 * @param string $time
	 * @return boolean
	 */
	public function isRecordPresent($userId,$time = NULL){

		$yearFromDate = getdate($time);
		$year = $yearFromDate['year'];

		$records = $this->find('all', array(
				'conditions' => array(
						'UserSymptom.user_id' => $userId,
						'record_year' => $year						
				),
				'fields' => array('record_value')
		)
		);
		
		foreach ( $records as $record ) {
			$recordJSON = $record['UserSymptom']['record_value'];
			$recordValue = json_decode($recordJSON, TRUE);
                              if(!empty($recordValue)) {
                                if ( array_key_exists ( $time, $recordValue )) {
                                    return true;
                                }                            
                              }
		}
		
		return false;
	}

	
	/**	 
	 * Function to get symptom severity detils
	 * 
	 * @param int $userId
	 * @param int $symptomId
	 * @return array
	 */
	public function getSymptomSeverityDetails($userId, $symptomId){
		
		// Return value of last seven severity reading on a symptom		 		
		$symptomSeverity = array();		
		$todayTimeStamp =strtotime(CakeTime::nice(time(), $this->timezone, '%m/%d/%Y'));
		$current_year = strftime('%Y', $todayTimeStamp);		
			
		/*
		 * Get severity records of a symptom
		 */
		$symptomSeverityRecords = $this->find('all', array(
				'conditions' => array(
						'UserSymptom.user_id' => $userId,						
						'UserSymptom.symptom_id' => $symptomId
				),
				'fields' => array('UserSymptom.record_value'),
				'order' => array('UserSymptom.record_year DESC')
		)
		);		
                
		/*
		 * Loop the records for each year
		 * 
		 */
		foreach ($symptomSeverityRecords as $severityInAnYear){

			//decode the record value
			$severityValues = json_decode( $severityInAnYear ['UserSymptom']['record_value'], TRUE);
                        
//                        foreach ($severityValues as $key => $value) {
//                            $key = strtotime(CakeTime::nice($key, $this->timezone, '%m/%d/%Y'));
//                            $symptomSeverity[$key] = $value;
//                        }
                        
                        if(!empty($severityValues)){
//                                $severityValues = strtotime(CakeTime::nice($severityValues, $this->timezone, '%m/%d/%Y'));
                                
				$symptomSeverity = $symptomSeverity + $severityValues;				
			}			
		}                
		ksort( $symptomSeverity );
		return $symptomSeverity;
	}
		
	
	/**
	 * Function return a weeks timestamp array starting from today
	 *
	 * @param String $currentTime
	 * @return Array
	 */
//	private function __getLastSevenDaysTimestamp($currentTime) {
////            $start = strtotime($currentTime);
//		$week = array(strtotime(' -6 day 23:59:59', $currentTime),
//				strtotime(' -5 day', $currentTime),
//				strtotime(' -4 day', $currentTime),
//				strtotime(' -3 day', $currentTime),
//				strtotime(' -2 day', $currentTime),
//				strtotime(' -1 day', $currentTime),
//				$currentTime
//		);
//		return $week;
//	}
        
    /**
     * Function to return array of severity types.
     * 
     * @return array severity types
     */
    public function _getSeverityTypes() {
        $severityTypes = array(
            self::SEVERITY_NONE => array(
                'label' => __('None'),
                'name' => 'none'
            ),
            self::SEVERITY_MILD => array(
                'label' => __('Mild'),
                'name' => 'mild'
            ),
            self::SEVERITY_MODERATE => array(
                'label' => __('Moderate'),
                'name' => 'moderate'
            ),
            self::SEVERITY_SEVERE => array(
                'label' => __('Severe'),
                'name' => 'severe'
            )
        );
        return $severityTypes;
    }


    /**
     * Function to return array of years for filter.
     * 
     * @return array symptom year array
     */
      public function symptomHistoryFilterYears($symptomId, $userId) {
        
        $symptomFilterYears = $this->find('all', array(
                'fields' => array('UserSymptom.record_year'),
                'conditions' => array(
                    'UserSymptom.symptom_id' => $symptomId,
                    'UserSymptom.user_id' => $userId,
                    'UserSymptom.record_value !=' => array('','[]')
                ),
                'order' => array(
                    'UserSymptom.record_year' => 'DESC'
                ),
            ));
        return $symptomFilterYears;

      }
      
    /**
     * Function to get symptom severity of last 7 days
     * @param int $userId
     * @param int $symptomId
     * @return array
     */
    public function getWeeklySymptomSeverity($userId, $symptomId){
    
    	// Return value of last seven severity reading on a symptom
    	$weeklySymptomSeverity = array();

        $todayTimeStampWithTime = CakeTime::convert(time(), new DateTimeZone($this->timezone));

    	$symptomSeveriyRecords = $this->find('all', array(
    			'conditions' => array(
    					'UserSymptom.user_id' => $userId,
    					'UserSymptom.symptom_id' => $symptomId    					
    			),
    			'fields' => array('UserSymptom.record_value'),
    			'order' => array('UserSymptom.record_year DESC')
    	)
    	);
    
    	/*
    	 * Loop the records of  years
    	* 
    	*/
    	foreach ($symptomSeveriyRecords as $severityInAnYear) {
    
    		//decode the record value
    		$severityValues = json_decode( $severityInAnYear ['UserSymptom']['record_value'], TRUE);
    		if(!empty($severityValues)){
                        krsort ( $severityValues );
                
                    foreach ($severityValues as $timestamp => $severity){
                           /*
                           * If the weekly record are saved then stop the searching in last year
                           */
                           if(count($weeklySymptomSeverity) == 5){
                                   break;
                           }
                           $weeklySymptomSeverity[$timestamp] = $severityValues[$timestamp]; 
                       
                    }

    		}
    		
    	}
          ksort($weeklySymptomSeverity);
    	return $weeklySymptomSeverity;

    }
    
    /*
     * Function to get user symptoms based on user Id
     */
    public function getUserSymptomNames($userId) {
        $data = $this->find('all', array(
            'conditions' => array('UserSymptom.user_id' => $userId),
            'fields' => array('Symptom.name')
        ));
        
        $result = array();
        
        foreach ($data as $value) {
            $result[] = $value['Symptom']['name'];
        }
        return $result;
    }

	/**
	 * Function to get the list of symptoms of a user
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getUserSymptomsList($userId) {
		$query = array(
			'conditions' => array(
				'user_id' => $userId
			),
			'joins' => array(
				array('table' => 'symptoms',
					'alias' => 'Symptom',
					'type' => 'INNER',
					'conditions' => array(
						'Symptom.id = UserSymptom.symptom_id',
					)
				)
			),
			'fields' => array(
				'Symptom.id',
				'Symptom.name'
			)
		);
		return $this->find('list', $query);
	}
}
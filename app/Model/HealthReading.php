<?php

App::uses('AppModel', 'Model');
App::uses('Date', 'Utility');
App::import('Model', 'NotificationSetting');

/**
 * HealthReading Model
 *
 */
class HealthReading extends AppModel {

    const RECORD_TYPE_WEIGHT = 1;
    const RECORD_TYPE_HEIGHT = 2;
    const RECORD_TYPE_BP = 3;
    const RECORD_TYPE_TEMPERATURE = 4;
    const RECORD_TYPE_HEALTH_STATUS = 5;
    const RECORD_TYPE_BMI = 6;
    const RECORD_TYPE_GENERAL_PAIN = 7;
    const RECORD_TYPE_QUALITY_OF_LIFE = 8;
    const RECORD_TYPE_SLEEPING_HABITS = 9;
    
    const MIN_COMMON = 0;
    const MAX_WEIGHT_POUNDS = 1000;
    const MAX_WEIGHT_KG = 454; //500
    const MAX_HEIGHT_CM = 335; //1000
    const MAX_HEIGHT_FEET = 11;
    const MAX_HEIGHT_INCH = 12;
    const MAX_TEMPERATURE_F = 300;
    const MIN_TEMPERATURE_F = 33;
    const MAX_TEMPERATURE_C = 149;
    const MIN_TEMPERATURE_C = 0;
    const MAX_BP_DIASTOLIC = 200;
    const MAX_BP_SYSTOLIC = 250;
	// Tracker values
	const SEVERITY_VERY_BAD = 1;
	const SEVERITY_BAD = 2;
	const SEVERITY_NORMAL = 3;
	const SEVERITY_GOOD = 4;
	const SEVERITY_VERY_GOOD = 5;

    /**
     * Function to get the health reading of a record type for a user for the
     * current year
     *
     * @param int $userId
     * @param int $recordType
     * @return array
     */
    public function getUserCurrentYearReading($userId, $recordType) {
        $query = array(
            'conditions' => array(
                'user_id' => $userId,
                'record_type' => $recordType,
                'record_year' => Date::getCurrentYear()
            )
        );
        return $this->find('first', $query);
    }

    /**
     * Function to get the health reading value of a record type for a user for
     * the current year
     *
     * @param int $userId
     * @param int $recordType
     * @return mixed
     */
    public function getUserCurrentYearReadingValue($userId, $recordType) {
        $value = null;

        $reading = $this->getUserCurrentYearReading($userId, $recordType);
        if (!empty($reading)) {
            $value = $reading['HealthReading']['record_value'];
        }

        return $value;
    }

    /**
     * Function to check if the user has set the health status today
     *
     * @param int $userId
     * @param string $timezone
     * @return boolean
     */
    public function isHealthStatusSetToday($userId, $timezone) {
        $isHealthStatusSetToday = false;

        $recordType = self::RECORD_TYPE_HEALTH_STATUS;
        $userHealthReading = $this->getUserCurrentYearReadingValue($userId, $recordType);
        if (!is_null($userHealthReading)) {
            $readings = json_decode($userHealthReading, true);
            $readingTimeStamps = array_keys($readings);
            $lastReadingTimeStamp = end($readingTimeStamps);
            $lastReadingDate = CakeTime::format('Y-m-d', date('Y-m-d H:i:s', $lastReadingTimeStamp), false, $timezone);
            $today = Date::getCurrentDate($timezone);
            $isHealthStatusSetToday = ($today === $lastReadingDate) ? true : false;
        }

        return $isHealthStatusSetToday;
    }
	
	 /**
     * Function to check if the user has set the health status weekly
     *
     * @param int $userId
     * @param string $timezone
     * @return boolean
     */
    public function isHealthStatusSetWeekly($userId, $timezone) {
        $isHealthStatusSetWeekly = false;

        $recordType = self::RECORD_TYPE_HEALTH_STATUS;
        $userHealthReading = $this->getUserCurrentYearReadingValue($userId, $recordType);
        if (!is_null($userHealthReading)) {
            $readings = json_decode($userHealthReading, true);
            $readingTimeStamps = array_keys($readings);
            $lastReadingTimeStamp = end($readingTimeStamps);
            $lastReadingDate = new DateTime(CakeTime::format('Y-m-d', date('Y-m-d H:i:s', $lastReadingTimeStamp), false, $timezone));
            $today = new DateTime(Date::getCurrentDate($timezone));
			$interval = date_diff($lastReadingDate, $today);
            $isHealthStatusSetWeekly = ($interval->days < 7) ? true : false;
        }

        return $isHealthStatusSetWeekly;
    }

    /**
     * Function to add the current health status of the user to the health readings
     *
     * @param int $userId
     * @param int $healthStatus
     * @param string $comment
     * @return boolean
     */
    public function addUserHealthStatus($userId, $healthStatus, $comment, $postId = "") {

        // get health status reading for the year
        $recordType = self::RECORD_TYPE_HEALTH_STATUS;
        $healthReading = $this->getUserCurrentYearReading($userId, $recordType);
        $healthReadingArray = array();
        if (!empty($healthReading)) {
            $healthReadingJSON = $healthReading['HealthReading']['record_value'];
            $healthReadingArray = json_decode($healthReadingJSON, true);
        }

        // add current health status to health reading array
        $timestamp = time();
        $healthReadingArray[$timestamp] = array(
            'status' => $healthStatus,
            'comment' => $comment,
            'post_id' => $postId
        );

        // data to be saved
        $data = array(
            'record_value' => json_encode($healthReadingArray),
            'latest_record_value' => $healthStatus,
        );

        if (!empty($healthReading)) {
            // if existing,  update
            $data['id'] = $healthReading['HealthReading']['id'];
        } else {
            // create first health status entry for the year
            $this->create();
            $data['user_id'] = $userId;
            $data['record_type'] = self::RECORD_TYPE_HEALTH_STATUS;
            $data['record_year'] = Date::getCurrentYear();
        }

        return $this->save($data, false);
    }

    /**
     * Returns the latest health status of a user.
     *
     * @param array $feelingStatus
     * @return string
     */
    public function getLatestHealthStatus($userId) {
        $recordType = self::RECORD_TYPE_HEALTH_STATUS;
        $userHealthReading = $this->getUserCurrentYearReadingValue($userId, $recordType);
        if (!is_null($userHealthReading)) {
            $readings = json_decode($userHealthReading, true);
            $readings_keys = array_keys($readings);
            $lastKey = array_pop($readings_keys);
            $lastReading = $readings[$lastKey];

            $lastReadingValue = $lastReading['status'];
            $lastReadingTimeStamp = $lastKey;
            $lastReadingDate = date('Y-m-d H:i:s', $lastReadingTimeStamp);

            return array(
                'created' => $lastReadingDate,
                'health_status' => $lastReadingValue
            );
        } else {
            return null;
        }
    }

    /*     * Unused functions copied from MyHealth model* */

//	/**
//	 * Function to get the user health status list per day
//	 *
//	 * @param int $userId
//	 * @param string $timezone
//	 * @return array
//	 */
//	public function getUserHealthStatusData($userId, $timezone) {
//		$data = array();
//		$this->recursive = -1;
//		$healthData = $this->find('all', array(
//			'conditions' => array(
//				"{$this->alias}.user_id" => $userId,
//			),
//			'order' => array("{$this->alias}.created ASC"),
//		));
//		if (!empty($healthData)) {
//			foreach ($healthData as $key => $health) {
//				$created = $health['MyHealth']['created'];
//				$status = $health['MyHealth']['health_status'];
//				$createdDate = CakeTime::format('Y-m-d', $created, false, $timezone);
//				if ($key === 0) {
//					$pointStart = strtotime($created);
//				}
//				$healthStatusData[$createdDate] = $status;
//			}
//			$data['chartData'] = array_values($healthStatusData);
//			$data['pointStart'] = $pointStart;
//		}
//		return $data;
//	}
//
//	public function getTodaysStatus($userId, $timezone) {
//
//		$today = Date::getCurrentDate($timezone);
//		$offset = Date::getTimeZoneOffsetText($timezone);
//		$query = array(
//			'conditions' => array(
//				"{$this->alias}.user_id" => $userId,
//				"CONVERT_TZ({$this->alias}.created, '+00:00', '{$offset}') LIKE" => "$today%"
//			),
//			'order' => array("{$this->alias}.id" => 'DESC')
//		);
//
//		$status = $this->find('first', $query);
//
//		if (!empty($status)) {
//			return $status['MyHealth']['health_status'];
//		} else {
//			return null;
//		}
//	}
    /*     * Unused functions copied from MyHealth model END* */
    function addNewRecord($loggedInUserId = NULL, $recordType = NULL, $time = NULL, $value1 = NULL, $value2 = NULL) {
        $this->NotificationSetting = ClassRegistry::init('NotificationSetting');
        $userUnitsSettings = $this->NotificationSetting->getUnitSettings($loggedInUserId);
        $userId = $loggedInUserId;
        $updateBmi = FALSE;
        if (isset($time) && $time != NULL) {
            $yearFromDate = getdate($time);
            $year = $yearFromDate['year'];
        } else {
            $year = Date::getCurrentYear();
        }
        $is_value_present = false;
        $healthRecords = array();
        switch ($recordType) {
            case 1://weight
                //covert to pounds
                if ($userUnitsSettings['weight_unit'] == 2) {
                    $value1 = round($this->convertKilogramsToPounds($value1), 2);
                }
                $newrecord = array(
                    $time => $value1
                );
                $recordValue = $value1;
                $updateBmi = TRUE;
                break;
            case 2://height
                //covert to std(ft inch)
//              if ($userUnitsSettings['height_unit'] == 2) {
                if ($value2 == 'metric') {
                    $result = $this->convertCmtoFootInch($value1);
                    $value1 = $result['feet'];
                    $value2 = $result['inch'];
                }
                $newrecord = array(
                    $time => $value1 . '/' . $value2
                );
                $recordValue = $value1 . '/' . $value2;
                $updateBmi = TRUE;
                break;
            case 3://blood pressure

                $newrecord = array(
                    $time => $value1 . '/' . $value2
                );
                $recordValue = $value1 . '/' . $value2;
                break;
            case 4://temperature
                // validate data
                if (is_numeric($value1)) {

                    //round value
                    $value1 = round($value1, 2);

                    // check user unit settings
                    if ($userUnitsSettings ['temp_unit'] == NotificationSetting::TEMP_UNIT_CELSIUS) {
                        // convert celsius to fahrenheit
                        $value1 = $value1 * 9 / 5 + 32;
                    }

                    $newrecord = array(
                        $time => $value1
                    );
                    $recordValue = $value1;
                }
                break;
            case 7://general pain
                $newrecord = array(
                    $time => $value1
                );
                $recordValue = $value1;
                break;
            case 8://quality of life
                $newrecord = array(
                    $time => $value1
                );
                $recordValue = $value1;
                break;
            case 9://Sleeping Habits
                $newrecord = array(
                    $time => $value1
                );
                $recordValue = $value1;
                break;
        }
        $data = $this->getUserHealthValues($userId, $recordType, $year);
        if (!empty($data)) {
            $healthRecords = json_decode($data['HealthReading']['record_value'], TRUE);
        } else {
            $healthRecords = null;
        }
        if (!empty($healthRecords) && $healthRecords != NULL) {
            foreach ($healthRecords as $key => $record) {
                if ($key == $time) {
                    $is_value_present = true;
                    $healthRecords[$key] = $recordValue;
                }
            }
            if ($is_value_present != true) {
                $healthRecords[$time] = $recordValue;
            }
        } else {
            $healthRecords = $newrecord;
        }

        $conditions = array(
            'user_id' => $userId,
            'record_year' => $year,
            'record_type' => $recordType
        );
        $healthRecordsJson = json_encode($healthRecords);
//        $latestRecordJson = json_encode($newrecord);
        $latestRecordKey = max(array_keys($healthRecords));
        $latestRecordJson = json_encode(array($latestRecordKey => $healthRecords[$latestRecordKey]));
        if ($this->hasAny($conditions)) {
            $id = $this->find('first', array(
                'conditions' => $conditions,
                'fields' => array('id')
                    )
            );
            $id = $id['HealthReading']['id'];
            $result['success'] = $this->updateHealthRow($id, $healthRecordsJson, $latestRecordJson);
        } else {
            $result['success'] = $this->saveNewHealthRow($userId, $healthRecordsJson, $recordType, $year, $latestRecordJson);
        }
        if ($result['success']) {
            $result['latest_value'] = $healthRecords[$latestRecordKey];
            $result['bmi'] = $this->calculateBMI($userId);
            if ($updateBmi == TRUE) {
                $this->addBMI($userId, $result['bmi'], $year, $time);
            }
        }
        return $result;
    }

    function updateHealthRow($id, $recordsJSON, $latestRecordJson) {
        $data = array(
            'id' => $id,
            'record_value' => $recordsJSON,
            'latest_record_value' => $latestRecordJson
        );
        if ($this->save($data)) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    function saveNewHealthRow($user_id, $recordsJSON, $record_type, $record_year, $latestRecordJson) {
        $this->create();
        $data = array(
            'user_id' => $user_id,
            'record_type' => $record_type,
            'record_year' => $record_year,
            'record_value' => $recordsJSON,
            'latest_record_value' => $latestRecordJson
        );
        if ($this->save($data)) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    function getUserHealthValues($userId, $type, $year = NULL) {
        if ($year == NULL) {
            $year = Date::getCurrentYear();
        }

        $data = $this->find('first', array(
            'conditions' => array(
                'HealthReading.user_id' => $userId,
                'record_year' => $year,
                'record_type' => $type
            ),
            'fields' => array('record_value')
                )
        );
        return $data;
    }

    /*
     * can be used for types 1 to 4 as its latest values jason structure is same.
     */

    function getLatestHealthRecordValue($userId, $recordType) {
        $year = Date::getCurrentYear();
        $data = $this->find('first', array(
            'conditions' => array(
                'HealthReading.user_id' => $userId,
                'record_year' => $year,
                'record_type' => $recordType
            ),
            'fields' => array('latest_record_value')
                )
        );
        if (isset($data) && isset($data['HealthReading']['latest_record_value']) && $data['HealthReading']['latest_record_value'] != NULL) {
            $result1 = json_decode($data['HealthReading']['latest_record_value'], TRUE);
            if (is_array($result1) && !empty($result1) && isset($result1)) {
                $result = array_values($result1);
                $result = $result[0];
            } else {
                $result = $result1;
            }
        } else {
            $result = NULL;
        }
        return $result;
    }

    function convertPoundsToKilograms($pounds) {
        $kilograms = $pounds * 0.45359237;
        return $kilograms;
    }

    function convertKilogramsToPounds($kilograms) {
        $pounds = $kilograms * 2.20462262185;
        return $pounds;
    }

    function convertCmtoFootInch($cm) {

        $inches = $cm * 0.3937008;
        $feet = $inches / 12;
        $inch = (($feet - intval($feet)) * 12);
        $feet = intval($feet);
        $result = array(
            'feet' => $feet,
            'inch' => $inch
        );
        return $result;
    }

    function convertFootInchtoCm($feet, $inch) {
        $inches = $feet * 12;
        $inches += $inch;
        $cm = $inches * 2.54;
        return $cm;
    }

    function calculateBMI($userId) {
        $height = $this->getLatestHealthRecordValue($userId, 2);
        $weight = intval($this->getLatestHealthRecordValue($userId, 1));
        if ($height != NULL && $weight != NULL && $height != 0 && $weight != 0) {
            $heightArray = explode('/', $height);
            $heightInInches = ($heightArray[0] * 12) + $heightArray[1];
            $bmi = ($weight * 703) / ($heightInInches * $heightInInches);
            $bmi = round($bmi, 2);
        } else {
            $bmi = NULL;
        }

        return $bmi;
    }

    function getHealthReadingsForYear($userId, $year, $recordType) {
        $readings = $this->find('first', array(
            'conditions' => array(
                'HealthReading.user_id' => $userId,
                'record_year' => $year,
                'record_type' => $recordType
            ),
            'fields' => array('record_value')
        ));
        return $readings;
    }

    //Function to add bmi to db.
    function addBMI($userId, $bmi, $year, $time) {
        $recordType = 6;
        $newrecord = array(
            $time => $bmi
        );
        $data = $this->getUserHealthValues($userId, $recordType, $year);
        if (!empty($data)) {
            $healthRecords = json_decode($data['HealthReading']['record_value'], TRUE);
        } else {
            $healthRecords = null;
        }
        if (!empty($healthRecords) && $healthRecords != NULL) {
            $healthRecords[$time] = $bmi;
        } else {
            $healthRecords = $newrecord;
        }
        $conditions = array(
            'user_id' => $userId,
            'record_year' => $year,
            'record_type' => $recordType
        );
        $healthRecordsJson = json_encode($healthRecords);
//        $latestRecordJson = json_encode($newrecord);
        $latestRecordKey = max(array_keys($healthRecords));
        $latestRecordJson = json_encode(array($latestRecordKey => $healthRecords[$latestRecordKey]));
        if ($this->hasAny($conditions)) {
            $id = $this->find('first', array(
                'conditions' => $conditions,
                'fields' => array('id')
                    )
            );
            $id = $id['HealthReading']['id'];
            $result['success'] = $this->updateHealthRow($id, $healthRecordsJson, $latestRecordJson);
        } else {
            $result['success'] = $this->saveNewHealthRow($userId, $healthRecordsJson, $recordType, $year, $latestRecordJson);
        }
    }
	
	/**
     * Function to return array of trackerValues used in Health Tracker
     * 
     * @return array trackerValues
     */
    public function getTrackerValues() {
        $trackerValues = array(
            self::SEVERITY_VERY_BAD => array(
                'label' => __('Very Bad'),
                'name' => 'very_bad'
            ),
            self::SEVERITY_BAD => array(
                'label' => __('Bad'),
                'name' => 'bad'
            ),
            self::SEVERITY_NORMAL => array(
                'label' => __('Normal'),
                'name' => 'neutral'
            ),
            self::SEVERITY_GOOD => array(
                'label' => __('Good'),
                'name' => 'good'
            ),
			self::SEVERITY_VERY_GOOD => array(
                'label' => __('Very Good'),
                'name' => 'very_good'
            )
        );
        return $trackerValues;
    }
	
	/**
     * Function to return array of years for filter.
     * 
     * @return array tracker year array
     */
     public function trackerHistoryFilterYears($recordType, $userId) {
        
        $trackerFilterYears = $this->find('all', array(
                'fields' => array('HealthReading.record_year'),
                'conditions' => array(
                    'HealthReading.record_type' => $recordType,
                    'HealthReading.user_id' => $userId,
                    'HealthReading.record_value !=' => array('','[]')
                ),
                'order' => array(
                    'HealthReading.record_year' => 'DESC'
                ),
            ));
        return $trackerFilterYears;

      }

}

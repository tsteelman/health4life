<?php

/**
 * PostingComponent class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('HealthReading', 'Model');
App::uses('PainTracker', 'Model');
App::uses('Common', 'Utility');
App::uses('Date', 'Utility');
App::import('Controller', 'Api');
App::uses('UserPrivacySettings', 'Lib');

/**
 * PostingComponent for handling posting.
 *
 * This class is used to handle posting and related functionalities.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Controller.Component
 * @category	Component
 */
class HealthRecordsReadingComponent extends Component {

    /**
     * Constructor
     *
     * Initialises the models
     */
    public function __construct() {
        $this->User = ClassRegistry::init('User');
        $this->HealthReading = ClassRegistry::init('HealthReading');
		$this->PainTracker = ClassRegistry::init('PainTracker');
        $this->NotificationSetting = ClassRegistry::init('NotificationSetting');
    }

    /**
     * Initialises the component
     *
     * @param Controller $controller
     */
    public function initialize(Controller $controller) {
//        print_r($controller);
//        exit;
        $this->controller = $controller;
        $this->clientIp = $controller->request->clientIp();
//        $user = $controller->Auth->user();
//        $this->user = $user;
//        $this->currentUserId = $user['id'];
    }

    public function addHealthRecord($loggedInUserId, $recordType, $dateJs, $timeJs, $value1, $value2) {
        if ($recordType == 2 || $recordType >= 7) {
            $date = $dateJs;
            $timezone = $this->User->getTimezone($loggedInUserId);
        } else {

            $dateSql = Date::JSDateToMySQL($dateJs);
            $timezone = $this->User->getTimezone($loggedInUserId);
            $currentTimeStamp = CakeTime::convert(time(), new DateTimeZone($timezone));
            $currentTime = date('G:i:s', $currentTimeStamp);
//            $currentTime = '00:00:00';
            if (isset($timeJs) && $timeJs != NULL) {
                $timeSql = Date::JSTimeToMySQL($timeJs);
                $newDateSql = $dateSql . ' ' . $timeSql;
            } else {
                $newDateSql = $dateSql . ' ' . $currentTime;
            }


//            $dateSqlTimezone = CakeTime::toServer($dateSql, $timezone);
            $dateSqlTimezone = CakeTime::toServer($newDateSql, $timezone);
            $date = strtotime($dateSqlTimezone); //timestamp
        }
        $userUnitsSettings = $this->NotificationSetting->getUnitSettings($loggedInUserId);
        $validate = $this->validateHealthData($userUnitsSettings, $recordType, $value1, $value2);
        if ($validate['error'] != true) {
            $result = $this->HealthReading->addNewRecord($loggedInUserId, $recordType, $date, $value1, $value2);
            $result['latest_value_string'] = $this->getHealthReadingsWithUnit($recordType, $result['latest_value'], $loggedInUserId);
            $result['latest_updated_time'] = $this->getHealthRecordedTime($recordType, $loggedInUserId, $timezone);
        } else {
            $result['success'] = false;
            $result['error_message'] = $validate['error_message'];
        }
        return $result;
    }

    function validateHealthData($userUnitsSettings, $type, $value1, $value2) {
        $error_message = '';
        $unit = NULL;
        $error_min = false;
        $error = false;
        $result = array();
        $values = array();
        $result = array(
            'error' => false,
            'error_message' => ''
        );
        $error_message = '';
        $values[0] = floatval($value1);
        $values[1] = ($value1 != NULL ) ? floatval($value2) : NULL;
        if ($values[0] <= 0) {
            $error = true;
            $error_message = 'Please enter a value greater than 0';
        }
        switch ($type) {
            case 1:
                $unit = $userUnitsSettings['weight_unit'];
                if ($unit == 1) {
                    if ($values[0] > HealthReading::MAX_WEIGHT_POUNDS) {
                        $error = true;
                        $error_message = 'Please enter a value less than ' . HealthReading::MAX_WEIGHT_POUNDS . ' pounds';
                    }
                } else if ($unit == 2) {
                    if ($values[0] > HealthReading::MAX_WEIGHT_KG) {
                        $error = true;
                        $error_message = 'Please enter a value less than ' . HealthReading::MAX_WEIGHT_KG . ' kg';
                    }
                }
                break;
            case 2:
                $unit = $userUnitsSettings['height_unit'];
                if ($unit == 1) {
                    if ($values[0] > HealthReading::MAX_HEIGHT_FEET) {
                        $error = true;
                        $error_message = 'Please enter a value less than ' . HealthReading::MAX_HEIGHT_FEET . ' feet';
                    } elseif ($values[1] > HealthReading::MAX_HEIGHT_INCH) {
                        $error = true;
                        $error_message = 'Please enter a value less than 13 inches';
                    }
                } elseif ($unit == 2) {
                    if ($values[0] > HealthReading::MAX_HEIGHT_CM) {
                        $error = true;
                        $error_message = 'Please enter a value less than ' . HealthReading::MAX_HEIGHT_CM . ' cm.';
                    }
                }
                break;
            case 3:
                if ($values[0] > HealthReading::MAX_BP_SYSTOLIC) {
                    $error = true;
                    $error_message = 'Please enter a systolic value less than ' . HealthReading::MAX_BP_SYSTOLIC;
                } elseif ($values[1] > HealthReading::MAX_BP_DIASTOLIC) {
                    $error = true;
                    $error_message = 'Please enter a diastolic value less than ' . HealthReading::MAX_BP_DIASTOLIC;
                } elseif ($values[1] > $values[0]) {
                    $error = true;
                    $error_message = 'Systolic pressure should be greater than diastolic pressure.';
                }
                break;
            case 4:
                $unit = $userUnitsSettings['temp_unit'];
                if ($unit == NotificationSetting::TEMP_UNIT_CELSIUS) {
                    if ($values[0] <= HealthReading::MIN_TEMPERATURE_C) {
                        $error = true;
                        $error_message = 'Please enter a value greater than ' . HealthReading::MIN_TEMPERATURE_C . ' °C';
                    } elseif ($values[0] > HealthReading::MAX_TEMPERATURE_C) {
                        $error = true;
                        $error_message = 'Please enter a value less than ' . HealthReading::MAX_TEMPERATURE_C . ' °C';
                    }
                } else if ($unit == NotificationSetting::TEMP_UNIT_FAHRENHEIT) {
                    if ($values[0] <= HealthReading::MIN_TEMPERATURE_F) {
                        $error = true;
                        $error_message = 'Please enter a value greater than ' . HealthReading::MIN_TEMPERATURE_F . ' °F';
                    } elseif ($values[0] > HealthReading::MAX_TEMPERATURE_F) {
                        $error = true;
                        $error_message = 'Please enter a value less than ' . HealthReading::MAX_TEMPERATURE_F . ' °F';
                    }
                }
                break;
            case 7:
                if ($values[0] <= 0 || $values[0] > 5) {
                    $error = true;
                    $error_message = 'Some error occured. Please try again.';
                }
                break;

            case 8:
                if ($values[0] <= 0 || $values[0] > 5) {
                    $error = true;
                    $error_message = 'Some error occured. Please try again.';
                }
                break;

            case 9:
                if ($values[0] <= 0 || $values[0] > 5) {
                    $error = true;
                    $error_message = 'Some error occured. Please try again.';
                }
                break;
        }
        $result = array(
            'error' => $error,
            'error_message' => $error_message
        );
        return $result;
    }

    public function getHealthReadingsWithUnit($type = null, $value = NULL, $loggedInUserId) {
        $userUnitsSettings = $this->NotificationSetting->getUnitSettings($loggedInUserId);
        if ($value != NULL) {
            $valuesArray = explode("/", $value);
        } else {
            $valuesArray[0] = 0;
            $valuesArray[1] = 0;
        }
        switch ($type) {
            //weight
            case 1:
                // If no entries found
                if ($valuesArray[0] == 0) {
                    $result = '-';
                } else {

                    if ($userUnitsSettings['weight_unit'] == 1) {
                        $result = $valuesArray[0] . ' lbs';
                    } else {
                        $value = round($this->HealthReading->convertPoundsToKilograms($valuesArray[0]), 2);
                        $result = $value . ' Kg';
                    }
                }
                break;

            //height
            case 2:

                if ($userUnitsSettings['height_unit'] == 1) {
                    if ($valuesArray[0] == 0 && $valuesArray[1] == 0) {
                        $result = '-';
                    } else {
                        $result = $valuesArray[0] . "' " . round($valuesArray[1], 2) . '"';
                    }
                } else {
                    $value = round($this->HealthReading->convertFootInchtoCm($valuesArray[0], $valuesArray[1]), 2);
                    if ($value == 0) {
                        $result = '-';
                    } else {
                        $result = $value . ' cm';
                    }
                }
                break;

            //blood pressure
            case 3:
                // If no entries found
                if ($valuesArray[0] == 0) {
                    $result = '-';
                } else {
                    $result = $valuesArray[0] . '/' . $valuesArray[1];
                }
                break;

            // Temperature
            case 4:
                // If no entries found
                if ($valuesArray[0] == 0) {

                    $result = '-';
                } else {

                    if ($userUnitsSettings['temp_unit'] == NotificationSetting::TEMP_UNIT_CELSIUS) {

                        // convert fahrenheit to celsius
                        $valuesArray[0] = ($valuesArray[0] - 32) * 5 / 9;

                        // round value
                        $valuesArray[0] = round($valuesArray[0], 2);

                        $result = $valuesArray[0] . ' &deg;C';
                    } else {
                        // round value
                        $valuesArray[0] = round($valuesArray[0], 2);

                        $result = $valuesArray[0] . ' &deg;F';
                    }
                }
                break;
            default :
                $result = $valuesArray[0];
                break;
        }
        return $result;
    }

    /**
     * Function returns last 7 days weight info in clients preference
     *
     * @param type $userId
     * @return Array
     */
    public function getWeeklyWeightInfo($userId) {
        $settings = $this->NotificationSetting->getUnitSettings($userId);
        $timezone = $this->User->getTimezone($userId);
        $today = CakeTime::toServer(strftime("%Y-%m-%d", time()), $timezone);
        $todayTimeStamp = strtotime($today);
        $current_year = strftime('%Y', $todayTimeStamp);

        $weight_info = $this->HealthReading->getHealthReadingsForYear($userId, $current_year, HealthReading::RECORD_TYPE_WEIGHT);

        $data = array();
        if (!empty($weight_info)) {
            $need_conversion = ($settings['weight_unit'] == 1) ? false : true;
            $weight_reading = json_decode($weight_info['HealthReading']['record_value'], true);
            $data = $this->getLastSevenDaysReadings($weight_reading, $timezone, $need_conversion);
            $data['unit'] = ($settings['weight_unit'] == 1) ? "lbs" : "Kg";
        }
        return $data;
    }

    /**
     * Function return a weeks timestamp array starting from today
     *
     * @param String $currentTime
     * @return Array
     */
    private function getLastSevenDays($currentTime) {
        $week = array(strtotime(' -6 day', $currentTime),
            strtotime(' -5 day', $currentTime),
            strtotime(' -4 day', $currentTime),
            strtotime(' -3 day', $currentTime),
            strtotime(' -2 day', $currentTime),
            strtotime(' -1 day', $currentTime),
            $currentTime
        );
        return $week;
    }

    /**
     * Function returns last 7 days weight info in clients preference
     *
     * @param type $userId
     * @return Array
     */
    public function getWeeklyStatusInfo($userId) {
        $settings = $this->NotificationSetting->getUnitSettings($userId);
        $timezone = $this->User->getTimezone($userId);
        $todayTimeStamp = time();
        $current_year = strftime('%Y', $todayTimeStamp);

        $week = $this->getLastSevenDays($todayTimeStamp);

        $status = $this->HealthReading->getHealthReadingsForYear($userId, $current_year, HealthReading::RECORD_TYPE_HEALTH_STATUS);

        $data = array();
        $temp = array();
        if (!empty($status)) {
            $status_readings = json_decode($status['HealthReading']['record_value'], true);
            $previous_value = 0;

            foreach ($status_readings as $key => $readings) {
                if ($key < $todayTimeStamp && $key > $week[0]) {
                    $temp['status'][$key] = $readings['status'];
                }
            }
            if (!empty($temp['status'])) {
                ksort($temp['status']);
                foreach ($temp['status'] as $key => $value) {
                    $date = CakeTime::format($key, "%B %d", false, $timezone);
                    $data['status'][$date] = $value;
                }
            }
        }
        return $data;
    }

    /**
     * Function returns last 7 days BP info
     *
     * @param Integer $userId
     * @return Array
     */
    public function getWeeklyBloodPressureInfo($userId) {
        $settings = $this->NotificationSetting->getUnitSettings($userId);
        $timezone = $this->User->getTimezone($userId);
        $current_year = strftime('%Y', time());

        $status = $this->HealthReading->getHealthReadingsForYear($userId, $current_year, HealthReading::RECORD_TYPE_BP);

        $data = array();
        if (!empty($status)) {
            $bp_readings = json_decode($status['HealthReading']['record_value'], true);
            $data = $this->getLastSevenDaysReadings($bp_readings, $timezone);
        }
        return $data;
    }

    /**
     * Function returns last 7 days Temperature info in clients preference
     *
     * @param Integer $userId
     * @return Array
     */
    public function getWeeklyTempInfo($userId) {
        $settings = $this->NotificationSetting->getUnitSettings($userId);
        $timezone = $this->User->getTimezone($userId);
        $todayTimeStamp = time();
        $current_year = strftime('%Y', $todayTimeStamp);
        $temp_readings = $this->HealthReading->getHealthReadingsForYear($userId, $current_year, HealthReading::RECORD_TYPE_TEMPERATURE);

        $data = array();
        if (!empty($temp_readings)) {
            $need_conversion = ($settings['temp_unit'] == 2) ? false : true;
            $readings = json_decode($temp_readings['HealthReading']['record_value'], true);
            $data = $this->getLastSevenDaysReadings($readings, $timezone, '', $need_conversion);
            $data['unit'] = ($settings['temp_unit'] == 2) ? "F" : "C";
        }
        return $data;
    }

    /**
     * Function to get last seven days readings
     *
     * @param Array $readings
     * @param type $timezone
     * @return Array
     */
    private function getLastSevenDaysReadings($readings, $timezone, $convert_weight = false, $convert_temp = false) {
        $todayTimeStamp = time();
        $current_year = strftime('%Y', $todayTimeStamp);
        $temp = $sorted_array = array();
        $week = $this->getLastSevenDays($todayTimeStamp);

        foreach ($readings as $key => $reading) {
            if ($key < $todayTimeStamp && $key > $week[0]) {
                if ($convert_weight) {
                    $reading = $reading * 0.45359237;
                } else if ($convert_temp) {
                    $reading = round((($reading - 32) * 5 / 9), 2);
                }

                $temp['reading'][$key] = $reading;
            }
        }
        // sort array
        if (!empty($temp['reading'])) {
            ksort($temp['reading']);
            //format array keys to date 
            foreach ($temp['reading'] as $key => $value) {
                $date = CakeTime::format($key, "%B %d", false, $timezone);
                $sorted_array['reading'][$date] = $value;
            }
        }
        return $sorted_array;
    }

    /**
     * This Function retreives complete Health Readings
     *
     * @param type $param
     */
    public function getHealthReadings($userId) {
        $settings = $this->NotificationSetting->getUnitSettings($userId);
        $timezone = $this->User->getTimezone($userId);

        $health_info = $this->HealthReading->find('all', array(
            'conditions' => array(
                'HealthReading.user_id' => $userId
            ),
            'fields' => array('record_type', 'record_value')
        ));

        $data = array();
        $temp = array();
        if (!empty($health_info)) {
            $weight_info = $status_info = $bp_info = $bmi_info = $temp_info = $life_quality_info = $pain_info = $sleep_info = array();

            foreach ($health_info as $readings) {
                switch ($readings['HealthReading']['record_type']) {
                    case HealthReading::RECORD_TYPE_WEIGHT:
                        $weight_info[] = json_decode($readings['HealthReading']['record_value'], true);
                        break;
                    case HealthReading::RECORD_TYPE_HEALTH_STATUS;
                        $status_info[] = json_decode($readings['HealthReading']['record_value'], true);
                        break;
                    case HealthReading::RECORD_TYPE_BP;
                        $bp_info[] = json_decode($readings['HealthReading']['record_value'], true);
                        break;
                    case HealthReading::RECORD_TYPE_TEMPERATURE;
                        $temp_info[] = json_decode($readings['HealthReading']['record_value'], true);
                        break;
                    case HealthReading::RECORD_TYPE_BMI:
                        $bmi_info[] = json_decode($readings['HealthReading']['record_value'], true);
                        break;
                    case HealthReading::RECORD_TYPE_GENERAL_PAIN:
                        $pain_info[] = json_decode($readings['HealthReading']['record_value'], true);
                        break;
                    case HealthReading::RECORD_TYPE_QUALITY_OF_LIFE:
                        $life_quality_info[] = json_decode($readings['HealthReading']['record_value'], true);
                        break;
                    case HealthReading::RECORD_TYPE_SLEEPING_HABITS:
                        $sleep_info[] = json_decode($readings['HealthReading']['record_value'], true);
                        break;
                }
            }

            $weight_reading = $status_readings = $bp_readings = $temp_readings = $bmi_readings = $pain_readings = $life_quality_readings = $sleep_readings = array();
            foreach ($weight_info as $info) {
                $weight_reading = $weight_reading + $info;
            }
            foreach ($status_info as $info) {
                $status_readings = $status_readings + $info;
            }
            foreach ($bp_info as $info) {
                $bp_readings = $bp_readings + $info;
            }
            foreach ($temp_info as $info) {
                $temp_readings = $temp_readings + $info;
            }
            foreach ($bmi_info as $info) {
                $bmi_readings = $bmi_readings + $info;
            }
            foreach ($pain_info as $info) {
                $pain_readings = $pain_readings + $info;
            }
            foreach ($life_quality_info as $info) {
               $life_quality_readings = $life_quality_readings + $info;
            }
            foreach ($sleep_info as $info) {
                $sleep_readings = $sleep_readings + $info;
            }

            if (!empty($weight_reading)) {
                foreach ($weight_reading as $key => $readings) {
                    $weight = ($settings['weight_unit'] == 1) ? $readings : $readings * 0.45359237;
                    $usertime = CakeTime::convert($key, $timezone);
                    $temp['weight'][$usertime * 1] = round($weight, 2);
                }
                ksort($temp['weight']);
            }

            if (!empty($status_readings)) {
                foreach ($status_readings as $key => $readings) {
                    $usertime = CakeTime::convert($key, $timezone);
                    $temp['status'][$usertime * 1] = $readings['status'];
                }
                ksort($temp['status']);
            }

            if (!empty($bp_readings)) {
                foreach ($bp_readings as $key => $readings) {
                    $usertime = CakeTime::convert($key, $timezone);
                    $temp['bp'][$usertime * 1] = $readings;
                }
                ksort($temp['bp']);
            }

            if (!empty($temp_readings)) {
                foreach ($temp_readings as $key => $readings) {
                    $usertime = CakeTime::convert($key, $timezone);
                    $temperature = ($settings['temp_unit'] == 1) ? round((($readings - 32) * 5 / 9), 2) : $readings;
                    $temp['temp'][$usertime * 1] = $temperature;
                }
                ksort($temp['temp']);
            }

            if (!empty($bmi_readings)) {
                foreach ($bmi_readings as $key => $readings) {
                    $usertime = CakeTime::convert($key, $timezone);
                    $bmi = $readings;
                    $temp['bmi'][$usertime * 1] = $bmi;
                }
                ksort($temp['bmi']);
            }
            
            if (!empty($pain_readings)) {
                foreach ($pain_readings as $key => $readings) {
                    $usertime = CakeTime::convert($key, $timezone);
                    $pain = $readings;
                    $temp['pain_tracker'][$usertime * 1] = $pain;
                }
                ksort($temp['pain_tracker']);
            }
            if (!empty($life_quality_readings)) {
                foreach ($life_quality_readings as $key => $readings) {
                    $usertime = CakeTime::convert($key, $timezone);
                    $life_quality = $readings;
                    $temp['life_quality_tracker'][$usertime * 1] = $life_quality;
                }
                ksort($temp['life_quality_tracker']);
            }
            if (!empty($sleep_readings)) {
                foreach ($sleep_readings as $key => $readings) {
                    $usertime = CakeTime::convert($key, $timezone);
                    $sleep = $readings;
                    $temp['sleeping_tracker'][$usertime * 1] = $sleep;
                }
                ksort($temp['sleeping_tracker']);
            }

            $data = $temp;

            $data['unit'] = ($settings['weight_unit'] == 1) ? "lbs" : "Kg";
            $data['temp_unit'] = ($settings['temp_unit'] == 2) ? "F" : "C";
        }
        return $data;
    }

    public function getHealthReadingForYear($record_type = 1, $user_id, $record_year) {
        $info = $this->HealthReading->find('first', array(
            'conditions' => array(
                'HealthReading.user_id' => $user_id,
                'HealthReading.record_year' => $record_year * 1,
                'HealthReading.record_type' => $record_type,
            ),
        ));
        return $info;
    }

    /**
     * This Function retreives last health updated time
     *
     * @param integer $recordType
     * @param integer $userId
     * @param type $userTimezone
     */
    public function getHealthRecordedTime($recordType, $userId, $userTimezone) {
        $data = $this->HealthReading->find('first', array(
            'conditions' => array(
                'HealthReading.user_id' => $userId,
                'HealthReading.record_type' => $recordType
            ),
            'fields' => array('HealthReading.latest_record_value')
                )
        );
        if (!empty($data)) {

            $value = $data['HealthReading']['latest_record_value'];
            $date = json_decode($value, true);
            if (isset($date) && !empty($date)) {
                $key = key($date);
                $content = date("m/d/Y h:i a", CakeTime::convert($key, new DateTimeZone($userTimezone)));
                if ($recordType >= 7) {
                    $latestTimeValue = CakeTime::convert($key, new DateTimeZone($userTimezone));
                    $latestTimeValueFormated = CakeTime::format($latestTimeValue, '%b %e, %Y');
                    $content = $latestTimeValueFormated;
                }

                return $content;
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
	
	/**
     * This Function retreives Pain tracker readings
     *
     * @param integer $userId
     * @param type $userTimezone
     */
	function getPainTrackerReadings($userId, $userTimezone) {

        $record_year = strftime("%Y", time());
        //$isAjax = ($this->request->is('ajax')) ? true : false;
        $bodyPartsArray = $this->PainTracker->getBodyPartsList();
        $painTypes = $this->PainTracker->getPainTypes();

        $recordsAll = $this->PainTracker->getUserAllPainRecords($userId, $record_year);
        $arrayByBodyPart = array();

        foreach ($recordsAll as $allPainData) {
            $currentPainType = $allPainData['PainTracker']['type'];
            $painDetails = json_decode($allPainData['PainTracker']['value'], TRUE);                        
            foreach ($painDetails as $bodyPart => $records) {
                foreach ($records as $time => $painData) {
                    $timeInUserTimeZone = CakeTime::convert($time, new DateTimeZone($userTimezone));
                    $allSeverity = 0;
                    $allSeverityLength = count($painData);
                    foreach ($painData as $severity) {
                        if ( ! is_null($severity['severity'])) {
                            $allSeverity += intval($severity['severity']);
                        } else {
                            $allSeverityLength --;
                        }
                    }    
                    if ( !is_null ( $allSeverity ) && $allSeverityLength > 0 && $allSeverity > 0) {
                        $arrayByBodyPart[$bodyPart][$currentPainType][$timeInUserTimeZone] = $allSeverity / $allSeverityLength;
                    } else {
                        $arrayByBodyPart[$bodyPart][$currentPainType][$timeInUserTimeZone] = NULL;//$allSeverity
                    }
                }
            }
        }

		return $arrayByBodyPart;
    }

}

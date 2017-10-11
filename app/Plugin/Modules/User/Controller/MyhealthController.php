<?php

/**
 * MyhealthController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');

/**
 * MyhealthController for the user profile
 *
 * MyhealthController is used to show the "My Health" in the profile page
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers
 */
class MyhealthController extends ProfileController {

    protected $_mergeParent = 'ProfileController';
    public $uses = array(
        'HealthSurveyForm',
        'HealthReading',
        'NotificationSetting',
        'UserSymptom',
        'Symptom',
        'PatientDisease',
        'Survey',
        'PainTracker',
        'SurveyQuestion',
		'MedicationSchedule',
		'MedicationSchedulerForm'
    );
    public $components = array('HealthRecordsReading');

    /**
     * Profile -> My Health
     */
    public function index($username = null) {
        $this->_setUserProfileData();
        $is_tile_page = TRUE; // to show both add symptom and select severity
        $isOwner = true;
        $userId = $this->Auth->user('id');
        $userTimezone = $this->Auth->user('timezone');		
        
        if (isset($this->_requestedUser['id'])) {
            $this->set('title_for_layout', $this->_requestedUser['username'] . "'s health");
        } else {
            $this->set('title_for_layout', $this->Auth->user('username') . "'s health");
        }

        if ($this->_requestedUser['id'] != $this->Auth->user('id')) {
            $userId = $this->_requestedUser['id'];
            $isOwner = false;
            $privacy = new UserPrivacySettings($this->_requestedUser['id']);
            $isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'], $this->Auth->user('id'));
            $viewSettting = array($privacy::PRIVACY_PUBLIC);
            if ($isFriend == MyFriends::STATUS_CONFIRMED) {
                array_push($viewSettting, $privacy::PRIVACY_FRIENDS);
            }
            if (!in_array($privacy->__get('view_your_health'), $viewSettting)) {
                $this->redirect(Common::getUserProfileLink($this->_requestedUser['username'], true));
            }
        }
        $userLatestBp = $this->HealthReading->getLatestHealthRecordValue($userId, 3);
        $userLatestWeight = $this->HealthReading->getLatestHealthRecordValue($userId, 1);
        $userLatestHeight = $this->HealthReading->getLatestHealthRecordValue($userId, 2);
        $userLatestTemperature = $this->HealthReading->getLatestHealthRecordValue($userId, 4);
        $userUnitsSettings = $this->NotificationSetting->getUnitSettings($this->Auth->user('id'));

        $unitSettings = $userUnitsSettings;

        $userLatestWeight = $this->HealthRecordsReading->getHealthReadingsWithUnit(1, $userLatestWeight, $userId);
        $userLatestHeight = $this->HealthRecordsReading->getHealthReadingsWithUnit(2, $userLatestHeight, $userId);
        $userLatestBp = $this->HealthRecordsReading->getHealthReadingsWithUnit(3, $userLatestBp, $userId);
        $userLatestTemperature = $this->HealthRecordsReading->getHealthReadingsWithUnit(4, $userLatestTemperature, $userId);
        $bmi = $this->HealthReading->calculateBMI($userId);
        $unitSettingsModel = 'NotificationSettingForm';

        $latestTrackerValues['pain_tracker'] = $this->HealthReading->getLatestHealthRecordValue($userId, 7);
        $latestTrackerValues['quality_of_life'] = $this->HealthReading->getLatestHealthRecordValue($userId, 8);
        $latestTrackerValues['sleeping_habits'] = $this->HealthReading->getLatestHealthRecordValue($userId, 9);
        $latestTrackerValues = json_encode($latestTrackerValues);
        $latestTrackerTime['pain_tracker'] = $this->HealthRecordsReading->getHealthRecordedTime(7, $userId, $userTimezone);
        $latestTrackerTime['quality_of_life'] = $this->HealthRecordsReading->getHealthRecordedTime(8, $userId, $userTimezone);
        $latestTrackerTime['sleeping_habits'] = $this->HealthRecordsReading->getHealthRecordedTime(9, $userId, $userTimezone);


        //set date as today

        $todayInUserTimeZone = CakeTime::convert(time(), new DateTimeZone($userTimezone));
        $date_today = CakeTime::format($todayInUserTimeZone, '%m/%d/%Y');

        // setting the last updated dates in current health tile
        $latestHealthUpdateTime = array();
        $latestHealthUpdateTime['weight'] = $this->HealthRecordsReading->getHealthRecordedTime(1, $userId, $userTimezone);
        $latestHealthUpdateTime['height'] = $this->HealthRecordsReading->getHealthRecordedTime(2, $userId, $userTimezone);
        $latestHealthUpdateTime['bp'] = $this->HealthRecordsReading->getHealthRecordedTime(3, $userId, $userTimezone);
        $latestHealthUpdateTime['temp'] = $this->HealthRecordsReading->getHealthRecordedTime(4, $userId, $userTimezone);

        //user date of birth
        $userDateOfBirth = $this->Auth->user('date_of_birth');

        /*
         * Get the disease details for survey
         */
        $myDiseases = $this->PatientDisease->getPatientDiseaseDetails($userId);
        if (isset($myDiseases)) {
            $diseaseSurvey = array();
            $medicationSurvey = array();
            foreach ($myDiseases as $disease) {
                $surveyId = $disease['Disease']['survey_id'];
                if (isset($surveyId)) {
                    $survey = $this->Survey->getSurveyDetails($surveyId);
                    $surveyType = $survey['type'];
                    $attendedQuestions = $this->Survey->getAnsweredQuestions($userId, $surveyId);
                    $questionCount = $this->SurveyQuestion->getQuestionCount($surveyId);
                    $attendedQuestionsCount = count($attendedQuestions);
                    $temp['surveyId'] = $surveyId;
                    $temp['name'] = $survey['name'];
                    $temp['surveyKey'] = $survey['surveyKey'];
                    if ($questionCount == $attendedQuestionsCount) {
                        $temp['completedStatus'] = true;
                    } else {
                        $temp['completedStatus'] = false;
                    }
                    if ($survey['status'] == true) {
                        if ($surveyType == 0) {
                            $diseaseSurvey[] = $temp;
                        } else {
                            $medicationSurvey[] = $temp;
                        }
                    }
                }
            }
            $diseaseSurvey = array_map("unserialize", array_unique(array_map("serialize", $diseaseSurvey)));
            $medicationSurvey = array_map("unserialize", array_unique(array_map("serialize", $medicationSurvey)));
        }
		
	/*
         * daily health indication
         */
		$dailyHealthIndicator = $this->__dailyHealthIndicator();
        
        /*
         * get pre defined array values 
         */
        $bodyPartsArray = json_encode($this->PainTracker->getBodyPartsList());
        $bodySubPartsArray = json_encode($this->PainTracker->getBodySubParts());
        $painTypes = $this->PainTracker->getPainTypes();
        $graphRoom = 'myhealth/'.$userId;
        /*
         * set latest pain values to display in slider.
         */
        
        $latestPainValues = array();
        $latestTimeValue = 0;
        for ($i = 1; $i <= 7; $i++) {
            $latestPainData = $this->PainTracker->getLatestPainRecords($userId, $i);

            if (isset($latestPainData ['PainTracker'] ['latest_value']) &&
                    $latestPainData ['PainTracker'] ['latest_value'] != NULL) {

                $latestPainArray = json_decode($latestPainData['PainTracker']['latest_value'], TRUE);
                foreach ($latestPainArray as $timeKey => $latestValues) {
                    foreach ($latestValues as $innerData) {
                        $innerData['pain_type'] = $i;
                        $latestPainValues[$timeKey][] = $innerData;
                    }
                }

//                $latestPainValues[$i] = $latestPainArray['severity'];
            }
//            else {
//                $latestPainValues[$i] = null;
//            }

            $latestPainData = NULL;
            $latestPainArray = NULL;
        }
        if (isset($latestPainValues) && $latestPainValues != NULL) {
            $latestRecordKey = max(array_keys($latestPainValues));
            $latestPainDataDetailArray = array();

            foreach ($latestPainValues[$latestRecordKey] as $latestPainDataDetail) {
                 if ($latestPainDataDetail ['severity'] != NULL && intval($latestPainDataDetail ['severity']) > 0) {
                    $latestPainDataDetailArray[] = $latestPainDataDetail;
                }
            }
            $latestPainDataDetails = json_encode($latestPainDataDetailArray);

            $latestTimeValue = CakeTime::convert($latestRecordKey, new DateTimeZone($userTimezone));
            $latestTimeValueFormated = CakeTime::format($todayInUserTimeZone, '%B %e, %Y');
        } else {
            $latestPainDataDetails = NULL;
            $latestTimeValueFormated = NULL;
        }

        $this->set(compact('bmi', 'isOwner', 'userLatestBp', 'userLatestWeight', 'userLatestHeight', 'userLatestTemperature', 'temp_unit', 'date_today', 'time_in_timezone', 'userWeightUnit', 'userHeightUnit', 'userTemperatureUnit', 'unitSettings', 'userDateOfBirth', 'dailyHealthIndicator', 'bodySubPartsArray', 'painTypes', 'bodyPartsArray', 'latestPainDataDetails', 'latestTimeValueFormated', 'diseaseSurvey', 'medicationSurvey', 'is_HealthIndicatorMoreBtn', 'latestHealthUpdateTime', 'is_tile_page', 'unitSettingsModel', 'latestTrackerValues','latestTrackerTime','graphRoom'));
		$this->JQValidator->addValidation('HealthSurveyForm', $this->HealthSurveyForm->validate, 'healthSurveyForm');
		$this->__setMedicationTileData();
	}

	/**
	 * Function to set medication tile data
	 */
	private function __setMedicationTileData() {
		if ($this->_requestedUser['id'] === $this->Auth->user('id')) {
			$showMedicationTile = true;
			$this->__addMedicationSchedulerFormValidation();
			$timezone = $this->Auth->user('timezone');
			$date = Date::getCurrentDate($timezone);
			$data = $this->__getMedicationDataOnDate($date);
			$this->set($data);
		} else {
			$showMedicationTile = false;
		}
		$this->set(compact('showMedicationTile'));
	}

	/**
	 * Function to add validation for medication scheduler form
	 */
	private function __addMedicationSchedulerFormValidation() {
		$model = 'MedicationSchedulerForm';
		$validations = $this->$model->validate;
		$this->JQValidator->addValidation($model, $validations, 'medication_scheduler_form');
	}

	/**
	 * Function to get logged in user medication data on date
	 */
	private function __getMedicationDataOnDate($date) {
		$userId = $this->Auth->user('id');
		$timezone = $this->Auth->user('timezone');
		$result = $this->MedicationSchedule->getUserMedicationDataOnDate($userId, $date, $timezone);
		return $result;
	}

	/**
	 * Function to get the medications for logged in user on a specified date
	 */
	public function getMedicationsOnDate() {
		$this->autoRender = false;
		$date = $this->request->data['date'];
		$data = $this->__getMedicationDataOnDate($date);
		$View = new View($this, false);
		$content = $View->element('User.Scheduler/medication_schedules', $data);
		echo $content;
		exit();
	}

	/**
	 * Function to get Daily Health Indicators widget.
	 *  
	 * @return type array
	 */
	private function __dailyHealthIndicator() {
				
		$userTimezone = $this->Auth->user('timezone');
		
		if ($this->_requestedUser['id'] != $this->Auth->user('id')) {
            $userId = $this->_requestedUser['id'];            
		} else {
			$userId = $this->Auth->user('id');			
		}
		  //set date as today

        $todayInUserTimeZone = CakeTime::convert(time(), new DateTimeZone($userTimezone));
        $date_today = CakeTime::format($todayInUserTimeZone, '%m/%d/%Y');
		
        $dailyHealthIndicator = array();
        // it contains symptoms with latest_record_value null
        $userSymptoms = $this->UserSymptom->getSymptomIdsWithLatestValue($userId);

        /*
         * Fetch symptom details 
         */
        foreach ($userSymptoms as $userSymptom) {
            $symptomId = $userSymptom ['UserSymptom'] ['symptom_id']; // symptom id
            $symptomName = $this->Symptom->getSymptomNameFromId($symptomId); // symptom name
            $symptomRecordValueJSON = $userSymptom ['UserSymptom'] ['latest_record_value']; // recorded value
            $symptomSeverityValue = 0;
            $lastUpdatedDate = NULL;
            if (!empty($symptomRecordValueJSON)) {
                $symptomLatestRecordValue = json_decode($symptomRecordValueJSON, TRUE); // Decode the json value
                $userTodayDate = date('Y-m-d', $todayInUserTimeZone);
                $lastUpdatedDateInTime = key($symptomLatestRecordValue);

                if (!is_null($lastUpdatedDateInTime)) {
                    $symptomSeverityValue = $symptomLatestRecordValue [$lastUpdatedDateInTime];
                    $lastUpdatedDate = date('m/d/Y', $lastUpdatedDateInTime);
                }
//                debug($lastUpdatedDate);
                // check todays severity added
//                if ( array_key_exists ( strtotime ( $userTodayDate ) , $symptomLatestRecordValue )) {
//                    $symptomSeverityValue = $symptomLatestRecordValue[ strtotime ( $userTodayDate ) ];
//                }
//                foreach ($symptomRecordValue as $key => $value) {
//                    //$record_date = date('Y-m-d', $key);
//                    //debug($symptomName." ".date('Y-m-d H:i:s', $key) . "  ".  strtotime($userTodayDate));
//                    //check if there is record for user's today
//                    if ($record_date == $userTodayDate) {       //debug($key. " ".strtotime($userTodayDate));              //   debug($record_date. " ".$userTodayDate);                       
//                        //fetch todays reading
//                        $symptomSeverityValue = $symptomRecordValue [$key];
//                    }
////                                else {
////					$symptomSeverityValue = 0;
////				}
//                }
            }

            switch ($symptomSeverityValue) {
                case 1: $symptomSeverity = 'None';
                    break;
                case 2: $symptomSeverity = 'Mild';
                    break;
                case 3: $symptomSeverity = 'Moderate';
                    break;
                case 4: $symptomSeverity = 'Severe';
                    break;
                default:$symptomSeverity = 'No Data';
            }
            /*
             * save details to an array
             */
            $dailyHealthIndicator[] = array('id' => $symptomId, 'name' => $symptomName,
                'severity' => $symptomSeverity, 'lastUpdated' => $lastUpdatedDate);
        }
		
		return $dailyHealthIndicator; 

	}

	public function getDailyHealthWidget(){
		$this->autoRender = false;
		$this->_setUserProfileData();
		$View = new View($this, false);
		
		if ($this->_requestedUser['id'] != $this->Auth->user('id')) {
            $userId = $this->_requestedUser['id'];
            $isOwner = false;
		} else {
			$userId = $this->Auth->user('id');
			$isOwner = true;
		}

		/*
         * daily health indication
         */
		$dailyHealthIndicator = $this->__dailyHealthIndicator();
		
		$response = $View->element('User.Myhealth/health_indicator_widget',
				array(
					'dailyHealthIndicator' => $dailyHealthIndicator,					
					'isOwner' => $isOwner
				));
		echo $response;
	}
}
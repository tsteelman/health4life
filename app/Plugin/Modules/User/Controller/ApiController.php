<?php

/**
 * ApiController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AuthComponent', 'Controller/Component');
App::uses('UserAppController', 'User.Controller');
App::uses('Controller', 'Controller');
App::import('Controller', 'Api');
App::uses('Validation', 'Utility');
App::uses('Common', 'Utility');
App::uses('CakeTime', 'Utility');
App::uses('Date', 'Utility');
App::uses('FollowingPage', 'Model');

/**
 * ApiController for the application.
 *
 * ApiController is used for API calls common to frontend and backend.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	app.Controller
 * @category	Controllers
 */
class ApiController extends Controller {

    const WEIGHT_GRAPH = 1;
    const HEALTH_STATUS_GRAPH = 2;
    const BP_GRAPH = 3;
    const TEMP_GRAPH = 4;

    /**
     * Models used by this controller
     *
     * @var array
     */
    public $uses = array(
        'User',
        'HealthReading',
        'UserSymptom',
        'Symptom',
        'NotificationSetting',
        'FollowingPage',
        'PainTracker'
    );
    public $components = array('HealthRecordsReading', 'Session', 'PainTracking');

    /**
     * Array to store API output data
     *
     * @var array
     */
    public $data = array();

    /**
     * Disable auto render
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->autoRender = false;
    }

    /**
     * Output JSON data
     */
    public function afterFilter() {
        parent::afterFilter();
        echo json_encode($this->data);
    }

    /**
     * Temporary function to add friends
     *
     * (Will be deleted when friends functionality is in place)
     */
    public function addHealthRecord() {
        $value1 = NULL;
        $value2 = NULL;
        $loggedInUserId = $this->Session->read('Auth.User.id');

        if (isset($this->request->data['date']) && $this->request->data['date'] != NULL) {
            $dateJs = $this->request->data['date'];
        } else {
            $dateJs = time();//strtotime("today");
        }
        if (isset($this->request->data['time']) && $this->request->data['time'] != NULL) {
            $timeJs = $this->request->data['time'];
        } else {
            $timeJs = NULL;
        }

        $recordType = $this->request->data['type'];
        $value1 = $this->request->data['value1'];
        if (isset($this->request->data['value2']) && $this->request->data['value2'] != NULL) {
            $value2 = $this->request->data['value2'];
        }

        $result = $this->HealthRecordsReading->addHealthRecord($loggedInUserId, $recordType, $dateJs, $timeJs, $value1, $value2);
        $this->data = $result;
    }

    public function addPainTrackerValues() {
        $save_pain_array = $this->request->data['save_pain_array'];
        $last_pain_array = $this->request->data['last_pain_array'];
        $timestamp = time();
        $loggedInUserId = $this->Session->read('Auth.User.id');
        $result = $this->PainTracking->addPainTracking($save_pain_array, $last_pain_array, $loggedInUserId, $timestamp);
        $this->data = $result;
    }

    public function getPainTrackerGraphValues() {
//        setPainData
        $record_year = strftime("%Y", time());
        $session = $this->Session->read('Auth');
        $userId = $session['User']['id'];
        $userTimezone = $session['User']['timezone'];
        
        $username = $this->request->data['username'];
        if (!empty($username)) {
            $user = $this->User->getFullUserDetails($username, 'username');
            if (!empty($user) && !$user[0]['User']['is_admin']) {
                $this->_requestedUser = $user[0]['User'];
                $userId = $this->_requestedUser['id'];
                $userTimezone = $this->_requestedUser['timezone'];
            }
        }
        $resultData = $this->PainTracker->getPainTrackerGraphData($userId,$userTimezone,$record_year);
        $this->data = $resultData;
        
    }

    /**
     * Function to retrieve last week weight info
     */
    public function getWeeklyHealthValues() {
        $session = $this->Session->read('Auth');
        $userId = $session['User']['id'];
        $username = $this->request->data['username'];
        if (!empty($username)) {
            $user = $this->User->getFullUserDetails($username, 'username');
            if (!empty($user) && !$user[0]['User']['is_admin']) {
                $this->_requestedUser = $user[0]['User'];
                $userId = $this->_requestedUser['id'];
            }
        }

        switch ($this->request->data['graph_type'] * 1) {
            case self::WEIGHT_GRAPH :
                $result = $this->HealthRecordsReading->getWeeklyWeightInfo($userId);
                break;
            case self::HEALTH_STATUS_GRAPH :
                $result = $this->HealthRecordsReading->getWeeklyStatusInfo($userId);
                break;
            case self::BP_GRAPH :
                $result = $this->HealthRecordsReading->getWeeklyBloodPressureInfo($userId);
                break;
            case self::TEMP_GRAPH :
                $result = $this->HealthRecordsReading->getWeeklyTempInfo($userId);
                break;
            default :
                $result = $this->HealthRecordsReading->getWeeklyWeightInfo($userId);
                break;
        }
        $this->data = $result;
    }

    /**
     * Function retrieves all health readings for a user
     */
    public function getHealthReadings() {
        $session = $this->Session->read('Auth');
        $userId = $session['User']['id'];
        $username = $this->request->data['username'];
        if (!empty($username)) {
            $user = $this->User->getFullUserDetails($username, 'username');
            if (!empty($user) && !$user[0]['User']['is_admin']) {
                $this->_requestedUser = $user[0]['User'];
                $userId = $this->_requestedUser['id'];
            }
        }
        $result = $this->HealthRecordsReading->getHealthReadings($userId);
        $this->data = $result;
    }

    /**
     * Search new symptom by name for adding to user symptoms
     */
    public function searchNewSymptom() {
        $searchStr = trim($this->request->query['term']);
        $this->loadModel('UserSymptom');
        $this->loadModel('Symptom');
        $year = Date::getCurrentYear();

        if (!empty($searchStr)) { // string has value
            $data = $this->Symptom->find('list', array(
                'conditions' => array('Symptom.name LIKE' => "%{$searchStr}%"))
            );

            $session = $this->Session->read('Auth');
            $userId = $session['User']['id'];

            if (!empty($data)) {
                $this->__searchExistingSymptom($data);
            } else {
                //Did you mean section with spell suggestion
                $first_word = substr($searchStr, 0, 1);
                $searchStr = strtolower($searchStr);
                $session = $this->Session->read('Auth');
                $userId = $session['User']['id'];
                $year = Date::getCurrentYear();
                $data = $this->Symptom->find('list', array(
                    'conditions' => array(
                        'Symptom.name LIKE' => "%{$first_word}%")
                        )
                );
                $this->__getSymptomAutoSuggestions($data, $searchStr);
            }
        }
    }

    /**
     * Function to search existing symptom
     * 
     * @param array $data array of symptoms
     */
    private function __searchExistingSymptom($data) {
        $items = array();
        $session = $this->Session->read('Auth');
        $userId = $session['User']['id'];
        $year = Date::getCurrentYear();

        foreach ($data as $id => $name) {
            $userSymptomCount = $this->UserSymptom->find('count', array(
                'conditions' => array(
                    'user_id' => $userId,
                    'symptom_id' => $id,
                    'record_year' => $year
                )
            )); // check if symptom already exist for the user for the given year
            if ($userSymptomCount == 0) {
                $items[] = array(
                    'label' => $name,
                    'value' => $name,
                    'id' => $id
                );
            }
        }
        $this->data = $items;
    }

    /**
     * Function to search did you mean ?
     * 
     * @param array $data array of symptoms
     */
    private function __getSymptomAutoSuggestions($data, $searchStr) {
        $session = $this->Session->read('Auth');
        $userId = $session['User']['id'];
        $year = Date::getCurrentYear();
        $items = array();
        if (!empty($data)) {
            $items[0] = array(
                'label' => 'Did you mean ?',
                'value' => '',
                'id' => '',
                'percentage' => 110
            ); //gave % more than 100 to display it 1st while sorting.
            $have_value = 0;
            foreach ($data as $id => $name) {
                $userSymptomCount = $this->UserSymptom->find('count', array(
                    'conditions' => array(
                        'user_id' => $userId,
                        'symptom_id' => $id,
                        'record_year' => $year
                    )
                )); // check if symptom already exist for the user for the given year
                if ($userSymptomCount == 0) {
                    $disease_name = strtolower($name);
                    similar_text($disease_name, $searchStr, $percent); // get similar text.
                    //Take only words having a certain %
                    if ($percent > 40) {
                        $have_value = 1;
                        $items[] = array(
                            'label' => $name,
                            'value' => $name,
                            'id' => $id,
                            'percentage' => $percent
                        );
                    }
                }
            }
            if ($have_value) {

                function cmp($a, $b) {
                    return $b["percentage"] - $a["percentage"];
                }

                @usort($items, "cmp"); //used @ to suppress warning due to a bug in php version.
            } else { // no value for suggestion so unset the did you mean.
                unset($items[0]);
            }
            $addKeywordText = array(
                'label' => $searchStr . ' isn\'t in our system. Submit to add it',
                'value' => $searchStr,
                'id' => '0'
            ); //new disease so value -1
            array_unshift($items, $addKeywordText);
        }
        $this->data = $items;
    }

    /**
     * Function to add a symptom to a user.
     *      
     */
    public function addUserSymptom() {
        $session = $this->Session->read('Auth');
        $userId = $session['User']['id'];
        $this->loadModel('UserSymptom');

        if (isset($this->request->data['id']) && $this->request->data['id'] != NULL) {
            $symptomId = $this->request->data['id'];
            $severity = $this->request->data['severity'];
            $userTimezone = $this->Session->read('Auth.User.timezone');
            $currentTime = CakeTime::convert(time(), new DateTimeZone($userTimezone));
            $userTime = date('Y-m-d', $currentTime);
            $timestamp = strtotime($userTime);
            $result = $this->UserSymptom->addPatientSymptoms($userId, $symptomId, $severity, $timestamp);
            $this->data = $result;
        }
    }

    /**
     * Function to add new symptom added by user.
     *      
     */
    public function addNewUserSymptom() {
        $session = $this->Session->read('Auth');
        $userId = $session['User']['id'];
        $this->loadModel('UserSymptom');

        if (!empty($this->request->data['new_symptom'])) {
            $severity = $this->request->data['severity'];
            $newSymptomName = $this->request->data['new_symptom'];
            $userTimezone = $this->Session->read('Auth.User.timezone');
            $currentTime = CakeTime::convert(time(), new DateTimeZone($userTimezone));
            $userTime = date('Y-m-d', $currentTime);
            $timestamp = strtotime($userTime);
            $symptomId = $this->Symptom->addNewSymptom($newSymptomName);
            $result['status'] = $this->UserSymptom->addPatientSymptoms($userId, $symptomId, $severity, $timestamp);
			$result['id'] = $symptomId; 
            $this->data = $result;
        }
    }

	/**
	 * Function to get weekly symptom data for symptom tile page graph
	 */
    public function getWeeklySymptomSeverity() {
		if ($this->Session->read('Auth')) {
			$userId = $this->request->data ['userId'];
			$userTimezone = $this->Session->read('Auth.User.timezone');
			$symptomName = $this->request->data ['symptomName'];

			$symptomdetails = $this->Symptom->findByName($symptomName);
			$weeklySymptomSeverityFormatted = array();
			if (!empty($symptomdetails)) {
				$this->UserSymptom->timezone = $userTimezone;
				$weeklySymptomSeverity = $this->UserSymptom->getWeeklySymptomSeverity($userId, $symptomdetails['Symptom']['id']);

				foreach ($weeklySymptomSeverity as $key => $value) {
					$formatedDate = CakeTime::format($key, "%b %d %Y");
					$weeklySymptomSeverityFormatted[$formatedDate] = $value;
				}
			}

			$this->data = $weeklySymptomSeverityFormatted;
		}
	}

    /**
     * Function to add a symptom to a user.
     *      
     */
    public function addUserSeverity() {
        $session = $this->Session->read('Auth');
        $timezone = $session['User']['timezone'];
        $userId = $session['User']['id'];
        $this->loadModel('UserSymptom');
        $currentTime = CakeTime::convert(time(), new DateTimeZone($timezone));

        if ($this->request->data['date']) {
            $userTime = $this->request->data['date'];
        } else {
            $userTime = date('Y-m-d', $currentTime);
        }

        $timestamp = strtotime($userTime);
        if (isset($this->request->data['id']) && $this->request->data['id'] != NULL) {
            $symptomId = $this->request->data['id'];
            $symptomSeverity = $this->request->data['severity'];
            $this->UserSymptom->addSymptomSeverity($userId, $symptomId, $timestamp, $symptomSeverity);

            return TRUE;
        }
    }

    /**
     * Function to get the severity value of a user for a symptom.
     *      
     */
    public function getUserSymptomSeverity() {
        $session = $this->Session->read('Auth');
        $userId = $session['User']['id'];
        $this->loadModel('UserSymptom');
        $symptomId = $this->request->data['id'];
        $date = $this->request->data['date'];
        $userTime = strtotime($date);
        $this->UserSymptom->timezone = $session['User']['timezone'];
        $severityValue = $this->UserSymptom->getSymptomSeverityInADay($userId, $symptomId, $userTime);

        $severityTypes = $this->UserSymptom->_getSeverityTypes();
        $items = array();
        if (!empty($severityValue)) {
            $items[] = array(
                'severityId' => $severityValue,
                'name' => $severityTypes[$severityValue]['name']
            );
        }

        $this->data = $items;
    }

    /**
     * Function to get symptom severity detils
     */
    public function getSymptomSeverityDetails() {

        if ($this->request->data['username'] == $this->Session->read('Auth.User.username')) {
            $userId = $this->Session->read('Auth.User.id');
        } else {
            $username = $this->request->data['username'];
            $user = $this->User->findByUsername($username);
            $userId = $user['User']['id'];
        }
        $userTimezone = $this->Session->read('Auth.User.timezone');
        $symptomName = $this->request->data ['symptomName'];
        $symptomdetails = $this->Symptom->findByName($symptomName);
        $symptomSeverity = array();
        if (!empty($symptomdetails)) {
            $this->UserSymptom->timezone = $userTimezone;
            $symptomSeverity = $this->UserSymptom->getSymptomSeverityDetails($userId, $symptomdetails ['Symptom'] ['id']);
        }

        $this->data = $symptomSeverity;
    }

    /**
     *  Temp funciton to update mysymptom datetime 
     */
    public function updateMysymptomDatetime() {

        $userSymptoms = $this->UserSymptom->find('all');

        foreach ($userSymptoms as $userSymptom) {
            $recordValue = $userSymptom['UserSymptom']['record_value'];
            $records = json_decode($recordValue, true);
            $newRecords = array();
            echo "<br>----------------------userId: " . $userSymptom['UserSymptom']['user_id'] .
            " ---- SymptomId:" . $userSymptom['UserSymptom']['symptom_id'] . "------------------------";
            foreach ($records as $record => $value) {
                echo "<br>";
                echo date('Y-m-d H:i:s ', $record);
                $symptom_date = date('Y-m-d', $record);
                $timestamp = strtotime($symptom_date);
                $newRecords [$timestamp] = $value;
                echo date('=>  Y-m-d H:i:s', $timestamp);
            }
            $latestRecordValueJson = $userSymptom['UserSymptom']['latest_record_value'];
            $latestRecordValue = json_decode($latestRecordValueJson, true);
            $newLatestRecordValue = array();
            foreach ($latestRecordValue as $latestRecordValueTemp => $value) {
                echo "<br> Laest record value : ";
                echo date('Y-m-d H:i:s ', $latestRecordValueTemp);
                $symptom_date = date('Y-m-d', $latestRecordValueTemp);
                $timestamp = strtotime($symptom_date);
                $newLatestRecordValue [$timestamp] = $value;
                echo date('=>  Y-m-d H:i:s', $timestamp);
            }
//         	debug($latestRecordValue);
//         	debug($newRecords);

            $id = $userSymptom['UserSymptom']['id'];
            $this->UserSymptom->id = $id;
            $userSymptom['UserSymptom']['record_value'] = json_encode($newRecords);
            $userSymptom['UserSymptom']['latest_record_value'] = json_encode($newLatestRecordValue);
            if ($this->UserSymptom->save($userSymptom)) {
                echo " <br>saved";
            } else {
                echo " <br>not saved!";
            }
        }
    }

    public function updateUnitSettings() {
        $result['success'] = FALSE;
        $this->loadModel('NotificationSetting');
        $loggedInUserId = $this->Session->read('Auth.User.id');
        if (isset($this->request->data['height']) && $this->request->data['height'] != NULL) {
            $height = $this->request->data['height'];
        }
        if (isset($this->request->data['weight']) && $this->request->data['weight'] != NULL) {
            $weight = $this->request->data['weight'];
        }
        if (isset($this->request->data['temperature']) && $this->request->data['temperature'] != NULL) {
            $temperature = $this->request->data['temperature'];
        }
        $notificationSetting = Array(
            'height' => $height ,
            'weight' => $weight,
            'temp' => $temperature
        );
        if($this->NotificationSetting->changeUnitSetting($notificationSetting, $loggedInUserId)) {
            $result['success'] = TRUE;
            $this->Session->setFlash(__('Successfully updated the unit settings.'), 'success');
        }
        $this->data = $result;
    }
	/**
	 * Function to implement follow Disease page request
	 * 
	 * @return boolean
	 */
	public function followDiseasePage() {
		$diseaseId = $this->request->data['diseaseId'];

		if (!empty($diseaseId)) {
			//Disease follow data
			$diseaseData = array(
				'type' => FollowingPage::DISEASE_TYPE,
				'page_id' => $diseaseId,
				'user_id' => $this->Session->read('Auth.User.id'),
				'notification' => FollowingPage::NOTIFICATION_ON
			);
			$this->FollowingPage->followPage($diseaseData);
			return TRUE;
		}
	}
	/**
	 * Function to get disease follow status.
	 * 
	 */
	public function checkDiseaseFollowStatus() {
		$diseaseId = $this->request->data['diseaseId'];		
		$userId = $this->Session->read('Auth.User.id');
		$followStatus = $this->FollowingPage->getFollowStatus(
				$userId, FollowingPage::DISEASE_TYPE, $diseaseId
		);
		
		$this->data = $followStatus;
	}

	/**
	 * Function to implement unfollow Disease page request
	 * 
	 * @return boolean
	 */
	public function unFollowDiseasePage() {
		$diseaseId = $this->request->data['diseaseId'];
		if (!empty($diseaseId)) {
			//Disease follow data
			$diseaseData = array(
				'type' => FollowingPage::DISEASE_TYPE,
				'page_id' => $diseaseId,
				'user_id' => $this->Session->read('Auth.User.id')
			);
			$this->FollowingPage->unFollowPage($diseaseData);
			return TRUE;
		}
	}
	
	/**
	 * Function to implement follow profile page request
	 * 
	 * @return boolean
	 */
	public function followProfilePage() {
		$profileId = $this->request->data['profileId'];

		if (!empty($profileId)) {
			//Disease follow data
			$profileData = array(
				'type' => FollowingPage::USER_TYPE,
				'page_id' => $profileId,
				'user_id' => $this->Session->read('Auth.User.id'),
				'notification' => FollowingPage::NOTIFICATION_ON
			);
			$this->FollowingPage->followPage($profileData);
			return TRUE;
		}
	}

	/**
	 * Function to implement unfollow profile page request
	 * 
	 * @return boolean
	 */
	public function unFollowProfilePage() {
		$profileId = $this->request->data['profileId'];
		if (!empty($profileId)) {
			//Disease follow data
			$profileData = array(
				'type' => FollowingPage::USER_TYPE,
				'page_id' => $profileId,
				'user_id' => $this->Session->read('Auth.User.id')
			);
			$this->FollowingPage->unFollowPage($profileData);
			return TRUE;
		}
	}
	
	/**
	 * Function to implement Disease notification
	 *  
	 * @return boolean
	 */
	public function switchNotification() {
		$Id = $this->request->data['Id'];
		$type = $this->request->data['type'];
		$status = $this->request->data['status'];
		$userId = $this->Session->read('Auth.User.id');
		if (!empty($Id)) {
			$notificationData = array(
				'type' => $type,
				'page_id' => $Id,
				'user_id' => $userId,
				'notification' => $status
			);
			$this->FollowingPage->pageNotification($notificationData);
			$result = array(
				'status' => TRUE,
				'notification_type' => $status
			);
//			$this->Session->setFlash('Successfully updated the notification setting.', 'success');
			$this->data = $result;
		}
		
	}
}

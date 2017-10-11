<?php

/**
 * MangageTracker Controller class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');
/**
 * ManageTrackerController for MyHealth
 * 
 * ManageTrackerController is used to show the history of health trackers
 * 
 * @package 	User
 * @category	Controllers 
 */

class ManageTrackerController extends ProfileController {

    protected $_mergeParent = 'ProfileController';
    public $uses = array('HealthSurveyForm', 'HealthReading', 'NotificationSetting', 'MyFriends');
    public $components = array('HealthRecordsReading');

    public function index() {
		$this->_setUserProfileData();
		if ($this->_requestedUser['id'] == $this->Auth->user('id')) {
            $userId = $this->Auth->user('id');
            $isOwner = TRUE;
        } else {
            $userId = $this->_requestedUser['id'];
            $isOwner = FALSE;
			$privacy = new UserPrivacySettings($this->_requestedUser['id']);
            $isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'], $this->Auth->user('id'));
            $viewSettting = array($privacy::PRIVACY_PUBLIC);
            if ($isFriend == MyFriends::STATUS_CONFIRMED) {
                array_push($viewSettting, $privacy::PRIVACY_FRIENDS);
            }
            if (!in_array($privacy->__get('view_your_health'), $viewSettting)) {
                $this->redirect( Common::getUserProfileLink( $this->_requestedUser['username'], true) );
            }
		}
		
		if (isset($this->_requestedUser['id'])) {
            //$this->set('title_for_layout', $this->_requestedUser['username'] . "'s health charts" );
            $profileOwner = $this->_requestedUser['username'];
        } else {
            //$this->set('title_for_layout', $this->Auth->user('username') . "'s health");
            $profileOwner = $this->Auth->user('username');
        }
		
		$record_type = $this->request->query('record_type');
		switch($record_type) {
            case 1:
                $tableTitle = "Pain Tracker";
                break;
            case 2:
                $tableTitle = "Quality Of Life";
                break;
            case 3:
                $tableTitle = "Sleeping Habits";
                break;
			default :
                $tableTitle = "Health Status";
                break;
        }		

		$this->set(compact('isOwner','profileOwner','tableTitle','record_type'));
    }

	/**
     * Function for getting user health tracker history details.
     */
    public function filterTrackerHistory() {
        $this->layout = "ajax";
        $year = Date::getCurrentYear();
        
        if ($this->request->data['username'] == $this->Auth->user('username')) {
            $userId = $this->Auth->user('id');
            $is_owner = TRUE;
        }
        else {
           $username = $this->request->data['username'] ;
           $user = $this->User->findByUsername( $username );           
           $userId = $user['User']['id'];
           $is_owner = FALSE;
        }
        
        $timezone = $this->Auth->user('timezone');
        $recordType = $this->request->data['type'];
		switch ($recordType) {
			 case 1:
					$type = HealthReading::RECORD_TYPE_GENERAL_PAIN;
					break;
			 case 2:
					$type = HealthReading::RECORD_TYPE_QUALITY_OF_LIFE;
					break;
             case 3:
					$type = HealthReading::RECORD_TYPE_SLEEPING_HABITS;
					break;
		}
        
        (isset($this->request->data['filterValue'])) ? $filterYear = $this->request->data['filterValue'] : $filterYear = $year;

        $histories = $this->HealthReading->find('first', array(
            'conditions' => array(
                'HealthReading.record_type' => $type,
                'HealthReading.user_id' => $userId,
                'HealthReading.record_year' => $filterYear
            )
        ));
        
        $historyResult = array();
        if (isset($histories) && isset($histories['HealthReading']['record_value']) && $histories['HealthReading']['record_value'] != NULL) {
            $historyResult = json_decode($histories['HealthReading']['record_value'], TRUE);
            krsort($historyResult); //reorder the array in desc order of timestamp
        }
        $trackerValues = $this->HealthReading->getTrackerValues();
        $filterYears = $this->HealthReading->trackerHistoryFilterYears($type,$userId);
           
        $View = new View($this, false);
        $response = $View->element('User.Myhealth/tracker_history_list', array(
            'trackerValues' => $trackerValues,
            'historyResult' => $historyResult,
            'histories' => $histories,
            'timezone' => $timezone,
            'filterYears' => $filterYears,
            'isOwner' => $is_owner
                )
        );
        echo $response;
        exit;
    }
	
	/**
     * Function to delete a particular user tracker history.
     *      
     */
    public function deleteTrackerHistory() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $recordType = $this->request->params['recordType'];

        $timestamp = $this->request->params['timestamp'];
        $userId = $this->Auth->user('id');
        $year = Date::getCurrentYear();

        $userTrackerHistory = $this->HealthReading->find('first', array(
            'conditions' => array(
                'HealthReading.record_type' => $recordType,
                'HealthReading.user_id' => $userId,
                'HealthReading.record_year' => $year
            )
        ));

        $historyResult = json_decode($userTrackerHistory['HealthReading']['record_value'], TRUE);

        unset($historyResult[$timestamp]); //remove the history from array.
        $newhistoryResultJSON = json_encode($historyResult);


        if (!empty($historyResult)) {
            $latestRecordKey = max(array_keys($historyResult));
            $latestRecordJson = json_encode(array(
                $latestRecordKey => $historyResult[$latestRecordKey]
            ));
        } else { //no more data left
            $latestRecordKey = '';
            $latestRecordJson = $newhistoryResultJSON;
        }
        $newdata = array(
            'id' => $userTrackerHistory['HealthReading']['id'],
            'record_value' => $newhistoryResultJSON,
            'latest_record_value' => $latestRecordJson
        );

        if ($this->HealthReading->save($newdata)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

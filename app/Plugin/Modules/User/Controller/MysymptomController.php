<?php

/**
 * MysymptomController class file.
 * 
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');

/**
 * MysymptomController for the user profile
 * 
 * MysymptomController is used to show the "My symptom"
 * 
 * @package 	User
 * @category	Controllers 
 */
class MysymptomController extends ProfileController {

    protected $_mergeParent = 'ProfileController';
    public $uses = array('PatientDisease', 'Disease', 'Symptom', 'UserSymptom', 'DiseaseSymptom', 'User');
    
       
  
    /**
     * Profile -> My symptom
     */
    public function index($username = null) {
        $userId = $this->Auth->user('id');
        $this->_setUserProfileData();		

        /**
         * If a get request is passed with a date, then edit symptom severiy in that date 
         * Else If todays severity is not added then view will be add severity page
         * Else the view will be the symptoms list tile page 
         */
        if ($this->_requestedUser['id'] == $userId) { //user visit his own page.
                        $graphRoom = 'myhealth/' . $userId;
			$this->set('title_for_layout', $this->Auth->user('username') . "'s Symptoms");
            if (isset($this->request->query['date'])) {
                $date = $this->request->query['date'];
                $this->addSymptomSeverity($date);
            } else {
                $this->showSymptomTiles();
            }
            $this->set('graphRoom', $graphRoom);
        } else { //other user's page.
            //Check if user have permissions for viewing the page.
                        $graphRoom = 'myhealth/' . $this->_requestedUser['id'];
            $privacy = new UserPrivacySettings($this->_requestedUser['id']);
            $isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'], $this->Auth->user('id'));
            $viewSetting = array($privacy::PRIVACY_PUBLIC);
            if ($isFriend == MyFriends::STATUS_CONFIRMED) {
                array_push($viewSetting, $privacy::PRIVACY_FRIENDS);
            }
            if (!in_array($privacy->__get('view_your_health'), $viewSetting)) {
                $this->redirect( Common::getUserProfileLink( $this->_requestedUser['username'], true) );
            }

            $this->set('title_for_layout', $this->_requestedUser['username'] . "'s Symptoms");
            $this->set('graphRoom', $graphRoom);
            $this->showSymptomTiles();
        }
    }
	/**
	 * Function to re-render symptom tile graph after ajax adding a new symptom.	 * 
	 */
	public function getGraphBlock() {
		$this->autoRender = false;
		$userId = $this->Auth->user('id');
		$this->_setUserProfileData();
		$View = new View($this, false);
		$symptoms = array();
		/*
		 * Contains only symptom ids
		 */
		$userSymptoms = $this->UserSymptom->getUserSymptomIds($userId);

		/*
		 * We have to fetch symptom name for each symptom id
		 * $symptoms = array( symptom id => symptom name)
		 */
		foreach ($userSymptoms as $userSymptom) {
			$symptomId = $userSymptom ['UserSymptom'] ['symptom_id'];
			$symptomName = $this->Symptom->getSymptomNameFromId($symptomId);
			$symptoms [] = array('id' => $symptomId, 'name' => $symptomName);
		}
		$response = $View->element('User.Mysymptom/symptom_graph_ajax', array('symptoms' => $symptoms));
		echo $response;
	}

	/**
	 * Function to re-render symptom lists after ajax adding a new symptom.
	 */
	public function getSymptomList() {
		$this->autoRender = false;
		$userId = $this->Auth->user('id');
		$this->_setUserProfileData();
		$View = new View($this, false);
		$symptoms = array();

		$date = $this->request->data('symptom_date');

		if ($date != NULL) {
			$userTime = strtotime($date);
		}

		/*
		 * Contains only symptom ids
		 */
		$userSymptoms = $this->UserSymptom->getUserSymptomIds($userId);

		/*
		 * We have to fetch symptom name for each symptom id
		 * $symptoms = array( symptom id => symptom name)
		 */
		foreach ($userSymptoms as $userSymptom) {
			$symptomId = $userSymptom ['UserSymptom'] ['symptom_id'];
			$symptomName = $this->Symptom->getSymptomNameFromId($symptomId);
			$diseaseName = $this->DiseaseSymptom->getUserSymptomDiseases($symptomId, $userId);
			$symptoms [] = array('id' => $symptomId, 'name' => $symptomName, 'conditions' => $diseaseName);
		}

		$timestamp = strtotime(date('Y-m-d', $userTime));
		foreach ($symptoms as $Id => $symptom) {
			$recordValueToday = $this->UserSymptom->getSymptomSeverityInADay($userId, $symptom['id'], $timestamp);
			$symptoms[$Id]['value'] = $recordValueToday;
		}

		$response = $View->element('User.Mysymptom/symptom_list', array('symptoms' => $symptoms));
		echo $response;
	}
    /**
     * Function to show default symptom tile view
     */
    public function showSymptomTiles() {

        if ($this->_requestedUser['id'] == $this->Auth->user('id')) {
            $userId = $this->Auth->user('id');
            $is_same = TRUE;
        } else {
            $userId = $this->_requestedUser['id'];
            $is_same = FALSE;
        }
        $graphRoom = 'myhealth/' . $userId;
        $is_tile_page = TRUE;
        $date = NULL;
        /*
         * Contains only symptom ids
         */
        $userSymptoms = $this->UserSymptom->getUserSymptomIds($userId);

        /*
         * We have to fetch symptom name for each symptom id
         * $symptoms = array( symptom id => symptom name)
         */
        foreach ($userSymptoms as $userSymptom) {
            $symptomId = $userSymptom ['UserSymptom'] ['symptom_id'];
            $symptomName = $this->Symptom->getSymptomNameFromId($symptomId);
            $symptoms [] = array('id' => $symptomId, 'name' => $symptomName);
        }

        $this->set(compact('symptoms', 'date', 'is_same', 'is_tile_page','graphRoom'));
        $this->render('index');
    }

    /**
     * Function to add new severiy 
     * @param string $date
     */
    public function addSymptomSeverity($date = NULL) {
        $symptoms = array();
        $titleDate = '';

        $userId = $this->Auth->user('id');
        $timezone = $this->Auth->user('timezone');
        $currentUserTime = CakeTime::convert(time(), new DateTimeZone($timezone));
        if ($date != NULL) {
            $userTime = strtotime($date);
        } else {
            $userTime = $currentUserTime;
        }

        //format title date
        if ($userTime == $currentUserTime) {
            $titleDate = 'today, ';
        }        
        $titleDate .= date('F j, Y', $userTime);

        /*
         * Contains only symptom ids
         */
        $userSymptoms = $this->UserSymptom->getUserSymptomIds($userId);

        /*
         * We have to fetch symptom name for each symptom id
         * $symptoms = array( symptom id => symptom name)
         */
        foreach ($userSymptoms as $userSymptom) {
            $symptomId = $userSymptom ['UserSymptom'] ['symptom_id'];
            $symptomName = $this->Symptom->getSymptomNameFromId($symptomId);
            $diseaseName = $this->DiseaseSymptom->getUserSymptomDiseases($symptomId, $userId);            
            $symptoms [] = array('id' => $symptomId, 'name' => $symptomName, 'conditions' => $diseaseName);
        }        

        if ($this->request->is('post')) {            
            if (isset($this->request->data['mySymptoms']['symptoms'])) {                                             
                
                /*
                 * Check if the date is selected OR the symptom details are 
                 * saved for today
                 */
                if(!empty($this->request->data['mySymptoms']['SymptomDate'])) {
                    $symptom_date = $this->request->data['mySymptoms']['SymptomDate'];
                } else {
                    $symptom_date = date('Y-m-d', $currentUserTime);
                }
              
                $timestamp = strtotime( $symptom_date );
              
                /**
                 * Save severity details
                 */ 
                foreach ($this->request->data['mySymptoms']['symptoms'] as $symptomId => $symptomSeverity) {
                    $this->UserSymptom->timezone = $this->Auth->user('timezone');
                    $this->UserSymptom->addSymptomSeverity($userId, $symptomId, $timestamp, $symptomSeverity);
                }
                $this->Session->setFlash(__('Severity added successfully'), 'success');
            }
            $this->redirect(array('action' => 'index'));
        }

        $timestamp = strtotime(date('Y-m-d', $userTime));
        foreach ($symptoms as $Id => $symptom) {            
            $recordValueToday = $this->UserSymptom->getSymptomSeverityInADay($userId, $symptom['id'], $timestamp);
            $symptoms[$Id]['value'] = $recordValueToday;
        }


        $this->set(compact('symptoms', 'data', 'titleDate', 'date'));
        $this->render('add_symptom_severity');
    }

    /**
     * Function for get html for adding severity.
     */
    public function showLatestAddedSymptom() {
        $this->layout = "ajax";

        $userId = $this->Auth->user('id');
        $timezone = $this->Auth->user('timezone');
        $symptoms = array();
        /*
         * Contains only symptom ids
         */
        $userSymptoms = $this->UserSymptom->find('all', array(
            'conditions' => array(
                'UserSymptom.user_id' => $userId
            ),
            'fields' => array(
                'UserSymptom.symptom_id'
            ),
            'group' => array(
                'UserSymptom.symptom_id'
            )
        ));

        /*
         * We have to fetch symptom name for each symptom id
         * $symptoms = array( symptom id => symptom name)
         */
        foreach ($userSymptoms as $userSymptom) {
            $symptomId = $userSymptom ['UserSymptom'] ['symptom_id'];
            $symptomName = $this->Symptom->getSymptomNameFromId($symptomId);
            $diseaseName = $this->DiseaseSymptom->getUserSymptomDiseases($symptomId, $userId);
            $symptoms [] = array('id' => $symptomId, 'name' => $symptomName, 'conditions' => $diseaseName);
        }
        $today = strtotime(date('Y-m-d'));
        foreach ($symptoms as $Id => $symptom) {
            $this->UserSymptom->timezone = $timezone;
            $recordValueToday = $this->UserSymptom->getSymptomSeverityInADay($userId, $symptom['id'], $today);
            $symptoms[$Id]['value'] = $recordValueToday;
        }
        $View = new View($this, false);
        $response = $View->element('User.Mysymptom/symptom_list', array('symptoms' => $symptoms));
        echo $response;
        exit;
    }	

    /**
     * Function for symptom detail page.
     */
    public function detail() {
        $this->_setUserProfileData();


        if ($this->_requestedUser['id'] == $this->Auth->user('id')) {
            $userId = $this->Auth->user('id');
            $is_owner = TRUE;
        } else {
            $userId = $this->_requestedUser['id'];
            $is_owner = FALSE;
            
            //Check if user have permissions for viewing the page.
            $privacy = new UserPrivacySettings($this->_requestedUser['id']);
            $isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'], $this->Auth->user('id'));
            $viewSetting = array($privacy::PRIVACY_PUBLIC);
            if ($isFriend == MyFriends::STATUS_CONFIRMED) {
                array_push($viewSetting, $privacy::PRIVACY_FRIENDS);
            }
            if (!in_array($privacy->__get('view_your_health'), $viewSetting)) {
                $this->redirect( Common::getUserProfileLink( $this->_requestedUser['username'], true) );
            }
        }

        $graphRoom = 'myhealth/' . $userId;
        $timezone = $this->Auth->user('timezone');
        $symptomid = $this->request->params['symptomid'];

        $symptomdetails = $this->Symptom->findById($symptomid);
		
        if ($symptomdetails) {
			
			$this->set('title_for_layout', $this->Auth->user('username') . "'s ". $symptomdetails['Symptom']['name']);
            $userSymptomCount = $this->UserSymptom->find('count', array(
                'conditions' => array(
                    'user_id' => $userId,
                    'symptom_id' => $symptomdetails['Symptom']['id']
                )
            ));

            $this->set(compact('symptomdetails', 'timezone', 'userSymptomCount', 'is_owner','graphRoom'));
        } else {
            $this->redirect('/profile/mysymptom');
        }
    }

  
    /**
     * Function for get user symptom history details.
     */
    public function filterSymptomHistory() {
        $this->layout = "ajax";

        $year = Date::getCurrentYear();
        //$this->_setUserProfileData();
        
        
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
        $symptomId = $this->request->data['symId'];//userId
        
        (isset($this->request->data['filterValue'])) ? $filterYear = $this->request->data['filterValue'] : $filterYear = $year;


        $symptomHistories = $this->UserSymptom->find('first', array(
            'conditions' => array(
                'UserSymptom.symptom_id' => $symptomId,
                'UserSymptom.user_id' => $userId,
                'UserSymptom.record_year' => $filterYear
            )
        ));
        
        $historyResult = array();
        if (isset($symptomHistories) && isset($symptomHistories['UserSymptom']['record_value']) && $symptomHistories['UserSymptom']['record_value'] != NULL) {
            $historyResult = json_decode($symptomHistories['UserSymptom']['record_value'], TRUE);
            krsort($historyResult); //reorder the array in desc order of timestamp
        }
        $severityTypes = $this->UserSymptom->_getSeverityTypes();
        $symptomFilterYears = $this->UserSymptom->symptomHistoryFilterYears($symptomId,$userId);
           
        $View = new View($this, false);
        $response = $View->element('User.Mysymptom/symptom_history_list', array(
            'severityTypes' => $severityTypes,
            'historyResult' => $historyResult,
            'symptomHistories' => $symptomHistories,
            'timezone' => $timezone,
            'symptomFilterYears' => $symptomFilterYears,
            'isOwner' => $is_owner
                )
        );
        echo $response;
        exit;
    }

    

    /**
     * Function to delete a particular user symptom history.
     *      
     */
    public function deleteUserHistory() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $symptomid = $this->request->params['usersymptomid'];

        $timestamp = $this->request->params['timestamp'];
        $userId = $this->Auth->user('id');
        $year = Date::getCurrentYear();

        $userSymptomHistory = $this->UserSymptom->find('first', array(
            'conditions' => array(
                'UserSymptom.symptom_id' => $symptomid,
                'UserSymptom.user_id' => $userId,
                'UserSymptom.record_year' => $year
            )
        ));

        $historyResult = json_decode($userSymptomHistory['UserSymptom']['record_value'], TRUE);

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
            'id' => $userSymptomHistory['UserSymptom']['id'],
            'record_value' => $newhistoryResultJSON,
            'latest_record_value' => $latestRecordJson
        );

        if ($this->UserSymptom->save($newdata)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Function to check severity is added for today
     */
    function isSeveriyAddedToday() {
    	$timezone = $this->Auth->user('timezone');        
        $time = strtotime(CakeTime::nice(time(), $timezone, '%m/%d/%Y')); 
        $this->UserSymptom->timezone = $timezone;
        return $this->UserSymptom->isRecordPresent($this->Auth->user('id'), $time);
    }
    
    /**
     * Function to delete symptom linked to a user
     */
    function deleteUserSymptom() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $year = Date::getCurrentYear();
        $userId = $this->Auth->user('id');
        $timezone = $this->Auth->user('timezone');
        $symptomid = $this->request->data['id'];
        
        $conditions = array(            
                'UserSymptom.symptom_id' => $symptomid,
                'UserSymptom.user_id' => $userId               
            
        );        
        $this->Session->setFlash(__('User symptom has been removed successfully'), 'success');
        if ($this->UserSymptom->deleteAll($conditions, FALSE)) {
        return TRUE;
        }
        else {
          return FALSE;  
        }
        
    }

}
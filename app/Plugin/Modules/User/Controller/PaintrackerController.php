<?php

/**
 * PaintrackerController class file.
 *
 * @author    varun ashok <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');

/**
 * PaintrackerController for the user profile
 *
 * PaintrackerController is used to show the "Paintracker" in the Myhealth page
 *
 * @author 	Varun ashok
 * @package 	User
 * @category	Controllers
 */
class PaintrackerController extends ProfileController {

    protected $_mergeParent = 'ProfileController';
    public $uses = array('HealthSurveyForm', 'HealthReading', 'NotificationSetting', 'PainTracker');
    public $components = array('HealthRecordsReading');

    /**
     * Profile -> Paintracker
     */
    public function index() {


        $currentUserId = $this->Auth->user('id');
        $this->_setUserProfileData();
        $graphRoom = NULL;

        /**
         * If a get request is passed with a date, then edit symptom severiy in that date 
         * Else If todays severity is not added then view will be add severity page
         * Else the view will be the symptoms list tile page 
         */
        if ($this->_requestedUser['id'] == $currentUserId) { //user visit his own page.
            $profileUrl = '/profile';
            $userId = $this->Auth->user('id');
            $userTimezone = $this->Auth->user('timezone');
            $this->setPainData($userId, $userTimezone);
        } else { //other user's page.
            //Check if user have permissions for viewing the page.
            $userId = $this->_requestedUser['id'];
            $userTimezone = $this->_requestedUser['timezone'];

            $privacy = new UserPrivacySettings($this->_requestedUser['id']);
            $isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'], $this->Auth->user('id'));
            $viewSetting = array($privacy::PRIVACY_PUBLIC);
            if ($isFriend == MyFriends::STATUS_CONFIRMED) {
                array_push($viewSetting, $privacy::PRIVACY_FRIENDS);
            }
            if (!in_array($privacy->__get('view_your_health'), $viewSetting)) {
                $this->redirect( Common::getUserProfileLink( $this->_requestedUser['username'], TRUE));
            }

            $this->set('title_for_layout', $this->_requestedUser['username'] . "'s pain history");
            $this->setPainData($userId, $userTimezone);
        }
    }

    function setPainData($userId, $userTimezone) {
        $graphRoom = 'myhealth/' . $userId;
        $record_year = strftime("%Y", time());
        $isAjax = ($this->request->is('ajax')) ? true : false;
        $resultData = $this->PainTracker->getPainTrackerGraphData($userId,$userTimezone,$record_year);
        $bodyPartsArray = $resultData['bodyPartsArray'];
        $painTypes = $resultData['painTypes'];
        $arrayByBodyPart = $resultData['arrayByBodyPart'];
        $this->set(compact('bodyPartsArray', 'painTypes', 'arrayByBodyPart','graphRoom'));
        if ($isAjax) {
            echo json_encode($resultData);
            die();
        }
    }

    /* avoided datatable and removed this function. */
    /*
      public function delete() {
      $userId = $this->Auth->user('id');

      $time = $this->request->data('time');
      $record_year = strftime("%Y", $time);
      $body_main_part = $this->request->data('main_part');
      $body_sub_part = $this->request->data('sub_part');
      $severity = $this->request->data('severity');
      //        $key_to_delete = explode('[', $key_to_delete);
      //        $key = rtrim($key_to_delete[1], ']');
      $painType = $this->request->data('pain_type');
      $painType = (!empty($painType)) ? $painType : 1;
      //        $records = $this->HealthRecordsReading->getHealthReadingForYear($record_type, $userId, $record_year);
      $records = $this->PainTracker->getUserAllPainRecords($userId, $painType);

      $readings = json_decode($records['PainTracker']['value'], TRUE);



      foreach ($readings[$body_main_part] as $key => $record) {
      if ($key == $time) {
      if ($record['part'] == $body_sub_part && $record['severity'] == $severity) {
      $is_value_present = true;
      unset($readings[$body_main_part][$key]);
      $result['success'] = true;
      //                    $painRecords[$key] = $painRecordValue;
      }
      }
      }
      if ($is_value_present != true) {
      $result['success'] = false;
      }

      if (isset($readings[$key])) {
      unset($readings[$key]);
      }
      $readings = json_encode($readings);
      $records['HealthReading']['record_value'] = $readings;
      $this->HealthReading->save($records);
      die(json_encode(array('status', 'success')));
      }
     */
}

<?php


App::uses('ProfileController', 'User.Controller');

/**
 * Charts Controller class file.
 *
 * @author    Amith Hariharan <amit@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
class TrackerController extends ProfileController {
    protected $_mergeParent = 'ProfileController';
    public $uses = array('HealthSurveyForm', 'HealthReading', 'NotificationSetting');
    public $components = array('HealthRecordsReading');

    public function index($username = null) {
        $this->_setUserProfileData();

        $isOwner = true;
        $userId = $this->Auth->user('id');
        if (isset($this->_requestedUser['id'])) {
            $this->set('title_for_layout', "Tracker" );
            $profile_owner = $this->_requestedUser['username'];
        } else {
            $this->set('title_for_layout', $this->Auth->user('username') . "'s health");
            $profile_owner = $this->Auth->user('username');
        }

        $userUnitsSettings = $this->NotificationSetting->getUnitSettings($userId);
        $unitSettings = $userUnitsSettings;

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
                $this->redirect( Common::getUserProfileLink( $this->_requestedUser['username'], true) );
            }
        }

        $graphRoom = 'myhealth/' . $userId;
        //set date as today
        $userTimezone = $this->Auth->user('timezone');
        $todayInUserTimeZone= CakeTime::convert(time(), new DateTimeZone($userTimezone));
        $date_today = CakeTime::format($todayInUserTimeZone, '%m/%d/%Y');

        //user date of birth
        $userDateOfBirth = $this->Auth->user('date_of_birth');

        $this->set(compact ('isOwner', 'unitSettings', 'date_today', 'userDateOfBirth', 'profile_owner','graphRoom'));
    }

}


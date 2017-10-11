<?php

/**
 * MydiseaseController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');

/**
 * MydiseaseController for the user profile
 * 
 * MydiseaseController is used to show the "My Disease" in the profile page
 *
 * @author 	Ajay Arjunan
 * @package 	User
 * @category	Controllers 
 */
class MyconditionController extends ProfileController {

    protected $_mergeParent = 'ProfileController';
    public $uses = array('PatientDisease', 'Disease');

    /**
     * Profile -> My Health
     */
    public function index($username = null) {
        $this->_setUserProfileData();
        if(isset($this->_requestedUser['id'])) {
            $this->set('title_for_layout',$this->_requestedUser['username']."'s condition");
        } else {
            $this->set('title_for_layout',$this->Auth->user('username')."'s condition");
        }
        
        if ($this->_requestedUser['id'] != $this->Auth->user('id')) {
            $privacy = new UserPrivacySettings($this->_requestedUser['id']);
            
            $isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'], $this->Auth->user('id'));
            $viewSetting = array($privacy::PRIVACY_PUBLIC);
            
            if ($isFriend == MyFriends::STATUS_CONFIRMED) {
                array_push($viewSetting, $privacy::PRIVACY_FRIENDS);
            }
            
            if (!in_array($privacy->__get('view_your_disease'), $viewSetting)) {
                $this->redirect( Common::getUserProfileLink( $this->_requestedUser['username'], true) );
            }
        }
        $this->getDiseases();
    }

    public function getDiseases() {
        $userId = $this->_requestedUser['id'];
        $timezone = $this->Auth->user('timezone');
        $diseases = $this->PatientDisease->getUserDisease($userId);
        
        $diseaseIds = array();
        
        foreach ($diseases as $key => $disease) {
            if(in_array($disease['Diseases']['id'], $diseaseIds)) {
                unset($diseases[$key]);
            } else {
                $diseaseIds[] = $disease['Diseases']['id'];
            }
        }
        
        $this->set(compact('diseases'));
    }

}
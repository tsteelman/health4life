<?php

/**
 * FollowingController class file.
 * 
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');
App::import('Controller', 'Api');
App::uses('UserPrivacySettings', 'Lib');

/**
 * FollowingController for the frontend
 *
 * FollowingController is used for to carry out operations on friends
 *
 * @package 	User
 * @category	Controllers
 */
class FollowingController extends ProfileController {

    /**$userId
     * Models needed in the Controller
     *
     * @var array
     */
    protected $_mergeParent = 'ProfileController';
    public $uses = array(
        'User',
        'MyFriends',        
        'NotificationSetting',
        'Notification',
		'FollowingPage'
    );
    public $components = array(
        'Session'              
	);

	/**
	 * Profile -> Following
	 */
	public function index($username = null) {
		$this->_setUserProfileData();
		$user = $this->Auth->user();
		$userId = $user['id'];
        if (isset($this->_requestedUser['id'])) {
			$this->set('title_for_layout', $this->_requestedUser['username'] . "'s friends");
		} else {
			$this->set('title_for_layout', $this->Auth->user('username') . "'s friends");
		}               
		
		$diseaseFollowList = $this->FollowingPage->getFollowingDiseaseList($this->_requestedUser['id']);
		$profileFollowList = $this->FollowingPage->getFollowingProfileList($this->_requestedUser['id']);		
		
		$this->set(compact('diseaseFollowList', 'profileFollowList'));
	}
}
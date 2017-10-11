<?php

/**
 * MynutritionController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');

/**
 * MynutritionController for the user profile
 * 
 * MynutritionController is used to show the "My Disease" in the profile page
 *
 * @author 	Ajay Arjunan
 * @package 	User
 * @category	Controllers 
 */
class MynutritionController extends ProfileController {
        
        protected $_mergeParent = 'ProfileController';

	/**
	 * Profile -> My Health
	 */
	public function index($username = null) {
		$this->_setUserProfileData();
                if(isset($this->_requestedUser['id'])) {
                    $this->set('title_for_layout',$this->_requestedUser['username']."'s nutrition");
                } else {
                    $this->set('title_for_layout',$this->Auth->user('username')."'s nutrition");
                }
                
                if ($this->_requestedUser['id'] != $this->Auth->user('id'))
		{
			$privacy = new UserPrivacySettings($this->_requestedUser['id']);
			$isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'],
			$this->Auth->user('id'));
			$viewSetting = array($privacy::PRIVACY_PUBLIC);
			if ($isFriend == MyFriends::STATUS_CONFIRMED)
			{
				array_push($viewSetting, $privacy::PRIVACY_FRIENDS);
			}
			if (!in_array($privacy->__get('view_your_nutrition'), $viewSetting))
			{
				$this->redirect(Common::getUserProfileLink( $this->_requestedUser['username'], true));
			}
		}

	}
}
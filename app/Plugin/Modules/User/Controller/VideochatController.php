<?php

/**
 * VideochatController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');

/**
 * VideochatController for the user profile
 * 
 * VideochatController is used to show the video chat in the profile page
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class VideochatController extends ProfileController {

	/**
	 * Profile -> Video Chat
	 */
	public function index($username = null) {
		$this->_setUserProfileData();
                $this->set('title_for_layout',$this->Auth->user('username')."'s video chat");
                //we get online friends from ProfileController extends
	}
}
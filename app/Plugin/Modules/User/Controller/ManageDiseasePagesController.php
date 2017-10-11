<?php

/**
 * ManageDiseasePagesController class file.
 * 
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');

/**
 * ManageDiseasePagesController for the frontend
 * 
 * ManageDiseasePagesController is used for managing user blocking
 * 
 * @package 	User
 * @category	Controllers 
 */
class ManageDiseasePagesController extends UserAppController {

	public $uses = array('User','FollowingPage');

	/**
	 * Function to list the users blocked by the logged in user
	 */
	public function index() {
		$user = $this->Auth->user();
		$userId = $user['id'];
		$type = $user['type'];
		$userImage = Common::getUserThumb($userId, $type, 'medium', 'img-responsive pull-left img-thumbnail', 'url');
		$profilePhotoClass = Common::getUserThumbClass($type);

		$this->FollowingPage->unbindModel(
				array('belongsTo' => array('User'))
		);	
		$followList = $this->FollowingPage->find('all', array(
			'joins' => array(
				array(
					'table' => 'diseases',
					'alias' => 'Disease',
					'type' => 'INNER',
					'conditions' => array(
						'Disease.id = FollowingPage.page_id'
					)
				),
			),
			'fields' => array(
				'FollowingPage.*',
				'Disease.*'
			),
			'conditions' => array(
				'FollowingPage.type' => FollowingPage::DISEASE_TYPE,
				'FollowingPage.user_id' => $userId,
			)
				)
		);

		
		$this->set(compact('followList', 'userId', 'userImage', 'profilePhotoClass'));
	}

	
}

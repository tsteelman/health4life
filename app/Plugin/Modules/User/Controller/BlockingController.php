<?php

/**
 * BlockingController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');

/**
 * BlockingController for the frontend
 * 
 * BlockingController is used for managing user blocking
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class BlockingController extends UserAppController {

	public $uses = array('User');

	/**
	 * Function to list the users blocked by the logged in user
	 */
	public function index() {
		$this->set('title_for_layout',"Manage Blocking");
		$user = $this->Auth->user();
		$userId = $user['id'];
		$type = $user['type'];
		$userImage = Common::getUserThumb($userId, $type, 'medium', 'img-responsive pull-left img-thumbnail', 'url');
		$profilePhotoClass = Common::getUserThumbClass($type);

		$blockedUsers = array();
		if (!empty($user['blocked_users'])) {
			$blockedUsersJSON = $user['blocked_users'];
			$blockedUsersList = json_decode($blockedUsersJSON, true);
			$blockedUserIds = array();
			foreach ($blockedUsersList as $userIds) {
				$blockedUserIds = array_merge($blockedUserIds, $userIds);
			}
			$blockedUsers = $this->User->getUsersData($blockedUserIds);
		}

		if (!empty($blockedUsers)) {
			if (!empty($blockedUsersList['anonymous_messaging'])) {
				$this->__anonymousMessagingBlockedUsers = $blockedUsersList['anonymous_messaging'];
			}
			if (!empty($blockedUsersList['messaging'])) {
				$this->__messagingBlockedUsers = $blockedUsersList['messaging'];
			}
			$blockedUsers = $this->__getBlockedUsersData($blockedUsers);
		}
		$this->set(compact('userId', 'userImage', 'profilePhotoClass', 'blockedUsers'));
	}

	/**
	 * Function to get the data of the blocked users
	 * 
	 * @param array $blockedUsers
	 * @return array
	 */
	private function __getBlockedUsersData($blockedUsers) {
		foreach ($blockedUsers as &$userData) {
			$user = $userData['User'];
			$photo = Common::getUserThumb($user['id'], $user['type'], 'x_small');
			$userData['User']['photo'] = $photo;
			$userData['User']['link'] = Common::getUserProfileLink($user['username'], true);
			if (!empty($this->__messagingBlockedUsers) && in_array($user['id'], $this->__messagingBlockedUsers)) {
				$userData['blocked_message_type'] = 'any';
			} elseif (!empty($this->__anonymousMessagingBlockedUsers) && in_array($user['id'], $this->__anonymousMessagingBlockedUsers)) {
				$userData['blocked_message_type'] = 'anonymous';
			}
		}
		return $blockedUsers;
	}

	/**
	 * Function to unblock a user
	 */
	public function unblockUser() {
		$this->autoRender = false;
		$userId = $this->request->data['user_id'];
		$userBlockedUsersJSON = $this->Auth->user('blocked_users');
		$blockedUsers = json_decode($userBlockedUsersJSON, true);
		if (!empty($blockedUsers)) {
			$anonymousMessagingBlockedUsers = array();
			if (!empty($blockedUsers['anonymous_messaging'])) {
				$anonymousMessagingBlockedUsers = $blockedUsers['anonymous_messaging'];
				$userIndex = array_search($userId, $anonymousMessagingBlockedUsers);
				if ($userIndex !== false) {
					unset($anonymousMessagingBlockedUsers[$userIndex]);
					$blockedUsers['anonymous_messaging'] = $anonymousMessagingBlockedUsers;
				}
			}
			$messagingBlockedUsers = array();
			if (!empty($blockedUsers['messaging'])) {
				$messagingBlockedUsers = $blockedUsers['messaging'];
				$userIndex = array_search($userId, $messagingBlockedUsers);
				if ($userIndex !== false) {
					unset($messagingBlockedUsers[$userIndex]);
					$blockedUsers['messaging'] = $messagingBlockedUsers;
				}
			}

			$blockedUsersJSON = json_encode($blockedUsers);
			$currentUserId = $this->Auth->user('id');
			$this->User->id = $currentUserId;
			if ($this->User->saveField('blocked_users', $blockedUsersJSON)) {
				$currentUser = $this->User->read(null, $currentUserId);
				$this->Session->write('Auth', $currentUser);
			}
		}
	}
}
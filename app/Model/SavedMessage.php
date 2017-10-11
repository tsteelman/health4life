<?php

App::uses('AppModel', 'Model');

/**
 * SavedMessage Model
 *
 * @property UserMessage $UserMessage
 * @property SavedUser $SavedUser
 * @property OtherUser $OtherUser
 */
class SavedMessage extends AppModel {

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'UserMessage' => array(
			'className' => 'UserMessage',
			'foreignKey' => false,
			'conditions' => array(
				'SavedMessage.saved_user_id = UserMessage.current_user_id',
				'SavedMessage.other_user_id = UserMessage.other_user_id',
				'UserMessage.id <= SavedMessage.user_message_id'
			),
			'fields' => '',
			'order' => ''
		),
		'SavedUser' => array(
			'className' => 'User',
			'foreignKey' => 'saved_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'OtherUser' => array(
			'className' => 'User',
			'foreignKey' => 'other_user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	/**
	 * Function to get the saved message id of a user with other user
	 * 
	 * @param int $savedUserId
	 * @param int $otherUserId
	 * @return int/null
	 */
	public function getSavedMessageId($savedUserId, $otherUserId) {
		$this->recursive = -1;
		$savedMessage = $this->find('first', array(
			'conditions' => array(
				'saved_user_id' => $savedUserId,
				'other_user_id' => $otherUserId
			),
			'fields' => "{$this->alias}.id"
		));

		if (!empty($savedMessage)) {
			return $savedMessage[$this->alias]['id'];
		} else {
			return null;
		}
	}

	/**
	 * Function to save message conversations between a user and a set of users
	 * 
	 * @param array $users
	 * @param int $userId
	 */
	public function saveConversations($users, $userId) {
		$conversations = array();
		foreach ($users as $otherUserId) {
			$savedMessageId = $this->getSavedMessageId($userId, $otherUserId);
			$userMessageId = $this->UserMessage->getUsersLatestMessageId($userId, $otherUserId);
			if ($userMessageId > 0) {
				$conversations[] = array(
					'id' => $savedMessageId,
					'user_message_id' => $userMessageId,
					'saved_user_id' => $userId,
					'other_user_id' => $otherUserId,
				);
			}
		}

		if (!empty($conversations)) {
			$this->saveMany($conversations);
		}
	}

	/**
	 * Function to get the saved messages of a user
	 * 
	 * @param int $userId
	 * @param string $searchTerm
	 * @return array
	 */
	public function getUserSavedMessages($userId, $searchTerm = '') {
		$db = $this->getDataSource();
		$conditions = 'sm.saved_user_id = :saved_user_id
						AND um.`id` <= sm.user_message_id';
		$params = array(
			'saved_user_id' => $userId,
		);

		$searchTerm = trim($searchTerm);
		if ($searchTerm !== '') {
			$conditions.=' AND (
				um.message LIKE :searchTerm 
				OR u.username LIKE :searchTerm
				)';
			$params['searchTerm'] = "%{$searchTerm}%";
		}

		$messages = $db->fetchAll(
				"SELECT * FROM (
					SELECT u.username, u.type AS user_type, um.id message_id, 
					um.message, um.created, um.other_user_id user_id,
					sm.created AS saved_time
					FROM `user_messages` um
					JOIN users u ON um.other_user_id = u.id
					JOIN saved_messages sm 
						ON sm.saved_user_id = um.`current_user_id`
						AND sm.other_user_id = um.`other_user_id`
					WHERE {$conditions}
					ORDER BY um.`created` DESC
				) AS message
				GROUP BY user_id
				ORDER BY saved_time DESC", $params
		);

		return $messages;
	}

	/**
	 * Function to delete the saved messages of a user with other user(s)
	 * 
	 * @param int $userId
	 * @param array $otherUsers
	 * @return boolean
	 */
	public function deleteMessages($userId, $otherUsers) {
		$conditions = array(
			"{$this->alias}.saved_user_id" => $userId,
			"{$this->alias}.other_user_id" => $otherUsers
		);
		$this->recursive = -1;
		$savedMessages = $this->find('all', array(
			'conditions' => $conditions
		));
		foreach ($savedMessages as $savedMessage) {
			$userMessageId = $savedMessage[$this->alias]['user_message_id'];
			$otherUserId = $savedMessage[$this->alias]['other_user_id'];
			$this->UserMessage->deleteAll(array(
				'UserMessage.current_user_id' => $userId,
				'UserMessage.other_user_id' => $otherUserId,
				'UserMessage.is_deleted' => UserMessage::STATUS_DELETED,
				'UserMessage.id <=' => $userMessageId,
			));
		}

		return $this->deleteAll($conditions, false);
	}

	/**
	 * Function to get the pagination settings to get the saved conversations of
	 * one user with another user
	 * 
	 * @param int $savedUserId
	 * @param int $otherUserId
	 * @return array
	 */
	public function getConversationPaginationSettings($savedUserId, $otherUserId) {
		return array(
			'limit' => 10,
			'conditions' => array(
				"{$this->alias}.saved_user_id" => $savedUserId,
				"{$this->alias}.other_user_id" => $otherUserId,
			),
			'fields' => array(
				'OtherUser.id', 'OtherUser.username', 'OtherUser.type',
				'SavedUser.id', 'SavedUser.username', 'SavedUser.type',
				'UserMessage.message', 'UserMessage.direction',
				'UserMessage.created'
			),
			'order' => 'UserMessage.created DESC'
		);
	}
}
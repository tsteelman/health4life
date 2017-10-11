<?php

App::uses('AppModel', 'Model');

/**
 * UserMessage Model
 *
 * @property CurrentUser $CurrentUser
 * @property OtherUser $OtherUser
 * @property SavedMessage $SavedMessage
 */
class UserMessage extends AppModel
{
	/**
	 * Message direction values
	 */
	const DIRECTION_IN = 0;
	const DIRECTION_OUT = 1;

	/**
	 * Read/Unread status values
	 */
	const STATUS_READ = 1;
	const STATUS_UNREAD = 0;

	/**
	 * Deleted/Not Deleted status values
	 */
	const STATUS_DELETED = 1;
	const STATUS_NOT_DELETED = 0;
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'CurrentUser' => array(
			'className' => 'User',
			'foreignKey' => 'current_user_id',
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
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'SavedMessage' => array(
			'className' => 'SavedMessage',
			'foreignKey' => 'user_message_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	/**
	 * Function to get the count of unread inbox messages of a user
	 * 
	 * @param int $userId
	 * @return int
	 */
	public function getInboxMessagesCount($userId)
	{
		$this->recursive = -1;
		$inboxMessagesCount = $this->find('count', array(
			'conditions' => array(
				'UserMessage.current_user_id' => $userId,
				'UserMessage.direction' => self::DIRECTION_IN,
				'UserMessage.is_read' => self::STATUS_UNREAD,
				'UserMessage.is_deleted' => self::STATUS_NOT_DELETED
			),
			'group' => 'other_user_id'
		));
		return $inboxMessagesCount;
	}

	/**
	 * Function to get the messages of a user
	 * 
	 * @param int $userId
	 * @param int $direction
	 * @param string $searchTerm
	 * @return array
	 */
	public function getMessages($userId, $direction, $searchTerm = '')
	{
		$db = $this->getDataSource();
		$searchTerm = trim($searchTerm);
		$conditions = 'm.current_user_id = :current_user_id
			AND m.direction = :direction 
			AND m.is_deleted = :is_deleted';
		$params = array(
			'current_user_id' => $userId,
			'direction' => $direction,
			'is_deleted' => self::STATUS_NOT_DELETED
		);
		if($searchTerm !== '')
		{
			$conditions.=' AND (
				m.message LIKE :searchTerm 
				OR u.username LIKE :searchTerm
				)';
			$params['searchTerm'] = "%{$searchTerm}%";
		}

		$fieldList = array(
			'u.username', 'u.type user_type', 'm.id message_id',
			'm.message', 'm.created', 'm.other_user_id user_id'
		);
		if($direction === self::DIRECTION_IN)
		{
			$fieldList[] = 'm.is_read';
		}
		$fields = join(', ', $fieldList);

		$messages = $db->fetchAll(
				"SELECT * FROM (
					SELECT {$fields}
					FROM user_messages m
					JOIN users u ON m.other_user_id = u.id
					WHERE {$conditions}
					ORDER BY m.created DESC
				) AS message GROUP BY user_id ORDER BY created DESC", $params
		);

		return $messages;
	}

	/**
	 * Function to get the inbox messages of a user, group by the sender users
	 * 
	 * @param int $userId
	 * @param string $searchTerm
	 * @return array
	 */
	public function getInboxMessages($userId, $searchTerm = '')
	{
		$direction = self::DIRECTION_IN;
		$messages = $this->getMessages($userId, $direction, $searchTerm);
		return $messages;
	}

	/**
	 * Function to get the messages sent by a user, group by the receiver users
	 * 
	 * @param int $userId
	 * @param string $searchTerm
	 * @return array
	 */
	public function getOutboxMessages($userId, $searchTerm = '')
	{
		$direction = self::DIRECTION_OUT;
		$messages = $this->getMessages($userId, $direction, $searchTerm);
		return $messages;
	}

	/**
	 * Deletes the conversations between a user and other user(s)
	 * 
	 * @param int $userId
	 * @param array $otherUsers
	 * @return boolean
	 */
	public function deleteConversations($userId, $otherUsers)
	{
		$fields = array(
			'is_deleted' => self::STATUS_DELETED
		);
		$conditions = array(
			'current_user_id' => $userId,
			'other_user_id' => $otherUsers
		);
		return $this->updateAll($fields, $conditions);
	}

	/**
	 * Function to the id of latest message between 2 users
	 * 
	 * @param int $userId
	 * @param int $otherUserId
	 * @return int
	 */
	public function getUsersLatestMessageId($userId, $otherUserId)
	{
		$this->recursive = -1;
		$latestMessage = $this->find('first', array(
			'conditions' => array(
				'current_user_id' => $userId,
				'other_user_id' => $otherUserId,
				'is_deleted' => self::STATUS_NOT_DELETED
			),
			'fields' => array("MAX({$this->alias}.id) AS latestMessageId")
		));

		if(!empty($latestMessage))
		{
			return $latestMessage[0]['latestMessageId'];
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Function to mark the messages of one user with another user as read
	 * 
	 * @param int $currentUserId
	 * @param int $otherUserId
	 * @return boolean
	 */
	public function markMessagesAsRead($currentUserId, $otherUserId)
	{
		$fields = array(
			'is_read' => self::STATUS_READ
		);
		$conditions = array(
			'current_user_id' => $currentUserId,
			'other_user_id' => $otherUserId
		);
		return $this->updateAll($fields, $conditions);
	}

	/**
	 * Function to realtime notify user about the unread message count
	 * 
	 * Emits 'notify_user' event to users socket for realtime notification
	 * 
	 * @param int $userId
	 * @param int $unreadMessageCount
	 */
	public function realTimeNotifyUser($userId, $unreadMessageCount = null) {
		if (is_null($unreadMessageCount)) {
			$unreadMessageCount = $this->getInboxMessagesCount($userId);
		}
		App::import('Vendor', 'elephantio/client');
		$elephant = new ElephantIO\Client(Configure::read('SOCKET.URL'), 'socket.io', 1, false, true, true);
		$elephant->init();
		$elephant->emit('notify_user', array(
			'user_id' => $userId,
			'notification_name' => 'unread_message_count',
			'notification_count' => $unreadMessageCount
		));
		$elephant->close();
	}
}
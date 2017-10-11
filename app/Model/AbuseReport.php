<?php

App::uses('AppModel', 'Model');

/**
 * AbuseReport Model
 */
class AbuseReport extends AppModel {

	/**
	 *  Object type constants
	 */
	const OBJECT_TYPE_POST = 'post';
	const OBJECT_TYPE_COMMENT = 'comment';

	/**
	 * Status constants
	 */
	const STATUS_NEW = 'new';
	const STATUS_REJECTED = 'rejected';
	const STATUS_DELETED = 'deleted';

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'ReportedUser' => array(
			'className' => 'User',
			'foreignKey' => 'reported_user_id',
			'fields' => array('username', 'email')
		),
		'ObjectOwner' => array(
			'className' => 'User',
			'foreignKey' => 'object_owner_id',
			'fields' => array('username', 'email')
		),
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'object_id',
			'type' => 'LEFT',
			'conditions' => array(
				'AbuseReport.object_id = Post.id',
				'AbuseReport.object_type' => AbuseReport::OBJECT_TYPE_POST
			),
			'fields' => array('content')
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'object_id',
			'type' => 'LEFT',
			'conditions' => array(
				'AbuseReport.object_id = Comment.id',
				'AbuseReport.object_type' => AbuseReport::OBJECT_TYPE_COMMENT
			),
			'fields' => array('comment_text', 'post_id')
		)
	);

	/**
	 * Function to get object types
	 * 
	 * @return array
	 */
	public static function getObjectTypes() {
		$objectTypes = array(
			self::OBJECT_TYPE_POST => __('Post'),
			self::OBJECT_TYPE_COMMENT => __('Comment')
		);
		return $objectTypes;
	}

	/**
	 * Function to add abuse report for a post
	 * 
	 * @param array $comment
	 * @param int $reportedUserId 
	 * @param string $reason
	 */
	public function addCommentAbuseReport($comment, $reportedUserId, $reason) {
		$data = array(
			'object_id' => $comment['id'],
			'object_type' => self::OBJECT_TYPE_COMMENT,
			'reported_user_id' => $reportedUserId,
			'object_owner_id' => $comment['created_by'],
			'reason' => $reason
		);
		$this->save($data);
	}

	/**
	 * Function to add abuse report for a post
	 * 
	 * @param array $post
	 * @param int $reportedUserId 
	 * @param string $reason
	 */
	public function addPostAbuseReport($post, $reportedUserId, $reason) {
		$data = array(
			'object_id' => $post['id'],
			'object_type' => self::OBJECT_TYPE_POST,
			'reported_user_id' => $reportedUserId,
			'object_owner_id' => $post['post_by'],
			'reason' => $reason
		);
		$this->save($data);
	}

	/**
	 * Function to get the count of new abuse reports
	 * 
	 * @return int
	 */
	public function getNewAbuseReportsCount() {
		$query = array(
			'conditions' => array("$this->alias.status" => self::STATUS_NEW)
		);
		return $this->find('count', $query);
	}

	/**
	 * Function to get the count of new abuse reports against a user
	 * 
	 * @param int $userId
	 * @return int 
	 */
	public function getUserAbuseReportCount($userId) {
		$this->recursive=-1;
		$query = array(
			'conditions' => array(
				'object_owner_id' => $userId,
				'status' => self::STATUS_NEW
			)
		);
		return $this->find('count', $query);
	}
}
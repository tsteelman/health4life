<?php

App::uses('AppModel', 'Model');

/**
 * Comment Model
 *
 * @property Post $Post
 * @property User $User
 */
class Comment extends AppModel {
	
	/**
	 * Comment status constants
	 */
	const STATUS_NORMAL = 0;
	const STATUS_ABUSE_REPORTED = 1;
	const STATUS_BLOCKED = 2;

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
    public $belongsTo = array(
        'Post' => array(
            'className' => 'Post',
            'foreignKey' => 'post_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
			'counterCache' => array(
				'comment_count' => array('Comment.status' => self::STATUS_NORMAL)
			)
		),
		'User' => array(
            'className' => 'User',
            'foreignKey' => 'created_by',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

	/**
	 * Function to get the comments for a post
	 * 
	 * @param int $postId
	 * @return array
	 */
	public function getPostComments($postId) {
		$query = array(
			'conditions' => array(
				'Comment.post_id' => $postId,
				'Comment.status' => self::STATUS_NORMAL
			),
			'order' => array('Comment.created' => 'desc')
		);
		return $this->find('all', $query);
	}

    /**
     * Function to get the latest comments for a post
     * 
     * @param int $postId
     * @param int $limit
     * @return array
     */
    public function getLatestPostComments($postId, $limit) {
        $latestComments = $this->find('all', array(
			'conditions' => array(
				'Comment.post_id' => $postId,
				'Comment.status' => self::STATUS_NORMAL
			),
			'order' => array('Comment.created' => 'desc'),
			'limit' => $limit
		));

		return $latestComments;
	}

	/**
	 * Function to get the id of the users who commented on a post
	 * 
	 * @param int $postId
	 * @return array
	 */
	public function getPostCommentedUserIds($postId) {
		$query = array(
			'conditions' => array("{$this->alias}.post_id" => $postId),
			'fields' => array("{$this->alias}.created_by")
		);
		return $this->find('list', $query);
	}
}
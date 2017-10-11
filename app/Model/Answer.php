<?php

App::uses('AppModel', 'Model');

/**
 * Answer Model
 *
 * @property Post $Post
 * @property User $User
 */
class Answer extends AppModel {

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
			'counterCache' => true
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
	 * Function to get the answers of a post
	 * 
	 * @param int $postId
	 * @param int $limit
	 * @return array
	 */
	public function getPostAnswers($postId, $limit = null) {
		$query = $this->getPostAnswersQuery($postId);
		if (!is_null($limit)) {
			$query['limit'] = $limit;
		}
		$answers = $this->find('all', $query);
		return $answers;
	}

	/**
	 * Function to get the query to get answers of a post
	 * 
	 * @param int $postId
	 * @return array
	 */
	public function getPostAnswersQuery($postId) {
		$query = array(
			'conditions' => array(
				'Answer.post_id' => $postId
			),
			'order' => array('Answer.created' => 'desc')
		);
		return $query;
	}
}
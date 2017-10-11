<?php

App::uses('AppModel', 'Model');

/**
 * Poll Model
 *
 * @property User $User
 */
class PollVote extends AppModel {
    /**
     * Posted in types
     */

    const POSTED_IN_TYPE_COMMUNITIES = 1;

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
    ));
    public $hasMany = array(
        'PollChoices' => array(
            'className' => 'PollChoices',
            'foreignKey' => 'poll_id',
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

    function isUserVoted($pollId, $userId) {
		$conditions = array('PollVote.user_id' => $userId, 'PollVote.poll_id' => $pollId);
		return $this->hasAny($conditions);
	}

    function getPollVoteDetails($pollId) {
        $conditions = array('PollVote.poll_id' => $pollId);
        if ($this->hasAny($conditions)) {
            $votes = $this->find('all', array(
                'conditions' => $conditions
            ));
            return $votes;
        } else {
            return false;
        }
    }
	
	/*
     * Function to get uses who voted the given poll
     *
     * @param $pollId
     * @return array
     */
	function getPollAttendedUsers($pollId) {
		$votedUsers = $this->find('list', array(
            'conditions' => array(
                "PollVote.poll_id" => $pollId
            ),
            'fields' => array('PollVote.user_id')
        ));
		foreach ($votedUsers as $key => $value) {
				$userIds[] = $value;
		}
        return $userIds;
	}

}
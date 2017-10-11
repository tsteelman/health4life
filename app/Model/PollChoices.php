<?php

App::uses('AppModel', 'Model');

/**
 * Poll Model
 *
 * @property User $User
 */
class PollChoices extends AppModel {
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
        'Poll' => array(
            'className' => 'Poll',
            'foreignKey' => 'poll_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    public function changeVoteCount($optionId) {
        $votes_count = $this->find('first', array(
            'conditions' => array('PollChoices.id' => $optionId),
            'fields' => array('votes')
        ));

        $votes_count = $votes_count['PollChoices']['votes'] + 1;
        $this->id = $optionId;
        $this->set('votes', $votes_count);
        //Save members count in communitys table.
        if ($this->save()) {
            $result['success'] = true;
        } else {
            $result['success'] = false;
        }

        return $result;
    }

    public function getVoteDetails($pollId) {
        $votes = $this->find('all', array(
            'conditions' => array('PollChoices.poll_id' => $pollId)
        ));
        $totalChoices = count($votes);
        $totalVotes = 0;
        if (isset($votes) && $totalChoices > 0) {
            foreach ($votes as $vote) {
                $totalVotes = $totalVotes + $vote['PollChoices']['votes'];
            }
            $result = array(
                'totalChoices' => $totalChoices,
                'totalVotes' => $totalVotes
            );
            return $result;
        }  else {
            return false;
        }
    }

}
<?php

App::uses('AppModel', 'Model');

/**
 * Poll Model
 *
 * @property User $User
 */
class Poll extends AppModel {

    /**
     * Posted in types
     */
    const POSTED_IN_TYPE_COMMUNITIES = 1;

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a ntitle or subject for the poll.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 300),
                'message' => 'Cannot be more than 300 characters long.'
            )
        )
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
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
        ),
        'Post' => array(
            'className' => 'Post',
            'foreignKey' => 'post_type_id',
            'conditions' => array(
                'post_type' => 'poll'
            ),
            'fields' => '',
            'order' => ''
        )
    );

    function getPoll($pollId) {
        $poll = $this->find('first', array(
            'conditions' => array('Poll.id' => $pollId)
        ));
//        echo '<pre>';
//        print_r($poll);
//        exit;
        if (isset($poll) && $poll['Poll']['id'] != NULL) {
            return $poll;
        } else {
            return false;
        }
    }

//    function isPollDeleted($pollId) {
//        $poll = $this->find('first', array(
//            'conditions' => array('Poll.id' => $pollId)
//        ));
////        echo '<pre>';
////        print_r($poll);
////        exit;
//        if (isset($poll) && $poll != NULL) {
//            return $poll;
//        } else {
//            return false;
//        }
//    }
}

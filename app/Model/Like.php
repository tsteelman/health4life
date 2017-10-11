<?php

App::uses('AppModel', 'Model');

/**
 * Like Model
 *
 * @property Post $Post
 * @property User $User
 */
class Like extends AppModel {

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
}
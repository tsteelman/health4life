<?php

App::uses('AppModel', 'Model');

/**
 * PrelaunchUser model
 * 
 */

class PrelaunchUser extends AppModel {
    
    /**
     *  Validation rules
     * 
     *  @var array 
     */
    public $validate = array(
        'name' => array(
                'notEmpty' => array(
                        'rule' => array('notEmpty'),
                        'message' => 'Please enter your name'
                )
        ),
        'email' => array(
                'required' => array(
                        'rule' => array('notEmpty'),
                        'message' => 'Please enter  your email address'
                ),
                'email' => array(
                        'rule' => '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/',
                        'message' => 'Please enter a valid email id'
                )
        )
    );
    
        
}
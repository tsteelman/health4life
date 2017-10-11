<?php

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * ContactUsForm Model
 *
 */
class ContactUsForm extends AppModel {

    public $useTable = false; // This model does not use a database table
    public $validate = array(
        'firstName' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'This field is required'
            )
        ),
        'email' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'This field is required'
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Please enter a valid email id'
            )
        ),
        'enquiry' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter your comment or question'
            )
        )
    );

}

?>
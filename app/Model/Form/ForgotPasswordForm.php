<?php

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * ResetPasswordForm Model
 *
 */
class ForgotPasswordForm extends AppModel {

    public $useTable = false; // This model does not use a database table
    public $validate = array(
        'email' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'This field is required'
            ),
            'email' => array(
                'rule' => array('email'),
                'message' => 'Please enter a valid email id'
            )
        )
    );

}

?>
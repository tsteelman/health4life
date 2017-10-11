<?php

App::uses('AppModel', 'Model');

/**
 * UserSettingsForm Model
 *
 */
class ChangePasswordForm extends AppModel {

    public $useTable = false; // This model does not use a database table

    /**
     * Validations
     * 
     * @var array
     */
    public $validate = array(
        'current_password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter your current password'
            )
        ),
        'new_password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter your new password'
            ),
            'minLength' => array(
                'rule' => array('minLength', 6),
                'message' => 'Enter a password with a minimum of 6 characters.'
            )
        ),
        'confirm_password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please confirm the password.'
            ),
            'equalTo' => array(
                'rule' => array('equalTo', 'new_password'),
                'message' => 'The passwords do not match. Please try again.'
            )
        )
    );

}

?>
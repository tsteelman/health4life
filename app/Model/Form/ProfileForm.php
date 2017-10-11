<?php

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * ResetPasswordForm Model
 *
 */
class ProfileForm extends AppModel {

    public $useTable = false; // This model does not use a database table
    public $validate = array(
        'current_password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter your current password'
            ),
            'minLength' => array(
                'rule' => array('minLength', '6'),
                'message' => 'Minimum 6 characters long'
            )
        ),
        'new_password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter your new password'
            ),
            'minLength' => array(
                'rule' => array('minLength', '6'),
                'message' => 'Minimum 6 characters long'
            )
        ),
        'confirm_password' => array(
            'equalTo' => array(
                'rule' => array('equalTo', 'password'),
                'message' => 'Should be equal to new password'
            )
        )
    );

}
?>
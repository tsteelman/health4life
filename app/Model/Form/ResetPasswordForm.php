<?php

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');

/**
 * ResetPasswordForm Model
 *
 */
class ResetPasswordForm extends AppModel {

    public $useTable = false; // This model does not use a database table
    public $validate = array(
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password is required'
            ),
            'minLength' => array(
                'rule' => array('minLength', '6'),
                'message' => 'Minimum 6 characters long'
            )
        ),
        'confirm-password' => array(
            'equalTo' => array(
                'rule' => array('equalTo', 'password'),
                'message' => 'Entered passwords do not match'
            )
        )
    );

}
?>
<?php

App::uses('AppModel', 'Model');

/**
 * UserSettingsForm Model
 *
 */
class UserSettingsForm extends AppModel {

    public $useTable = false; // This model does not use a database table

    /**
     * Validations
     * 
     * @var array
     */
    public $validate = array(
        'timezone' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select your timezone'
            )
        )
    );
}
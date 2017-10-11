<?php

App::uses('AppModel', 'Model');

/**
 * Appoinment Model
 *
 */
class AppoinmentForm extends AppModel {

    public $useTable = false; // This model does not use a database table

    /**
     * Validations
     * 
     * @var array
     */
    public $validate = array(
        'doctor_name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter your doctor name'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 50),
                'message' => 'Cannot be more than 50 characters long.'               
            )
        ),
        'appoinment_date' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a date for the appoinment'
            )
        ),
        'appoinment_time' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a time for the appoinment'
            ),
            'regex' => array(
				'rule' => '/(([0-9]|[1][012])\:[0-5][0|5]\s(a|p)m)$/i',
                'message' => 'Please enter a valid time'
            )
        ),
        'appoinment_reason' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 150),
                'message' => 'Cannot be more than 150 characters long.',
                'allowEmpty' => true
            )
        ),
        'repeat' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select event type'
            )
        ),
        'end_date' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty', 'dependentField' => 'repeat_end_type', 'dependentValue' => Event::REPEAT_END_DATE, 'isRadio' => true),
                'message' => 'Please enter end date for the event'
            )
        ),
         'repeat_end_type' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please specify the end for the event'
            )
        ),
    );

}

?>
<?php

App::uses('AppModel', 'Model');

/**
 * Task updation form
 *
 */
class TaskUpdationForm extends AppModel {
    
        /**
         * This model does not use a database table
         */
        public $useTable = false;
    
        public $validate = array(
            'note' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please let the team know why you are changing the status of this task.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 300),
				'message' => 'Cannot be more than 300 characters long.'
			)
            )
        );
}
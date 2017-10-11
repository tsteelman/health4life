<?php
App::uses ( 'AppModel', 'model' );

/**
 * User edit profile form
 */
class UserEditForm extends AppModel {
	public $useTable = false; // This model does not use a database table
	
	public $validate = array(
			'first_name' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'Please enter your First Name.'
					),
					'regex' => array(
							'rule' => '/^([a-zA-Z \']+[\-]*)+$/',
							'message' => 'Only alphabets, space, hyphen and “ ’ ” is allowed.'
					),
					'maxLength' => array(
							'rule' => array('maxLength', 30),
							'message' => 'Maximum 30 characters'
					)
			),
			'last_name' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'Please enter your Last Name.'
					),
					'regex' => array(
							'rule' => '/^([a-zA-Z \']+[\-]*)+$/',
							'message' => 'Only alphabets, space, hyphen and “ ’ ” is allowed.'
					),
					'maxLength' => array(
							'rule' => array('maxLength', 30),
							'message' => 'Maximum 30 characters'
					)
			),
			'dob-year' => array(
					'age' => array(
							'rule' => array('dob'),
							'message' => 'Minimum age limit is 13 years',
							'allowEmpty' => true
					),
			),
			'dob-month' => array(
					'age' => array(
							'rule' => array('dob'),
							'message' => 'Minimum age limit is 13 years',
							'allowEmpty' => true
					),
			),
			'dob-day' => array(
					'age' => array(
							'rule' => array('dob'),
							'message' => 'Minimum age limit is 13 years',
							'allowEmpty' => true
					),
			),
                        'aboutMe' => array(
                                        'maxLength' => array(
                                                        'rule' => array('maxLength', 150),
                                                        'message' => 'Cannot be more than 150 characters long.',
                                                        'allowEmpty' => true
                                        ),
                        ),
			'gender' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'Please select a Gender.'
					)
			),
			'country' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'Please select a country.'
					)
			),
			'state' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'Please select a state/province.'
					)
			),
			'city' => array(
					'required' => array(
							'rule' => array('notEmpty'),
							'message' => 'Please select a city.'
					)
			),
			'zip' => array(
					'maxLength' => array(
					'rule' => array('maxLength', 15),
					'message' => 'Zip cannot exceed 15 characters.',
					'allowEmpty' => true
				)
			)
	);
}
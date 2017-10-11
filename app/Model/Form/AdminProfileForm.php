<?php

App::uses('AppModel', 'Model');

/**
 * AdminProfileForm Model
 *
 */
class AdminProfileForm extends AppModel {

	/**
	 * This model does not use a database table
	 */
	public $useTable = false;

	/**
	 * Validations
	 * 
	 * @var array 
	 */
	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Username is required.'
			),
			'minLength' => array(
				'rule' => array('minLength', 5),
				'message' => 'Minimum 5 characters.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 30),
				'message' => 'Maximum 30 characters.'
			),
			'regex' => array(
				'rule' => '/^[a-z][a-z0-9]*$/i',
				'message' => 'Should start with an alphabet and only alphanumeric are allowed with a maximum of 30 characters.'
			),
			'remote' => array(
				'rule' => array('remote', '/api/checkExistingUsername', 'username'),
				'message' => 'This username is already taken, please select another one.'
			),
		),
		'email' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Email is required.'
			),
			'email' => array(
				'rule' => '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/',
				'message' => 'Please enter a valid email id'
			),
			'remote' => array(
				'rule' => array('remote', '/api/checkExistingEmail', 'email'),
				'message' => 'Possible errors: Invalid email address or Email address exists in our system. Please use a different email to create an account.'
			)
		),
		'first_name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter first name.'
			),
			'regex' => array(
				'rule' => '/^[a-zA-Z \']+$/',
				'message' => 'Only alphabets, space, and “ ’ ” is allowed.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 30),
				'message' => 'Maximum 30 characters.'
			)
		),
		'last_name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter last name.'
			),
			'regex' => array(
				'rule' => '/^[a-zA-Z \']+$/',
				'message' => 'Only alphabets, space, and “ ’ ” is allowed.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 30),
				'message' => 'Maximum 30 characters.'
			)
		),
		'date_of_birth' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please select birth date.'
			),
			'age' => array(
				'rule' => array('dob'),
				'message' => 'Minimum age limit is 13 years',
			)
		),
		'gender' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please select gender.'
			)
		),
		'timezone' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please select timezone.'
			)
		)
	);

}
<?php

App::uses('AppModel', 'Model');

/**
 * HealthSurveyForm Model
 *
 */
class HealthSurveyForm extends AppModel {

	public $useTable = false; // This model does not use a database table
	public $validate = array(
		'firstname' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'lastname' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'address' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'city' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'state' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'zipcode' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'country' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'mobile' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'email' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'gender' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please select your gender'
			)
		),
		'dob_year' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'dob_month' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'dob_day' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'race' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		),
		'blood_rh_type' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please fill out this field'
			)
		)
	);

}
<?php

App::uses('AppModel', 'Model');

/**
 * AdminChangePasswordForm Model
 *
 */
class AdminChangePasswordForm extends AppModel {

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
		'current_password' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Current password is required.'
			),
			'remote' => array(
				'rule' => array('remote', '/api/checkCurrentPassword', 'current_password'),
				'message' => 'Current password entered is wrong.'
			)
		),
		'new_password' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'New password is required.'
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
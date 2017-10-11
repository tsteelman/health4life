<?php

App::uses('AppModel', 'Model');

/**
 * SurgeryForm Model
 *
 */
class SurgeryForm extends AppModel {

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
		'year' => array(
			'isValidYear' => array(
				'rule' => array('isValidYear'),
				'message' => 'Please enter a valid year',
				'allowEmpty' => true
			)
		),
		'type' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Surgery type is required'
			)
		)
	);

}
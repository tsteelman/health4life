<?php

App::uses('AppModel', 'model');

/**
 * MedicationSchedulerForm
 */
class MedicationSchedulerForm extends AppModel {

	/**
	 * This model does not use any table
	 * 
	 * @var bool
	 */
	public $useTable = false;

	/**
	 * Validations for this form
	 * 
	 * @var array
	 */
	public $validate = array(
		'medicine_name' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter medication name.'
			)
		),
		'amount' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter amount of medicine to be given each time.'
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please enter a valid amount.',
				'allowEmpty' => true
			),
			'min' => array(
				'rule' => array('min', 0.01),
				'message' => 'Please enter a valid amount.',
				'allowEmpty' => true
			)
		)
	);

	/**
	 * Medicine dosage units
	 * 
	 * @var array
	 */
	public static $dosageUnits = array(
		'mg' => 'Milligram',
		'iu' => 'Internation unit',
		'mcg' => 'Micrograms',
		'cc' => 'Cubic Centimeter',
		'meq' => 'Milliequivalent',
		'meq/ml' => 'Milliequivalent per milliliter',
		'mg/ml' => 'Milligram per milliliter',
		'ml' => 'Milliliter',
		'%' => 'Percent',
		'unt' => 'Unit',
		'unt/ml' => 'Units per milliliter',
		'g' => 'Gram',
		'gr' => 'Grain',
		'TbSp' => 'Teaspoon'
	);

	/**
	 * Medicine forms
	 * 
	 * @var array
	 */
	public static $medicineForms = array(
		1 => 'Capsules',
		2 => 'Tablets',
		3 => 'Chewables',
		4 => 'Solution',
		5 => 'Aerosol',
		6 => 'Ointment',
		7 => 'Lotion',
		8 => 'Puffs',
		9 => 'Drops',
		10 => 'Dropperfuls',
		11 => 'Applicatorfuls',
		12 => 'Lozenges',
		13 => 'Packets',
		14 => 'Pads',
		15 => 'Patches',
		16 => 'Sprays',
		17 => 'Suppositories',
		18 => 'Bags'
	);

	/**
	 * Medicine routes
	 * 
	 * @var array
	 */
	public static $medicineRoutes = array(
		1 => 'By mouth (p.o.)',
		2 => 'Intramuscular (IM)',
		3 => 'Topical (top.)',
		4 => 'Intravenous (IV)',
		5 => 'Subcutaneous (s.q.)',
		6 => 'Intradermal (I.D.)',
		7 => 'Inhaled',
		8 => 'Right ear (a.d.)',
		9 => 'Left ear (a.s.)',
		10 => 'Right eye (o.d.)',
		11 => 'Left eye (o.s.)',
		12 => 'Sublingual (s.l.)',
		13 => 'Intranasal',
		14 => 'Rectal (per rectum or p.r.)',
		15 => 'Through a feeding tube',
		16 => 'Vaginally'
	);

	/**
	 * Function to get dosage units list
	 * 
	 * @return array
	 */
	public static function getDosageUnits() {
		return self::$dosageUnits;
	}

	/**
	 * Function to get medicine forms list
	 * 
	 * @return array
	 */
	public static function getMedicineForms() {
		return self::$medicineForms;
	}

	/**
	 * Function to get medicine routes list
	 * 
	 * @return array
	 */
	public static function getMedicineRoutes() {
		return self::$medicineRoutes;
	}

	/**
	 * Function to get repeat frequency list
	 * 
	 * @return array
	 */
	public static function getRepeatFrequency() {
		$repeatFrequency = array(
			'DAILY:1' => 'Every day',
			'DAILY:2' => 'Every other day',
			'WEEKLY:1' => 'Every week',
			'WEEKLY:2' => 'Every two weeks'
		);
		return $repeatFrequency;
	}

	/**
	 * Function to get medicine form name from form key
	 * 
	 * @param int $formKey
	 * @return string 
	 */
	public static function getMedicineFormName($formKey) {
		return self::$medicineForms[$formKey];
	}

	/**
	 * Function to get medicine route name from route key
	 * 
	 * @param int $routeKey
	 * @return string 
	 */
	public static function getMedicineRouteName($routeKey) {
		return self::$medicineRoutes[$routeKey];
	}
}
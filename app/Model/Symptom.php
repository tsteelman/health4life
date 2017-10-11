<?php
App::uses('AppModel', 'Model');
App::import('Controller', 'Api');
/**
 * Symptom Model
 *
 */
class Symptom extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';
	
	public $hasMany = array(
		'UserSymptom' => array(
			'className' => 'UserSymptom',
			'foreignKey' => 'symptom_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	public $validate = array(
			'id' => array(
	
			),
			'name' => array(
					'notEmpty' => array(
							'rule' => array('notEmpty'),
							'message' => 'Please enter the symtom name'
					),
					'maxLength' => array(
							'rule' => array('maxLength', 100),
							'message' => 'Cannot be more than 100 characters long.'
					),
					'remote' => array(
							'rule' => array('remote', '/api/checkExistingSymptomName', 'name'),
							'message' => 'This symptom name already exists.'
			)
			)
	);

	/* Functin to check the disease name already exists before save */
	public function beforeSave($options = array()){
		//Commented since causing namespace conflict of classes
//		$Api = new ApiController;
//		$name = $this->data['Symptom']['name'];
//		$id = $this->data['Symptom']['id'];
//	
//		return $Api->checkExistingSymptomName($name, $id);
	
	}
	
	public function getSymptomNameFromId($symptomId = NULL) {
		if ($symptomId) {
			$symptom = $this->findById ( $symptomId );
			return ($symptom ['Symptom'] ['name']);
		} else {
			return null;
		}
	}
	/**
	 * Function to save new symptom
	 * 
	 * @param type $name string
	 */
	public function addNewSymptom($name){
		$this->create();
		$this->saveField('name', $name, array(
			'validate' => false
		));
		return $this->id;
	}
}

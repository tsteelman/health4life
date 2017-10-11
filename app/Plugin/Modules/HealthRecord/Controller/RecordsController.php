<?php

/**
 * RecordsController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('HealthRecordAppController', 'HealthRecord.Controller');

/**
 * RecordsController for the health history
 * 
 * RecordsController is used to save the health history records of a user
 *
 * @author 	    Greeshma Radhakrishnan
 * @package 	HealthRecord
 * @category	Controllers 
 */
class RecordsController extends HealthRecordAppController {

	public $uses = array('UserHealthHistory', 'City', 'HealthReading');
	protected $_modelName = 'UserHealthHistory';
	private $__userId;
	private $__userHealthHistoryData = array();

	public function beforeFilter() {
		$this->__userId = $userId = $this->Auth->user('id');
		if (!$this->request->is('post')) {
			$userHealthHistoryRecord = $this->UserHealthHistory->findByUserId($userId);
			if (!empty($userHealthHistoryRecord)) {
				$this->__userHealthHistoryData = $userHealthHistoryRecord[$this->_modelName];
				$this->request->data[$this->_modelName] = $this->__userHealthHistoryData;
			}
		}
	}

	/**
	 * This function is executed before the view is rendered
	 */
	public function beforeRender() {
		parent::beforeRender();
		if ($this->request->is('ajax')) {
			$this->layout = 'ajax';
			$this->view = 'ajax_index';
		} else {
			$this->view = 'index';
			$menuItems = $this->__getMenuItems();
			$this->set(compact('menuItems'));
		}

		$modelName = $this->_modelName;
		$inputDefaults = array(
			'label' => false,
			'div' => false,
			'class' => 'form-control'
		);
		$formOptions = array(
			'inputDefaults' => $inputDefaults,
			'type' => 'POST'
		);
		$this->set(compact('modelName', 'formOptions'));
	}

	/**
	 * Function to get the list of menu items
	 *
	 * @return array
	 */
	private function __getMenuItems() {
		$action = $this->request->params['action'];
		$urlPrefix = '/health_record/records';
		$menuItems = array(
			array(
				'label' => __('Personal History'),
				'url' => "{$urlPrefix}/personal",
				'active' => ($action === 'personal'),
			),
			array(
				'label' => __('Medical Conditions'),
				'url' => "{$urlPrefix}/conditions",
				'active' => ($action === 'conditions'),
			),
			array(
				'label' => __('Allergies'),
				'url' => "{$urlPrefix}/allergies",
				'active' => ($action === 'allergies'),
			),
			array(
				'label' => __('Immunizations'),
				'url' => "{$urlPrefix}/immunizations",
				'active' => ($action === 'immunizations'),
			),
			array(
				'label' => __('Surgeries'),
				'url' => "{$urlPrefix}/surgeries",
				'active' => ($action === 'surgeries'),
			),
			array(
				'label' => __('Injuries'),
				'url' => "{$urlPrefix}/injuries",
				'active' => ($action === 'injuries'),
			)
		);

		return $menuItems;
	}

	/**
	 * Personal Information tab
	 */
	public function personal() {
		if ($this->request->is('post')) {
			$data = $this->request->data[$this->_modelName];
			$data['user_id'] = $this->__userId;
			$data['dob'] = Date::JSDateToMySQL($data['dob']);
			$this->UserHealthHistory->save($data, false);
			$this->redirect('conditions');
		} else {
			$this->__setPersonalFormData();
		}
	}

	/**
	 * Set personal information tab data
	 */
	public function __setPersonalFormData() {
		$authUser = $this->Auth->user();
		$profileInfo = $authUser;

		// if the logged in user is care giver, set caregiver's patient profile info
		if (intval($authUser['type']) === User::ROLE_CAREGIVER) {
			$this->CareGiverPatient = ClassRegistry::init('CareGiverPatient');
			$patient = $this->CareGiverPatient->findByCareGiverId($this->__userId);
			if (!empty($patient)) {
				$profileInfo = $patient['CareGiverPatient'];
			}
		}

		$fields = array(
			'first_name' => 'first_name',
			'last_name' => 'last_name',
			'gender' => 'gender',
			'city_id' => 'city',
			'dob' => 'date_of_birth',
		);

		foreach ($fields as $field => $profileField) {
			if (empty($this->__userHealthHistoryData[$field])) {
				$this->__userHealthHistoryData[$field] = $profileInfo[$profileField];
			}
		}

		if ($this->__userHealthHistoryData['dob'] === '0000-00-00') {
			$this->__userHealthHistoryData['dob'] = $profileInfo['date_of_birth'];
		}

		$this->request->data[$this->_modelName] = $this->__userHealthHistoryData;

		$element = 'personal';

		// location
		if (!empty($this->__userHealthHistoryData)) {
			$userHealthHistory = $this->__userHealthHistoryData;
			if (isset($userHealthHistory['city_id'])) {
				$cityId = $userHealthHistory['city_id'];
				$location = $this->City->getCityLocationName($cityId);
				$this->request->data[$this->_modelName]['location'] = $location;
			}
		}

		// convert dob to US format
		if (!empty($this->__userHealthHistoryData['dob'])) {
			$dob = $this->__userHealthHistoryData['dob'];
			$USDob = CakeTime::nice($dob, null, '%m/%d/%Y');
			$this->request->data[$this->_modelName]['dob'] = $USDob;
		}

		// drop down options
		$genderOptions = UserHealthHistory::listGenderOptions();
		$maritalStatusOptions = UserHealthHistory::listMaritalStatusOptions();
		$raceOptions = UserHealthHistory::listRaceOptions();
		$smokingOptions = UserHealthHistory::listSmokingOptions();
		$drinkingOptions = UserHealthHistory::listDrinkingOptions();

		// validation
		$formId = 'UserHealthHistoryPersonalForm';
		$model = $this->_modelName;
		$validations = $this->$model->validate;
		$this->JQValidator->addValidation($model, $validations, $formId);

		$this->set(compact('element', 'genderOptions', 'maritalStatusOptions', 'raceOptions', 'smokingOptions', 'drinkingOptions'));
	}

	/**
	 * Medical Conditions tab
	 */
	public function conditions() {
		$fields = array(
			'conditions' => 'Condition',
			'childhood_illnesses' => 'ChildhoodIllness'
		);
		if ($this->request->is('post')) {
			$this->__saveRecords($fields);
			$this->redirect('allergies');
		} else {
			$element = 'conditions';
			$backUrl = 'personal';
			$conditions = UserHealthHistory::listConditions();
			$childhoodIllnesses = UserHealthHistory::listChildhoodIllnesses();
			$this->__setSelectedItems($fields);
			$this->set(compact('element', 'backUrl', 'conditions', 'childhoodIllnesses'));
		}
	}

	/**
	 * Allergies tab
	 */
	public function allergies() {
		$fields = array(
			'allergic_medicines' => 'AllergicMedicine',
			'allergic_food_items' => 'AllergicFood',
			'environmental_allergies' => 'EnvironmentalAllergy',
		);
		if ($this->request->is('post')) {
			$this->__saveRecords($fields);
			$this->redirect('immunizations');
		} else {
			$element = 'allergies';
			$backUrl = 'conditions';

			$allergicMedicines = UserHealthHistory::listAllergicMedicines();
			$allergicFoodItems = UserHealthHistory::listAllergicFoodItems();
			$environmentalAllergies = UserHealthHistory::listEnvironmentalAllergies();

			$this->__setSelectedItems($fields);

			$this->set(compact('element', 'backUrl', 'allergicMedicines', 'allergicFoodItems', 'environmentalAllergies'));
		}
	}

	/**
	 * Immunizations tab
	 */
	public function immunizations() {
		$fields = array(
			'vaccinations' => 'Vaccination',
		);

		if ($this->request->is('post')) {
			$this->__saveRecords($fields);
			$this->redirect('surgeries');
		} else {
			$element = 'immunizations';
			$backUrl = 'allergies';

			$vaccinations = UserHealthHistory::listVaccinations();

			$this->__setSelectedItems($fields);

			$this->set(compact('element', 'backUrl', 'vaccinations'));
		}
	}

	/**
	 * Surgeries tab
	 */
	public function surgeries() {
		$field = 'surgeries_json';
		$formField = 'Surgery';
		if ($this->request->is('post')) {
			$this->__saveJSONFieldRecords($field, $formField);
			$this->redirect('injuries');
		} else {
			$element = 'surgeries';
			$backUrl = 'immunizations';

			// set born year for year validation
			$bornYear = $this->__getPatientBornYear();

			// set already saved surgeries
			$this->__setJSONFieldRecordsFormData($field, $formField);

			// validation
			$formId = 'UserHealthHistorySurgeriesForm';
			$model = $this->_modelName;
			$validationOptions = array();
			$relatedForms = array('SurgeryForm');
			$validationGroups = array();
			$this->JQValidator->addValidation($model, $validationOptions, $formId, $validationGroups, $relatedForms);

			// set data on view
			$this->set(compact('element', 'backUrl', 'bornYear'));
		}
	}

	/**
	 * Injuries tab
	 */
	public function injuries() {
		$field = 'injuries_json';
		$formField = 'Injury';
		if ($this->request->is('post')) {
			$this->__saveJSONFieldRecords($field, $formField);
			$this->Session->setFlash('Thank You for filling the information.', 'success');
			$this->redirect('/profile/myhealth');
		} else {
			$element = 'injuries';
			$backUrl = 'surgeries';

			// set born year for year validation
			$bornYear = $this->__getPatientBornYear();

			// set already saved injuries
			$this->__setJSONFieldRecordsFormData($field, $formField);

			// validation
			$formId = 'UserHealthHistoryInjuriesForm';
			$model = $this->_modelName;
			$validationOptions = array();
			$relatedForms = array('InjuryForm');
			$validationGroups = array();
			$this->JQValidator->addValidation($model, $validationOptions, $formId, $validationGroups, $relatedForms);

			// set data on view
			$this->set(compact('element', 'backUrl', 'bornYear'));
		}
	}

	/**
	 * Function to set records of a JSON field in form data
	 * 
	 * @param string $field field name in table
	 * @param string $formField field name in form
	 */
	private function __setJSONFieldRecordsFormData($field, $formField) {
		$recordsCount = 0;
		if (!empty($this->__userHealthHistoryData)) {
			$userHealthHistory = $this->__userHealthHistoryData;
			$fieldJSON = $userHealthHistory[$field];
			$fieldRecords = json_decode($fieldJSON, true);
			if (!empty($fieldRecords)) {
				unset($this->request->data[$field]);
				$this->request->data[$this->_modelName][$formField] = $fieldRecords;
				$recordsCount = count($fieldRecords);
			}
		}

		// hide/show injury records container
		$recordsContainerClass = ($recordsCount > 0) ? '' : 'hide';
		$this->set(compact('recordsCount', 'recordsContainerClass'));
	}

	/**
	 * Function to save records in a JSON field
	 * 
	 * @param string $field field name in table
	 * @param string $formField field name in form
	 */
	private function __saveJSONFieldRecords($field, $formField) {
		$postData = $this->request->data[$this->_modelName];
		if (isset($postData[$formField]) && !empty($postData[$formField])) {
			$fieldRecords = array_values($postData[$formField]);
			foreach ($fieldRecords as $fieldRecord) {
				$filteredRecord = array_filter($fieldRecord);
				if (!empty($filteredRecord)) {
					$fieldData[] = $filteredRecord;
				}
			}

			$fieldJSONData = json_encode($fieldData);

			$data = array(
				'user_id' => $this->__userId,
				$field => $fieldJSONData,
			);

			if (isset($postData['id']) && $postData['id'] > 0) {
				$data['id'] = $postData['id'];
			}

			$this->UserHealthHistory->save($data);
		}
	}

	/**
	 * Function to set the select items in each field as checked
	 * 
	 * @param array $fields
	 */
	private function __setSelectedItems($fields) {
		if (!empty($this->__userHealthHistoryData)) {
			$userHealthHistory = $this->__userHealthHistoryData;
			foreach ($fields as $fieldName => $postFieldName) {
				if (isset($userHealthHistory[$fieldName])) {
					unset($this->request->data[$this->_modelName][$postFieldName]);
					$fieldSelectedItemsList = $userHealthHistory[$fieldName];
					$fieldSelectedItems = explode(',', $fieldSelectedItemsList);
					foreach ($fieldSelectedItems as $itemId) {
						$this->request->data[$this->_modelName][$postFieldName][$itemId] = true;
					}
				}
			}
		}
	}

	/**
	 * Function to save a list of records
	 * 
	 * @param array $fields checkbox field names
	 */
	private function __saveRecords($fields) {
		$data = $this->request->data[$this->_modelName];
		$data['user_id'] = $this->__userId;

		foreach ($fields as $fieldName => $postFieldName) {
			$data[$fieldName] = $this->__getFieldSelectedItemsList($data, $postFieldName);
			unset($data[$postFieldName]);
		}

		$this->UserHealthHistory->save($data);
	}

	/**
	 * Function to get the list of selected items for a field
	 * 
	 * @param array $postData posted data array
	 * @param string $fieldName name of the field
	 * @return string comma separated list
	 */
	private function __getFieldSelectedItemsList($postData, $fieldName) {
		$selectedItemsList = '';
		if (isset($postData[$fieldName])) {
			$items = $postData[$fieldName];
			$selectedItems = array_filter($items);
			$selectedItemsList = join(',', $selectedItems);
		}
		return $selectedItemsList;
	}

	/**
	 * Function to get the year of birth of the patient
	 * 
	 * @return string
	 */
	private function __getPatientBornYear() {
		$authUser = $this->Auth->user();
		$profileInfo = $authUser;

		// if the logged in user is care giver, get patient profile info
		if (intval($authUser['type']) === User::ROLE_CAREGIVER) {
			$this->CareGiverPatient = ClassRegistry::init('CareGiverPatient');
			$patient = $this->CareGiverPatient->findByCareGiverId($this->__userId);
			if (!empty($patient)) {
				$profileInfo = $patient['CareGiverPatient'];
			}
		}


		$dob = $profileInfo['date_of_birth'];
		if (!is_null($dob) && $dob !== '') {
			list($bornYear, $bornMonth, $bornDay) = explode('-', $dob);
		} else {
			$bornYear = '';
		}

		return $bornYear;
	}
}
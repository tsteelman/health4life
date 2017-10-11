<?php

/**
 * SchedulerController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');
App::uses('MedicationSchedulerForm', 'Model/Form');

/**
 * Charts Controller class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
class SchedulerController extends ProfileController {

	protected $_mergeParent = 'ProfileController';

	/**
	 * Models used by this controller
	 * 
	 * @var array
	 */
	public $uses = array(
		'MedicationSchedulerForm',
		'MedicationSchedule'
	);

	/**
	 * Before filer
	 *
	 * @param null
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('stopMedicationReminder');
	}

	/**
	 * List medication schedules of the user
	 */
	public function index($username = null) {
		$userId = $this->Auth->user('id');
		$timezone = $this->Auth->user('timezone');
		$medications = $this->MedicationSchedule->getUserMedications($userId);
		$medications = $this->MedicationSchedule->getMedicationsData($medications, $timezone);
		$this->set('title_for_layout',"Medication Scheduler");
		$this->set(compact('medications'));
		$this->__addFormValidation();
	}

	/**
	 * Function to add validation for medication scheduler form
	 */
	private function __addFormValidation() {
		$model = 'MedicationSchedulerForm';
		$validations = $this->$model->validate;
		$this->JQValidator->addValidation($model, $validations, 'medication_scheduler_form');
	}

	/**
	 * Function to save medication schedule
	 */
	public function save() {
		$result = array();
		$this->autoRender = false;
		$userId = $this->Auth->user('id');
		$timezone = $this->Auth->user('timezone');
		$postData = $this->request->data['MedicationSchedulerForm'];
		$data = array(
			'id' => $postData['id'],
			'treatment_id' => $postData['medicine_id'],
			'user_id' => $userId,
			'indication' => trim($postData['indication']),
			'dosage' => $postData['dosage'],
			'dosage_unit' => $postData['dosage_unit'],
			'form' => $postData['form'],
			'amount' => $postData['amount'],
			'route' => $postData['route'],
			'additional_instructions' => trim($postData['additional_instructions']),
			'prescribed_by' => trim($postData['prescribed_by'])
		);

		$startDateJSON = null;
		$startDate = null;
		if (!empty($postData['start_year']) && $postData['start_year'] > 0) {
			$startDateValues = array();
			$startYear = $postData['start_year'];
			$startDateValues['year'] = $startYear;
			if (!empty($postData['start_month']) && $postData['start_month'] > 0) {
				$startMonth = $postData['start_month'];
				$startDateValues['month'] = $startMonth;
			} else {
				$startMonth = '01';
			}
			if (!empty($postData['start_day']) && $postData['start_day'] > 0) {
				$startDay = $postData['start_day'];
				$startDateValues['day'] = $startDay;
			} else {
				$startDay = '01';
			}
			$startDateJSON = json_encode($startDateValues);
			$startDate = sprintf('%s-%s-%s', $startYear, $startMonth, $startDay);
			$startDate = Date::convertDateToServerDateTime($startDate, $timezone);
		}

		$data['start_date'] = $startDate;
		$data['start_date_json'] = $startDateJSON;

		if (isset($postData['end_date']) && $postData['end_date'] !== '') {
			$endDate = Date::JSDateToMySQL($postData['end_date']);
			$data['end_date'] = Date::convertDateToServerDateTime($endDate, $timezone);
		}

		$rrule = '';
		if ($postData['repeat_frequency'] !== '') {
			list($frequency, $interval) = explode(':', $postData['repeat_frequency']);
			$rrule = sprintf('FREQ=%s;INTERVAL=%s;', $frequency, $interval);
		}

		foreach ($postData['medication_time'] as $medicationTime) {
			if ($medicationTime > 0) {
				$medicationTimes[] = Date::JSTimeToRRuleTime($medicationTime);
			}
		}
		if (!empty($medicationTimes)) {
			$rruleTime = join(',', $medicationTimes);
			$rrule.='TIME=' . $rruleTime;
		}

		$data['rrule'] = $rrule;
		$this->MedicationSchedule->create();
		if ($this->MedicationSchedule->save($data)) {
			$result['success'] = true;
			$message = ($data['id'] > 0) ? 'updated' : 'added';
			$successMessage = __("Succesfully {$message} the medication schedule.");
			$result['message'] = $successMessage;
			if (!empty($postData['selected_date'])) {
				$selectedDate = $postData['selected_date'];
				$selectedServerDate = Date::convertDateToServerDateTime($selectedDate, $timezone, 'Y-m-d');
				$rruleArray = Date::parseRRule($rrule);
				$isMedicationOnDate = MedicationSchedule::isMedicationOnDate($selectedServerDate, $data, $rruleArray);
				if ($isMedicationOnDate === true) {
					$medicationTileData = $this->MedicationSchedule->getUserMedicationDataOnDate($userId, $selectedDate, $timezone);
					$View = new View($this, false);
					$result['content'] = $View->element('User.Scheduler/medication_schedules', $medicationTileData);
				}
			} else {
				$result['refresh'] = true;
				$this->Session->setFlash($successMessage, 'success');
			}
		} else {
			$result['error'] = true;
			$result['message'] = __('Failed to save medication schedule.');
		}
		echo json_encode($result);
		exit();
	}

	/**
	 * Function to mark medication schedules as deleted
	 */
	public function deleteMedications() {
		$this->autoRender = false;
		if (!empty($this->request->data['MedicationSchedule'])) {
			$ids = $this->request->data['MedicationSchedule'];
			$this->MedicationSchedule->deleteMedications($ids);
		}
	}

	/**
	 * Function to handle stop medication reminder
	 */
	public function stopMedicationReminder() {
		if (isset($this->request->data['id'])) {
			$this->__stopMedicationReminder($this->request->data['id']);
		} else {
			$invalidLinkMessage = __('Sorry, this seems to be an invalid link.');
			if (!empty($this->request->query['token'])) {
				$token = $this->request->query['token'];
				$tokenData = json_decode(base64_decode($token), true);
				$id = $tokenData['id'];
				$userId = $tokenData['user_id'];
				$model = $this->MedicationSchedule->findById($id);
				if (!empty($model)) {
					$medicationSchedule = $model['MedicationSchedule'];
					if ($medicationSchedule['user_id'] !== $userId) {
						$errorMessage = $invalidLinkMessage;
					} elseif ($this->Auth->loggedIn() && $medicationSchedule['user_id'] !== $this->Auth->user('id')) {
						$errorMessage = __('You are not allowed to access this link.');
					} else if (!is_null($medicationSchedule['reminder_stopped_date'])) {
						$errorMessage = __('Reminder for this medication is already stopped.');
					} else if ($medicationSchedule['is_deleted'] === true) {
						$errorMessage = __('This medication scheduler does not exist any more.');
					}
				} else {
					$errorMessage = $invalidLinkMessage;
				}
			} else {
				$errorMessage = $invalidLinkMessage;
			}

			if (isset($errorMessage)) {
				$this->set(compact('errorMessage'));
			} else {
				$medicationName = $model['Treatment']['name'];
				$this->set(compact('id', 'medicationName'));
			}
		}
	}

	/**
	 * Function to stop a medication reminder
	 * 
	 * @param int $id
	 */
	private function __stopMedicationReminder($id) {
		$this->autoRender = false;
		$result = array();
		if ($this->MedicationSchedule->stopMedicationReminder($id)) {
			$result['success'] = true;
		} else {
			$result['error'] = true;
		}
		echo json_encode($result);
	}
}
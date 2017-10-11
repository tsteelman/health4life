<?php

App::uses('AppModel', 'Model');

/**
 * MedicationSchedule Model
 *
 * @property Treatment $Treatment
 * @property User $User
 */
class MedicationSchedule extends AppModel {
	/**
	 * Number of days after which to stop sending reminder after end date
	 */
	const NO_OF_REMINDER_DAYS_AFTER_END_DATE=14;

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Treatment' => array(
			'className' => 'Treatment',
			'foreignKey' => 'treatment_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => array('username', 'email', 'timezone'),
			'order' => ''
		)
	);

	/**
	 * Function to get the medications of a user
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getUserMedications($userId) {
		$this->unbindModel(array('belongsTo' => array('User')));
		$query = array(
			'conditions' => array(
				"{$this->alias}.user_id" => $userId,
				"{$this->alias}.is_deleted" => 0
			)
		);
		return $this->find('all', $query);
	}

	/**
	 * Function to get medications for a user for a date
	 * 
	 * @param int $userId
	 * @param string $date
	 * @return array
	 */
	public function getUserMedicationsByDate($userId, $date) {
		$this->unbindModel(array('belongsTo' => array('User')));
		$query = array(
			'conditions' => array(
				'AND' => array(
					"{$this->alias}.user_id" => $userId,
					"{$this->alias}.is_deleted" => 0,
					'OR' => array(
						"{$this->alias}.end_date IS NULL",
						"? <= DATE({$this->alias}.end_date)" => $date
					),
				),
				array(
					'OR' => array(
						array(
							"{$this->alias}.start_date IS NOT NULL",
							"? >= DATE({$this->alias}.start_date)" => $date,
						),
						array(
							"{$this->alias}.start_date IS NULL",
							"? >= DATE({$this->alias}.created)" => $date,
						)
					)
				)
			)
		);
		return $this->find('all', $query);
	}

	/**
	 * Function to get medications on a date for reminder 
	 * 
	 * @param string $date
	 * @return array
	 */
	public function getReminderMedicationsByDate($date) {
		$endDateLimit = self::NO_OF_REMINDER_DAYS_AFTER_END_DATE;
		$query = array(
			'conditions' => array(
				'AND' => array(
					"{$this->alias}.is_deleted" => 0,
					"{$this->alias}.reminder_stopped_date IS NULL",
					'OR' => array(
						"{$this->alias}.end_date IS NULL",
						"? <= DATE_ADD({$this->alias}.end_date, INTERVAL ? DAY)" => array($date, $endDateLimit),
					),
				),
				array(
					'OR' => array(
						array(
							"{$this->alias}.start_date IS NOT NULL",
							"? >= DATE({$this->alias}.start_date)" => $date,
						),
						array(
							"{$this->alias}.start_date IS NULL",
							"? >= DATE({$this->alias}.created)" => $date,
						)
					)
				)
			)
		);
		return $this->find('all', $query);
	}

	/**
	 * Function to get user medication data on date
	 * 
	 * @param int $userId
	 * @param string $date
	 * @param string $timezone
	 * @return array 
	 */
	public function getUserMedicationDataOnDate($userId, $date, $timezone) {
		$selectedDate = $date;
		$nextDate = date('Y-m-d', strtotime($date . ' + 1 days'));
		$prevDate = date('Y-m-d', strtotime($date . ' - 1 days'));
		$medicationDate = CakeTime::format($date, '%b %d %Y');
		$date = Date::convertDateToServerDateTime($date, $timezone, 'Y-m-d');
		$medications = $this->getUserMedicationsByDate($userId, $date);
		$data = array();
		if (!empty($medications)) {
			foreach ($medications as $medication) {
				$medicationSchedule = $medication['MedicationSchedule'];
				$rrule = $medicationSchedule['rrule'];
				$rruleArray = Date::parseRRule($rrule);
				$isMedicationOnDate = self::isMedicationOnDate($date, $medicationSchedule, $rruleArray);
				if ($isMedicationOnDate === true) {
					$strength = $medicationSchedule['dosage'] . ' ' . $medicationSchedule['dosage_unit'];
					$data[] = array(
						'name' => $medication['Treatment']['name'],
						'strength' => $strength,
						'time' => $rruleArray['TIME_TEXT'],
						'amount' => $medicationSchedule['amount']
					);
				}
			}
			$medications = $data;
		}
		return compact('medications', 'medicationDate', 'nextDate', 'prevDate', 'selectedDate');
	}

	/**
	 * Function to get medication data from medication details
	 * 
	 * @param array $medications
	 * @param string $timezone
	 * @return array
	 */
	public static function getMedicationsData($medications, $timezone) {
		$data = array();
		if (!empty($medications)) {
			foreach ($medications as $medication) {
				$medicationData = array();
				$medicationSchedule = $medication['MedicationSchedule'];
				$frequency = '';
				$frequencyValue = '';
				$time = '';
				$timeList = '';
				$form = '';
				$route = '';
				if (!empty($medicationSchedule['rrule'])) {
					$rrule = $medicationSchedule['rrule'];
					$rruleArray = Date::parseRRule($rrule);
					if (isset($rruleArray['FREQUENCY_TEXT'])) {
						$frequency = $rruleArray['FREQUENCY_TEXT'];
					}
					if (isset($rruleArray['FREQUENCY_VALUE'])) {
						$frequencyValue = $rruleArray['FREQUENCY_VALUE'];
					}
					if (isset($rruleArray['TIME_TEXT'])) {
						$time = $rruleArray['TIME_TEXT'];
					}
					if (isset($rruleArray['TIME_LIST'])) {
						$timeList = $rruleArray['TIME_LIST'];
					}
				}
				if (!empty($medicationSchedule['form'])) {
					$formKey = $medicationSchedule['form'];
					$form = MedicationSchedulerForm::getMedicineFormName($formKey);
				}
				if (!empty($medicationSchedule['route'])) {
					$routeKey = $medicationSchedule['route'];
					$route = MedicationSchedulerForm::getMedicineRouteName($routeKey);
				}

				$medicationData['start_year'] = '';
				$medicationData['start_month'] = '';
				$medicationData['start_day'] = '';
				if (!empty($medicationSchedule['start_date_json'])) {
					$startDateJSON = $medicationSchedule['start_date_json'];
					$startDate = json_decode($startDateJSON, true);
					if (isset($startDate['year'])) {
						$medicationData['start_year'] = $startDate['year'];
					}
					if (isset($startDate['month'])) {
						$medicationData['start_month'] = $startDate['month'];
					}
					if (isset($startDate['day'])) {
						$medicationData['start_day'] = $startDate['day'];
					}
				}

				if (!empty($medicationSchedule['end_date'])) {
					$medicationData['end_date_value'] = CakeTime::format($medicationSchedule['end_date'], '%m/%d/%Y', false, $timezone);
				} else {
					$medicationData['end_date_value'] = '';
				}

				$medicationData['frequency'] = $frequency;
				$medicationData['frequency_value'] = $frequencyValue;
				$medicationData['time'] = $time;
				$medicationData['time_list'] = $timeList;
				$medicationData['form'] = $form;
				$medicationData['route'] = $route;
				$medicationData['form_value'] = $medicationSchedule['form'];
				$medicationData['route_value'] = $medicationSchedule['route'];
				$strength = $medicationSchedule['dosage'] . ' ' . $medicationSchedule['dosage_unit'];
				$medicationData['strength'] = $strength;
				$otherFields = array(
					'id',
					'dosage',
					'dosage_unit',
					'amount',
					'additional_instructions',
					'prescribed_by',
					'indication',
					'start_date',
					'end_date'
				);
				foreach ($otherFields as $field) {
					$medicationData[$field] = $medicationSchedule[$field];
				}
				$medicationData['medication_id'] = $medication['Treatment']['id'];
				$medicationData['name'] = $medication['Treatment']['name'];
				$data[] = $medicationData;
			}
		}
		return $data;
	}

	/**
	 * Function to check if a medication is on the specified date
	 * 
	 * @param string $date (in GMT timezone)
	 * @param array $medicationSchedule
	 * @param array $rruleArray
	 * @return bool
	 */
	public static function isMedicationOnDate($date, $medicationSchedule, $rruleArray) {
		$isMedicationOnDate = false;
		if (isset($rruleArray['INTERVAL_DAYS'])) {
			$intervalDays = $rruleArray['INTERVAL_DAYS'];
			if (!empty($medicationSchedule['start_date'])) {
				$startDateTime = $medicationSchedule['start_date'];
			} else {
				$startDateTime = $medicationSchedule['created'];
			}
			$startDate = CakeTime::format($startDateTime, '%Y-%m-%d');
			$dateObj = new DateTime($date);
			$startDateObj = new DateTime($startDate);
			$dDiff = $dateObj->diff($startDateObj);
			$daysDiff = $dDiff->days;
			if ($daysDiff % $intervalDays === 0) {
				$isMedicationOnDate = true;
			}
		} else {
			$isMedicationOnDate = true;
		}
		return $isMedicationOnDate;
	}

	/**
	 * Function to mark medication schedules as deleted
	 * 
	 * @param array $ids
	 * @return bool 
	 */
	public function deleteMedications($ids) {
		$fields = array("{$this->alias}.is_deleted" => 1);
		$conditions = array("{$this->alias}.id" => $ids);
		return $this->updateAll($fields, $conditions);
	}

	/**
	 * Function to stop a medication reminder
	 * 
	 * @param int $id
	 */
	public function stopMedicationReminder($id) {
		$currentDateTime = Date::getCurrentDateTime();
		$this->id = $id;
		return $this->saveField('reminder_stopped_date', $currentDateTime);
	}
}
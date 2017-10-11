<?php

/**
 * PrintController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('MedicationSchedulerForm', 'Model/Form');

/**
 * PrintController for the frontend
 * 
 * PrintController is used for front end user printing functionalities
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class PrintController extends UserAppController {

    public $uses = array('User','UserSymptom','Symptom','MedicationSchedule','PatientDisease', 'MyFriends','TeamMember', 'Event', 'UserTreatment');
    public $components = array('HealthRecordsReading');

    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('login');
    }

	/**
     * Health Graph printing functionality
     */
	public function printGraph() {
		$this->layout = 'print_layout';
		$userId = $this->Auth->user('id');
		$userName = $this->Auth->user('username');
		$userTimezone = $this->Auth->user('timezone');
		$date = CakeTime::format('Y-m-d H:i:s', date('m/d/Y H:i:s'), false, $userTimezone);
		
		$graphsToPrint = $_GET['graphIds'];
		$graphs=explode(",",$graphsToPrint);
		// To make the array index starts with 1 (for graph printing)
		$graphArray = array_combine(range(1, count($graphs)), $graphs);
		
		if(!empty($graphArray)) {
			if(isset($_GET['customDates'])) {
				$dateRangeOption = $_GET['customDates'];
				$startDate = $_GET['mindate'];
				$endDate = $_GET['maxdate'];
			}
			$readings = $this->HealthRecordsReading->getHealthReadings($userId);
			$painReadings = $this->HealthRecordsReading->getPainTrackerReadings($userId,$userTimezone);
			$healthValues = array();
			$graphTitles = array();
			$index = 1;
			
			foreach ($graphArray as $key => $graph) {
				switch ($graph) {
					case 0: if(isset($readings['weight'])) {
								$healthValues[$index] = $readings['weight']; // weight
								$unit = $readings['unit'];
								if($dateRangeOption == 1) {
									$healthValues[$index] = $this->getDataBetweenDateRange($healthValues[$index], $startDate, $endDate);
								} else {
									$healthValues[$index] = $this->getDataInPeriod($healthValues[$index], $dateRangeOption);
								}
							}
							$graphTitles['title'][$index] = 'Weight';
							$graphTitles['type'][$index] = 'health';
							$graphType = 'health';
							$index++;
							break;
					case 1: if(isset($readings['bp'])) {
								$bpValues = $readings['bp']; // Bp
								if($dateRangeOption == 1) {
									$bpValues = $this->getDataBetweenDateRange($bpValues, $startDate, $endDate);
								} else {
									$bpValues = $this->getDataInPeriod($bpValues, $dateRangeOption);
								}
								$healthValues[$index] = $this->seperatePressures($bpValues);
							}
							$graphTitles['title'][$index] = 'Blood Pressure';
							$graphTitles['type'][$index] = 'bp';
							$graphType = 'health';
							$index++;
						    break;
					case 2: if(isset($readings['temp'])) {
								$healthValues[$index] = $readings['temp']; // Temparature
								$unit = $readings['unit'];
								if($dateRangeOption == 1) {
									$healthValues[$index] = $this->getDataBetweenDateRange($healthValues[$index], $startDate, $endDate);
								} else {
									$healthValues[$index] = $this->getDataInPeriod($healthValues[$index], $dateRangeOption);
								}
							}
							$graphTitles['title'][$index] = 'Temparature';
							$graphTitles['type'][$index] = 'health';
							$graphType = 'health';
							$index++;
						    break;
					case 3: if(isset($readings['bmi'])) {
								$healthValues[$index] = $readings['bmi']; // bmi
								if($dateRangeOption == 1) {
									$healthValues[$index] = $this->getDataBetweenDateRange($healthValues[$index], $startDate, $endDate);
								} else {
									$healthValues[$index] = $this->getDataInPeriod($healthValues[$index], $dateRangeOption);
								}
							}
							$graphTitles['title'][$index] = 'BMI';
							$graphTitles['type'][$index] = 'health';
							$graphType = 'health';
							$index++;
						    break;
					case 4: if(isset($readings['status'])) {
								$healthValues[$index] = $readings['status']; // Health status
								if($dateRangeOption == 1) {
									$healthValues[$index] = $this->getDataBetweenDateRange($healthValues[$index], $startDate, $endDate);
								} else {
									$healthValues[$index] = $this->getDataInPeriod($healthValues[$index], $dateRangeOption);
								}
								//Converting string array into integer array for graph plotting
								$healthValues[$index] = array_map('intval',$healthValues[$index]);
							}
							$graphTitles['title'][$index] = 'Health';
							$graphTitles['type'][$index] = 'tracker';
							$graphType = 'health';
							$index++;
						    break;
					case 5: if(isset($readings['pain_tracker'])) {
								$painValues = $readings['pain_tracker']; // Pain Tracker
								if($dateRangeOption == 1) {
									$painValues = $this->getDataBetweenDateRange($painValues, $startDate, $endDate);
								} else {
									$painValues = $this->getDataInPeriod($painValues, $dateRangeOption);
								}
								//Converting string array into integer array for graph plotting
								$healthValues[$index] = array_map('intval',$painValues);
							}
							$graphTitles['title'][$index] = 'Pain Tracker';
							$graphTitles['type'][$index] = 'tracker';
							$graphType = 'health';
							$index++;
						    break;
					case 6: if(isset($readings['life_quality_tracker'])) {
								$qualityValues = $readings['life_quality_tracker']; // Life quality tracker
								if($dateRangeOption == 1) {
									$qualityValues = $this->getDataBetweenDateRange($qualityValues, $startDate, $endDate);
								} else {
									$qualityValues = $this->getDataInPeriod($qualityValues, $dateRangeOption);
								}
								$healthValues[$index] = array_map('intval',$qualityValues);
							}
							$graphTitles['title'][$index] = 'Quality Of Life';
							$graphTitles['type'][$index] = 'tracker';
							$graphType = 'health';
							$index++;
						    break;
					case 7: if(isset($readings['sleeping_tracker'])) {
								$sleepValues = $readings['sleeping_tracker']; // Sleeping tracker
								if($dateRangeOption == 1) {
									$sleepValues = $this->getDataBetweenDateRange($sleepValues, $startDate, $endDate);
								} else {
									$sleepValues = $this->getDataInPeriod($sleepValues, $dateRangeOption);
								}
								$healthValues[$index] = array_map('intval',$sleepValues);
							}
							$graphTitles['title'][$index] = 'Sleeping Habits';
							$graphTitles['type'][$index] = 'tracker';
							$graphType = 'health';
							$index++;
						    break;
					case 8: if(isset($painReadings[1])) { 
								$healthValues[$index] = $painReadings[1]; // Head Area
								if($dateRangeOption == 1) {
								   $healthValues[$index] = $this->getTrackerDataBetweenRange($healthValues[$index], $startDate, $endDate);
								} else {
								   $healthValues[$index] = $this->getTrackerDataInPeriod($healthValues[$index], $dateRangeOption);
								}
								$healthValues[$index][0] = true; // condition for graph plotting
							}
							$graphTitles['title'][$index] = 'Body Pain Tracker - Head Area';
							$graphTitles['type'][$index] = 'pain';
							$graphType = 'health';
							$index++;
							break;	
				    case 9: if(isset($painReadings[2])) {
								$healthValues[$index] = $painReadings[2]; // Chest Area
								if($dateRangeOption == 1) {
								   $healthValues[$index] = $this->getTrackerDataBetweenRange($healthValues[$index], $startDate, $endDate);
								} else {
								   $healthValues[$index] = $this->getTrackerDataInPeriod($healthValues[$index], $dateRangeOption);
								}
								$healthValues[$index][0] = true;
							}
							$graphTitles['title'][$index] = 'Body Pain Tracker - Chest Area';
							$graphTitles['type'][$index] = 'pain';
							$graphType = 'health';
							$index++;
							break;	
				    case 10: if(isset($painReadings[3])) {
								$healthValues[$index] = $painReadings[3]; // Abdomen
								if($dateRangeOption == 1) {
								   $healthValues[$index] = $this->getTrackerDataBetweenRange($healthValues[$index], $startDate, $endDate);
								} else {
								   $healthValues[$index] = $this->getTrackerDataInPeriod($healthValues[$index], $dateRangeOption);
								}
								$healthValues[$index][0] = true;
					         }
							 $graphTitles['title'][$index] = 'Body Pain Tracker - Abdomen';
							 $graphTitles['type'][$index] = 'pain';
							 $graphType = 'health';
							 $index++;
							 break;	
				    case 11: if(isset($painReadings[4])) {
								$healthValues[$index] = $painReadings[4]; // Pelvic Area
								if($dateRangeOption == 1) {
								   $healthValues[$index] = $this->getTrackerDataBetweenRange($healthValues[$index], $startDate, $endDate);
								} else {
								   $healthValues[$index] = $this->getTrackerDataInPeriod($healthValues[$index], $dateRangeOption);
								}
								$healthValues[$index][0] = true;
							 }
							 $graphTitles['title'][$index] = 'Body Pain Tracker - Pelvic Area';
							 $graphTitles['type'][$index] = 'pain';
							 $graphType = 'health';
							 $index++;
							 break;	
				    case 12: if(isset($painReadings[5])) {
								$healthValues[$index] = $painReadings[5]; // Back Area
								if($dateRangeOption == 1) {
								   $healthValues[$index] = $this->getTrackerDataBetweenRange($healthValues[$index], $startDate, $endDate);
								} else {
								   $healthValues[$index] = $this->getTrackerDataInPeriod($healthValues[$index], $dateRangeOption);
								}
								$healthValues[$index][0] = true;
							 }
							 $graphTitles['title'][$index] = 'Body Pain Tracker - Back Area';
							 $graphTitles['type'][$index] = 'pain';
							 $graphType = 'health';
							 $index++;
							 break;	
					case 13: if(isset($painReadings[6])) {
								$healthValues[$index] = $painReadings[6]; // Arm
								if($dateRangeOption == 1) {
								   $healthValues[$index] = $this->getTrackerDataBetweenRange($healthValues[$index], $startDate, $endDate);
								} else {
								   $healthValues[$index] = $this->getTrackerDataInPeriod($healthValues[$index], $dateRangeOption);
								}
								$healthValues[$index][0] = true;
							 }
							 $graphTitles['title'][$index] = 'Body Pain Tracker - Arm';
							 $graphTitles['type'][$index] = 'pain';
							 $graphType = 'health';
							 $index++;
							 break;	
				    case 14: if(isset($painReadings[7])) {
								$healthValues[$index] = $painReadings[7]; // Legs
								if($dateRangeOption == 1) {
								   $healthValues[$index] = $this->getTrackerDataBetweenRange($healthValues[$index], $startDate, $endDate);
								} else {
								   $healthValues[$index] = $this->getTrackerDataInPeriod($healthValues[$index], $dateRangeOption);
								}
								$healthValues[$index][0] = true;
							 }
							 $graphTitles['title'][$index] = 'Body Pain Tracker - Legs';
							 $graphTitles['type'][$index] = 'pain';
							 $graphType = 'health';
							 $index++;
							 break;	
					case 15: $medications = $this->MedicationSchedule->getUserMedications($userId); // Medication scheduler for medical summary
							 $healthValues[$index] = $this->MedicationSchedule->getMedicationsData($medications, $userTimezone);
							 $graphTitles['title'][$index] = 'Medication Scheduler';
							 $graphTitles['type'][$index] = 'scheduler';
							 $graphType = 'health';
							 $index++;
							 break;
					case 16: $symptomsToPrint = $_GET['symptoms']; // Symptoms
							 $symptomsArray=explode(",",$symptomsToPrint);
							 foreach ($symptomsArray as $symptomId) {
								 $symptomValues = $this->UserSymptom->getSymptomSeverityDetails($userId, $symptomId);
								 if($dateRangeOption == 1) {
									$symptomValues = $this->getDataBetweenDateRange($symptomValues, $startDate, $endDate);
								 } else {
									$symptomValues = $this->getDataInPeriod($symptomValues, $dateRangeOption);
								 }
								 //Converting string array into integer array for graph plotting
								 $healthValues[$index] = array_map('intval',$symptomValues);
								 $symptomName = $this->Symptom->getSymptomNameFromId($symptomId);
								 $graphTitles['title'][$index] = 'Symptom - '.$symptomName;
								 $graphTitles['type'][$index] = 'symptom';
								 $index++;
							 }
							 $graphType = 'health';
							 break;
				    case 17: $diagnosisData = $this->PatientDisease->getUserDisease($userId); // Conditions
							 foreach ($diagnosisData as $data) {
								 $diseaseId = $data['PatientDisease']['id'];
								 $treatments = $this->UserTreatment->getTreatmentNamesForDisease($userId, $diseaseId);
								 $temp['Diseases'] = $data['Diseases'];
								 $temp['Treatment'] = $treatments;
								 $temp['PatientDisease'] =$data['PatientDisease'];
								 $values[] = $temp;
							 }
							 $healthValues[$key] = $values;
							 $userType = $this->Auth->user('type');
							 $graphType = 'tableview';
							 $graphTitles[$key] = 'conditions';
							 break;
					case 18: $healthValues[$key] = $this->MyFriends->getMyFriendsDetails($userId); // Friends
							 $graphType = 'tableview';
							 $graphTitles[$key] = 'friends';
							 break;
					case 19: $healthValues[$key] = $this->TeamMember->getMyTeamsAndMembers($userId); // Teams
							 $graphType = 'tableview';
							 $graphTitles[$key] = 'teams';
							 break;
					case 20: $medications = $this->MedicationSchedule->getUserMedications($userId); // Medication scheduler for single print
							 $healthValues[$key] = $this->MedicationSchedule->getMedicationsData($medications, $userTimezone);
							 $graphType = 'tableview';
							 $graphTitles[$key] = 'scheduler';
							 break;
				}
			}
			//debug($healthValues); die;
			$this->set(compact('userName', 'date', 'healthValues', 'graphTitles', 'startDate', 'endDate', 'tableType', 'userType'));
			if($graphType == 'health') {
				$this->render('healthGraph');
			} else if($graphType =='tableview') {
				$this->render('printView');
			}
		}
	}
	
	/**
	 * Function to get the health data within given range - between date1 and date2
	 */
	public function getDataBetweenDateRange($healthValues, $date1, $date2) {
		$valuesInRange = array();
		foreach ($healthValues as $key => $value) {
			$healthDate =  date('m/d/Y', $key);
			if(strtotime($healthDate) >= strtotime($date1) && strtotime($healthDate) <= strtotime($date2)) {
				$valuesInRange[$key] = $value;
			}
		}
		return $valuesInRange;
	}
	
	/**
	 * Function to get the body pain tracker health data within given range - between date1 and date2
	 */
	public function getTrackerDataBetweenRange($healthValues, $date1, $date2) {
		$valuesInRange = array();
		foreach ($healthValues as $key => $healthArray) {
			$temp = array();
			foreach ($healthArray as $key1 => $value) {
				$healthDate =  date('m/d/Y', $key1);
				if(strtotime($healthDate) >= strtotime($date1) && strtotime($healthDate) <= strtotime($date2)) {
					$temp[$key1] = $value;
				}
			}
			$valuesInRange[$key] = $temp;
		}
		return $valuesInRange;
	}
	
	/**
	 * Function to seperate systolic and diastolic pressures
	 */
	public function seperatePressures($bpArray) {
		$pressureValues = array();
		if(!empty($bpArray)) {
			foreach ($bpArray as $timestamp => $bp) {
				$pressures = explode("/", $bp);
				$systolic[$timestamp] = intval($pressures[0]);
				$diastolic[$timestamp] = intval($pressures[1]);
			}
			$pressureValues[1] = $systolic;
			$pressureValues[2] = $diastolic; 
		}
		return $pressureValues;
		
	}
	
	/**
	 * Function to get the health data within given period(last week, month, year)
	 */
	public function getDataInPeriod($healthValues, $option) {
		$valuesInRange = array();
		switch ($option) {
			case 2 : $valuesInRange = $healthValues; // All dates
					 break;
			case 3 : foreach ($healthValues as $key => $value) { // Last week
						$healthDate =  date('m/d/Y', $key);
						if(strtotime($healthDate) >= strtotime('-7 day')) {
							$valuesInRange[$key] = $value;
						}
					 }
					 break;
			case 4 : foreach ($healthValues as $key => $value) { // Last month
						$healthDate =  date('m/d/Y', $key);
						if(strtotime($healthDate) >= strtotime('-30 day')) {
							$valuesInRange[$key] = $value;
						}
					 }
					 break;
			case 5 : foreach ($healthValues as $key => $value) { // Last year
						$healthDate =  date('m/d/Y', $key);
						if(strtotime($healthDate) >= strtotime('-365 day')) {
							$valuesInRange[$key] = $value;
						}
					 }
					 break;
			case 6 : $firstDay = date('m/d/Y', strtotime('first day of January this year'));  // Year till date
					 foreach ($healthValues as $key => $value) {
						$healthDate =  date('m/d/Y', $key);
						if(strtotime($healthDate) >= strtotime($firstDay) ) {
							$valuesInRange[$key] = $value;
						}
					 }
					 break;
		}
		return $valuesInRange;
	}
	
	/**
	 * Function to get the tracker data within given period(last week, month, year)
	 */
	public function getTrackerDataInPeriod($healthValues, $option) {
		$valuesInRange = array();
		switch ($option) {
			case 2 : $valuesInRange = $healthValues; // All dates
					 break;
			case 3 : foreach ($healthValues as $key => $healthArray) { // Last week
						 $temp = array();
						 foreach ($healthArray as $key1 => $value) {
							 $healthDate =  date('m/d/Y', $key1);
							 if(strtotime($healthDate) >= strtotime('-7 day')) {
								 $temp[$key1] = $value;
							 }
						 }
						 $valuesInRange[$key] = $temp;
					 }
					 break;
			case 4 : foreach ($healthValues as $key => $healthArray) { // Last month
						 $temp = array();
						 foreach ($healthArray as $key1 => $value) {
							 $healthDate =  date('m/d/Y', $key1);
							 if(strtotime($healthDate) >= strtotime('-30 day')) {
								 $temp[$key1] = $value;
							 }
						 }
						 $valuesInRange[$key] = $temp;
					 }
					 break;
			case 5 : foreach ($healthValues as $key => $healthArray) { // Last year
						 $temp = array();
						 foreach ($healthArray as $key1 => $value) {
							 $healthDate =  date('m/d/Y', $key1);
							 if(strtotime($healthDate) >= strtotime('-365 day')) {
								 $temp[$key1] = $value;
							 }
						 }
						 $valuesInRange[$key] = $temp;
					 }
					 break;
			case 6 : $firstDay = date('m/d/Y', strtotime('first day of January this year')); // Year till date
					 foreach ($healthValues as $key => $healthArray) {
						 $temp = array();
						 foreach ($healthArray as $key1 => $value) {
							 $healthDate =  date('m/d/Y', $key1);
							 if(strtotime($healthDate) >= strtotime($firstDay)) {
								 $temp[$key1] = $value;
							 }
						 }
						 $valuesInRange[$key] = $temp;
					 }
					 break;
		}
		return $valuesInRange;
	}
	
}
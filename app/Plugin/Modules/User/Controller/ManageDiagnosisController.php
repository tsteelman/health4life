<?php

/**
 * ManageMedicationController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('AuthComponent', 'Controller/Component');
App::import('Controller', 'Api');
App::uses('FollowingPage', 'Model');

/**
 * ManageMedicationController for the frontend
 *
 * ManageMedicationController is used for managing user medication
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers
 */
class ManageDiagnosisController extends UserAppController {

    /**
     * Models used by this controller
     *
     * @var array
     */
    public $uses = array('User', 'PatientDisease', 'Treatment',
		'Disease', 'UserRegistrationForm', 'UserTreatment',
		'FollowingPage'
		);

    /**
     * Edit User Account Settings
     */
    public function index() {
        $formId = 'patient_disease_form';
		$this->set('title_for_layout',"Manage Diagnosis");

        $validationOptions = $this->PatientDisease->validate;
        $relatedForms = "";
        $validationGroups = array();
        //$this->JQValidator->addValidation('PatientDisease', $validationOptions, $formId, $validationGroups, $relatedForms);
        $this->JQValidator->addValidation('PatientDisease', $validationOptions, 'patient_disease_form', array(), '');

        $inputDefaults = array(
            'label' => false,
            'div' => false,
            'class' => 'form-control'
        );
        $model = 'User';

        $userId = $this->Auth->user('id');

        $userData = $this->User->findById($userId);
        $type = $userData['User']['type'];
        $userImage = Common::getUserThumb($userId, $type, 'medium', 'img-responsive pull-left img-thumbnail', 'url');
		$profilePhotoClass = Common::getUserThumbClass($type);

        // get patient diseases
        if ($this->request->isPost()) {
            $this->__addPatientDiseaseDetails($userId, $this->request);
            $this->Session->setFlash(__('Diagnosis details have been updated.'), 'success');
        }

        $disease_list = $this->PatientDisease->getPatientDiseaseDetails($userId);
        $treatment_list = array();
        $ids = array();
        foreach ($disease_list as $diseases) {
            $treatmentIds = $this->UserTreatment->getTreatmentForDisease($userId, $diseases['PatientDisease']['id']);
            $treatment_list[$diseases['PatientDisease']['id']] = array_merge($treatmentIds);
            $ids = array_merge($ids, $treatmentIds);
        }

        $treatments = $this->Treatment->getTreatementDetailsByIdList($ids);

        $dob = array();
        $dob['year'] = Date::getYears();
        $dob['month'] = Date::getMonths();
        $dob['day'] = Date::getDays();
        $this->set(compact('model', 'inputDefaults', 'formId', 'userId', 'userImage', 'profilePhotoClass', 'dob', 'disease_list', 'treatments', 'treatment_list', 'type'));
    }

    private function __addPatientDiseaseDetails($userId, $params) {
        if (isset($params->data['PatientDisease'])) {
            $patientDiseases = $params->data['PatientDisease'];
            foreach ($patientDiseases as $patientDisease) {
                // diagnosis date
				if (!empty($patientDisease['diagnosis_date_year'])) {
                $diagnosisDate = join('-', array(
                    $patientDisease['diagnosis_date_year'], '01', '01'));
                $diagnosisDate.=' 00:00:00';
				} else {
					$diagnosisDate = NULL;
				}

                // treatment list
                $userTreatments = trim($patientDisease['treatment_id'], ',');

                // for new user created disease
                if ($patientDisease ['disease_id'] == 0) {
                    $this->Disease->create();
                    $data_disease['Disease']['id'] = '';
                    $data_disease['Disease']['name'] = $patientDisease ['disease_name'];
                    $data_disease['Disease']['user_id'] = $userId;
                    $data_disease['Disease']['status'] = Disease::AWAITING_USER_CREATED_DISEASE; // user requested disease
                    $flag = $this->Disease->save($data_disease, array(
                        'validate' => false
                    ));
                    $patientDisease['disease_id'] = $this->Disease->id;
                }

				if (!empty($patientDisease['disease_id'])) {
					// data to be saved
					$data = array(
						'id' => empty($patientDisease['id']) ? "" : $patientDisease['id'],
						'disease_id' => $patientDisease['disease_id'],
						'patient_id' => $userId,
						'user_treatments' => $userTreatments
					);
					if (isset($diagnosisDate)) {
						$data['diagnosis_date'] = $diagnosisDate;
					}

					$patientDiseaseData = $this->PatientDisease->save($data, array('validate' => false));
					//Disease follow data
					$diseaseData = array(
						'type' => FollowingPage::DISEASE_TYPE,
						'page_id' => $patientDisease['disease_id'],
						'user_id' => $userId,
						'notification' => FollowingPage::NOTIFICATION_ON
					);
					$this->FollowingPage->followPage($diseaseData);
					$this->UserTreatment->deleteUserTreatmentDiseaseRecord($userId, $patientDiseaseData['PatientDisease']['id']);

					$this->loadModel('UserSymptom');
					$this->UserSymptom->addPatientDiseaseSymptoms($userId, $patientDisease['disease_id']);

					$this->loadModel('UserTreatment');
					$user_treatments = array();
					if (!empty($userTreatments)) {
						$user_treatments = explode(',', $userTreatments);
					}

					if (isset($user_treatments) && !empty($user_treatments)) {
						foreach ($user_treatments as $treatment) {
							$this->UserTreatment->addPatientTreatment($userId, $treatment, $patientDiseaseData['PatientDisease']['id']);
						}
					}
				}
            }
        }
        
        $this->redirect('/user/manage_diagnosis');
    }

    public function delete() {
        if ($this->request->isPost()) {
            $userId = $this->Auth->user('id');
            $records = $this->PatientDisease->getPatientDiseaseDetails($userId);
            if (count($records) != 1) {
                $treatment_id = $this->request->data('id');
				$unfollow_status = $this->request->data('unfollow');				
				if ($unfollow_status === 'true') {					
				$patientRecord = $this->PatientDisease->findById($treatment_id);
				//Disease unfollow data
				$diseaseData = array(
					'type' => FollowingPage::DISEASE_TYPE,
					'page_id' => $patientRecord['PatientDisease']['disease_id'],
					'user_id' => $userId
				);
				$this->FollowingPage->unFollowPage($diseaseData);
				}
				$this->PatientDisease->delete($treatment_id);
                die('success');
            } else {
                die('failed');
            }
        }
    }

}

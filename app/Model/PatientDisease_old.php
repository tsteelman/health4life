<?php

App::uses('AppModel', 'Model');

/**
 * PatientDisease Model
 *
 * @property Disease $Disease
 * @property Patient $Patient
 */
class PatientDisease extends AppModel {

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Disease' => array(
            'className' => 'Disease',
            'foreignKey' => 'disease_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )/* ,
              'Patient' => array(
              'className' => 'Patient',
              'foreignKey' => 'patient_id',
              'conditions' => '',
              'fields' => '',
              'order' => ''
              ) */
    );

    /**
     * Validations
     *
     * @var array
     */
    public $validate = array(
        'disease_name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a valid diagnosis'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'message' => 'Cannot be more than 100 characters long.'
            )
        ),
        'diagnosis_date_year' => array(
            'isValidDiagonisedDate' => array(
                'rule' => array('isValidDiagonisedDate'),
                'message' => 'Please enter a valid date.',
                'allowEmpty' => true
            )
        )
    );

    /*
     * Function to find the diseases associated with a user
     *
     * @param $userId Integet user's id
     *
     * @return integer
     */

    function findDiseases($userId) {
        $disease = $this->find('list', array(
            'conditions' => array('patient_id' => $userId),
            'fields' => array('disease_id')
        ));

        if (isset($disease)) {
            return $disease;
        } else {
            return FALSE;
        }
    }

    function findUsersWithDisease($diseaseId) {
        $users = $this->find('list', array(
            'conditions' => array('disease_id' => $diseaseId),
            'fields' => array('patient_id')
        ));

        if (isset($users)) {
            return $users;
        } else {
            return FALSE;
        }
    }

    function replaceDiseaseOfUsers($currentDiseaseId, $newDiseaseId) {
        $users = $this->find('all', array(
            'conditions' => array('disease_id' => $currentDiseaseId)
        ));
        foreach ($users as $patient) {
            if ($this->hasAny(array('disease_id' => $newDiseaseId, 'patient_id' => $patient['PatientDisease']['patient_id']))) {
                $this->delete($patient['PatientDisease']['id']);
            } else {
                $this->id = $patient['PatientDisease']['id'];
                $this->set('disease_id', $newDiseaseId);
                $this->save();
            }
        }
//        if ($this->updateAll(
//                        array('PatientDisease.disease_id' => $newDiseaseId), array('PatientDisease.disease_id' => $currentDiseaseId)
//                )) {
//            return TRUE;
//        } else {
//            return FALSE;
//        }
    }

    /**
     * Function to get disease names of a user
     *
     * @param type $userId
     * @return array
     */
    function getUserDisease($userId) {
        $disease = $this->find("all", array(
            'joins' => array(
                array(
                    'table' => 'diseases',
                    'alias' => 'Diseases',
                    'type' => 'LEFT',
                    'conditions' => 'Diseases.id  = PatientDisease.disease_id'
                ),
                array(
                    'table' => 'user_treatments',
                    'alias' => 'UserTreatment',
                    'type' => 'LEFT',
                    'conditions' => 'PatientDisease.id  = UserTreatment.patient_disease_id'
                ),
                array(
                    'table' => 'treatments',
                    'alias' => 'Treatment',
                    'type' => 'LEFT',
                    'conditions' => 'Treatment.id  = UserTreatment.treatment_id'
                ),                 
            ),
            'conditions' => array(
                'PatientDisease.patient_id' => $userId,
            ),
            'fields' => array('Diseases.name', 'Diseases.id', 'Diseases.survey_id', 
            'Treatment.name', 'PatientDisease.diagnosis_date'),
            'group' => array('PatientDisease.id'),
            'order' => array('PatientDisease.created' => 'asc','Disease.name' => 'asc')
                )
        );
        return $disease;
    }

    function getPatientDiseaseDetails($userId) {
        $diseaseRecord = $this->find("all", array(
            'joins' => array(
                array(
                    'table' => 'diseases',
                    'alias' => 'Diseases',
                    'type' => 'INNER',
                    'conditions' => 'Diseases.id = PatientDisease.disease_id'
                ),
            ),
            'conditions' => array(
                'PatientDisease.patient_id' => $userId,
            ),
            'group' => array('PatientDisease.disease_id')
        ));
        
        return($diseaseRecord);
    }
    
	/**
	 * Function to get user disease ids
	 * 
	 * @param int $userId
	 * @return array disease ids
	 */
	  function getPatientDiseaseIds($userId) {
		$diseaseRecord = $this->find("all", array(
			'conditions' => array(
				'PatientDisease.patient_id' => $userId,
			),
			'fields' => array('PatientDisease.disease_id'),
			'group' => array('PatientDisease.disease_id')
		));

		return($diseaseRecord);
	}

	/**
	 * Function to get the disease name of a user
	 * 
	 * @param int $userId
	 * @return string
	 */
	function getUserDiseaseName($userId) {
		$diseaseName = '';
		$disease = $this->find('first', array(
			'conditions' => array(
				'PatientDisease.patient_id' => $userId
			),
			'fields' => array('Disease.name')
		));
		if (!empty($disease)) {
			$diseaseName = $disease['Disease']['name'];
		}
		return($diseaseName);
	}
}
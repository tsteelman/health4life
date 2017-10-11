<?php

App::uses('AppModel', 'Model');

/**
 * UserTreatment Model
 *
 */
class UserTreatment extends AppModel {
    /*
     * Function to get user treatments
     */

    public function getUserTreatmentNames($user_id) {

        $data = $this->find('all', array(
            'joins' => array(
                array(
                    'table' => 'treatments',
                    'alias' => 'Treatment',
                    'type' => 'INNER',
                    'conditions' => array('Treatment.id = UserTreatment.treatment_id')
                )
            ),
            'conditions' => array('UserTreatment.user_id' => $user_id),
            'fields' => array('DISTINCT Treatment.name')
                )
        );

        $result = array();
        foreach ($data as $treatment) {
            $result[] = $treatment['Treatment']['name'];
        }

        return $result;
    }

    public function addPatientTreatment($patientId, $treatmentId, $patientDiseaseId) {
        $this->create();
        $data = array(
            'user_id' => $patientId,
            'treatment_id' => $treatmentId,
            'patient_disease_id' => $patientDiseaseId
        );
        $result = $this->save($data);
    }

    public function getTreatmentForDisease($userId, $PatientDiseaseId) {

        $record = $this->find('all', array(
            'conditions' => array(
                'UserTreatment.user_id' => $userId,
                'UserTreatment.patient_disease_id' => $PatientDiseaseId
            )
        ));

        $result = array();
        foreach ($record as $treatment) {
            $result[] = $treatment['UserTreatment']['treatment_id'];
        }

        return $result;
    }

    public function deleteUserTreatmentDiseaseRecord($userId, $patientDiseaseId) {
        $this->deleteAll(
                array(
                    'UserTreatment.user_id' => $userId,
                    'UserTreatment.patient_disease_id' => $patientDiseaseId,
                )
        );
	}

	/**
	 * Function to get a comma separated list of treatment names of a user
	 * 
	 * @param int $userId
	 * @return string
	 */
	public function getUserTreatmentNamesList($userId) {
		$treatments = '';
		$treatmentsList = $this->getUserTreatmentNames($userId);
		if (!empty($treatmentsList)) {
			$treatments = join(', ', $treatmentsList);
		}
		return $treatments;
	}
	
	/**
	 * Function to get a comma separated list of treatment names for diseases
	 * 
	 * @param int $userId
	 * @param int $PatientDiseaseId
	 * @return string
	 */
	public function getTreatmentNamesForDisease($userId, $PatientDiseaseId) {
		
		$treatments = array();
        $record = $this->find('all', array(
			'joins' => array(
                array(
                    'table' => 'treatments',
                    'alias' => 'Treatment',
                    'type' => 'INNER',
                    'conditions' => array('Treatment.id = UserTreatment.treatment_id')
                )
            ),
            'conditions' => array(
                'UserTreatment.user_id' => $userId,
                'UserTreatment.patient_disease_id' => $PatientDiseaseId
            ),
			'fields' => array('Treatment.name')
        ));

		foreach ($record as $data) {
			$treatments[] = $data['Treatment']['name'];
		}
		$result = implode(', ', $treatments);
        return $result;
    }
}
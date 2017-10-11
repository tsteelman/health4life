<?php

App::uses('AppModel', 'Model');

/**
 * DiseaseSymptom Model
 *
 */

class DiseaseSymptom extends AppModel {


	/**
	 * Function get disease names of a symptom based on user diseases
	 * @param int $symptomId
	 * @param int $userId
	 * @return string|NULL comma separated disease names
	 */
	public function getUserSymptomDiseases($symptomId, $userId) {
		
		$diseaseNameList = $this->find ( 'all', array (
				'joins' => array (
						array (
								'table' => 'patient_diseases',
								'alias' => 'PatientDisease',
								'type' => 'INNER',
								'foreignKey' => false,
								'conditions' => array (
										'PatientDisease.disease_id = DiseaseSymptom.disease_id'
								)
						),
						array (
								'table' => 'diseases',
								'alias' => 'Disease',
								'type' => 'INNER',
								'foreignKey' => false,
								'conditions' => array (
										'Disease.id = PatientDisease.disease_id'
								)
						)
				),
				'conditions' => array (
						'PatientDisease.patient_id' => $userId,
						'FIND_IN_SET('.$symptomId.',DiseaseSymptom.symptom_ids)'
				),
				'fields' => array (
						'Disease.name'
				)
					
		) );
		
		if (! empty ( $diseaseNameList )) {
			$diseaseNames = "";
			
			foreach ( $diseaseNameList as $diseaseName){
				$diseaseNames .= $diseaseName ['Disease'] ['name'] . ", ";
			}
			//trim the last comma and space
			$diseaseNames = rtrim($diseaseNames, ', ');
			
			return ($diseaseNames);
				
		} else {
			return null;
		}
	}
}

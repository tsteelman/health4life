<?php
App::uses('AppModel', 'Model');
/**
 * Treatment Model
 *
 */
class Treatment extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


 /**
  *
  * Function retrieves comma seperated treatment names
  *
  * @param Array $id_list
  * @return String
  */
 public function getTreatementDetailsByIdList($id_list) {
     $records = $this->find('all', array('conditions' => array(
         'id' => $id_list)));

     $treatment_names = array();
     foreach($records as $key=>$treatment) {
         $treatment_names[$treatment['Treatment']['id']] = $treatment['Treatment']['name'];
     }

     return $treatment_names;
 }
 
 /*
  * Function to update count of uses using a treatment
  * 
  * @param int $id
  * @param int $count
  */
 public function updateTreatmentUsersCount($id, $count) {
     $record = $this->findById($id);
     if(!empty($record)) {
         $this->id = $record['Treatment']['id'];
         $data = $record['Treatment'];
         $data['count'] = $count;
         $this->save($data);
         debug($id);
         debug($count);
     }
 }
}

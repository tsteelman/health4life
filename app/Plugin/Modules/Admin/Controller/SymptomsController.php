<?php

/**
 * SymtomsController class file.
 *
 * @author    Varun Ashok <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
/**
 * Symptoms List Management for the admin
 *
 * Symptoms List Management Controller is used for admin to edit and create Symptoms
 *
 * @author 	Varun Ashok
 * @package 	Admin
 * @category	Controllers
 */
App::uses('Common', 'Utility');
App::uses('CakeTime', 'Utility');

class SymptomsController extends AdminAppController {
	
	const PAGE_LIMIT = 10;
	
	public $uses = array('Symptom');
		
	function index(){
		
		$condition = array() ;		
		
		$this->paginate = array(
				'limit' => SymptomsController::PAGE_LIMIT,
				'conditions' => $condition,
		);
		
		$symptoms_list = $this->paginate('Symptom');
		$this->set(compact('symptoms_list'));
	}
	
	/**
	 * Function to search a particular symptom
	 *
	 */
	function search() {
		$admin = $this->Auth->user ();
		if ($this->request->query('symptom_name')) {
			$keyword = $this->request->query('symptom_name');
			$this->paginate = array(
					'conditions' => array('Symptom.name LIKE' => '%' . $keyword . '%'),
					'limit' => SymptomsController::PAGE_LIMIT
			);
		}
		$symptoms_list = $this->paginate('Symptom');
		if (sizeof($symptoms_list) == 0) {
			$this->Session->setFlash('No Symptom found.', 'warning');
		} 
		
		$this->set(compact('keyword', 'symptoms_list'));
		$this->render('index');
	}
	
	/**
	 * Function to add new symptom
	 */
	function add(){
		$this->JQValidator->addValidation('Symptom', $this->Symptom->validate, 'SymptomAddForm');
		if (!empty($this->data)) {
			$this->Symptom->create();
			if ($this->Symptom->save($this->data, array('validate' => false))) {
				$this->Session->setFlash(__('The symptom has been added.', true));
				$this->redirect(array('action' => 'index'));
				
			} else {
				$this->Session->setFlash(__('The symptom could not be saved. Please, try again.', true));
			}
		}
		
		$this->render('add_symptoms');
	}
	
	function edit($id = null){
		
		$this->JQValidator->addValidation('Symptom', $this->Symptom->validate, 'SymptomAddForm');
		
		$symptom = $this->Symptom->find('first', array(
			'conditions' => array('id' => $id)
		));

		if (!empty($this->data)) {
			if ($this->Symptom->save($this->data, array('validate' => false))) {
				$this->Session->setFlash(__('The symptom has been updated.', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The symptom could not be updated. Please, try again.', true));
			}
		}
		
		$this->data = $symptom;
		$this->render('edit_symptoms');
	}
	
	/**
	 * Function to delete a particular Survey
	 *
	 */
	function delete($delete_id = null) {
		$this->loadModel('Survey');
		if (!$delete_id) {
			$this->Session->setFlash(__('Invalid id for symptom', true));
			$this->redirect(array('action' => 'index'));
		}
		if ($this->Symptom->deleteAll(array('Symptom.id' => $delete_id))) {
			$this->Session->setFlash(__('Symptom has been deleted', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Symptom not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
}
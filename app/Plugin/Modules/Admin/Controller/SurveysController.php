<?php

/**
 * SurveyListManagementController class file.
 *
 * @author    Varun Ashok <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
/**
 * Survey List Management for the admin
 *
 * Survey List Management Controller is used for admin to edit and create Surveys
 *
 * @author 	Varun Ashok
 * @package 	Admin
 * @category	Controllers
 */
App::uses('Common', 'Utility');
App::uses('CakeTime', 'Utility');

class SurveysController extends AdminAppController {

    public $uses = array(
        'Survey',
        'SurveyQuestion',
        'User',
        'SurveyResult',
        'Disease'
    );
    public $components = array('Session');
    
    const PAGE_LIMIT = 10;

    /**
     * Admin Survey List Management home
     */
    function index() {
        $this->JQValidator->addValidation('Survey', $this->Survey->validate, 'Survey');
        $condition = array() ;
        $this->paginate = array(
            'limit' => SurveysController::PAGE_LIMIT,
        	'conditions' => $condition,
        );
        $survey_list = $this->paginate('Survey');
        $this->setOtherData($survey_list);
    }

     /**
     * Function to list the Surveys
     *
     */
    function setOtherData($survey_list) {
    	$admin = $this->Auth->user();
        $timezone = new DateTimeZone($admin['timezone']);
        foreach ($survey_list as $key => $survey) {
            $survey_list[$key]['Survey']['created'] = Date::getUSFormatDateTime($survey_list[$key]['Survey']['created_date'], $timezone);
        } 
        $this->set(compact('survey_list'));
    }
    
    /**
     * Function to search a particular Survey
     *
     */
    function search() {
        if ($this->request->query('survey_name')) {
            $keyword = $this->request->query('survey_name');
            $this->paginate = array(
                'conditions' => array('Survey.name LIKE' => '%' . $keyword . '%'),
                'limit' => 10
            );
        } 
        $survey_list = $this->paginate('Survey');
        if (sizeof($survey_list) == 0) {
            $this->Session->setFlash('No Survey found.', 'warning');
        } else {
            $this->setOtherData($survey_list);
        }
        $this->set(compact('keyword'));
        $this->render('index');
    }

        /**
	 * Function to delete a particular Survey
	 *
	 */
        function deleteSurvey() {
            $deleteSurveyId = $this->request->data['Survey']['delete_survey_id'];
            if (!$deleteSurveyId) {
                $this->Session->setFlash(__('Invalid id for Survey', true));
                $this->redirect(array('action' => 'index'));
            }
            if ($this->Survey->deleteAll(array('Survey.id' => $deleteSurveyId))) {
                $this->Session->setFlash(__('Survey has been deleted', true));
                $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Survey not deleted', true));
            $this->redirect(array('action' => 'index'));
        }

	/**
	 * Function to edit a particular Survey
	 *
	 */
	function editSurvey($id = null) {
		$survey_details = $this->Survey->getSurveyDetails($id);
                $diseases = $this->Disease->find ( 'all', array (
				'conditions' => array (
						'Disease.survey_id' => $id 
				) 
		) );
		$this->set(compact('survey_details','id','diseases'));
		if (!empty($this->request->data)) {
                        $surveyId = $this->request->data['Survey']['id'];
                        $diseaseName = $this->request->data['Survey']['SurveyDisease'];
                        if(!empty($diseaseName)) { // in case of invalid disease names
                                $this->Session->setFlash(__('Disease name is not valid', true));
                                $this->redirect(array('action' => 'editSurvey', $surveyId));
                        }
                        
			$this->Survey->id = $surveyId;
			if ($this->Survey->save($this->request->data, array('validate' => false))) {
                                if(!empty($this->request->data['Survey']['Disease_Id'])){
                                    // trim the last ','
                                    $deseaseIds = trim($this->request->data['Survey']['Disease_Id'], ',');
                                    $deseaseArray = explode(',',$deseaseIds);
                                    $this->linkDiseaseToSurvey($surveyId, $deseaseArray);
                                }
				$this->Session->setFlash(__("Survey details updated."));
			} else {
                                $this->Session->setFlash(__("Survey details not updated, try again later"));
                        }
                        return $this->redirect(array('action' => 'index'));
		}
		if (!$this->request->data) {
			$this->request->data = $survey_details;
		}
		$this->render('edit_survey');
	}

	/**
	 * Function to add new Survey
	 *
	 * @return type
	 */
	function add() {
		$this->render('add_survey');
                $userId = $this->Auth->user('id');
                if (!empty($this->data)) {
                    $this->request->data['Survey']['created_by'] = $userId;
                    $this->request->data['Survey']['created_date'] = date('Y-m-d H:i:s'); 
                    $surveyKey = 'Surv'.$this->salt(10);
                    $this->request->data['Survey']['survey_key'] = $surveyKey;
                    $diseaseName = $this->request->data['Survey']['SurveyDisease'];
                    if(!empty($diseaseName)) { // in case if invalid disease names
                            $this->Session->setFlash(__('Disease name is not valid', true));
                            $this->redirect(array('action' => 'add'));
                    }
                    $this->Survey->create();
                    if ($this->Survey->save($this->data)) {
                        $surveyId = $this->Survey->id;
                        $diseaseId = $this->request->data['Survey']['Disease_Id'];
                        if(!empty($diseaseId)){
                            // trim the last ','
                            $deseaseIds = trim($diseaseId, ',');
                            $deseaseArray = explode(',',$deseaseIds);
                            $this->linkDiseaseToSurvey($surveyId, $deseaseArray);
                        }
                        $this->Session->setFlash(__('The survey has been added. Please add questions in it.', true));
                        $this->redirect(array('action' => 'addQuestion', $surveyId));
                    } else {
                            if ($this->Survey->hasAny(array('name' => $this->request->data['Survey']['name']))) {
                                $this->Session->setFlash(__('This survey name already exists.', true));
                                $this->redirect(array('action' => 'add'));
                            } else {
                                $this->Session->setFlash(__('Please fill out the fields.', true));
                                $this->redirect(array('action' => 'add'));
                            }
                    }
                }
	}
        
        /**
	 * Function to add a Survey question
	 *
	 * @return type
	 */
        function addQuestion($surveyId = null) {
                    
                  $survey = $this->Survey->getSurveyDetails($surveyId);
                  $surveyName = $survey['name'];
                  $status = $survey['status'];
                  if (!empty($this->data)) {
                      $userId = $this->Auth->user('id');
                      $this->request->data['SurveyQuestion']['question_text'] = $this->request->data['Survey']['question_text'];
                      $this->request->data['SurveyQuestion']['created_by'] = $userId;
                      $this->request->data['SurveyQuestion']['created_time'] = date('Y-m-d H:i:s');
                      $this->request->data['SurveyQuestion']['survey_id'] = $this->request->data['Survey']['id'];
                      if($surveyId == null) {
                        $surveyId = $this->request->data['Survey']['id'];
                      }
                      $type = $this->request->data['Survey']['question_type'];
                      $options = $this->request->data['Survey']['answer_option'];
                      $required = $this->request->data['Survey']['required'];
                      $placeHolder = $this->request->data['Survey']['place_holder'];
                      $options = trim(preg_replace('/\n\s+/', "\n", $options)); // avoiding null values
                      if(isset($options)) {
                            $optionValues = preg_split("/[\n,]+/", $options);
                      }

                      $json = array();
                      $json['type'] = $type;
                      if(isset($optionValues)) {
                          $json['options'] = $optionValues;
                      }
                      $json['required'] = $required;
                      if(isset($placeHolder)) {
                          $json['placeHolder'] = $placeHolder;
                      }
                      $this->request->data['SurveyQuestion']['answers'] = json_encode($json);
                      $this->SurveyQuestion->create();
                      if ($this->SurveyQuestion->save($this->data)) {
                        $this->Session->setFlash(__('The Question has been added.', true));
                      } else {
                        $this->Session->setFlash(__('The Question could not be saved. Please provide a question text.', true));
                      }
                      $this->redirect(array('action' => 'addQuestion/'.$surveyId));
                  }
                  $this->paginate = array(
                            'conditions' => array("survey_id" => $surveyId),
                  );
                  $question_list = $this->paginate('SurveyQuestion');
                  $this->set(compact('surveyId','surveyName','question_list','status'));
        }
        
         /**
	 * Function to delete a particular Question
	 *
	 */
        function deleteQuestion($deleteQuestionId = null, $surveyId = null) {
            $this->loadModel('SurveyQuestion');
            if (!$deleteQuestionId) {
                $this->Session->setFlash(__('Invalid id for Survey', true));
            }
            if ($this->SurveyQuestion->deleteAll(array('SurveyQuestion.id' => $deleteQuestionId))) {
                $this->Session->setFlash(__('Question has been deleted', true));
                $this->redirect(array('action' => 'addQuestion/'.$surveyId));
            }
            $this->Session->setFlash(__('Question not deleted', true));
        }

	/**
	 * Function to edit a particular Question
	 *
	 */
	function editQuestion($id = null) {
		$questionDetails = $this->SurveyQuestion->getQuestionDetails($id);
                $options = json_decode($questionDetails['answers'], true);
                $survey = $this->Survey->getSurveyDetails($questionDetails['survey_id']);
		$this->set(compact('questionDetails','id','options','survey'));
		if (!empty($this->request->data)) {
			$this->SurveyQuestion->id = $this->request->data['Survey']['id'];
                        $surveyId = $this->request->data['Survey']['surveyId'];
                        $this->request->data['SurveyQuestion']['question_text'] = $this->request->data['Survey']['question_text'];
                        $type = $this->request->data['Survey']['question_type'];
                        $options = $this->request->data['answer_options'];
                        $required = $this->request->data['Survey']['required'];
                        $placeHolder = $this->request->data['Survey']['place_holder'];
                        $options = trim(preg_replace('/\n\s+/', "\n", $options)); // avoiding null values
                        if(isset($options)) {
                            $optionValues = preg_split("/[\n,]+/", $options);
                        }
                        $json = array();
                        $json['type'] = $type;
                        if(isset($optionValues)) {
                            $json['options'] = $optionValues;
                        }
                        $json['required'] = $required;
                        if(isset($placeHolder)) {
                            $json['placeHolder'] = $placeHolder;
                        }
                        $this->request->data['SurveyQuestion']['answers'] = json_encode($json);
			if ($this->SurveyQuestion->save($this->request->data, array('validate' => false))) {
				$this->Session->setFlash(__("Question edited."));
			} else {
                                $this->Session->setFlash(__("Question not edited, try again later"));
                        }
                        $this->redirect(array('action' => 'addQuestion/'.$surveyId));
		}
		if (!$this->request->data) {
			$this->request->data = $questionDetails;
		}
	}
        
        /**
	 * Generates a random key for survey
	 *
	 */
        function salt($salt_length = 6)
        {
            return substr(md5(uniqid(rand(), true)), 0, $salt_length);
        }
        
        /**
	 * Function to show survey analytics
	 *
	 */
        function showAnalytics($surveyId = null) {
            $attendedUsers = $this->SurveyResult->getAttendedUsersCount($surveyId);
            $analytics = $this->Survey->getAnalytics($surveyId);
            $surveyName = $analytics[0]['Survey']['name'];
            $selectedOptions = array();
            foreach ($analytics as $row) {
                $option = json_decode($row['Survey_result']['selected_answers'], true);
                $selectedValue = str_replace("\n","", $option['selected_answer']);
                $questionId = $row['Survey_question']['id'];
                if(is_array($selectedValue)) {
                    foreach ($selectedValue as $value) {
                        $selectedOptions[] = $value.$questionId;
                    }
                } else {
                    $selectedOptions[] = $selectedValue.$questionId;
                }
            }
            
            $this->paginate = array(
                   'conditions' => array("survey_id" => $surveyId),
                );
            $question_list = $this->paginate('SurveyQuestion');
            foreach($question_list as $question) {
                $temp['questionText'] = $question['SurveyQuestion']['question_text'];
                $queId = $question['SurveyQuestion']['id'];
                $json = json_decode($question['SurveyQuestion']['answers'], true);
                if($json['type'] == 2 || $json['type'] == 3 ) {
                    continue;
                }
                $optionsArray = $json['options'];
                $optionDetails = array();
                $optionCount = array();
                $optionPercent = array();
                foreach ($optionsArray as $value) {
                     $value = str_replace("\r","",$value);
                     $valueId = $value.$queId;
                     $tempOption = $value;
                     $count = count(array_keys($selectedOptions, $valueId));
                     $optionDetails[] = $tempOption;
                     $optionCount[] = $count;
                }
                $sum = array_sum($optionCount);
                $percentage = 0;
                foreach($optionCount as $number){
                    if($sum!=0) {
                        $percentage = ($number/$sum) * 100;
                        $percentage = round($percentage);
                    }
                    $optionPercent[] = $percentage;
                }
                $temp['options'] = $optionDetails;
                $temp['count'] = $optionCount;
                $temp['percentage'] = $optionPercent;
                $answeredCount = $this->SurveyResult->getAnsweredUsersCount($question['SurveyQuestion']['id']);
                $skippedCount = $this->SurveyResult->getSkippedUsersCount($question['SurveyQuestion']['id']);
                $temp['answered'] = $answeredCount;
                $temp['skipped'] = $skippedCount;
                $analyticData[] = $temp;
            }
            
            $this->set(compact('attendedUsers','analyticData','surveyName'));
            $this->render('analytics');
           
        }
        
        /**
	 * Function to add surveys to the disease
	 *
	 */
        function linkDiseaseToSurvey($surveyId, $diseaseIds) {
            foreach ($diseaseIds as $disease) {
                $this->Disease->updateAll(array('Disease.survey_id' => $surveyId), array('Disease.id' => $disease));
            }
        }
        
        /**
	 * Function to dissociate a disease from survey 
	 *
	 */
        function dissociateDisease() {
            $diseaseId = $this->request->data('id');
            $this->Disease->updateAll(
                    array('Disease.survey_id' => 0), 
                    array('Disease.id' => $diseaseId));
        }
        
        /**
	 * Function to publish a survey 
	 *
	 */
        function publishSurvey() {
            $data = $this->request->data;
            $surveyId = $data['surveyId'];
            $this->Survey->id = $surveyId;
            $this->request->data['Survey']['status'] = 1;
            if ($this->Survey->save($this->request->data, array('validate' => false))) {
		$this->Session->setFlash(__("Survey has been published successfully."));
                die(json_encode(array('success' => true)));
            } else {
                $this->Session->setFlash(__("Survey not published, try again later."));
                die(json_encode(array('success' => false)));
            }
        }
}
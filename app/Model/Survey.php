<?php

App::uses('AppModel', 'Model');
App::import('Controller', 'Api');
/**
 * Survey Model
 *
 */
class Survey extends AppModel {
    
     public $validate = array(
            'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter the survey name'
            ),
            'exists' => array(
                'rule' => array('checkExistingSurveyName', 'name'),
                'message' => 'This Survey name already exists.'
            )
           )
         
     );
     
    /**
      * Function returns survey details by id
      *
      * @param Integer $id
      * @return mixed
      */
     public function getSurveyDetails($id) {
                $result = array();
		$survey_details = $this->findById($id);
		if (!empty($survey_details))
		{
			$result['name'] = $survey_details['Survey']['name'];
			$result['description'] = $survey_details['Survey']['description'];
                        $result['surveyKey'] = $survey_details['Survey']['survey_key'];
                        $result['type'] = $survey_details['Survey']['type'] == true ? 1 : 0;
                        $result['status'] = $survey_details['Survey']['status'];
			return $result;
		}
		else {
			return FALSE;
		}
    }
    
     /**
      * Function checks a Survey name already exists or not
      *
      * @param $name
      * @param $id
      * 
      * @return boolean
      */
    public function checkExistingSurveyName($name = NULL, $id = 0) {
		if (isset($this->request->data ['name'])) {
			$name = trim($this->request->data ['name']);
		}
		$survey = $this->findByName($name);
		if (!empty($survey['Survey'])) {
			if (isset($this->request->data ['id'])) {
				$id = $this->request->data ['id'];
			}
			if ($id > 0) {
				$surveyId = $survey['Survey']['id'];
				$valid = ($id == $surveyId) ? true : false;
			} else {
				$valid = false;
			}
		} else {
			$valid = true;
		}
		if (!isset($this->request->data ['name'])) {
			return $valid;
			exit();
		} else {
			$this->data = $valid;
		}
	}
     
     /**
     * Function to get attended questions of a survey for a user
     * 
     * @param type $userId
     * @param type $surveyKey
     * @return array
     */ 
     public function getAttendedQuestions($userId, $surveyKey) {
                   $data = $this->find ( "all", array (
                                    'joins' => array (
                                                    array (
                                                           'table' => 'survey_questions',
                                                           'alias' => 'Survey_question',
                                                           'type' => 'LEFT',
                                                           'conditions' => 'Survey_question.survey_id  = Survey.id' 
                                                    ),
                                                    array (
								'table' => 'survey_results',
								'alias' => 'Survey_result',
								'type' => 'LEFT',
								'conditions' => 'Survey_result.question_id = Survey_question.id' 
                                                    )
                ),
                'conditions' => array(
                    'Survey.survey_key' => $surveyKey, 'Survey_result.user_id' => $userId  
                ),
                'fields' => array('Survey.id','Survey.name','Survey.description','Survey_question.id', 'Survey_question.question_text', 'Survey_result.id', 'Survey_result.selected_answers'),
               'order' => array('Survey_question.id'),
              )
            );
            return $data;
     }
     
     /**
     * Function to get not attended questions of a survey for a user
     * 
     * @param type $surveyKey
     * @param array $queIds
     * @return array
     */ 
     public function getNotAttendedQuestions($surveyKey, $queIds) {
                    $data = $this->find ( "all", array (
                                           'joins' => array (
                                                           array (
                                                                  'table' => 'survey_questions',
                                                                  'alias' => 'Survey_question',
                                                                  'type' => 'LEFT',
                                                                  'conditions' => 'Survey_question.survey_id  = Survey.id' 
                                                           ),
                                                           array (
                                                                   'table' => 'survey_results',
                                                                   'alias' => 'Survey_result',
                                                                   'type' => 'LEFT',
                                                                   'conditions' => 'Survey_result.question_id = Survey_question.id' 
                                                           )
                      ),
                       'conditions' => array(
                           'Survey.survey_key' => $surveyKey, 
                           "NOT" => array( 'Survey_question.id' => $queIds)
                     ),
                       'fields' => array('Survey.id','Survey.name','Survey.description','Survey_question.id', 'Survey_question.question_text', 'Survey_question.answers'),
                      'order' => array('Survey_question.id'),
                      'group' => array('Survey_question.id')
                    )
                );
           return $data;
     }
     
     /**
     * Function to get answered questions of a survey for a user
     * 
     * @param type $userId
     * @param type $surveyId
     * @return array
     */ 
     public function getAnsweredQuestions($userId, $surveyId) {
                $data = $this->find ( "all", array (
                                    'joins' => array (
                                                    array (
                                                           'table' => 'survey_questions',
                                                           'alias' => 'Survey_question',
                                                           'type' => 'LEFT',
                                                           'conditions' => 'Survey_question.survey_id  = Survey.id' 
                                                    ),
                                                    array (
								'table' => 'survey_results',
								'alias' => 'Survey_result',
								'type' => 'LEFT',
								'conditions' => 'Survey_result.question_id = Survey_question.id' 
                                                    )
                ),
                'conditions' => array(
                    'Survey.id' => $surveyId, 'Survey_result.user_id' => $userId  
                ),
                'fields' => array('Survey.id','Survey.name','Survey.description','Survey_question.id', 'Survey_question.question_text', 'Survey_result.id', 'Survey_result.selected_answers'),
               'order' => array('Survey_question.id'),
              )
            );
            return $data;
     }
     
     /**
     * Function to get next questions of a survey for a user
     * 
     * @param type $surveyId
     * @param array $queIds
     * @return array
     */ 
     public function getNextQuestion($surveyId, $queIds) {
             $data = $this->find ( "all", array (
                                           'joins' => array (
                                                           array (
                                                                  'table' => 'survey_questions',
                                                                  'alias' => 'Survey_question',
                                                                  'type' => 'LEFT',
                                                                  'conditions' => 'Survey_question.survey_id  = Survey.id' 
                                                           ),
                                                           array (
                                                                   'table' => 'survey_results',
                                                                   'alias' => 'Survey_result',
                                                                   'type' => 'LEFT',
                                                                   'conditions' => 'Survey_result.question_id = Survey_question.id' 
                                                           )
                      ),
                       'conditions' => array(
                           'Survey.id' => $surveyId, 
                           "NOT" => array( 'Survey_question.id' => $queIds)
                     ),
                       'fields' => array('Survey.id','Survey.name','Survey.description','Survey_question.id', 'Survey_question.question_text', 'Survey_question.answers'),
                      'order' => array('Survey_question.id'),
                      'group' => array('Survey_question.id')
                    )
                );
           return $data;
     }
     
     /**
     * Function to get data for survey analytics at admin side
     * 
     * @param type $surveyId
     * @return array
     */ 
     public function getAnalytics($surveyId) {
            $data = $this->find ( "all", array (
                                    'joins' => array (
                                                    array (
                                                           'table' => 'survey_questions',
                                                           'alias' => 'Survey_question',
                                                           'type' => 'LEFT',
                                                           'conditions' => 'Survey_question.survey_id  = Survey.id' 
                                                    ),
                                                    array (
								'table' => 'survey_results',
								'alias' => 'Survey_result',
								'type' => 'LEFT',
								'conditions' => 'Survey_result.question_id = Survey_question.id' 
                                                    )
                ),
                'conditions' => array(
                    'Survey.id' => $surveyId  
                ),
                'fields' => array('Survey.id','Survey.name','Survey.description','Survey_question.id', 'Survey_question.question_text', 'Survey_result.id', 'Survey_result.user_id', 'Survey_result.selected_answers'),
               'order' => array('Survey_question.id'),
              )
            );
            return $data;
     }
     
}
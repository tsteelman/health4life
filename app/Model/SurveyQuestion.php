<?php

App::uses('AppModel', 'Model');
App::import('Controller', 'Api');
/**
 * SurveyQuestion Model
 *
 */
class SurveyQuestion extends AppModel {
    
    public $validate = array(
            'question_text' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter the question'
            ),
           ),
         
            'question_type' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter the question type'
            ),
         ),
    );
    
    /**
      * Function returns survey question details by id
      *
      * @param Integer $id
      * @return mixed
      */
     public function getQuestionDetails($id) {
                $result = array();
		$question_details = $this->findById($id);
		if (!empty($question_details))
		{
			$result['id'] = $question_details['SurveyQuestion']['id'];
			$result['survey_id'] = $question_details['SurveyQuestion']['survey_id'];
                        $result['question_text'] = $question_details['SurveyQuestion']['question_text'];
                        $result['answers'] = $question_details['SurveyQuestion']['answers'];
			return $result;
		}
		else {
			return FALSE;
		}
    }
    
    /**
      * Function returns survey question details by id
      *
      * @param $surveyId
      * @return Integer
      */
    public function getQuestionCount($surveyId) {
            $questionCount = $this->find('count', array(
                'conditions' => array('survey_id' => $surveyId),
            ));
        return $questionCount;
    }
    
}
<?php

App::uses('AppModel', 'Model');
App::import('Controller', 'Api');
/**
 * SurveyQuestion Model
 *
 */
class SurveyResult extends AppModel {
    
    const ANSWERED_STATUS = 1;
    const SKIPPED_STATUS = 0;
    
    /**
      * Function returns the count of users who attended the survey
      *
      * @param Integer $surveyId
      * @return Integer
      */
    public function getAttendedUsersCount($surveyId) {
            $count = $this->find('count', array(
                'conditions' => array('survey_id' => $surveyId),
                'fields' => 'COUNT(DISTINCT user_id) as count'
            ));
        return $count;
    }
    
    /**
      * Function returns the count of users who answered a question
      *
      * @param Integer $questionId
      * @return Integer
      */
    public function getAnsweredUsersCount($questionId) {
            $count = $this->find('count', array(
                'conditions' => array('question_id' => $questionId, 'status' => self::ANSWERED_STATUS)
            ));
        return $count;
    }
    
    /**
      * Function returns the count of users who skipped a question
      *
      * @param Integer $questionId
      * @return Integer
      */
    public function getSkippedUsersCount($questionId) {
            $count = $this->find('count', array(
                'conditions' => array('question_id' => $questionId, 'status' => self::SKIPPED_STATUS)
            ));
        return $count;
    }
}
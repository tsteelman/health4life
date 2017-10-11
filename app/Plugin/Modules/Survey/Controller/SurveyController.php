<?php

/**
 * SurveyController class file.
 *
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('SurveyAppController', 'Survey.Controller');

/**
 * SurveyController for front end survey.
 *
 * SurveyController is used for listing surveys.
 *
 * @package 	Survey
 * @category	Controllers
 */
class SurveyController extends SurveyAppController {

    const ANSWERED_STATUS = 1;
    const SKIPPED_STATUS = 0;
    
    public $uses = array(
        'Survey','SurveyQuestion','SurveyResult'
    );

    public function beforeFilter() {
        parent::beforeFilter();
    }
    
    /*
     * Function to display survey questions to the user.
     *
     */
    public function index() {
        $userId = $this->Auth->user('id');
        $surveyKey = $this->request->params['surveyKey'];
        if (!$this->Survey->hasAny(array('survey_key' => $surveyKey, 'status' => 1))) {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect('/dashboard');
        }

        $attendedQuestions = $this->Survey->getAttendedQuestions($userId, $surveyKey);
        $queIds = array();
        foreach ($attendedQuestions as $que) {
            $queIds[] = $que['Survey_question']['id'];
        }
        $notAttendedQuestions = $this->Survey->getNotAttendedQuestions($surveyKey, $queIds);
        
        if(isset($attendedQuestions) && $attendedQuestions != NULL) {
            $surveyId = $attendedQuestions[0]['Survey']['id'];
        } else {
            $surveyId = $notAttendedQuestions[0]['Survey']['id'];
        }
        $questionCount = $this->SurveyQuestion->getQuestionCount($surveyId);
        $attendedQuestionsCount = count($attendedQuestions);
        if($questionCount == $attendedQuestionsCount) {
            $completedStatus = true;
        } else {
            $completedStatus = false;
        }
        $this->set( compact ('attendedQuestions', 'notAttendedQuestions', 'completedStatus'));
    }
    
    /*
     * Function to save the survey result.
     *
     */
    public function saveSurveyResult() {
        
        $userId = $this->Auth->user('id');
        $data = $this->request->data;
        $questionId = $data['questionId'];
        $surveyId = $data['surveyId'];
        $selectedAnswer = $data['optionSelected'];
        $lastStatus = $data['lastStatus'];
        if(!empty($selectedAnswer)) {
            $this->request->data['SurveyResult']['user_id'] = $this->Auth->user('id');
            $this->request->data['SurveyResult']['question_id'] = $questionId;
            $this->request->data['SurveyResult']['survey_id'] = $surveyId;
            if($selectedAnswer==" ") {
                $this->request->data['SurveyResult']['status'] = self::SKIPPED_STATUS;
            } else {
                $this->request->data['SurveyResult']['status'] = self::ANSWERED_STATUS;
            }
            $questionDetails = $this->SurveyQuestion->findById($questionId);
            $options = $questionDetails['SurveyQuestion']['answers'];
            $options = json_decode($options, true);
            $options['selected_answer'] = $selectedAnswer;
            $optionsAndAnswer = json_encode($options);
            $this->request->data['SurveyResult']['selected_answers'] = $optionsAndAnswer;
            $this->request->data['SurveyResult']['attended_time'] = date('Y-m-d H:i:s');
            if($this->SurveyResult->save($this->data)) {
                $attendedQuestions = $this->Survey->getAnsweredQuestions($userId, $surveyId);
                $queIds = array();
                foreach ($attendedQuestions as $que) {
                    $queIds[] = $que['Survey_question']['id'];
                }
                $nextQuestion = $this->Survey->getNextQuestion($surveyId, $queIds);
                if(isset($nextQuestion)) {
                    $attendedView = new View($this, false);
                    $attendedViewContent = $attendedView->element('Survey.survey_ques_attended', array('attendedQuestions' => $attendedQuestions));
                    $nextView = new View($this, false);
                    $viewContent = $nextView->element('Survey.survey_ques_new', array('question' => $nextQuestion));
                }
                if($lastStatus == true) {
                    $this->Session->setFlash(__('Thank you for attending the survey. Your response has been recorded.'), 'success');
                }
                die(json_encode(array('success' => true, 'newContent' => $viewContent, 'attendedContent' => $attendedViewContent)));
            } else {
                die(json_encode(array('success' => false)));
            }
         } else {
             die(json_encode(array('success' => false)));
         }
    }
    
    /*
     * Function to update the survey option.
     *
     * @param int survey_id
     */
    public function updateOption() {
        $data = $this->request->data;
        $resultId = $data['resultId'];
        $selectedAnswer = $data['optionUpdated'];
        $resultDetails = $this->SurveyResult->findById($resultId);
        $newResult = json_decode($resultDetails['SurveyResult']['selected_answers'],true);
        $newResult['selected_answer'] = $selectedAnswer;
        $this->SurveyResult->id = $resultId;
        $this->request->data['SurveyResult']['selected_answers'] = json_encode($newResult);
        if ($this->SurveyResult->save($this->request->data)) {
                die(json_encode(array('success' => true)));
        } else {
                die(json_encode(array('success' => false)));
        }
    }
}
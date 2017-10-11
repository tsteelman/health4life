<?php

/**
 * CalendarController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MyTeamAppController', 'MyTeam.Controller');
App::uses('Validation', 'Utility');


class CalendarController extends MyTeamAppController {

    /**
     * Models used by this controller
     *
     * @var array
     */
    public $uses = array('Event', 'User', 'Country', 'State', 'City', 
        'EventMember', 'TeamMember', 'CareCalendarEvent', 'Event');
    
    /**
     * Components userd by this controller
     * 
     * @var array
     */
    public $components = array('EventForm');

    /**
     * View Team Details
     */
    public function index($showDate = null) {

//        $calendarType = Event::CARE_CALENDAR_EVENTS_ONLY; //4
        $calendarType = 4; //Event::CARE_CALENDAR_EVENTS_ONLY;
        $teamId = $this->_teamId;
        
        if (isset($this->request->date)) {
            $showDate = $this->request->date;
        } else {
            $showDate = null;
        }
        
                
        $allNeedTypes = $this->CareCalendarEvent->getTaskTypes();
        $allStatusList = $this->CareCalendarEvent->getAllStatusList();
        $teamMembers = $this->TeamMember->getApprovedTeamMembersByList($teamId);

        $this->set(compact('calendarType', 'teamId','allNeedTypes','allStatusList','teamMembers'));

        if (isset($this->request->params['pass'][0])) {
            $page_action = $this->request->params['pass'][0];
            switch ($page_action) {
                case 'add':
                    if( $this->request->is('post') ) {
                        $this->__saveTask();
                    } else {
                        $this->__createHelp();
                    }
                    break;
                    
                case 'edit':
                    if( $this->request->is('post') ) {
                        $this->__saveTask();
                    } else {
                        $this->__editTask();
                    }
                    
                    break;
                default:
                    $this->__showDefault($showDate);
                    break;
            }
        } else {
            $this->__showDefault($showDate);
        }
    }

    /**
     * Add new event.
     */
    private function __createHelp() {
        
        $teamId = $this->_teamId;
        $eventTypes = $this->CareCalendarEvent->getTaskTypes();
        $this->JQValidator->addValidation('Event', $this->CareCalendarEvent->validate, 'careCalendarEventForm');
        $inputDefaults = array(
			'label' => false,
			'div' => false,
			'class' => 'form-control'
        );
        
        $teamMemberList = $this->TeamMember->getApprovedTeamMembersByList ( $teamId );
        $redirectUrl = '/myteam/'.$teamId.'/calendar';
        
        $this->set('startTime', 'false');
        $this->set('endTime', 'false');
        $this->set('title', 'Create New Task');
        $this->set('eventTypes', $eventTypes);
        $this->set('teamMemberList', $teamMemberList);
        $this->set('inputDefaults', $inputDefaults);
        $this->set('redirectUrl', $redirectUrl);
        
        $this->render('create_help');
    }

    private function __showDefault($showDate = null) {

        $timezone = $this->Auth->user('timezone');
        $time = new DateTime('now', new DateTimeZone($timezone));

        $timezoneOffset = $time->format('P');
        $timezoneOfindcator = $timezoneOffset;
        $hourMinuteArray = explode(":", $timezoneOffset);
        $hours = $hourMinuteArray[0] * 60;
        if ($hours < 0) {
            $hasNegSign = true;
            $hours = $hours * (-1);
            $timezoneOffsetInMinutes = (($hourMinuteArray[0]) * (-1) * 60) + $hourMinuteArray[1];
            $timezoneOffsetInMinutes = $timezoneOffsetInMinutes * (-1);
        } else {
            $timezoneOffsetInMinutes = (($hourMinuteArray[0]) * 60) + $hourMinuteArray[1];
        }
        $timezoneOffsetInMinutes = $timezoneOffsetInMinutes / (60);
        $timezoneOffset = $timezoneOffsetInMinutes;
        $this->set(compact('timezoneOffset', 'showDate', 'timezoneOfindcator'));
        $this->render('index');
    }

    /**
     * Function to save new care calendar event (Task)
     * @return boolean
     */
    private function __saveTask(){
       
        $teamId = $this->_teamId;
        $data = $this->request->data;
        $id= 0;        
        
        if ( $this->EventForm->saveCareCalendarEvent( $data, $teamId) ) {
            $id = $this->Event->id; 
            $careType = $data ['Event']['type'];
            if ( (isset($data ['Event']['additional_notes']) && 
                        ($data ['Event']['additional_notes'] != '')) ) {
                    
                $careType .= ' ('.$data ['Event']['additional_notes'].')';

            } 
            /*
             * New task
             */
            if( empty( $data['Event']['id'] ) ) {
                

                /*
                 * Team Notification about task creation
                 */
                $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                $notificationData = array(
                                'activity_type' => Notification::ACTIVITY_TEAM_CARE_REQUEST,
                                'team_id' => $teamId, // team id
                                'task_id' => $id,
                                'task_name' => $data ['Event']['name'],
                                'sender_id' => $this->_currentUserId, // request created user id
                                'care_type' => $careType
                        );
                $this->QueuedTask->createJob('TeamNotification', $notificationData);            

                /*
                 * Set success message 
                 */
                $this->Session->setFlash('The task has been saved successfully', 'success');
                
              // edit
            } else { 
                
                /*
                 * Team Notification about task updation
                 */
                $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                $notificationData = array(
                                'activity_type' => Notification::ACTIVITY_TEAM_CARE_REQUEST_CHANGE,
                                'team_id' => $teamId, // team id
                                'task_id' => $id,
                                'task_name' => $data ['Event']['name'],
                                'sender_id' => $this->_currentUserId, // request created user id
                                'care_type' => $careType
                        );
                $this->QueuedTask->createJob('TeamNotification', $notificationData);      
                
                /*
                 * Set success message 
                 */
                $this->Session->setFlash('The task has been updated successfully', 'success');
            }
        
        if( $data['Event']['save'] == 2 ) {
                $this->redirect('/myteam/'. $teamId. '/calendar/add');
            } else {
                $this->redirect('/myteam/'. $teamId. '/task/' . $id);
            }
            
        } else {
            $this->Session->setFlash('Something went wrong please create event again', 'error');
            $this->redirect('/myteam/'. $teamId. '/calendar/add');
        }
        
    }
        
    
    /*
     * Function to edit a task
     */
    private function __editTask(){   
        
        $teamId = $this->_teamId;
        $redirect = true;
       
        if (isset($this->request->params['pass'][1])) {
            $taskId = $this->request->params['pass'][1];
            
            $taskPermission = $this->CareCalendarEvent->getTaskPermission(
                $this->_currentUserId, $taskId, $this->_memberRole, $this->_memberStatus);
            
            /*
             *  If the current usre has no edit permission then redirect to task
             *  detail page
             */
            if ( !$this->CareCalendarEvent->hasEditPemission($taskPermission) ) {
                $this->__noPermissionForEditRedirect( $taskId );
            }
            
            $taskDetails = $this->CareCalendarEvent->getTaskDetails($taskId);
            $timezone = $this->Auth->user('timezone');
            
            /*
             * If task exists
             */
            if( !empty( $taskDetails)) {
    
                $redirect = false;
                
                /*
                 * Set the data to display
                 */
                $data ['Event']['id'] = $taskDetails['CareCalendarEvent']['id'];
                $data ['Event']['name'] = $taskDetails['Event']['name'];
                $data ['Event']['type'] = $taskDetails['CareCalendarEvent']['type'];
                $data ['Event']['description'] = $taskDetails['Event']['description'];
                $data ['Event']['additional_notes'] = $taskDetails['CareCalendarEvent']['additional_notes'];
                $data ['Event']['start_date'] = Date::MySqlDateTimeToJSDate($taskDetails['Event']['start_date'], $timezone);
                $data ['Event']['start_time'] = Date::MySqlDateTimeoJSTime($taskDetails['Event']['start_date'], $timezone);
                $data ['Event']['end_time'] = Date::MySqlDateTimeoJSTime($taskDetails['Event']['end_date'], $timezone);
                $data ['Event']['times_per_day'] = $taskDetails['CareCalendarEvent']['times_per_day'];
                $data ['Event']['assigned_to'] = $taskDetails['CareCalendarEvent']['assigned_to'];
                
                $this->request->data = $data;
                
                $eventTypes = $this->CareCalendarEvent->getTaskTypes();
                
                /*
                 * Jquery validator
                 */
                $this->JQValidator->addValidation('Event', $this->CareCalendarEvent->validate, 'careCalendarEventForm');
                $inputDefaults = array(
                                'label' => false,
                                'div' => false,
                                'class' => 'form-control'
                );

                $teamMemberList = $this->TeamMember->getApprovedTeamMembersByList ( $teamId );
                
                   
               
                /*
                 * Set the form default data
                 */
                if ( !is_null( $data ['Event']['additional_notes'] ) &&
                     !empty( $data ['Event']['additional_notes'] )) {
                    $this->set('additional_notes', 'true');
                }
                
                $redirectUrl = '/myteam/'.$teamId.'/task/'.$taskId;
                $this->set('is_editing', 'true');
                $this->set('startTime', 'false');
                $this->set('endTime', 'false');
                $this->set('title', 'Edit Task');
                $this->set('eventTypes', $eventTypes);
                $this->set('teamMemberList', $teamMemberList);
                $this->set('inputDefaults', $inputDefaults);
                $this->set('redirectUrl', $redirectUrl);
                $this->render('create_help');                
            }
            
            
        } 
        if ( $redirect ) { 
            $this->Session->setFlash('This task does not exist', 'error');
            $this->redirect('/myteam/' . $teamId. '/calendar');
        }
    }
    
    /**
     * Function to redict when a user has no permission over a task to edit
     * 
     * @param int $id task id
     */
    private function __noPermissionForEditRedirect( $id = 0){
        $this->Session->setFlash("You don't have permission to edit this task", 'error');
        $this->redirect('/myteam/' . $this->_teamId. '/task/' . $id);
    }
}

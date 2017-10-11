<?php

/**
 * TaskController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MyTeamAppController', 'MyTeam.Controller');

class TaskController extends MyTeamAppController {

    public $uses = array(
        'Event',
        'User',
        'CareCalendarEvent',
        'CareCalendarEvent',
        'TeamMember',
        'TaskUpdationForm'
    );
    protected $_taskId;
    protected $_taskDetails = NULL;
    protected $_current_timezone;

    public function beforeFilter() {
        //Calling parent flter
        parent::beforeFilter();
        
        $this->_current_timezone = $this->Auth->user('timezone');
        
        if (isset($this->params->params['pass'][0]) && ($this->params->params['pass'][0] > 0)) {
            $this->_taskId = $this->params->params['pass'][0];
            $event_exist = ($this->Event->exists($this->_taskId)) ? TRUE : FALSE;
        }
        
        if ($event_exist) {
            $this->_taskDetails = $this->CareCalendarEvent->getTaskDetails($this->_taskId);
        }
    }


    public function index() {
        $user_today = Date::getCurrentDate($this->_current_timezone);

        if (!empty($this->_taskDetails)) {

            if ($this->_taskDetails ['Event']['event_type'] != Event::EVENT_TYPE_CARE_CALENDAR_EVENT) {
                $this->Session->setFlash(__('This task does not exist'), 'error');
                $this->redirect('/myteam/' . $this->_teamId . '/calendar');
            } else {
                if ($this->request->is('post')) {
                    if ($this->__updateTask($this->_taskId)) {
                        $this->Session->setFlash('Task has been updated successfully ', 'success');
                        $this->redirect('/myteam/' . $this->_teamId . '/task/' . $this->_taskId);
                    }
                } else if ($this->request->is('ajax')) {
                    if (isset($this->request->params['pass'][1])) {
                        $action = $this->request->params['pass'][1];
                        switch ($action) {
                            case 'approveTask':
                                $this->__approveTask();
                                break;
                            case 'declineTask':
                                $this->__declineTask();
                                break;
                        }
                    }
                } else {                    
                    $this->__showDefault();
                }
            }
        } else {
            $this->Session->setFlash(__('This task does not exist'), 'error');
            $this->redirect('/myteam/' . $this->_teamId . '/calendar');
        }
    }
    
    /**
     * Function to show the detail page for task
     */
    private function __showDefault(){
        
        $this->JQValidator->addValidation('TaskUpdation', $this->TaskUpdationForm->validate, 'careCalendarTaskUpdationForm');
                    
        $userId = $this->_currentUserId;
        $timezone = $this->_current_timezone;
        $memberRole = $this->_memberRole;
        $memberStatus = $this->_memberStatus;
        $taskPermission = $this->CareCalendarEvent->getTaskPermission(
                $userId, $this->_taskId, $memberRole, $memberStatus);
        $teamMemberList = $this->TeamMember->getApprovedTeamMembersByList($this->_teamId);

        $isAssignee = ($this->_taskDetails['CareCalendarEvent']['assigned_to'] == $userId) ? TRUE : FALSE;
        $isOpen = ($this->_taskDetails['CareCalendarEvent']['status'] == CareCalendarEvent::STATUS_WAITING_FOR_APPROVAL) ? TRUE : FALSE;

        $has_updatePermission = $this->CareCalendarEvent->hasUpdatePermission($taskPermission);
        $has_selfAssignPermission = $this->CareCalendarEvent->hasSelfAssignPermission($taskPermission);
        $has_editPermission = $this->CareCalendarEvent->hasEditPemission($taskPermission);
        $historiesDecoded = json_decode($this->_taskDetails['CareCalendarEvent']['history'], TRUE);
        $histories = $this->__addUserNames( $historiesDecoded );
        if( $has_editPermission ) {
            $editUrl = '/myteam/' . $this->_teamId.'/calendar/edit/' . $this->_taskId;
        }
        if (!$has_updatePermission && $has_selfAssignPermission) {
            $assigneeOptions = array($userId => $teamMemberList[$userId] . ' (You)');
        } else {
            $assigneeOptions = $teamMemberList;
            $assigneeOptions [$userId] = $teamMemberList[$userId] . ' (You)';
        }
        $task_details = $this->_taskDetails;
        $task_details ['Event']['created_by'] = $this->User->getUsername( $task_details ['Event']['created_by']);
        $this->set(compact('task_details', 'timezone', 'teamMemberList', 
                'has_updatePermission', 'has_selfAssignPermission','has_editPermission', 
                'histories', 'isOpen', 'isAssignee', 'assigneeOptions', 'editUrl'));
    }
    
     
    /**
     * Function to update task 
     * @param int $id : care calendar event id
     * @return boolean
     */
    private function __updateTask($task_id) {

        $data = $this->request->data['TaskUpdation'];
        $note = $data['note'];
        $saveData['assigned_to'] = $data['assigned_to'];
        $taskDetails = $this->CareCalendarEvent->getTaskDetails($task_id);
        $id = $taskDetails['CareCalendarEvent']['id'];
        $eventId = $taskDetails['CareCalendarEvent']['event_id'];
        $previousAssignee = $taskDetails['CareCalendarEvent']['assigned_to'];
        $previousHistoryJson = $taskDetails['CareCalendarEvent']['history'];
        $userId = $this->_currentUserId;
        $careType = $taskDetails['CareCalendarEvent']['type'];
        $teamId = $this->_teamId;
        $taskName = $taskDetails['Event']['name'];
        
        if ( (isset( $this->_taskDetails['CareCalendarEvent']['additional_notes']) && 
                   ( $this->_taskDetails['CareCalendarEvent']['additional_notes'] != '')) ) {
                    
                $careType .= ' ('.$this->_taskDetails['CareCalendarEvent']['additional_notes'].')';

        }

        /*
         * If it is a completion
         */
        if (isset($data['completed']) && $data['completed'] == 1) {
            
            /*
             * Status changed to complete and save history
             */
            $saveData['status'] = CareCalendarEvent::STATUS_COMPLETED;
            $saveData['history'] = $this->CareCalendarEvent->createHistory(
                    CareCalendarEvent::ACTION_COMPLETION, 
                    $previousHistoryJson, $note, $userId, 0, 0);
        } else {
            
            /*
             * Check the assignee is selected
             */
            if (isset($saveData['assigned_to'])) {
                $recevierId = $saveData['assigned_to'];
            } else {
                $recevierId = 0;
            }

            /*
             * If assignee is not changed then save the note to history
             */
            if ($recevierId == $previousAssignee) {

                $saveData['history'] = $this->CareCalendarEvent->createHistory(
                        CareCalendarEvent::ACTION_UPDATION_ONLY, 
                        $previousHistoryJson, $note, $userId);
            } else {
                /*
                 * Changing the assignee
                 */
                if ($recevierId == $userId) {
                    $saveData['status'] = CareCalendarEvent::STATUS_ASSIGNED;
                } else {
                    $saveData['status'] = CareCalendarEvent::STATUS_WAITING_FOR_APPROVAL;
                }

                $saveData['history'] = $this->CareCalendarEvent->createHistory(
                        CareCalendarEvent::ACTION_ASSIGNING, $previousHistoryJson, 
                        $note, $userId, $previousAssignee, $recevierId);
            }
        }
        
        /*
         * Team notification
         */
        $this->__notifyTaskUpdation($teamId, $eventId, $taskName, $careType, 
                            Notification::ACTIVITY_TEAM_CARE_REQUEST_CHANGE);
        $this->CareCalendarEvent->id = $id;
        return $this->CareCalendarEvent->save($saveData);
    }

    /**
     * Function to approve a task
     * @param int $task_id
     * @return boolean
     */
    private function __approveTask() {
        $this->autoRender = FALSE;
        $assignee = $this->_taskDetails['CareCalendarEvent']['assigned_to'];
        $id = $this->_taskDetails['CareCalendarEvent']['id'];
        $eventId = $this->_taskDetails['CareCalendarEvent']['event_id'];
        $previousHistoryJson = $this->_taskDetails['CareCalendarEvent']['history'];
        $teamId = $this->_teamId;
        $careType = $this->_taskDetails['CareCalendarEvent']['type'];
        $taskName = $this->_taskDetails['Event']['name'];
        
        if ( (isset( $this->_taskDetails['CareCalendarEvent']['additional_notes']) && 
                   ( $this->_taskDetails['CareCalendarEvent']['additional_notes'] != '')) ) {
                    
                $careType .= ' ('.$this->_taskDetails['CareCalendarEvent']['additional_notes'].')';

        }
        
        /*
         * Return value
         */
        $is_accepted = FALSE;

        /*
         * If assigee is the loged in user
         */
        if ($this->_currentUserId == $assignee) {
            
            /*
             * Change status to accept
             */
            $saveData['status'] = CareCalendarEvent::STATUS_ASSIGNED;
            $saveData['history'] = $this->CareCalendarEvent->createHistory(
                    CareCalendarEvent::ACTION_ACCEPT, $previousHistoryJson, null, $this->_currentUserId);

            $this->CareCalendarEvent->id = $id;
            $is_accepted = $this->CareCalendarEvent->save($saveData);
            /*
             * Team notificaiton
             */
            $this->__notifyTaskUpdation($teamId, $eventId, $taskName, $careType, Notification::ACTIVITY_TEAM_CARE_REQUEST_CHANGE);
        }

        if ($is_accepted) {
            $this->Session->setFlash('Successfully updated', 'success');
        } else {
            $this->Session->setFlash('Something went wrong', 'error');
        }
        exit;
    }

    /**
     * Function to decline a task
     * @param int $task_id
     * @return boolean
     */
    private function __declineTask() {
        $this->autoRender = FALSE;
        $assignee = $this->_taskDetails['CareCalendarEvent']['assigned_to'];
        $id = $this->_taskDetails['CareCalendarEvent']['id'];
        $eventId = $this->_taskDetails['CareCalendarEvent']['event_id'];
        $previousHistoryJson = $this->_taskDetails['CareCalendarEvent']['history'];
        $teamId = $this->_teamId;
        $careType = $this->_taskDetails['CareCalendarEvent']['type'];
        $taskName = $this->_taskDetails['Event']['name'];
        
        if ( (isset( $this->_taskDetails['CareCalendarEvent']['additional_notes']) && 
                   ( $this->_taskDetails['CareCalendarEvent']['additional_notes'] != '')) ) {
                    
                $careType .= ' ('.$this->_taskDetails['CareCalendarEvent']['additional_notes'].')';

        }
        /*
         * Return value
         */
        $is_accepted = false;

        /*
         * If assigee is the loged in user
         */
        if ($this->_currentUserId == $assignee) {

            /*
             * Change status to accept
             */
            $saveData['assigned_to'] = 0;
            $saveData['status'] = CareCalendarEvent::STATUS_OPEN;
            $saveData['history'] = $this->CareCalendarEvent->createHistory(
                    CareCalendarEvent::ACTION_DECLINE, $previousHistoryJson, null, $this->_currentUserId);
            $this->CareCalendarEvent->id = $id;
            $is_accepted = $this->CareCalendarEvent->save($saveData);
            $this->__notifyTaskUpdation($teamId, $eventId, $taskName, $careType, Notification::ACTIVITY_TEAM_CARE_REQUEST_CHANGE);
        }

        if ($is_accepted) {
            $this->Session->setFlash('Successfully updated', 'success');
        } else {
            $this->Session->setFlash('Something went wrong', 'error');
        }
        exit;
    }

    
    private function __notifyTaskUpdation($teamId, $taskId, $taskName, $careType, $activity){
        $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
        $data = array(
                        'activity_type' => $activity,
                        'team_id' => $teamId, // team id
                        'task_id' => $taskId,
                        'task_name' => $taskName,
                        'sender_id' => $this->_currentUserId, // request created user id
                        'care_type' => $careType
                );
        $this->QueuedTask->createJob('TeamNotification', $data);
    }
    
    private function __addUserNames( $historiesDecoded ) {
        $histories = array();
        
        foreach ($historiesDecoded as $key => $history) {
            
            if ( isset($history['action_by'])) {
                $history['action_by'] = $this->User->getUsername( $history['action_by'] );
            }
            
            if ( isset( $history['assigned_to'] ) ) {
                 $history['assigned_to'] = $this->User->getUsername( $history['assigned_to'] );
            }
            
            if ( isset( $history['assigned_from'] ) ) {
                 $history['assigned_from'] = $this->User->getUsername( $history['assigned_from'] );
            }
            
            $histories[$key] = $history;
        }
            
        return $histories;
    }
}
?>

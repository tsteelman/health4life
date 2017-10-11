<?php

App::uses('AppModel', 'Model');
App::uses('Date', 'Utility');
/*
 * Model for care calendar events
 */


class CareCalendarEvent extends AppModel {

    /**
     * Status type
     */
    const STATUS_OPEN = 0;
    const STATUS_WAITING_FOR_APPROVAL = 1;
    const STATUS_ASSIGNED = 2;
    const STATUS_COMPLETED = 3;

	/**
     * Event actions
     */
    const ACTION_CREATION = 'creation';
    const ACTION_COMPLETION = 'completion';
    const ACTION_ACCEPT = 'accept';
    const ACTION_DECLINE = 'decline';
    const ACTION_ASSIGNING = 'assigning';
    const ACTION_UPDATION_ONLY = 'updation';
    const ACTION_EDITING = 'editing';
    const ACTION_ASSIGNEE_LEFT = 'assignee_left';
    
    /**
     * Task permissions
     * 
     */    
    const TASK_PERMISSION_NONE = 0; // No permission
    const TASK_PERMISSION_SELF_ASSIGN = 1; // slef assign permission
    const TASK_PERMISSION_ASSIGNEE =2 ; // reassign , status cange permission
    const TASK_PERMISSION_FULL = 3; // edit, delete, reassign, status change
    

    private $_taskTypes = array(
        'errands' => 'Errands',
        'housework' => 'Housework',
        'childcare' => 'Childcare',
        'need transportation' => 'Need transportation',
        'visit by caregiver' => 'Visit by caregiver',
        'visit by family member' => 'Visit by family member',
        'visit by friend' => 'Visit by friend',
        'visit by other' => 'Visit by other',
        'online chat' => 'Online chat',
        'phone call' => 'Phone call',
        'yard-work' => 'Yard-work',
        'breakfast' => 'Breakfast',
        'lunch' => 'Lunch',
        'dinner' => 'Dinner',
        'give medications' => 'Give medications',
        'bathing assistance' => 'Bathing assistance',
        'get mail' => 'Get mail',
        'take out trash' => 'Take out trash',
        'wash clothing' => 'Wash clothing',
        'walk dog' => 'Walk dog',
        'feed dog' => 'Feed dog',
        'feed cat' => 'Feed cat',
        'other' => 'Other'
    );

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a title for the event.'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 50),
                'message' => 'Cannot be more than 50 characters long.'
            )
        ),
        'type' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select the type of need.'
            )
        ),
        'additional_notes' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter the detail.'
            )
        ),
        'description' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 300),
                'message' => 'Cannot be more than 300 characters long.',
                'allowEmpty' => true
            )
        ),
        'start_date' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a start date for the event'
            )
        ),
        'start_time' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a start time for the event'
            )
        ),
        'end_time' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter an end time for the event'
            )
        ),
        'start_date_time' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a start date for the event'
            )            
        ),
        'times_per_day' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a valid number'
            ),
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'Please enter a valid number'
            ),
            'min' => array(
                'rule' => array('min',  1),
                'message' => 'Please enter a number grater than 0'
            ),
            'max' => array(
                'rule' => array('max', 10),
                'message' => 'Please enter a number less than 10'
            )
        )
    );
    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        )
    );
   
    private $allStatusList = array(
        0 => 'Open',
        1 => 'Waiting for approval',
        2 => 'Assigned',
        3 => 'Completed',
    );

    /**
     * Function to generate history data
     * 
     * @param string $action
     * @param json $jsonData
     * @param int $actorId : action done by     
     * @param int $receiverId : next assignee
     * @param int $giverId : pervious assignee
     * @return json
     */
    public function createHistory($action, $jsonData = null, $note = null,
                                    $actorId=0, $giverId = 0, $receiverId = 0) {
       
        $history ['action'] = $action;
        $history ['action_by'] = $actorId;
        
        if ( $giverId != 0) {
            $history ['assigned_from'] = $giverId;
        }
        
        if ( $receiverId != 0) {
            $history ['assigned_to'] = $receiverId;
        }
        
        if ( !is_null( $note ) ) {
            $history ['note']  = $note;
        }
        if ( is_null( $jsonData ) ) {
            
            $historyData = array(time() => $history);
        } else {
            
            $historyData = json_decode($jsonData, true);
            $historyData[ time() ] = $history;
            
        }
       
        /*
         * Json encode data
         */
        $newJsonData = json_encode($historyData);

        return $newJsonData;
    }
      
    /**
     * Function to get task history field
     * @param int $id care calendar event id
     */
    public function getTaskHistoryJson( $id = 0 ) {
        $task = $this->find('first', array(
            'conditions' => array('CareCalendarEvent.id' => $id),
            'fields' => array('CareCalendarEvent.history')
        ));
        
        if( !empty( $task ) && !is_null( $task )) {
            return $task['CareCalendarEvent']['history'];
        }
        return NULL;
    }

    /**
     * Function to get care calendar eventy types
     * @return array
     */
    public function getTaskTypes(){
        return $this->_taskTypes;      
    }
    
    public function getTaskDetails($eventId) {
        
        $details = $this->find('first', array(
            'conditions' => array(
                'event_id' => $eventId
            )
        ));
        
        return $details;
    }

    public function getAllStatusList() {
        return $this->allStatusList;
    }  
    
    /**
     * Function to check permission of a user over a task
     * 
     * @param int $userId user id of logged in user
     * @param int $taskId task id
     * @param int $memberRole member role in team
     * @param int $memberStatus Status of the member approved/not approved
     * @return int permission type
     */
    public function getTaskPermission( $userId = 0, $taskId = 0 , 
                                         $memberRole = 0, $memberStatus = 0) {

            $taskDetails =  $this->getTaskDetails ( $taskId );                        
            
            $task ['created_by'] = $taskDetails ['Event']['created_by'];
            $task ['assignee'] = $taskDetails ['CareCalendarEvent']['assigned_to'];
            $task ['status'] = $taskDetails ['CareCalendarEvent']['status'];
            
            /*
             * If approved user and non-closed task
             */
            if( $memberStatus == TeamMember::STATUS_APPROVED &&
                $task ['status'] != CareCalendarEvent::STATUS_COMPLETED) {
                
                /*
                 * If user is a patint or organizer or patient organizer or 
                 * task crator
                 */
                if ( $memberRole == TeamMember::TEAM_ROLE_PATIENT ||
                     $memberRole == TeamMember::TEAM_ROLE_ORGANIZER ||
                     $memberRole == TeamMember::TEAM_ROLE_PATIENT_ORGANIZER ||
                     $task['created_by'] == $userId ) {
                    
                    /*
                     * Return full permission
                     */
                    return CareCalendarEvent::TASK_PERMISSION_FULL;
                    
                } else if ( $memberRole == TeamMember::TEAM_ROLE_MEMBER) {
                    
                    /*
                     * If user is the assignee
                     */

                    if( $task ['assignee'] == $userId) {
                        
                        /*
                         * Assignee permission
                         */
                        return CareCalendarEvent::TASK_PERMISSION_ASSIGNEE;
                        
                    } else if(
                            $task ['status'] == CareCalendarEvent::STATUS_OPEN ||
                            $task ['status'] == CareCalendarEvent::STATUS_WAITING_FOR_APPROVAL) {
                        
                        /*
                         * Self assign permission
                         */
                        return CareCalendarEvent::TASK_PERMISSION_SELF_ASSIGN;
                    }
                }
            } 
            
            /*
             * Return no permission
             */
            return CareCalendarEvent::TASK_PERMISSION_NONE;
	}

    /**
     * Function to get the events happening today that are assigned to the users 
     * in the specified timezones
     * 
     * @param array $timezones
     * @return array
     */
    public function getTodayEventsAssignedToUsersInTimezones($timezones) {
		App::uses('Date', 'Utility');
		$timezone = $timezones[0];
		$today = Date::getCurrentDate($timezone);
		$offset = Date::getTimeZoneOffsetText($timezone);
		$belongsTo = array(
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'assigned_to',
			)
		);
		$this->bindModel(array('belongsTo' => $belongsTo), false);
		$query = array(
			'fields' => array(
				"{$this->alias}.*",
				'Event.name',
				'Event.description',
				'Event.start_date',
				'Event.created_by',
				'Event.section_id AS team_id',
				'User.username',
				'User.email',
				'User.type',
				'User.timezone'
			),
			'conditions' => array(
				"{$this->alias}.status" => self::STATUS_ASSIGNED,
				"CONVERT_TZ(Event.start_date, '+00:00', '{$offset}') LIKE" => "$today%",
				'User.status' => 1,
				'User.is_admin' => 0,
				'User.timezone' => $timezones
			)
		);
		return $this->find('all', $query);
	}
    
    /**
     * Function to get event_id from task id
     * 
     * @param int $taskId : id in CareCalendarEvent table
     * @return int
     */
    public function getEventIdfromTaskId( $taskId ){
        $details = $this->find('first', array(
            'conditions' => array(
                'CareCalendarEvent.id' => $taskId
            ),
            'fields' => array('event_id')
        ));
        
        return $details['CareCalendarEvent']['event_id'];
    }
    
    /**
     * Functiont to check the update permission for user
     * @param int $taskPermission permission type
     * @return boolean 
     */
    public function hasUpdatePermission($taskPermission) {

        if ($taskPermission == CareCalendarEvent::TASK_PERMISSION_FULL ||
                $taskPermission == CareCalendarEvent::TASK_PERMISSION_ASSIGNEE) {

            return true;
        }

        return false;
    }

    /**
     * Functiont to check the assign permission for user
     * @param int $taskPermission permission type
     * @return boolean 
     */
    public function hasReAssignPermission($taskPermission) {

        if ($taskPermission == CareCalendarEvent::TASK_PERMISSION_FULL ||
                $taskPermission == CareCalendarEvent::TASK_PERMISSION_ASSIGNEE) {

            return true;
        }

        return false;
    }

    /**
     * Functiont to check edit permission for user
     * @param int $taskPermission permission type
     * @return boolean 
     */
    public function hasEditPemission($taskPermission) {

        if ($taskPermission == CareCalendarEvent::TASK_PERMISSION_FULL ) {

            return true;
        }

        return false;
    }
    
     /**
     * Functiont to check self assign permission for user
     * @param int $taskPermission permission type
     * @return boolean 
     */
    public function hasSelfAssignPermission($taskPermission) {

        if ($taskPermission == CareCalendarEvent::TASK_PERMISSION_FULL ||
                $taskPermission == CareCalendarEvent::TASK_PERMISSION_SELF_ASSIGN) {

            return true;
        }

        return false;
    }

	/**
	 * Function to get the tasks for the day in a team
	 * 
	 * @param int $teamId
	 * @param string $timezone
	 * @return array 
	 */
	public function getTeamTasksForToday($teamId, $timezone) {
		App::uses('Date', 'Utility');
		App::uses('Event', 'Model');

		$today = Date::getCurrentDate($timezone);
		$offset = Date::getTimeZoneOffsetText($timezone);

		$belongsTo = array(
			'Assignee' => array(
				'className' => 'User',
				'foreignKey' => 'assigned_to'
			)
		);
		$this->bindModel(array('belongsTo' => $belongsTo), false);
		$query = array(
			'conditions' => array(
				'Event.section_id' => $teamId,
				'Event.section' => Event::SECTION_EVENT_IN_TEAM,
				'Event.event_type' => Event::EVENT_TYPE_CARE_CALENDAR_EVENT,
				"CONVERT_TZ(Event.start_date, '+00:00', '{$offset}') LIKE" => "$today%",
			),
			'fields' => array(
				'Event.name',
				'Event.description',
				'Event.start_date',
				'Event.created_by',
				'CareCalendarEvent.*',
				'Assignee.username'
			)
		);

		return $this->find('all', $query);
	}

	/**
	 * Function to get the text for a status
	 * 
	 * @param int $status
	 * @return string 
	 */
	public function getTaskStatusText($status) {
		$statusText = $this->allStatusList[$status];
		return $statusText;
	}
        
        /**
	 * Function to get the tasks for the day in a team
	 * 
	 * @param int $teamId
	 * @return array 
	 */
	public function getTeamTasks($teamId, $limit = 0, $offset = 0) {
		App::uses('Date', 'Utility');
		App::uses('Event', 'Model');

		$today = Date::getCurrentDate();

		$belongsTo = array(
			'Assignee' => array(
				'className' => 'User',
				'foreignKey' => 'assigned_to'
			)
		);
		$this->bindModel(array('belongsTo' => $belongsTo), false);
		$query = array(
			'conditions' => array(
				'Event.section_id' => $teamId						
			),
			'fields' => array(
				'Event.id',
				'Event.name',
				'Event.description',
				'Event.start_date',
				'Event.created_by',
				'CareCalendarEvent.*',   
				'Assignee.username'
			),
                        'order' => array(
                                'Event.start_date ASC'
                        ),
                        'limit' => $limit,
                        'offset' => $offset
		);

		return $this->find('all', $query);
	}

     public function getTaskDetailsFromTaskId($taskId) {
        
        $details = $this->find('first', array(
            'conditions' => array(
                'CareCalendarEvent.id' => $taskId
            )
        ));
        
        return $details;
    }
    
    public function resetAllTasksOfUser( $userId ) {
        
        $result = true;
        
        $tasks = $this->find('all', array(
                'conditions'  => array(
                    'CareCalendarEvent.assigned_to' => $userId,
                    'CareCalendarEvent.status !=' => CareCalendarEvent::STATUS_COMPLETED,
                ),
                'fields' => array(
                    'CareCalendarEvent.id',
                    'CareCalendarEvent.history'
                )
        ));
//        debug($tasks);
        foreach ( $tasks as $task ) {
                $this->id = $task['CareCalendarEvent']['id'];
                $data ['CareCalendarEvent']['assigned_to'] = 0;
                $data ['CareCalendarEvent']['status'] = CareCalendarEvent::STATUS_OPEN;
                $data ['CareCalendarEvent']['history'] = $this->createHistory(
                        CareCalendarEvent::ACTION_ASSIGNEE_LEFT,$task['CareCalendarEvent']['history'],
                        null, $userId);
                if ( !$this->save($data) ) {
                        $result = false;

                }
        }
        
        return $result;
    }
    
        /**
	 * Function to check if the future tasks are more than five
	 * 
	 * @param int $teamId
	 * @return boolean 
	 */
	public function isLastFiveTeamTasksMore( $teamId ) {
		App::uses('Date', 'Utility');
		App::uses('Event', 'Model');

		$today = Date::getCurrentDate();

		$query = array(
			'conditions' => array(
				'Event.section_id' => $teamId,
				'Event.start_date >= ' => $today		
			),
			'fields' => array(
				'Event.id',
				'Event.name',
				'Event.description',
				'Event.start_date',
				'Event.created_by',
				'CareCalendarEvent.*',
				'Assignee.username'
			),
                        'order' => array(
                                'Event.start_date ASC'
                        )                        
		);

		$count = $this->find('count', $query);
                
                if( $count > 5) {
                    return true;
                }
                
                return false;
	}
        
        public function getCurrentTaskOffset($teamId, $timezone = NULL){
                		
		$today = Date::getCurrentDate($timezone);

		$query = array(
			'conditions' => array(
				'Event.section_id' => $teamId,
                                'Event.start_date < ' => $today
			)
		);
                
		return $this->find('count', $query);
        }
        
        public function getTaskCount( $teamId ) {
            
		$query = array(
			'conditions' => array(
				'Event.section_id' => $teamId,                               
			)
		);
		return $this->find('count', $query);
        }
        
        public function getNextOffset($count, $currentOffset, $limit){
        
            /*
             * If next page is valid
             */
            if ( $count > ( $currentOffset + $limit ) ) {
                return $currentOffset + $limit ;
            } else {
                return 0;
            }
        }
        
        public function getPreviousOffset($count, $currentOffset, $limit){
        
            /*
             * If next page is valid
             */
            if($currentOffset == 0) {
                return -1;
            }else if ( ( $currentOffset - $limit ) > 0) {
                return $currentOffset - $limit ;
            } else {
                return 0;
            }
        }
    
}
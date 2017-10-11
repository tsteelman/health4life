<?php

App::uses('AppModel', 'Model');

/**
 * Event Model
 *
 * @property Community $Community
 * @property EventDisease $EventDisease
 * @property EventMember $EventMember
 */
class Event extends AppModel {

    /**
     * Event types
     */
    const EVENT_TYPE_PUBLIC = 1;
    const EVENT_TYPE_PRIVATE = 2;
    const EVENT_TYPE_SITE = 3;
    const EVENT_TYPE_CALENDAR_REMINDER = 4;
    const EVENT_TYPE_CARE_CALENDAR_EVENT = 5;
    const EVENT_TYPE_APPOINMENT = 6;
    const EVENT_TYPE_MEDICATION = 7;

    /**
     * Ordinary event, virtual event 
     */
    const ORDINARY_EVENT = 0;
    const VIRTUAL_EVENT = 1;

    /**
     * Repeat modes
     */
    const REPEAT_MODE_DAILY = 1;
    const REPEAT_MODE_WEEKLY = 2;
    const REPEAT_MODE_MONTHLY = 3;
    const REPEAT_MODE_YEARLY = 4;
//    const REPEAT_MODE_WEEKDAY = 5;
//    const REPEAT_MODE_MON_WED_FRI = 6;
//    const REPEAT_MODE_TUE_THU = 7;

    /**
     * Repeats on
     */
    const REPEATS_ON_MON = 'MON';
    const REPEATS_ON_TUE = 'TUE';
    const REPEATS_ON_WED = 'WED';
    const REPEATS_ON_THU = 'THU';
    const REPEATS_ON_FRI = 'FRI';
    const REPEATS_ON_SAT = 'SAT';
    const REPEATS_ON_SUN = 'SUN';

    /**
     * All day event or not
     */
    const ALL_DAY_EVENT_TRUE = 1;
    const ALL_DAY_EVENT_FALSE = 0;

    /**
     * Repeat by
     */
    const REPEATS_BY_DAY_OF_MONTH = 1;
    const REPEATS_BY_DAY_OF_WEEK = 2;

    /**
     * Repeat end types
     */
    const REPEAT_END_NEVER = 1;
    const REPEAT_END_AFTER = 2;
    const REPEAT_END_DATE = 3;

    /**
     * Event listing types
     */
    const MY_EVENTS = 1;
    const PENDING_EVENTS = 2;
    const UPCOMING_EVENTS = 3;
    const INTERESTING_EVENTS = 4;
    const PAST_EVENTS = 5;
    const UPCOMING_COMMUNITY_EVENTS = 6;
    const PAST_COMMUNITY_EVENTS = 7;
    const UPCOMING_USER_EVENTS = 8;
    const PAST_USER_EVENTS = 9;

    /**
     * Event listing classification in calendars
     */
    const ORDINARY_EVENTS_ONLY = 1;
    const CALENDAR_EVENTS_ONLY = 2;
    const CALENDAR_AND_ORDINARY_EVENTS = 3;
    const CARE_CALENDAR_EVENTS_ONLY = 4;
    const ALL_TYPE_EVENTS = 5;

    /**
     * Event section ie, created from
     */
    const SECTION_NORMAl_EVENT = 0;
    const SECTION_EVENT_IN_COMMUNITY = 1;
    const SECTION_EVENT_IN_TEAM = 2;
    
    /**
     * Cover slideshow enabled/disabled status
     */
    const COVER_SLIDESHOW_ENABLED = 1;
    const COVER_SLIDESHOW_DISABLED = 0;

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';
    public $components = array('Paginator');

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a name for the event'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 50),
                'message' => 'Cannot be more than 50 characters long.'
            ),
            'remote' => array(
                'rule' => array('remote', '/api/checkExistingEventName', 'name'),
                'message' => 'This event name already exists.'
            )
        ),
        'description' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'message' => 'Cannot be more than 100 characters long.',
                'allowEmpty' => true
            )
        ),
        'event_type' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select an event type.'
            )
        ),
        'repeat' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select event type'
            )
        ),
        'start_date' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a start date for the event'
            )
        ),
        'start_date_time' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a start date for the event'
            )
            ),
        'start_time' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a start time for the event'
            ),
            'regex' => array(
				'rule' => '/(([0-9]|[1][012])\:[0-5][0|5]\s(a|p)m)$/i',
                'message' => 'Please enter a valid time'
            )
        ),
        'start_date_timeonly' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a start time for the event'
            ),
            'regex' => array(
                'rule' => '/(([0-9]|[1][012])\:[03]0\s(a|p)m)$/i',
                'message' => 'Please enter a valid time'
            )
        ),
        'end_time' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter an end time for the event'
            ),
            'regex' => array(
                'rule' => '/(([0-9]|[1][012])\:[0-5][0|5]\s(a|p)m)$/i',
                'message' => 'Please enter a valid time'
            )
        ),
        'start_date_time' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter a start date for the event'
            )
        ),
        'end_date' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty', 'dependentField' => 'repeat_end_type', 'dependentValue' => self::REPEAT_END_DATE, 'isRadio' => true),
                'message' => 'Please enter end date for the event'
            )
        ),
//        'repeat_occurrences' => array(
//            'notEmpty' => array(
//                'rule' => array('notEmpty', 'dependentField' => 'repeat_end_type', 'dependentValue' => self::REPEAT_END_AFTER, 'isRadio' => true),
//                'message' => 'Please enter number of occurrences for the event'
//            ),
//            'number' => array(
//                'rule' => array('naturalNumber'),
//                'message' => 'Please enter a valid number',
//                'allowEmpty' => true
//            )
//        ),
        'virtual_event' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select an event location'
            )
        ),
        'online_event_details' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter online event details or post a valid video URL.'
            )
        ),
        'location' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter the address for the event'
            )
        ),
        'country' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a country'
            )
        ),
        'state' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a state/province'
            )
        ),
        'city' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select a city'
            )
        ),
        'zip' => array(
            'maxLength' => array(
                'rule' => array('maxLength', 15),
                'message' => 'Zip cannot exceed 15 characters.',
				'allowEmpty' => true
            )
        ),
        'repeat_end_type' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please specify the end for the event'
            )
        ),
        'timezone' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select your timezone'
            )
        )
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Community' => array(
            'className' => 'Community',
            'foreignKey' => 'community_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'EventDisease' => array(
            'className' => 'EventDisease',
            'foreignKey' => 'event_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'EventMember' => array(
            'className' => 'EventMember',
            'foreignKey' => 'event_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    public $hasOne = array(
        'CareCalendarEvent' => array(
            'className' => 'CareCalendarEvent',
            'foreignKey' => 'event_id',
            'dependent' => true
        )
    );

    //Function to get event by eventId
    function getEvent($eventId) {
        $event = $this->find('first', array(
            'conditions' => array('Event.id' => $eventId)
        ));

        return $event['Event'];
    }

    function getAllEvents() {

        $events = $this->find('all', array(
            'conditions' => array('Event.event_type' => 1)
        ));
        $array = array();

        foreach ($events as $event) {

            $array[] = $event['Event'];
        }
        $events = $array;

        return $events;
    }

    /*
     * Function to get events not in the given list of events
     * 
     * @param $eventList list of events array
     * 
     * @return array
     */

    function getEventsOther($eventList) {

        $events = $this->find('all', array(
            'conditions' => array(
                'NOT' => array('Event.id' => $eventList)
            )
                )
        );

        $array = array();

        foreach ($events as $event) {

            $array[] = $event['Event'];
        }

        $events = $array;

        return $events;
    }

    /**
     * Function to get event types
     * 
     * @return array
     */
    public static function getEventTypes() {
        $eventTypes = array(
            self::EVENT_TYPE_PUBLIC => __('Public'),
            self::EVENT_TYPE_PRIVATE => __('Private'),
            self::EVENT_TYPE_SITE => __('Site wide')
        );
        return $eventTypes;
    }

    /**
     * Function to get event locations
     * 
     * @return array
     */
    public static function getEventLocations() {
        $eventLocations = array(
            self::ORDINARY_EVENT => __('On-site'),
            self::VIRTUAL_EVENT => __('Online')
        );
        return $eventLocations;
    }

    /**
     * Function to get the repeat modes for events
     * 
     * @return array
     */
    public static function getRepeatModes() {
        $repeatModes = array(
            self::REPEAT_MODE_DAILY => __('Daily'),
            self::REPEAT_MODE_WEEKLY => __('Weekly'),
            self::REPEAT_MODE_MONTHLY => __('Monthly'),
            self::REPEAT_MODE_YEARLY => __('Annually')
//            self::REPEAT_MODE_WEEKDAY => __('Every weekday (Monday to Friday)'),
//            self::REPEAT_MODE_MON_WED_FRI => __('Every Monday, Wednesday and Friday'),
//            self::REPEAT_MODE_TUE_THU => __('Every Tuesday, And Thursday'),
        );
        return $repeatModes;
    }

    /**
     * Function to get event repeat interval list
     * 
     * @return array
     */
    public static function getRepeatIntervalList() {
        $repeatIntervalList = array();
        for ($i = 1; $i <= 30; $i++) {
            $repeatIntervalList[$i] = $i;
        }

        return $repeatIntervalList;
    }
    public static function getRepeatIntervalText() {
        $repeatIntervalText = array(
            self::REPEAT_MODE_DAILY => __('Days'),
            self::REPEAT_MODE_WEEKLY => __('Weeks'),
            self::REPEAT_MODE_MONTHLY => __('Months'),
            self::REPEAT_MODE_YEARLY => __('Years')
        );
        return $repeatIntervalText;
    }
/*
 * For dashboard calendar.
 */
    public function getAllUserRelatedEvents($userId, $startDate = NULL, $endtDate = NULL) {

        $eventsAttending = $this->EventMember->find('list', array(
            'conditions' => array(
                'EventMember.user_id' => $userId,
                'EventMember.status !=' => EventMember::STATUS_NOT_ATTENDING
            ),
            'fields' => 'EventMember.event_id'
        ));
        $eventsDetails = $this->find('all', array(
            'joins' => array(
                array(
                    'table' => 'care_calendar_events',
                    'alias' => 'CareCalendar',
                    'type' => 'LEFT',
                    'conditions' => 'CareCalendar.event_id  = Event.id'
                )
            ),
            'conditions' => array(
                'OR' => array(
                    'AND' => array(
                        'Event.id' => $eventsAttending,
//                        'Event.start_date >=' => $startDate,
                        array(
                            'OR'=>array(
                                array(
                                    'Event.end_date >=' => $startDate
                                ),
                                array(
                                    'Event.repeat_end_type' => Event::REPEAT_END_NEVER
                                )
                            )
                        )
                    ),
                    'OR' => array(
                        array('AND' => array(
                                'Event.created_by' => $userId,
                                'Event.event_type' => self::EVENT_TYPE_CALENDAR_REMINDER,
//                                'Event.start_date >=' => $startDate,
                            array(
                                    'OR'=>array(
                                        array(
                                            'Event.end_date >=' => $startDate
                                        ),
                                        array(
                                            'Event.repeat_end_type' => Event::REPEAT_END_NEVER
                                        )
                                     )
                                )
                            )
                        ),
                        array('AND' => array(
                                'CareCalendar.assigned_to' => $userId,
                                'Event.event_type' => self::EVENT_TYPE_CARE_CALENDAR_EVENT,
                            array(
                                    'OR'=>array(
                                        array(
                                            'Event.end_date >=' => $startDate
                                            ),
                                        array(
                                            'Event.repeat_end_type' => Event::REPEAT_END_NEVER
                                        )
                                     )
                                 )
                            )
                        ),
                        array('AND' => array(
                                'Event.created_by' => $userId,
                                'Event.event_type' => self::EVENT_TYPE_APPOINMENT,
                                array(
                                    'OR'=>array(
                                        array(
                                            'Event.end_date >=' => $startDate
                                            ),
                                        array(
                                            'Event.repeat_end_type' => Event::REPEAT_END_NEVER
                                        )
                                     )
                                 )
                            )
                        )
                    )
                )
            ),
            'order' => array('Event.start_date')
                )
        );
        return $eventsDetails;
    }

    public function getAllUserRelatedEventsForCalendar($userId, $startDate = NULL, $endtDate = NULL, $team_id = 0) {


        $eventsAttending = $this->EventMember->find('list', array(
            'conditions' => array(
                'EventMember.user_id' => $userId,
                'EventMember.status !=' => EventMember::STATUS_NOT_ATTENDING
            ),
            'fields' => 'EventMember.event_id'
        ));
//         $condition['Event.id'] = $eventsAttending;
//        if ($team_id > 0) {
//            $condition['Event.section'] = 2;
//            $condition['Event.section_id'] = $teamId;
//            $condition['CareCalendar.assigned_to'] = $userId;
//        }
        $eventsDetails = $this->find('all', array(
            'joins' => array(
                array(
                    'table' => 'care_calendar_events',
                    'alias' => 'CareCalendar',
                    'type' => 'LEFT',
//                    'conditions' => $joinCondition
                    'conditions' => 'CareCalendar.event_id  = Event.id'
                )
            ),
            'conditions' => array(
                'OR' => array(
                    'AND' => array(
                        'Event.id' => $eventsAttending,
                    ),
                    'OR' => array(
                        array('AND' => array(
                                'Event.created_by' => $userId,
                                'Event.event_type' => self::EVENT_TYPE_CALENDAR_REMINDER
                            )
                        ),
                        array('AND' => array(
                                'CareCalendar.assigned_to' => $userId,
                                'Event.event_type' => self::EVENT_TYPE_CARE_CALENDAR_EVENT
                            )  
                        ),
                        array('AND' => array(
                                'Event.created_by' => $userId,
                                'Event.event_type' => self::EVENT_TYPE_APPOINMENT
                            )
                        )
                    )
                )
            ),
            'order' => array('Event.start_date'),
            'fields' => array('Event.*', 'CareCalendar.*')
                )
        );
        return $eventsDetails;
    }

    public function getAllUserRelatedEventsForCareCalendar($userId, $startDate = NULL, $endtDate = NULL, $teamId = 0, $filterValues = null) {
//        if ($filtertype != 0) {
//            $condition = array(
//                'Event.section' => 2, //section 2 means the team section
//                'Event.section_id' => $teamId,
//                'CareCalendar.assigned_to' => $filtertype
//            );
//        } else {
//            $condition = array(
//                'Event.section' => 2, //section 2 means the team section
//                'Event.section_id' => $teamId
//            );
//        }
        $condition['Event.section'] = 2; //section 2 means the team section
        $condition['Event.section_id'] = $teamId;
        if (isset($filterValues['assignedToFilter']) && $filterValues['assignedToFilter'] != null && $filterValues['assignedToFilter'] != 'null') {
            $condition['CareCalendar.assigned_to'] = $filterValues['assignedToFilter'];
        }
        if (isset($filterValues['needTypeFilter']) && $filterValues['needTypeFilter'] != null && $filterValues['needTypeFilter'] != 'null') {
            $condition['CareCalendar.type'] = $filterValues['needTypeFilter'];
        }
        if (isset($filterValues['statusFilter']) && $filterValues['statusFilter'] != null && $filterValues['statusFilter'] != 'null') {
            $condition['CareCalendar.status'] = $filterValues['statusFilter'];
        }
        $eventsDetails = $this->find('all', array(
            'joins' => array(
                array(
                    'table' => 'care_calendar_events',
                    'alias' => 'CareCalendar',
                    'type' => 'LEFT',
//                    'conditions' => $joinCondition
                    'conditions' => 'CareCalendar.event_id  = Event.id'
                )
            ),
            'conditions' => $condition,
            'order' => array('Event.start_date'),
            'fields' => array('Event.*', 'CareCalendar.*')
                )
        );
        return $eventsDetails;
    }

    function removeReminderEvent($LoggedInUserId, $id) {
        $redirectUrl = '/calendar';
        if (!$id) {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect($redirectUrl);
            $result ['message'] = 'id not exist';
            $result ['success'] = false;
        }
        $this->id = $id;
        if ($this->exists()) {
            $options = array(
                'conditions' => array(
                    'Event.' . $this->primaryKey => $id
                )
            );
            $event = $this->find('first', $options);
            if ($event['Event']['created_by'] == $LoggedInUserId) {
                if ($this->delete($id)) {
                    $result ['message'] = 'Deleted successfully';
                    $result ['success'] = TRUE;
                } else {
                    $result ['message'] = 'Cannot delete. please try again.';
                    $result ['success'] = FALSE;
                }
            } else {
                $result ['message'] = 'No permission to delete the reminder';
                $result ['success'] = FALSE;
            }
        } else {
            $result ['message'] = 'Event not exist';
            $result ['success'] = FALSE;
        }
        return $result;
    }

    /**
     * Function to get the events and the 'attending/may be attending' members
     * with start date and time is between 'from' and 'to'
     * 
     * @param string $from mysql datetime string
     * @param string $to mysql datetime string
     * @return array 
     */
    public function getEventsWithStartTimeBetween($from, $to) {
        App::uses('EventMember', 'Model');

        $this->unbindModel(array(
            'hasMany' => array('EventMember', 'EventDisease'),
            'belongsTo' => array('Community')
        ));

        $this->bindModel(array(
            'hasMany' => array(
                'EventMember' => array(
                    'className' => 'EventMember',
                    'foreignKey' => 'event_id',
                    'conditions' => array(
                        'EventMember.status' => array(
                            EventMember::STATUS_ATTENDING,
                            EventMember::STATUS_MAYBE_ATTENDING
                        ),
                    ),
                    'fields' => array(
                        'EventMember.user_id',
                    )
                ))), false);

        $query = array(
            'conditions' => array(
                'Event.start_date BETWEEN ? AND ?' => array($from, $to),
                'Event.repeat' => 0
            ),
            'fields' => array(
                'Event.id', 'Event.name',
                'Event.start_date', 'Event.end_date', 'Event.created_by'
            )
        );

        $this->recursive = 2;
        return $this->find('all', $query);
    }
    
    
    /**
     * Function to get the Recurring events and the 'attending/may be attending' members
     * with start date and time is between 'from' and 'to'
     * 
     * @param string $from mysql datetime string
     * @param string $to mysql datetime string
     * @return array 
     */
    public function getRecurringEventsWithStartTimeBetween($from, $to) {
        App::uses('EventMember', 'Model');

        $this->unbindModel(array(
            'hasMany' => array('EventMember', 'EventDisease'),
            'belongsTo' => array('Community')
        ));

        $this->bindModel(array(
            'hasMany' => array(
                'EventMember' => array(
                    'className' => 'EventMember',
                    'foreignKey' => 'event_id',
                    'conditions' => array(
                        'EventMember.status' => array(
                            EventMember::STATUS_ATTENDING,
                            EventMember::STATUS_MAYBE_ATTENDING
                        ),
                    ),
                    'fields' => array(
                        'EventMember.user_id',
                    )
                ))), false);

        $query = array(
            'conditions' => array(
                'Event.repeat' => 1,
                array(
                    'OR'=>array(
                        array(
                            'Event.end_date >=' => $from
                        ),
                        array(
                             'Event.repeat_end_type' => 1
                        )
                    )
                )
                
            ),
            'fields' => array('Event.*')
        );

        $this->recursive = 2;
        return $this->find('all', $query);
    }

    
        /**
    	 * Function to save a event's cover slideshow enabled/disabled status
	 * 
	 * @param int $eventId
	 * @param int $status 
	 * @return boolean
	 */
	public function saveEventCoverSlideshowStatus($eventId, $status) {
		$this->id = $eventId;
		return $this->saveField('is_cover_slideshow_enabled', $status);
	}
}
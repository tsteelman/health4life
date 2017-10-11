<?php

App::uses('AppModel', 'Model');
App::uses('Date', 'Utility');

/**
 * EventMember Model
 *
 */
class EventMember extends AppModel {
    /**
     * Status
     */

    const STATUS_PENDING = 0;
    const STATUS_ATTENDING = 1;
    const STATUS_NOT_ATTENDING = 2;
    const STATUS_MAYBE_ATTENDING = 3;

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'fields' => array(
				'User.id', 'User.email', 'User.username', 'User.timezone'
			)
		)
	);

	/*
	 * Function to get eventid from event user relations by userId
	 * 
	 * @param $userId integer
	 * 
	 * @return array
	 */

    function getEventIds($userId) {
        return $this->find('list', array(
                    'conditions' => array('EventMember.user_id' => $userId),
                    'fields' => array('EventMember.event_id')
        ));
    }

    /*
     * Function to get status from event user relation by event and user id
     * 
     * @param $eventId Integer event's id
     * @param $userId Integer user's id
     * 
     * @return array
     */

    function getStatus($eventId, $userId) {
        $status = $this->find('first', array(
            'conditions' => array('EventMember.user_id' => $userId, 'EventMember.event_id' => $eventId),
            'fields' => array('EventMember.status')
		));


		if (!isset($status['EventMember']['status']) || $status['EventMember']['status'] == NULL) {
			return null;
		} else {
			return $status['EventMember']['status'];
		}
	}

    /*
     * Function to get users and thier invite status to an event
     * 
     * @param $eventId Integer event's id
     * 
     * @return array
     */

    function getEventMembers($eventId) {
        $eventMembes = $this->find('all', array(
            'conditions' => array('EventMember.event_id' => $eventId),
            'fields' => array('EventMember.status', 'EventMember.user_id')
        ));

        return $eventMembes;
    }

    /*
     * Function to get users who have been invited to an event
     * 
     * @param $eventId Integer event's id
     * 
     * @return array
     */

    function getEventMemberIds($eventId) {
        $eventMembers = $this->find('all', array(
            'conditions' => array('EventMember.event_id' => $eventId),
            'fields' => array('EventMember.user_id')
        ));

        return $eventMembers;
    }

    /**
     * Add a member who is attending an event
     * 
     * @param int $eventId
     * @param int $userId
     */
    public function addEventAttendingMember($eventId, $userId) {
        $this->create();
        $data = array(
            'event_id' => $eventId,
            'user_id' => $userId,
            'status' => self::STATUS_ATTENDING
        );
        $this->save($data, false);
    }

    /**
     * Function to get members who were already invited/attending/maybe to the event
     * 
     * @param int $eventId event id
     * @param int $userId user id to exclude
     * @return array
     */
    public function getExistingMembers($eventId, $userId) {
        $conditions = array(
            'event_id' => $eventId,
            'status' => array(
                self::STATUS_PENDING,
                self::STATUS_ATTENDING,
                self::STATUS_MAYBE_ATTENDING
            ),
            'user_id !=' => $userId
        );
		$this->recursive = -1;
		return $this->find('all', array('conditions' => $conditions));
	}

    /**
     * Function to remove a member from events in a community
     * 
     * @param int $communityId community id
     * @param int $userId member id
     */
    public function removeMemberFromCommunityEvents($communityId, $userId) {
        $this->bindModel(array('belongsTo' => array(
                'Event' => array(
                    'className' => 'Event',
                    'foreignKey' => 'event_id',
                ))), false
        );

        $conditions = array(
            'Event.community_id' => $communityId,
            'EventMember.user_id' => $userId
        );
        $cascade = false;

        $this->deleteAll($conditions, $cascade);
    }

    /*
     * Function to get eventId based on user id and status
     * 
     * @param int $userId
     * @param int $status
     * @return array eventId
     */

    public function getEvents($userId, $status) {
        $events = $this->find('list', array(
            'conditions' => array(
                'EventMember.user_id' => $userId,
                'EventMember.status' => $status
            ),
            'fields' => array('EventMember.event_id')
        ));

        return $events;
    }

    /**
     * Function to get the events that the user is attending today
     * 
     * @param int $userId
     * @param string $timezone
     * @return array
     */
    public function getUserAttendingEventsHappeningToday($userId, $timezone) {
        $today = Date::getCurrentDate($timezone);
        $offset = Date::getTimeZoneOffsetText($timezone);
        $events = $this->find('all', array(
            'conditions' => array(
                "{$this->alias}.user_id" => $userId,
                "CONVERT_TZ(Event.start_date, '+00:00', '{$offset}') LIKE" => "$today%",
                "{$this->alias}.status" => array(
                    self::STATUS_ATTENDING,
                    self::STATUS_MAYBE_ATTENDING
                )
            ),
            'joins' => array(
                array(
                    'table' => 'events',
                    'alias' => 'Event',
                    'type' => 'INNER',
                    'conditions' => "Event.id = {$this->alias}.event_id"
                )
            ),
            'fields' => array(
                'Event.id', 'Event.name',
                'Event.created_by', 'Event.start_date', 'Event.end_date'
            )
        ));
        return $events;
    }

	/**
	 * Function to get the list of user ids of attending members of an event
	 * 
	 * @param int $eventId
	 * @return array
	 */
	public function getAttendingMembers($eventId) {
		$this->recursive = -1;
		$query = array(
			'conditions' => array(
				"{$this->alias}.event_id" => $eventId,
				"{$this->alias}.status" => self::STATUS_ATTENDING
			),
			'fields' => array('user_id')
		);
		return $this->find('list', $query);
	}
        
    /**
     *  Function to get community events of a user
     * 
     * @param int $communityId
     * @param int $userId
     * @return array
     */    
    public function getCommunityEvents($communityId, $userId) {
        
        $this->bindModel(array('belongsTo' => array(
                'Event' => array(
                    'className' => 'Event',
                    'foreignKey' => 'event_id',
                ))), false
        );

        $conditions =array(
                'conditions' => array(
                            'Event.community_id' => $communityId,
                            'EventMember.user_id' => $userId
                ),
                'fields' => array(
                            'Event.id',
                            'Event.attending_count', 
                            'Event.not_attending_count', 
                            'Event.maybe_count', 
                            'Event.invited_count',
                            'EventMember.status'
                )
        );       

        return $this->find('all', $conditions);
    }
}
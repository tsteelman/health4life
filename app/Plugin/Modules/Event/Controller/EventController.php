<?php

/**
 * EventController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('EventAppController', 'Event.Controller');

/**
 * EventController for frontend events.
 * 
 * EventController is used for managing events.
 *
 * @author 	Greeshma Radhakrishnan
 * @package 	Event
 * @category	Controllers 
 */
class EventController extends EventAppController {

    public $uses = array('Event', 'User', 'EventMember', 'PatientDisease', 'EventDisease', 'CommunityMember');
    public $components = array('Paginator', 'RadiusSearch');

    function index($event_type = "") {
		
        $this->set('title_for_layout',"Events");
        $user = $this->Auth->user(); //Get current user details
        $events = array();
        $now = date("Y-m-d H:i:s"); //Get current date and time
        $pendingEventIds = array();
        $attendingEventIds = array();
        $notAttendingEventIds = array();
        $goingEventIds = array();
        $maybeEventIds = array();

        //logic to determine the diseases associated with the user.
        $diseases = $this->PatientDisease->findDiseases($user['id']); //Get the diseases associated with current user for identifing interested events

        $eventIds = $this->EventMember->getEventIds($user['id']); //User's Event ids

        foreach ($eventIds as $eventId) {

            $status = $this->EventMember->getStatus($eventId, $user['id']); //Status of event associated with user
            
            switch ($status) {
                case '0':
                    $pendingEventIds[] = $eventId; //Events that are pending invitation
                    break;
                case '1':
                    $goingEventIds[] = $eventId;
                    $attendingEventIds[] = $eventId;
                    break;
                case '2':
                    $notAttendingEventIds[] = $eventId;
                    break;
                case '3':
                    $maybeEventIds[] = $eventId;
                    $attendingEventIds[] = $eventId; //Events that user is attending to
                    break;
            }
        }
        $nearByCities = $this->RadiusSearch->getNearByCities($user['id'], 100, 10);
        //List of interesting events
        $interestingEventIds = $this->Event->find('list', array(
            'conditions' => array(
                'NOT' => array('Event.id' => $eventIds),
                'Event.created_by !=' => $user['id'],
                'Event.event_type' => array(
					Event::EVENT_TYPE_PUBLIC,
					Event::EVENT_TYPE_SITE
				),
                'Event.start_date > ' => $now,
//                'Event.repeat' => 0,
                'OR' => array(
                    array('AND' => array(
                        'EventDisease.disease_id' => $diseases,
                        'Event.city' => $nearByCities
                    )),
                    array('AND' => array(
                        'EventDisease.disease_id' => $diseases
                    )),
                    array('Event.event_type' => Event::EVENT_TYPE_SITE)
                    )
                ),
            'joins' => array(
                array(
                    'table' => 'event_diseases',
                    'type' => 'LEFT',
                    'alias' => 'EventDisease',
                    'conditions' => array(
                        'Event.id = EventDisease.event_id'
                        )
                    )
                ),
            'fields' => array('Event.id'),
            'group' => array('Event.id')
            )
        );
        
        $users = $this->User->find('all', array(
            'fields' => array('id', 'first_name', 'last_name')
        ));
        
        $user_communities = $this->CommunityMember->find('list', array(
            'conditions' => array(
                'CommunityMember.user_id' => $user['id'],
                'CommunityMember.status' => CommunityMember::STATUS_APPROVED
            ),
            'fields' => array('CommunityMember.community_id'),
        ));
        
        if (isset($event_type) && $event_type != "") {

            switch ($event_type) {
                case Event::MY_EVENTS:
                    //Events created by user
                    $this->getPaginatorMyEvents($user['id'], $now);
                    break;
                case Event::PENDING_EVENTS:
                    //Pending invites of user
                    $this->getPaginatorPendingEvents($pendingEventIds, $now);
                    
                    break;
                case Event::UPCOMING_EVENTS:
                    //Users upcoming events
                    $this->getPaginatorUpcomingEvents($attendingEventIds, $user['id'], $now);
                    
                    break;
                case Event::INTERESTING_EVENTS:
                    //Events user might be interested in based upon diseases of user
                    $this->getPaginatorInterestedEvents($interestingEventIds);
                    
                    break;
                case Event::PAST_EVENTS:
                    //Past events that the user attended to.
                    $this->getPaginatorPastEvents($attendingEventIds, $now);
                    break;
            }
            
            $events = $this->paginate('Event');
            
            if (isset($this->request->params['named']['page'])) {
                $nextPage = $this->request->params['named']['page'] + 1;
            }

            $timezone = $this->Auth->user('timezone');

            $this->set(compact('event_type', 'users', 'events', 'nextPage', 'timezone', 'goingEventIds', 'notAttendingEventIds', 'maybeEventIds', 'user_communities', 'now', 'user'));

            $this->layout = "ajax";
            $View = new View($this, false);
            $response = $View->element('Event.events_row');
            echo $response;
            exit;
        } else {
            $this->getPaginatorMyEvents($user['id'], $now);
            $eventsMy = $this->paginate('Event');
            $pageCountArray[1] = $this->params['paging']['Event']['pageCount'];
            
            
            $this->getPaginatorPendingEvents($pendingEventIds, $now);
            $eventsPending = $this->paginate('Event');
            $pageCountArray[2] = $this->params['paging']['Event']['pageCount'];
            $this->getPaginatorUpcomingEvents($attendingEventIds, $user['id'], $now);
            $eventsUp = $this->paginate('Event');
            $pageCountArray[3] = $this->params['paging']['Event']['pageCount'];
            $this->getPaginatorInterestedEvents($interestingEventIds);
            $eventsInterested = $this->paginate('Event');
            $pageCountArray[4] = $this->params['paging']['Event']['pageCount'];
            $this->getPaginatorPastEvents($attendingEventIds, $now);
            $eventsPast = $this->paginate('Event');
            $pageCountArray[5] = $this->params['paging']['Event']['pageCount'];
            $nextPage = '';
            
            $timezone = $this->Auth->user('timezone');
            $this->set(
                    compact(
                            'event_type','users', 'eventsMy','eventsPending', 
                            'eventsUp', 'eventsInterested', 'eventsPast', 'nextPage',
                            'timezone', 'goingEventIds', 'notAttendingEventIds',
                            'maybeEventIds', 'user_communities', 'now', 'user',
                            'pageCountArray'
                            )
                    );
        }
    }
    
    function getPaginatorMyEvents($userId, $now) {
        $this->paginate = array(
                        'limit' => 3,
                        'conditions' => array(
                            'Event.created_by' => $userId,
//                            'Event.repeat' => 0,
                            'Event.event_type' => array(
                                        Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
                                        ),
//                            'OR' => array(
//                                'Event.start_date >' => $now,
//                                'Event.end_date >' => $now
//                            )
                            'OR' => array(
                                array('AND' => array(
                                        'Event.end_date >' => $now,
                                    )
                                ),
                                array('AND' => array(
                                        'Event.end_date' => '0000-00-00 00:00:00',
                                        'Event.repeat' => 1, 
                                    )
                                )
                            )
                        ),
                        'order' => array('Event.start_date' => 'asc'),
                        'group' => array('Event.id')
                    );
    }
    
    function getPaginatorPendingEvents($pendingEventIds, $now) {
        $this->paginate = array(
                        'limit' => 3,
                        'conditions' => array(
                            'Event.id' => $pendingEventIds,
                            'Event.event_type' => array(
                                        Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
                                        ),
//                            'OR' => array(
//                                'Event.start_date >' => $now,
//                                'Event.end_date >' => $now
//                            ),
//                            'Event.repeat' => 0
                            'OR' => array(
                                array('AND' => array(
                                        'Event.end_date >' => $now,
                                    )
                                ),
                                array('AND' => array(
                                        'Event.end_date' => '0000-00-00 00:00:00',
                                        'Event.repeat' => 1, 
                                    )
                                )
                            )
                        ),
                        'order' => array('Event.start_date' => 'asc')
                    );
    }
    
    function getPaginatorUpcomingEvents($attendingEventIds, $userId, $now) {
        $this->paginate = array(
                        'limit' => 3,
                        'conditions' => array(
//                            array(
                                'Event.id' => $attendingEventIds,//0000-00-00 00:00:00
//                                'Event.repeat' => 0, 
                                'Event.created_by != ' => $userId,
                                'Event.event_type' => array(
                                            Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
                                            ),
//                            'OR' => array(
//                                'Event.start_date >' => $now,
////                                'Event.end_date >' => $now,
//                                'Event.end_date' => '0000-00-00 00:00:00'
//                             ),
                            'OR' => array(
                                array('AND' => array(
                                        'Event.end_date >' => $now,
                                    )
                                ),
                                array('AND' => array(
                                        'Event.end_date' => '0000-00-00 00:00:00',
                                        'Event.repeat' => 1, 
                                    )
                                )
                            )
                            
//                            array(
//                                'OR' => array(
//    //                                'Event.start_date >' => $now,
//    //                                    'Event.end_date >' => $now,
////                                    'AND'=>array(
//                                    array(
//                                        'Event.start_date >' => $now,
//    //                                    'Event.end_date >' => $now,
//                                        'Event.repeat' => 0
//                                        ),
////                                    'AND'=>array(
//                                    array(
//    //                                    'Event.start_date >' => $now,
//                                        'Event.end_date >' => $now,
//                                        'Event.repeat' => 0
//                                        ),
////                                    'AND'=>array(
//                                    array(
//                                        'Event.repeat_end_type' => Event::REPEAT_END_DATE,
//                                        'Event.end_date >' => $now,
//                                        'Event.repeat' => 1
//                                        ),
////                                    'AND'=>array(
//                                    array(
//                                        'Event.repeat_end_type' => Event::REPEAT_END_NEVER,
//                                        'Event.repeat' => 1
//                                        )
//                                )
//                            )
                        ),
                        'order' => array('Event.start_date' => 'asc')
                    );
    }
    
    function getPaginatorInterestedEvents($interestingEventIds) {
       $this->Paginator->settings = array(
                        'limit' => 3,
                        'conditions' => array(
                            'Event.id' => $interestingEventIds,
                            'Event.event_type' => array(
                                        Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
                                        )
                        ),
                        'order' => array('Event.start_date' => 'asc')
                    );
    }
    function getPaginatorPastEvents($attendingEventIds, $now) {
                    $this->paginate = array(
                        'limit' => 3,
                        'conditions' => array(
                            'Event.id' => $attendingEventIds,
//                            'Event.end_date <' => $now,
                            'Event.event_type' => array(
                                        Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE
                                        ),
                            'Event.end_date <' => $now,
                            'Event.end_date !=' => '0000-00-00 00:00:00',
//                            'Event.repeat' => 0
//                            'OR' => array(
//                                array('AND' => array(
//                                        'Event.end_date <' => $now,
//                                        'Event.repeat' => 0, 
//                                    )
//                                ),
//                                array('AND' => array(
//                                        'Event.end_date !=' => '0000-00-00 00:00:00',
//                                        'Event.repeat' => 1, 
//                                    )
//                                )
//                            )
                        ),
                        'order' => array('Event.start_date' => 'desc')
                    );
    }
    
    //Function to update count in event listing page
    public function updateCount($eventId) {
        
        $attending_count = $this->Event->find('first', array(
            'conditions' => array('Event.id' => $eventId),
            'fields' => array('Event.attending_count')
        ));
        $maybe_count = $this->Event->find('first', array(
            'conditions' => array('Event.id' => $eventId),
            'fields' => array('Event.maybe_count')
        ));
        echo 'Attending (' . $attending_count['Event']['attending_count'] . ') | Maybe (' . $maybe_count['Event']['maybe_count'] . ')';
        exit;
    }



    //Temporary function to update event count columns
    public function updateEventCounts() {
        
        $eventIds = $this->Event->find('list', array(
            'fields' => array('Event.id')
        ));
        
        foreach ($eventIds as $eventId){
//            debug($eventId);
            $attending_count = $this->EventMember->find('count', array(
                'conditions' => array(
                    'EventMember.status' => 1,
                    'EventMember.event_id' => $eventId
                    )
            ));
            
            $invited_count = $this->EventMember->find('count', array(
                'conditions' => array(
                    'EventMember.status' => 0,
                    'EventMember.event_id' => $eventId
                    )
            ));
            
            $not_attending_count =  $this->EventMember->find('count', array(
                'conditions' => array(
                    'EventMember.status' => 2,
                    'EventMember.event_id' => $eventId
                    )
            ));
            
            $maybe_count =  $this->EventMember->find('count', array(
                'conditions' => array(
                    'EventMember.status' => 3,
                    'EventMember.event_id' => $eventId
                    )
            ));
            
            if($attending_count == null) {
                $attending_count = 0;
            }
            if($invited_count == null) {
                $invited_count = 0;

            }
            if($not_attending_count == null) {
                $not_attending_count = 0;

            }
            if($maybe_count == null) {
                $maybe_count = 0;

            }
            $this->Event->id = $eventId;
            $this->Event->saveField('attending_count', $attending_count);
            $this->Event->saveField('invited_count', $invited_count);
            $this->Event->saveField('not_attending_count', $not_attending_count);
            $this->Event->saveField('maybe_count', $maybe_count);
            
//            debug($maybe_count);
//            debug($not_attending_count);
//            debug($invited_count);
//            debug($attending_count);
        }
        
        exit;
    }
        
}
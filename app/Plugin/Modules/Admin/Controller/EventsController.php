<?php

/*
 * EventsController class file
 * 
 * @author Varun Ashok <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

App::uses('Date', 'Utility');
App::uses('Common', 'Utility');

class EventsController extends AdminAppController {

    const FILTER_ALL = 0;
    const FILTER_UPCOMING = 1;
    const FILTER_TODAY = 2;
    const FILTER_PAST = 3;

    public $uses = array('Event', 'Disease', 'User', 'EventMember');
    public $components = array('Paginator', 'RadiusSearch');

    public function index() {

        /*
         * Conditions needed for filtering upcoming, past and today events.
         */
        $conditions = array(
            'Event.event_type' => array(
                Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
            ),
            'Event.repeat' => 0,
            'DATE(Event.start_date) !=' => '0000-00-00'
        );
        $conditionsNormal = array(
            'Event.event_type' => array(
                Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
            ),
            'Event.repeat' => 0,
            'DATE(Event.start_date) !=' => '0000-00-00'
        );
        $now = date("Y-m-d H:i:s");

//        if (isset($this->request->data['DiseaseFilter']['filter'])) {
        if ($this->request->query('filter')) {
            $filter = $this->request->query('filter');
//            $filter = $this->request->data['DiseaseFilter']['filter'];
//             $filter = $this->request->query('filter');
            switch ($filter) {
                case self::FILTER_UPCOMING:
                    $conditions = array_merge($conditions, array('OR' => array(
                            'Event.start_date >' => $now,
                            'Event.end_date >' => $now
                    )));
                    break;
                case self::FILTER_TODAY:
                    $conditions = array_merge($conditions, array(
                        'DATE(Event.start_date)' => Date::getCurrentDate()
                    ));
                    break;
                case self::FILTER_PAST:
                    $conditions = array_merge($conditions, array(
                        'Event.start_date <' => $now
                    ));
                    break;
                default:
                    break;
            }
        } else {
            $filter = 0;
        }
        if ($this->request->query('event_name')) {
            $keyword = $this->request->query('event_name');
            $conditions = array_merge($conditions, array(
                'Event.name LIKE' => '%' . $keyword . '%'
            ));
            $conditionsNormal = array_merge($conditionsNormal, array(
                'Event.name LIKE' => '%' . $keyword . '%'
            ));
        }
        $today_events_condition = array_merge($conditionsNormal, array(
            'DATE(Event.start_date)' => Date::getCurrentDate()
        ));
        $past_events_condition = array_merge($conditionsNormal, array(
            'Event.start_date <' => $now
        ));
        $upcoming_events_condition = array_merge($conditionsNormal, array('OR' => array(
                'Event.start_date >' => $now,
                'Event.end_date >' => $now
        )));
        $upcoming_events_count = $this->Event->find('count', array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array('User.id = Event.created_by')
                )
            ),
            'conditions' => $upcoming_events_condition
//            'conditions' => array(
//                'Event.event_type' => array(
//                    Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
//                ),
//                'Event.repeat' => 0,
//                'DATE(Event.start_date) !=' => '0000-00-00',
//                'OR' => array(
//                    'Event.start_date >' => $now,
//                    'Event.end_date >' => $now
//                )
//            )
        ));

        $today_events_count = $this->Event->find('count', array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array('User.id = Event.created_by')
                )
            ),
            'conditions' => $today_events_condition
//            'conditions' => array(
//                'Event.event_type' => array(
//                    Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
//                ),
//                'Event.repeat' => 0,
//                'DATE(Event.start_date) !=' => '0000-00-00',
//                'DATE(Event.start_date)' => Date::getCurrentDate()
//            )
        ));

        $past_events_count = $this->Event->find('count', array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array('User.id = Event.created_by')
                )
            ),
            'conditions' => $past_events_condition
//            'conditions' => array(
//                'Event.event_type' => array(
//                    Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
//                ),
//                'Event.repeat' => 0,
//                'DATE(Event.start_date) !=' => '0000-00-00',
//                'Event.start_date <' => $now
//            )
        ));

        $all_events_count = $this->Event->find('count', array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array('User.id = Event.created_by')
                )
            ),
            'conditions' => $conditionsNormal
//            'conditions' => array(
//                'Event.event_type' => array(
//                    Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
//                ),
//                'Event.repeat' => 0,
//                'DATE(Event.start_date) !=' => '0000-00-00'
//            )
        ));

        $this->paginate = array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array('User.id = Event.created_by')
                )
            ),
            'limit' => 10,
            'conditions' => $conditions,
            'fields' => array('Event.*', 'User.username'),
            'order' => array('Event.start_date' => 'desc')
        );

        $event_list = $this->paginate('Event');

        $this->set(compact('filter', 'keyword', 'event_list', 'all_events_count', 'upcoming_events_count', 'today_events_count', 'past_events_count'));
    }

    public function view($event_id = NULL) {
        $now = date("Y-m-d H:i:s"); //Get current date and time
        if (isset($event_id) && ($event_id != NULL)) {
            $event_details = $this->Event->find('first', array(
                'conditions' => array(
                    'Event.id' => $event_id
                )
            ));

            $creator = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $event_details['Event']['created_by']
                ),
                'fields' => array('User.username')
            ));

            $disease_names = array();

            foreach ($event_details['EventDisease'] as $disease) {
                $disease_name = $this->Disease->find('first', array(
                    'conditions' => array(
                        'Disease.id' => $disease['disease_id']
                    ),
                    'fields' => array('Disease.name')
                ));
                $disease_names[] = $disease_name['Disease']['name'];
            }

            $attending_members = array();
            $maybe_members = array();
            $invited_members = array();

            foreach ($event_details['EventMember'] as $member) {
                $user = $this->User->find('first', array(
                    'conditions' => array('User.id' => $member['user_id'])
                ));
                switch ($member['status']) {
                    case EventMember::STATUS_ATTENDING:
                        $attending_members[] = array(
                            'User' => $user['User'],
                            'Modified' => $member['modified']
                        );
                        break;
                    case EventMember::STATUS_MAYBE_ATTENDING:
                        $maybe_members[] = array(
                            'User' => $user['User'],
                            'Modified' => $member['modified']
                        );
                        break;
                    case EventMember::STATUS_PENDING:
                        $invited_members[] = array(
                            'User' => $user['User'],
                            'Modified' => $member['modified']
                        );
                        break;
                }
            }

            $this->set(compact('event_details', 'creator', 'disease_names', 'now', 'attending_members', 'invited_members', 'maybe_members'
            ));
        }
    }
///*
//*Search functionality has been added in the index itself.
//*/
//    public function search() {
//        $condition = array();
//        $admin = $this->Auth->user();
//        $filter = $this->request->query('filter');
//        $conditions = array('Event.event_type' => array(
//                Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
//            ), 'Event.repeat' => 0);
//        $now = date("Y-m-d H:i:s");
//
//        switch ($filter) {
//            case self::FILTER_ALL:
//                $conditons;
//                break;
//            case self::FILTER_UPCOMING:
//                $conditions = array_merge($conditions, array('OR' => array(
//                        'Event.start_date >' => $now,
//                        'Event.end_date >' => $now
//                )));
//                break;
//            case self::FILTER_TODAY:
//                $conditions = array_merge($conditions, array(
//                    'DATE(Event.start_date)' => Date::getCurrentDate()
//                ));
//                break;
//            case self::FILTER_PAST:
//                $conditions = array_merge($conditions, array(
//                    'Event.start_date <' => $now
//                ));
//                break;
//            default:
//                break;
//        }
//
//        if ($this->request->query('event_name')) {
//            $keyword = $this->request->query('event_name');
//            $conditions = array_merge($conditions, array(
//                'Event.name LIKE' => '%' . $keyword . '%'
//            ));
//        }
//
//        $this->paginate = array(
//            'joins' => array(
//                array(
//                    'table' => 'users',
//                    'alias' => 'User',
//                    'type' => 'INNER',
//                    'conditions' => array('User.id = Event.created_by')
//                )
//            ),
//            'fields' => array('Event.*', 'User.username'),
//            'limit' => 10,
//            'conditions' => $conditions,
//            'order' => array('Event.start_date' => 'desc')
//        );
//
//        $event_list = $this->paginate('Event');
//
//        $upcoming_events_count = $this->Event->find('count', array(
//            'joins' => array(
//                array(
//                    'table' => 'users',
//                    'alias' => 'User',
//                    'type' => 'INNER',
//                    'conditions' => array('User.id = Event.created_by')
//                )
//            ),
//            'conditions' => array(
//                'Event.event_type' => array(
//                    Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
//                ),
//                'Event.repeat' => 0,
//                'DATE(Event.start_date) !=' => '0000-00-00',
//                'OR' => array(
//                    'Event.start_date >' => $now,
//                    'Event.end_date >' => $now
//                )
//            )
//        ));
//
//        $today_events_count = $this->Event->find('count', array(
//            'joins' => array(
//                array(
//                    'table' => 'users',
//                    'alias' => 'User',
//                    'type' => 'INNER',
//                    'conditions' => array('User.id = Event.created_by')
//                )
//            ),
//            'conditions' => array(
//                'Event.event_type' => array(
//                    Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
//                ),
//                'Event.repeat' => 0,
//                'DATE(Event.start_date) !=' => '0000-00-00',
//                'DATE(Event.start_date)' => Date::getCurrentDate()
//            )
//        ));
//
//        $past_events_count = $this->Event->find('count', array(
//            'joins' => array(
//                array(
//                    'table' => 'users',
//                    'alias' => 'User',
//                    'type' => 'INNER',
//                    'conditions' => array('User.id = Event.created_by')
//                )
//            ),
//            'conditions' => array(
//                'Event.event_type' => array(
//                    Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
//                ),
//                'Event.repeat' => 0,
//                'DATE(Event.start_date) !=' => '0000-00-00',
//                'Event.start_date <' => $now
//            )
//        ));
//
//        $all_events_count = $this->Event->find('count', array(
//            'joins' => array(
//                array(
//                    'table' => 'users',
//                    'alias' => 'User',
//                    'type' => 'INNER',
//                    'conditions' => array('User.id = Event.created_by')
//                )
//            ),
//            'conditions' => array(
//                'Event.event_type' => array(
//                    Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_SITE
//                ),
//                'Event.repeat' => 0,
//                'DATE(Event.start_date) !=' => '0000-00-00'
//            )
//        ));
//
//        $this->set(compact('filter', 'event_list', 'keyword', 'all_events_count', 'upcoming_events_count', 'today_events_count', 'past_events_count'));
//        $this->render('index');
//    }

}

?>

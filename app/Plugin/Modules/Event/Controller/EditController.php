<?php

/**
 * EditController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('EventAppController', 'Event.Controller');

/**
 * EditController for events.
 * 
 * EditController is used for editing events.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Event
 * @category	Controllers 
 */
class EditController extends EventAppController {

    public $uses = array('Event', 'EventDisease', 'Country', 'State', 'CommunityMember', 'Timezone');
    public $components = array('EventForm');

    /**
     * Function to edit an event
     * 
     * @param int $id
     * @throws NotFoundException
     */
    public function index($id) {
        // throw error if id is not passed
        if (!$id) {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect('/event');
        }

        // throw error if no such event
        $event = $this->Event->findById($id);
        if (!$event) {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect('/event');
        }

        $eventData = $event['Event'];
        $data['eventId'] = $eventData['id'];
        $data['eventName'] = $eventData['name'];

        // check if the logged in user has permission to edit the event
        $userId = $this->Auth->user('id');
        $hasEditPermission = $this->__userHasEditPermission($event, $userId);
        if (!$hasEditPermission) {
            $this->Session->setFlash(__('You are not allowed to access that page'), 'error');
            $this->redirect('/event');
        }

        // event diseases
        $eventDiseases = $this->EventDisease->findAllByEventId($id);
        $eventDiseasesData = array();
        if (!empty($eventDiseases)) {
            $eventDiseasesData = array();
            foreach ($eventDiseases as $eventDisease) {
                $eventDiseasesData[] = array(
                    'id' => $eventDisease['EventDisease']['id'],
                    'disease_id' => $eventDisease['Disease']['id'],
                    'disease_name' => $eventDisease['Disease']['name']
                );
            }
            $eventDiseasesCount = count($eventDiseasesData);
            $data['eventDiseasesCount'] = $eventDiseasesCount;
        }

        // event image
        $data['eventImage'] = Common::getEventThumb($id);

        if (intval($eventData['event_type']) === Event::EVENT_TYPE_PRIVATE) {
            $data['diagnosisVisibilityClass'] = 'hide';
        }
		if (intval($eventData['event_type']) === Event::EVENT_TYPE_SITE) {
			$data['step3SiteWideVisibilityClass'] = '';
			$data['step3CommonVisibilityClass'] = 'hide';
		}
		if (intval($eventData['virtual_event']) === Event::VIRTUAL_EVENT) {
            $data['onlineEventFieldsVisibilityClass'] = '';
        } elseif (intval($eventData['virtual_event']) === Event::ORDINARY_EVENT) {
            $data['onsiteEventFieldsVisibilityClass'] = '';

            $states = $this->Country->getCountryStates($eventData['country']);
            $cities = $this->State->getStateCities($eventData['state']);

            $data['states'] = $states;
            $data['cities'] = $cities;
            $data['stateDisabled'] = false;
            $data['cityDisabled'] = false;
        }
		
        // event timezone
        $event['Event']['timezone'] = $eventData['timezone'];

        // start and end time
        $startDateTime = $eventData['start_date'];
        $endDateTime = $eventData['end_date'];
        $event['Event']['start_date'] = Date::MySqlDateTimeToJSDate($startDateTime, $eventData['timezone']);
        $event['Event']['start_time'] = Date::MySqlDateTimeoJSTime($startDateTime, $eventData['timezone']);
        $event['Event']['end_time'] = Date::MySqlDateTimeoJSTime($endDateTime, $eventData['timezone']);
        $event['Event']['old_name'] = $eventData['name'];        
        
        if($eventData['repeat'] == 1) {
            $startDateTime = $eventData['start_date'];
            $event['Event']['start_date_time'] = Date::MySqlDateTimeToJSDate($startDateTime, $eventData['timezone']);
            $event['Event']['start_date_timeonly'] = Date::MySqlDateTimeoJSTime($startDateTime, $eventData['timezone']); 

            if(!empty($eventData['span_date']) && $eventData['span_date'] != '0000-00-00 00:00:00') {
                $event['Event']['upto_date'] = Date::MySqlDateTimeToJSDate($eventData['span_date'], $eventData['timezone']); 
                $event['Event']['upto_timeonly'] = Date::MySqlDateTimeoJSTime($eventData['span_date'], $eventData['timezone']);
            }
            if(!empty($eventData['end_date']) && $eventData['end_date'] != '0000-00-00 00:00:00' && $eventData['repeat_end_type'] == Event::REPEAT_END_DATE) {
                $event['Event']['end_date'] = Date::MySqlDateTimeToJSDate($eventData['end_date'], $eventData['timezone']);
                unset($event['Event']['repeat_occurrences']);
            } else {
                unset($event['Event']['end_date']);
            }
            if($eventData['repeat_end_type'] == Event::REPEAT_END_NEVER) {
                unset($event['Event']['end_date']);
                unset($event['Event']['repeat_occurrences']);
            }

            /*$event['Event']['repeat_mode'] = $eventData['repeat_mode']; 
            $event['Event']['repeat_interval'] = $eventData['repeat_interval']; 
            $event['Event']['repeat_interval_text'] = $eventData['repeat_interval']; 
            $event['Event']['repeat_end_type'] = $eventData['repeat_end_type']; 
            $event['Event']['repeats_by'] = $eventData['repeats_by']; 
            $event['Event']['repeats_on '] = isset($eventData['repeats_on ']) ? $eventData['repeats_on '] : ''; */
        }

        // edit community event
        if ($eventData['community_id'] > 0) {
            $community = $event['Community'];
            $data['communityId'] = $community['id'];
            $data['communityName'] = $community['name'];
            $this->EventForm->communityId = $eventData['community_id'];
            $data['diagnosisVisibilityClass'] = '';
        }

        $timeZones = $this->Timezone->get_timezone_list();
        
        // set data on form
        $this->EventForm->setFormData($id);
        $this->set($data);
        $this->set('backUrl', '/event/details/index/' . $id);
        $this->set('timeZones', $timeZones);
        $this->set('defaultTimeZone', null);
        $this->set('isEditing', true);
        $this->set('startTime', $event['Event']['start_time']);
        $this->set('endTime', $event['Event']['end_time']);

        if (!$this->request->data) {
            // set event data on form
            $this->request->data = array_merge($event, array('EventDisease' => $eventDiseasesData));
        } else {
            // save event
            $this->EventForm->saveEvent();
        }
    }

    /**
     * Function to check if a user has edit permission to an event
     * 
     * @param array $event
     * @param int $userId
     * @return boolean
     */
    private function __userHasEditPermission($event, $userId) {
        $hasEditPermission = false;

        // only the creator of the event can edit the event
        if ($event['Event']['created_by'] == $userId) {
            $hasEditPermission = true;
        }

        // if community event, community admin also can edit
        if ($event['Event']['community_id'] > 0) {
            $communityId = $event['Event']['community_id'];
            $hasCommunityManagePermission = $this->CommunityMember->hasManagePermission($userId, $communityId);
            if ($hasCommunityManagePermission) {
                $hasEditPermission = true;
            }
        }

        return $hasEditPermission;
    }
}

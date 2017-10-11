<?php

App::uses('AppModel', 'Model');
App::uses('Event', 'Model');

/**
 * EventDisease Model
 *
 * @property Event $Event
 * @property Disease $Disease
 */
class EventDisease extends AppModel {

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $components = array('Paginator');
    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Disease' => array(
            'className' => 'Disease',
            'foreignKey' => 'disease_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    function findEventsWithDisease($diseaseId) {
        $events = $this->find('all', array(
            'conditions' => array('EventDisease.disease_id' => $diseaseId)
        ));

        if (isset($events)) {
            return $events;
        } else {
            return FALSE;
        }
    }

    function replaceDiseaseOfEvents($currentDiseaseId, $newDiseaseId) {


        $events = $this->find('all', array(
            'conditions' => array('disease_id' => $currentDiseaseId)
        ));
        foreach ($events as $event) {
            if ($this->hasAny(array('disease_id' => $newDiseaseId,'event_id' => $event['EventDisease']['event_id']))) {
                $this->delete($event['EventDisease']['id']);
            } else {
                $this->id = $event['EventDisease']['id'];
                $this->set('disease_id', $newDiseaseId);
                $this->save();
            }
        }



//        if ($this->updateAll(
//                        array('EventDisease.disease_id' => $newDiseaseId), array('EventDisease.disease_id' => $currentDiseaseId)
//                )) {
//            return TRUE;
//        } else {
//            return FALSE;
//        }
    }

	/**
	 * Function to get the public events tagged with a disease
	 * 
	 * @param int $diseaseId
	 * @return array
	 */
	public function getPublicEventsWithDisease($diseaseId) {
		$eventList = array();
		$query = array(
			'conditions' => array(
				'EventDisease.disease_id' => $diseaseId,
				'Event.event_type' => array(
					Event::EVENT_TYPE_PUBLIC,
					Event::EVENT_TYPE_SITE
				)
			),
			'fields' => array('Event.id')
		);
		$events = $this->find('all', $query);
		if (!empty($events)) {
			foreach ($events as $event) {
				$eventList[] = $event['Event']['id'];
			}
		}
		return $eventList;
	}

	/**
	 * Function to get the diseases tagged with a public event
	 * 
	 * @param int $eventId
	 * @return array
	 */
	public function getDiseasesOfPublicEvent($eventId) {
		$diseaseList = array();
		$query = array(
			'conditions' => array(
				'EventDisease.event_id' => $eventId,
				'Event.event_type' => array(
					Event::EVENT_TYPE_PUBLIC,
					Event::EVENT_TYPE_SITE
				)
			),
			'fields' => array('Disease.id')
		);
		$eventDiseases = $this->find('all', $query);
		if (!empty($eventDiseases)) {
			foreach ($eventDiseases as $eventDisease) {
				$diseaseList[] = $eventDisease['Disease']['id'];
			}
		}
		return $diseaseList;
	}
}
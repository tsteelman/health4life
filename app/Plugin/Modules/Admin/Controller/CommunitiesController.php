<?php

/**
 * CommunitiesController class file.
 *
 * @author    Greeshma Radhakrishnan <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
/**
 * Communities Controller for the admin
 *
 * CommunitiesController is used for the admin to view communities
 *
 * @author 	 Greeshma Radhakrishnan
 * @package  Admin
 * @category Controllers
 */
App::uses('Common', 'Utility');
App::uses('CakeTime', 'Utility');

class CommunitiesController extends AdminAppController {

	public $uses = array('Community', 'CommunityMember');

	const PAGE_LIMIT = 10;

	/**
	 * Admin Community List
	 */
	function index() {
		$conditions = array();

		$searchKey = '';
		if ($this->request->query('search_key')) {
			$searchKey = $this->request->query('search_key');
			if ($searchKey !== '' && !is_null($searchKey)) {
				$conditions['Community.name LIKE'] = "%{$searchKey}%";
			}
		}

		$filter = 0;
		if ($this->request->query('filter')) {
			$filter = $this->request->query('filter');
			if ($filter > 0) {
				$conditions['Community.type'] = $filter;
			}
		}

		$this->paginate = array(
			'limit' => self::PAGE_LIMIT,
			'conditions' => $conditions,
		);
		$this->Community->bindModel(array(
			'belongsTo' => array(
				'User' => array(
					'className' => 'User',
					'foreignKey' => 'created_by',
					'fields' => array(
						'User.username'
					)
				))), false);
		$communities = $this->paginate('Community');

		$communityTypes = Community::getCommunityTypes();

		if (count($communities) > 0) {
			$timezone = $this->Auth->user('timezone');
			foreach ($communities as &$community) {
				$community['Community']['created'] = Date::getUSFormatDateTime($community['Community']['created'], $timezone);
				$community['Community']['type'] = $communityTypes[$community['Community']['type']];
			}
		} else {
			$this->Session->setFlash('No communities found.', 'warning');
		}

		$this->set(compact('communities', 'communityTypes'));
		$this->request->data['Community']['search_key'] = $searchKey;
		$this->request->data['Community']['filter'] = $filter;
	}

	/**
	 * View the details of a community
	 * 
	 * @param int $communityId 
	 */
	public function view($communityId) {
		if ($this->Community->exists($communityId)) {
			$communityData = $this->Community->getCommunityData($communityId);
			$community = $this->__getCommunityBasicInfo($communityData);
			$events = $this->__getEventsData($communityData['Event']);
			$members = $this->CommunityMember->getCommunityMembers($communityId);
			$eventsCount = count($events);
			$membersCount = count($members);
			$this->set(compact('community', 'events', 'members', 'eventsCount', 'membersCount'));
		} else {
			$this->Session->setFlash(__('No community exists with id: %d', $communityId), 'warning');
			$this->redirect('/admin/communities');
		}
	}

	/**
	 * Function to get the basic information about a community from community data
	 * 
	 * @param array $communityData
	 * @return array
	 */
	private function __getCommunityBasicInfo($communityData) {
		$community = $communityData['Community'];
		$timezone = $this->Auth->user('timezone');
		$communityTypes = Community::getCommunityTypes();
		$community['created'] = Date::getUSFormatDate($community['created'], $timezone);
		$community['type'] = $communityTypes[$community['type']];
		$community['image'] = Common::getCommunityThumb($community['id']);
		$locationName = $this->__getLocationName($communityData);
		$community['leader'] = $communityData['User']['username'];
		$community['location'] = $locationName;
		return $community;
	}

	/**
	 * Function to get the location name of a community from community data
	 * 
	 * @param array $communityData
	 * @return string
	 */
	private function __getLocationName($communityData) {
		$location['city'] = $communityData['City']['description'];
		$location['state'] = $communityData['State']['description'];
		$location['country'] = $communityData['Country']['short_name'];
		$locationName = join(', ', $location);
		return $locationName;
	}

	/**
	 * Function to get the events data from events
	 * 
	 * @param array $events
	 * @return array
	 */
	private function __getEventsData($events) {
		if (!empty($events)) {
			$timezone = $this->Auth->user('timezone');
			foreach ($events as &$event) {
				$eventPhotoPath = Common::getEventThumb($event['id']);
				$event['image'] = $eventPhotoPath;
				$event['url'] = "/admin/Events/view/{$event['id']}";
				$startTime = CakeTime::nice($event['start_date'], $timezone, '%a, %b %e, %Y %l:%M %p');
				$endTime = CakeTime::nice($event['end_date'], $timezone, '%l:%M %p');
				$event['time'] = $startTime . ' - ' . $endTime;
			}
		}

		return $events;
	}
}
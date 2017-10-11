<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
/**
 * FollowingPage Model
 *
 * @property User $User
 */
class FollowingPage extends AppModel {


/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	/**
	 * Following page type const
	 */
	const DISEASE_TYPE = 1;
	const USER_TYPE = 2;
	const EVENT_TYPE = 3;
	const COMMUNITY_TYPE = 4;

	/**
	 * Notification constant
	 */
	const NOTIFICATION_ON = 1;
	const NOTIFICATION_OFF = 0;
	
	/**
	 * Function to follow a page
	 * 
	 * @param array $data
	 */
	public function followPage($data) {
		$this->create();
		$followCount = $this->getFollowStatus($data['user_id'], $data['type'], $data['page_id']);
		if ($followCount == 0) { // to check unique		
			return $this->save($data
							, array('validate' => false));
		} else {
			return FALSE;
		}
	}
	
	/**
	 * Function to unfollow a page
	 * 
	 * @param array $data
	 */
	public function unFollowPage($data) {
		$this->deleteAll(
				array(
					'FollowingPage.type' => $data['type'],
					'FollowingPage.page_id' => $data['page_id'],
					'FollowingPage.user_id' => $data['user_id']
				)
		);
	}
	
	/**
	 * Function to remove all followers when a particular page is removed.
	 * 
	 * @param array $data
	 */
	public function deleteAllTypeFollowers($data) {
		$this->deleteAll(
				array(
					'FollowingPage.type' => $data['type'],
					'FollowingPage.page_id' => $data['page_id']
				)
		);
		
		return TRUE;
	}
	
	/**
	 * Check if user is already following, return count if following, zero if not.
	 * 
	 * @param int $userId
	 * @param int $type
	 * @param int $pageId
	 * @return int count
	 */
	public function getFollowStatus($userId, $type, $pageId){
		$followCount = $this->find('count', array(
			'conditions' => array(
				'FollowingPage.type' => $type,
				'FollowingPage.page_id' => $pageId,
				'FollowingPage.user_id' => $userId,
			)
				)
		);
		
		return $followCount;
	}
	/**
	 * Function to get user ids of users following a page & notification is set	 
	 * @param int $type
	 * @param int $pageId
	 * @return int count
	 */
	public function getNotificationMembers($type, $pageId){
		$this->recursive = -1;
		
		$notificationUsers = $this->find('list', array(
			'conditions' => array(
				"{$this->alias}.type" => $type,
				"{$this->alias}.page_id" => $pageId,
				"{$this->alias}.notification" => self::NOTIFICATION_ON
			),
			'fields' => array('user_id')
				)
		);
		
		return $notificationUsers;
	}
	
	/**
	 * Function to turn of notification of a page
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function pageNotification($data) {
		$this->updateAll(
				array(
					'FollowingPage.notification' => $data['notification']
				),
				array(
					'FollowingPage.type' => $data['type'],
					'FollowingPage.page_id' => $data['page_id'],
					'FollowingPage.user_id' => $data['user_id']
				)
		);		
		return TRUE;
	}
	
	/**
	 * Function to get list of diseases login user is following.
	 * 
	 * @param int $type follow type
	 */
	public function getFollowingDiseaseList($userId) {

		$this->unbindModel(
				array('belongsTo' => array('User'))
		);	
		
		$followList = $this->find('all', array(
			'joins' => array(
				array(
					'table' => 'diseases',
					'alias' => 'Disease',
					'type' => 'INNER',
					'conditions' => array(
						'Disease.id = FollowingPage.page_id'
					)
				),
			),
			'fields' => array(
				'FollowingPage.*',
				'Disease.*'
			),
			'conditions' => array(
				'FollowingPage.type' => FollowingPage::DISEASE_TYPE,
				'FollowingPage.user_id' => $userId,
			)
				)
		);
		
		return $followList;
	}
	
	/**
	 * Function to get list of profiles login user is following.
	 * 
	 * @param int $type follow type
	 */
	public function getFollowingProfileList($userId) {
		
		$this->unbindModel(
				array('belongsTo' => array('User'))
		);
		
		$followList = $this->find('all', array(
			'joins' => array(
				array(
					'table' => 'users',
					'alias' => 'User',
					'type' => 'INNER',
					'conditions' => array(
						'User.id = FollowingPage.page_id'
					)
				),  
				array(
                    'table' => 'patient_diseases',
                    'alias' => 'PatientDisease',
                    'type' => 'LEFT',
                    'conditions' => array(
						'PatientDisease.patient_id = FollowingPage.page_id'
						)
                ),
                array(
                    'table' => 'diseases',
                    'alias' => 'Disease',
                    'type' => 'LEFT',
                    'conditions' => array(
						'PatientDisease.disease_id = Disease.id'
					)
                ),
                array(
                    'table' => 'states',
                    'alias' => 'State',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'User.state = State.id'
                    )
                ),
                array(
                    'table' => 'cities',
                    'alias' => 'City',
                    'type' => 'LEFT',
                    'conditions' => 'City.id = User.city'
                ),
                array(
                    'table' => 'countries',
                    'alias' => 'Country',
                    'type' => 'LEFT',
                    'conditions' => 'Country.id = User.country'
                )),
			'fields' => array(
				'FollowingPage.*',
				'User.*',
				'Disease.*',
				'State.description',
				'City.description',
				'Country.*'
			),
			'conditions' => array(
				'FollowingPage.type' => FollowingPage::USER_TYPE,
				'FollowingPage.user_id' => $userId,
			),
			'group' => array('FollowingPage.id')
				)
		);
		
		return $followList;
	}

	/**
	 * Function to get the pages a user is following
	 * 
	 * @param int $userId user id
	 * @param bool $group whether to group the pages by type
	 * @return array
	 */
	public function getUserFollowingPages($userId, $group = false) {
		$pages = array();

		$this->recursive = -1;
		$followingPages = $this->find('all', array(
			'conditions' => array(
				"{$this->alias}.user_id" => $userId,
			),
			'fields' => array(
				"{$this->alias}.page_id",
				"{$this->alias}.type"
			)
		));

		if (!empty($followingPages)) {
			if ($group === true) {
				foreach ($followingPages as $followingPageModel) {
					$followingPage = $followingPageModel['FollowingPage'];
					$pageId = $followingPage['page_id'];
					$type = $followingPage['type'];
					$pages[$type][] = $pageId;
				}
			} else {
				$pages = $followingPages;
			}
		}

		return $pages;
	}

	/**
	 * Function to get the users following a page
	 * 
	 * @param int $pageId
	 * @param int $type
	 * @return array
	 */
	public function getPageFollowingUsers($pageId, $type) {
		$this->recursive = -1;
		$followingUsers = $this->find('list', array(
			'conditions' => array(
				"{$this->alias}.page_id" => $pageId,
				"{$this->alias}.type" => $type
			),
			'fields' => array(
				"{$this->alias}.user_id",
			)
		));
		if (!empty($followingUsers)) {
			$followingUsers = array_values($followingUsers);
		}
		return $followingUsers;
	}

	/**
	 * Function to get the users following a community page
	 * 
	 * @param int $communityId
	 * @return array
	 */
	public function getCommunityFollowingUsers($communityId) {
		return $this->getPageFollowingUsers($communityId, self::COMMUNITY_TYPE);
	}

	/**
	 * Function to get the users following an event page
	 * 
	 * @param int $eventId
	 * @return array
	 */
	public function getEventFollowingUsers($eventId) {
		return $this->getPageFollowingUsers($eventId, self::EVENT_TYPE);
	}

	/**
	 * Function to get the users following a profile page
	 * 
	 * @param int $profileId
	 * @return array
	 */
	public function getProfileFollowingUsers($profileId) {
		return $this->getPageFollowingUsers($profileId, self::USER_TYPE);
	}

	/**
	 * Function to get the users following a disease page
	 * 
	 * @param int $diseaseId
	 * @return array
	 */
	public function getDiseaseFollowingUsers($diseaseId) {
		return $this->getPageFollowingUsers($diseaseId, self::DISEASE_TYPE);
	}

	/**
	 * Function to get the rooms following a disease post
	 * 
	 * @param int $diseaseId
	 * @return array 
	 */
	public function getDiseasePostFollowingRooms($diseaseId) {
		$rooms = array("diseases/{$diseaseId}");
		$followingUsers = $this->getDiseaseFollowingUsers($diseaseId);
		if (!empty($followingUsers)) {
			foreach ($followingUsers as $followingUserId) {
				$rooms[] = "newsfeed/{$followingUserId}";
			}
		}
		return $rooms;
	}
	/**
	 * Function to get the rooms following a community post
	 * 
	 * @param int $communityId
	 * @return array 
	 */
	public function getCommunityPostFollowingRooms($communityId) {
		$rooms[] = "communities/{$communityId}";
		$followingUsers = $this->getCommunityFollowingUsers($communityId);
		if (!empty($followingUsers)) {
			foreach ($followingUsers as $followingUserId) {
				$rooms[] = "newsfeed/{$followingUserId}";
			}
		}
		return $rooms;
	}
        
	/**
	 * Function to get list of diseases login user is following.
	 * 
	 * @param int $type follow type
	 */
	public function getFollowingDiseaseListId($userId) {

		$this->unbindModel(
                    array('belongsTo' => array('User'))
		);	
		$followArray = array();
                
		$followList = $this->find('all', array(
			'fields' => array(
                            'FollowingPage.page_id',
			),
			'conditions' => array(
                            'FollowingPage.type' => FollowingPage::DISEASE_TYPE,
                            'FollowingPage.user_id' => $userId,
			)
                    )
		);
		if(!empty($followList)) {
                    foreach($followList as $follow) {
                       $followArray[] = $follow['FollowingPage']['page_id'];
                    }
                }
		return $followArray;
	}        
}
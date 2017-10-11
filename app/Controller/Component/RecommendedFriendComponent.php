<?php

/**
 * RecommendedFriendComponent class file.
 * 
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('MyFriends', 'Model');
App::uses('User', 'Model');
App::uses('Date', 'Utility');

/**
 * RecommendedFriendComponent for Recommended friend.
 * 
 * This class is used to handle posting and related functionalities.
 *
 * @package 	Controller.Component
 * @category	Component 
 */
class RecommendedFriendComponent extends Component {

	public $components = array('Paginator', 'RadiusSearch');
	
	const ADMIN_USER = 1;

	/**
	 * Number of recommended users per email
	 */
	const EMAIL_RECOMMENDED_USERS_COUNT = 10;

	/**
	 * Age range boundary
	 */
	const AGE_RANGE_BOUNDARY = 5;
	
	/**
	 * Variable to hold the users excluded from the recommended users list
	 * 
	 * @var array 
	 */
	private $__excludedUsersList = array();

	/**
	 * Function to exclude users from recommended users list
	 * 
	 * @param array $excludeUsersList
	 */
	public function excludeUsers($excludeUsersList) {
		$this->__excludedUsersList = $excludeUsersList;
	}

	/**
	 * Function to paginate recommended friends
	 * 
	 * @param int $userId
	 * @param int $MAX_RESULT_COUNT
	 * @return array
	 */
	public function paginateRecommendedFriends($userId, $MAX_RESULT_COUNT = 10) {
		$query = $this->getRecommendedUsersQuery($userId, $MAX_RESULT_COUNT);
		$this->Paginator->settings = array('User' => $query);
		$recommendedUsers = $this->Paginator->paginate('User');
		return $recommendedUsers;
	}

	/**
	 * Function to get the query to get the recommended friends of a user
	 * 
	 * @param int $userId
	 * @param int $MAX_RESULT_COUNT
	 * @return array
	 */
	public function getRecommendedUsersQuery($userId, $MAX_RESULT_COUNT = 10) {
		$this->MyFriends = ClassRegistry::init('MyFriends');
		$this->User = ClassRegistry::init('User');

		$excludeID = $this->MyFriends->getIdOfAllTypeFriendsList($userId);
		array_push($excludeID, $userId); //exclude the $userId also.

		if (!empty($this->__excludedUsersList)) {
			$excludeID = array_merge($excludeID, $this->__excludedUsersList);
		}

		$userData = $this->User->getFullUserDetails($userId, 'id');
		$nearByCities = $this->RadiusSearch->getNearByCities($userId, 250, 1000);

		if ($userData[0][0]['diseases_id'] == NULL) {
			$diseaseArray = '';
		} else {
			$diseaseArray = explode(', ', $userData[0][0]['diseases_id']);
		}

		$age = $userData[0]['User']['age'];
		$ageBoundary = self::AGE_RANGE_BOUNDARY;
		$ageRange = array(
			$age - $ageBoundary,
			$age + $ageBoundary
		);

		$friendsOfFriends = $this->MyFriends->getFriendsofFriends($userId);

		$query = array(
			'joins' => array(
				array('table' => 'patient_diseases',
					'alias' => 'PatientDisease',
					'type' => 'LEFT',
					'conditions' => 'User.id = PatientDisease.patient_id'
				),
				array('table' => 'diseases',
					'alias' => 'Disease',
					'type' => 'LEFT',
					'conditions' => 'Disease.id = PatientDisease.disease_id'
				),
				array('table' => 'states',
					'alias' => 'State',
					'type' => 'INNER',
					'conditions' => array('User.state = State.id')
				),
				array('table' => 'cities',
					'alias' => 'City',
					'type' => 'INNER',
					'conditions' => 'City.id = User.city'
				),
				array('table' => 'countries',
					'alias' => 'Country',
					'type' => 'INNER',
					'conditions' => 'Country.id = User.country'
				)
			),
			'conditions' => array(
				'OR' => array(
					'City.id' => $nearByCities,
					'age BETWEEN ? AND ?' => $ageRange,
					'PatientDisease.disease_id' => $diseaseArray,
					'User.id' => $friendsOfFriends
				),
				'NOT' => array(
					'User.id' => $excludeID,
					'User.is_admin' => self::ADMIN_USER
				)
			),
			'fields' => array(
				'User.username',
				'User.id',
				'User.type',
				'User.privacy_settings',
				'Country.short_name',
				'State.description',
				'City.description',
				'age'
			),
			'limit' => $MAX_RESULT_COUNT,
			'group' => 'User.id'
		);
		return $query;
	}

	/**
	 * Function to get recommented users
	 * 
	 * @param int $userId
	 * @param int $MAX_RESULT_COUNT
	 * @return array
	 */
	public function getRecommendedUsers($userId, $MAX_RESULT_COUNT = 10) {
		$query = $this->getRecommendedUsersQuery($userId, $MAX_RESULT_COUNT);
		$this->User = ClassRegistry::init('User');
		$recommendedUsers = $this->User->find('all', $query);
		return $recommendedUsers;
	}

	/**
	 * Function to send friends recommendations emails
	 */
	public function sendFriendRecommendationEmails() {
		$this->User = ClassRegistry::init('User');
		$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
		$users = $this->User->getAllActiveUsersWithNotificationSettings();
		if (!empty($users)) {
			foreach ($users as $userData) {
				$settings = $userData['NotificationSetting'];
				if ($this->__isEmailNotificationOn($settings) && $this->__isNotificationTime($settings)) {
					$this->__sendFriendRecommendationEmailToUser($userData);
				}
			}
		}
	}

	/**
	 * Function to check if recommend_friends email notification is on/off
	 * in the settings
	 * 
	 * @param array $settings
	 * @return bool
	 */
	public function __isEmailNotificationOn($settings) {
		$status = true;
		if (!empty($settings['email_settings'])) {
			$emailSettings = json_decode($settings['email_settings'], true);
			if (isset($emailSettings['email_settings']['recommend_friends'])) {
				$status = (bool) $emailSettings['email_settings']['recommend_friends'];
			}
		}
		return $status;
	}

	/**
	 * Function to check if current time is recommend_friends email notification
	 * time as per the user settings
	 * 
	 * @param array $settings
	 * @return bool
	 */
	public function __isNotificationTime($settings) {
		$isNotificationTime = false;

		if (!empty($settings) && ($settings['last_recommended_datetime'] > 0)) {
			$frequency = $settings['recommend_friends_frequency'];
			switch ($frequency) {
				case NotificationSetting::FREQUENCY_DAILY:
					$intervalDays = 1;
					break;
				case NotificationSetting::FREQUENCY_WEEKLY:
					$intervalDays = 7;
					break;
				case NotificationSetting::FREQUENCY_MONTHLY:
					$intervalDays = 30;
					break;
				case NotificationSetting::FREQUENCY_YEARLY:
					$intervalDays = 365;
					break;
			}
			$lastRecommendedTimeStamp = strtotime($settings['last_recommended_datetime']);
			$interval = $intervalDays * 24 * 60 * 60;
			$nextNotificationTimeStamp = $lastRecommendedTimeStamp + $interval;
			$nextNotificationDateTime = date('Y-m-d H:i:s', $nextNotificationTimeStamp);
			$nextNotificationDate = date('Y-m-d', $nextNotificationTimeStamp);
			$today = Date::getCurrentDate();
			if ($nextNotificationDate < $today) {
				$frequencyChangedTimeStamp = strtotime($settings['frequency_changed_datetime']);
				$nextNotificationTimeStamp = $frequencyChangedTimeStamp + $interval;
				$nextNotificationDateTime = date('Y-m-d H:i:s', $nextNotificationTimeStamp);
			}
			if (CakeTime::isToday($nextNotificationDateTime)) {
				$isNotificationTime = true;
			}
		} else {
			$isNotificationTime = true;
		}

		return $isNotificationTime;
	}

	/**
	 * Function to send friend recommendation email to a user
	 * 
	 * @param array $userData
	 */
	private function __sendFriendRecommendationEmailToUser($userData) {
		// find recommended users excluding users which are already recommended
		$settings = $userData['NotificationSetting'];
		$excludeUsersList = array();
		if (!empty($settings['recommended_users'])) {
			$alreadyRecommendedUsers = $settings['recommended_users'];
			$excludeUsersList = explode(',', $alreadyRecommendedUsers);
		}
		$user = $userData['User'];
		$userId = $user['id'];
		$limit = self::EMAIL_RECOMMENDED_USERS_COUNT;
		$this->excludeUsers($excludeUsersList);
		$recommendedUsers = $this->getRecommendedUsers($userId, $limit);
		$recommendedUsersCount = count($recommendedUsers);
		if ($recommendedUsersCount < $limit) {
			$excludeUsersCount = count($excludeUsersList);
			if ($excludeUsersCount > 0) {
				$countDiff = $limit - $recommendedUsersCount;
				if ($countDiff >= $excludeUsersCount) {
					$excludeUsersList = array();
				} else {
					for ($i = 0; $i < $countDiff; $i++) {
						unset($excludeUsersList[$i]);
					}
				}
				$excludeUsersList = array_values($excludeUsersList);
				$this->excludeUsers($excludeUsersList);
				$recommendedUsers = $this->getRecommendedUsers($userId, $limit);
			}
		}

		if (!empty($recommendedUsers)) {
			// send mail
			$Api = new ApiController;
			$Api->constructClasses();
			$Api->sendFriendRecommendationsEmailToUser($user, $recommendedUsers);

			// update recommended_users list and last_recommended_datetime
			foreach ($recommendedUsers as $recommendedUser) {
				$recommendedUserId = $recommendedUser['User']['id'];
				array_push($excludeUsersList, $recommendedUserId);
			}
			$newRecommendedUsersList = join(',', $excludeUsersList);
			$settingsData[] = array(
				'id' => $userData['NotificationSetting']['id'],
				'user_id' => $userId,
				'recommended_users' => $newRecommendedUsersList,
				'last_recommended_datetime' => Date::getCurrentDateTime()
			);
			$this->NotificationSetting->saveMany($settingsData);
		}
	}
}
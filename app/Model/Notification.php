<?php

App::uses('AppModel', 'Model');
App::uses('UserPrivacySettings', 'Lib');

/**
 * Notification Model
 *
 * @property Sender $Sender
 * @property Recipient $Recipient
 */
class Notification extends AppModel {

	/**
	 * Activity types
	 */
	const ACTIVITY_INVITE = 'invite';
	const ACTIVITY_UPDATE = 'update';
	const ACTIVITY_DELETE = 'delete';
	const ACTIVITY_REMINDER = 'reminder';
	const ACTIVITY_COMMUNITY_JOIN_REQUEST = 'community_join_request';
	const ACTIVITY_POST = 'post';
	const ACTIVITY_LIKE = 'like';
	const ACTIVITY_COMMENT = 'comment';
	const ACTIVITY_EVENT_RSVP = 'event_rsvp';
	const ACTIVITY_ANSWERED_QUESTION = 'answered_question';
	const ACTIVITY_SITE_EVENT = 'site_event';
	const ACTIVITY_SITE_COMMUNITY = 'site_community';
	const ACTIVITY_TEAM_JOIN_INVITATION = 'team_join_invitation';
	const ACTIVITY_ACCEPT_TEAM_JOIN_INVITATION = 'accept_team_join_invitation';
	const ACTIVITY_DECLINE_TEAM_JOIN_INVITATION = 'decline_team_join_invitation';
	const ACTIVITY_REMOVED_FROM_TEAM = 'removed_from_team';
	const ACTIVITY_TEAM_CARE_REQUEST = 'care_request';
	const ACTIVITY_TEAM_CARE_REQUEST_CHANGE = 'care_request_change';
	const ACTIVITY_HEALTH_STATUS_CHANGE = 'health_status_change';
	const ACTIVITY_CREATE_TEAM = 'create_team';
	const ACTIVITY_TEAM_APPROVED = 'team_approved';
	const ACTIVITY_TEAM_DECLINED = 'team_declined';
	const ACTIVITY_TEAM_TASK_REMINDER = 'team_task_reminder';
	const ACTIVITY_TEAM_ROLE_INVITATION = 'team_role_invitation';
	const ACTIVITY_TEAM_ROLE_APPROVED = 'team_role_approved';
	const ACTIVITY_TEAM_ROLE_DECLINED = 'team_role_declined';
	const ACTIVITY_TEAM_PRIVACY_CHANGE = 'team_privacy_change';
	const ACTIVITY_TEAM_PRIVACY_CHANGE_REQUEST = 'team_privacy_change_request';
	const ACTIVITY_TEAM_PRIVACY_CHANGE_REQUEST_REJECTED = 'team_privacy_change_request_rejected';
	const ACTIVITY_FRIEND_REQUEST_APPROVED = 'friend_request_approved';
	const ACTIVITY_DEMOTE_ORGANIZER = 'demote_organizer';
	const ACTIVITY_REGISTER = 'register';
	const ACTIVITY_TEAM_JOIN_REQUEST = 'team_join_request';
	const ACTIVITY_ACCEPT_TEAM_JOIN_REQUEST = 'accept_team_join_request';
	const ACTIVITY_DECLINE_TEAM_JOIN_REQUEST = 'decline_team_join_request';
	const ACTIVITY_MEDICATION_REMINDER = 'medication_reminder';
	const ACTIVITY_QUESTION = 'question';
	const ACTIVITY_TEAM_REQUEST_CANCEL = 'team_request_cancel'; 

	/**
	 * Object types
	 */
	const OBJECT_TYPE_EVENT = 'event';
	const OBJECT_TYPE_COMMUNITY = 'community';
	const OBJECT_TYPE_PROFILE = 'profile';
	const OBJECT_TYPE_TEAM = 'team';
	const OBJECT_TYPE_POST = 'post';
	const OBJECT_TYPE_POLL_POST = 'poll_post';
	const OBJECT_TYPE_DISEASE = 'disease';

	/**
	 * Activity in types
	 */
	const ACTIVITY_IN_COMMUNITY = 'community';
	const ACTIVITY_IN_EVENT = 'event';
	const ACTIVITY_IN_PROFILE = 'profile';
	const ACTIVITY_IN_OTHER_PROFILE = 'other_profile';
	const ACTIVITY_IN_TEAM = 'team';
	const ACTIVITY_IN_DISEASE = 'disease';

	/**
	 * Currently logged in user id
	 * 
	 * @var int
	 */
	private $__currentUserId = null;

	/**
	 * Variable to hold like updated status
	 * 
	 * @var boolean
	 */
	private $__likeUpdated = false;

	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Sender' => array(
			'className' => 'User',
			'foreignKey' => 'sender_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Recipient' => array(
			'className' => 'User',
			'foreignKey' => 'recipient_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ObjectOwner' => array(
			'className' => 'User',
			'foreignKey' => 'object_owner_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	/**
	 * Function to add event invite notifications
	 * 
	 * @param array $params
	 * @return boolean Success
	 */
	public function addEventInviteNotifications($params) {
		return $this->_addEventNotification(self::ACTIVITY_INVITE, $params);
	}

	/**
	 * Function to add event update notifications
	 * 
	 * @param array $params
	 * @return boolean Success
	 */
	public function addEventUpdateNotifications($params) {
		return $this->_addEventNotification(self::ACTIVITY_UPDATE, $params);
	}

	/**
	 * Function to add event delete notifications
	 * 
	 * @param array $params
	 * @return boolean Success
	 */
	public function addEventDeleteNotifications($params) {
		return $this->_addEventNotification(self::ACTIVITY_DELETE, $params);
	}

	/**
	 * Function to add event rsvp notification
	 * 
	 * @param array $params
	 * @return boolean Success
	 */
	public function addEventRSVPNotification($params) {
		return $this->_addEventNotification(self::ACTIVITY_EVENT_RSVP, $params);
	}

	/**
	 * Function to add site wide event notification
	 * 
	 * @param array $params
	 * @return boolean Success
	 */
	public function addSiteWideEventNotification($params) {
		return $this->_addEventNotification(self::ACTIVITY_SITE_EVENT, $params);
	}

	/**
	 * Function to add event object type notification
	 * 
	 * @param string $activityType
	 * @param array $params
	 * @return boolean Success
	 */
	protected function _addEventNotification($activityType, $params) {
		$additionalInfo = array();
		$additionalParams = array('event_name', 'start_date', 'status');
		foreach ($additionalParams as $additionalParam) {
			if (isset($params[$additionalParam])) {
				$additionalInfo[$additionalParam] = $params[$additionalParam];
			}
		}

		$objectOwnerId = isset($params['event_owner_id']) ? $params['event_owner_id'] : $params['sender_id'];

		if (isset($params['recipients']) && is_array($params['recipients'])) {
			$recipients = join(',', $params['recipients']);
		} elseif (isset($params['recipient_id'])) {
			$recipients = $params['recipient_id'];
		}

		$data = array(
			'activity_type' => $activityType,
			'object_id' => $params['event_id'],
			'object_type' => self::OBJECT_TYPE_EVENT,
			'sender_id' => $params['sender_id'],
			'recipient_id' => $recipients,
			'object_owner_id' => $objectOwnerId,
			'additional_info' => json_encode($additionalInfo)
		);
		$this->create();
		return $this->save($data);
	}

	/**
	 * Function to add community invite notifications
	 * 
	 * @param array $params
	 * @return boolean Success
	 */
	public function addCommunityInviteNotifications($params) {
		return $this->_addCommunityNotification(self::ACTIVITY_INVITE, $params);
	}

	/**
	 * Function to add community join request notification to community owner
	 * 
	 * @param array $params
	 * @return boolean Success
	 */
	public function addCommunityJoinRequestNotification($params) {
		return $this->_addCommunityNotification(self::ACTIVITY_COMMUNITY_JOIN_REQUEST, $params);
	}

	/**
	 * Function to add site wide community notification
	 * 
	 * @param array $params
	 * @return boolean Success
	 */
	public function addSiteWideCommunityNotification($params) {
		return $this->_addCommunityNotification(self::ACTIVITY_SITE_COMMUNITY, $params);
	}

	/**
	 * Function to add community delete notifications
	 * 
	 * @param array $params
	 * @return boolean Success
	 */
	public function addCommunityDeleteNotifications($params) {
		return $this->_addCommunityNotification(self::ACTIVITY_DELETE, $params);
	}

	/**
	 * Function to add community object type notification
	 * 
	 * @param string $activityType
	 * @param array $params
	 * @return boolean Success
	 */
	protected function _addCommunityNotification($activityType, $params) {
		$additionalInfo = array();
		$additionalParams = array('community_name');
		foreach ($additionalParams as $additionalParam) {
			if (isset($params[$additionalParam])) {
				$additionalInfo[$additionalParam] = $params[$additionalParam];
			}
		}

		if (isset($params['recipients']) && is_array($params['recipients'])) {
			$recipients = join(',', $params['recipients']);
		} elseif (isset($params['recipient_id'])) {
			$recipients = $params['recipient_id'];
		}

		$data = array(
			'activity_type' => $activityType,
			'object_id' => $params['community_id'],
			'object_type' => self::OBJECT_TYPE_COMMUNITY,
			'sender_id' => $params['sender_id'],
			'recipient_id' => $recipients,
			'object_owner_id' => $params['sender_id'],
			'additional_info' => json_encode($additionalInfo)
		);
		$this->create();
		return $this->save($data);
	}

	/**
	 * Function to get the unread notifications of a user
	 * 
	 * @param int $userId
	 * @param int $limit
	 * @return array
	 */
	public function getUserUnreadNotifications($userId, $limit = null) {
		$userIdSearchRegExp = $this->__getListSearchRegExp($userId);
		$query = array(
			'conditions' => array(
				'recipient_id REGEXP' => $userIdSearchRegExp,
				'read_recipients NOT REGEXP' => $userIdSearchRegExp
			),
			'joins' => array(
				array('table' => 'events',
					'alias' => 'Event',
					'type' => 'LEFT',
					'conditions' => array(
						"{$this->alias}.activity_in = Event.id",
						"{$this->alias}.activity_in_type" => self::ACTIVITY_IN_EVENT,
					)
				),
				array('table' => 'communities',
					'alias' => 'Community',
					'type' => 'LEFT',
					'conditions' => array(
						"{$this->alias}.activity_in = Community.id",
						"{$this->alias}.activity_in_type" => self::ACTIVITY_IN_COMMUNITY,
					)
				),
			),
			'fields' => array(
				"{$this->alias}.*",
				"Sender.id",
				"Sender.type",
				"Sender.username",
				"ObjectOwner.gender",
				"ObjectOwner.username",
				"Recipient.username",
				"Event.name",
				"Community.name",
			),
			'order' => array("{$this->alias}.modified DESC")
		);

		if (!is_null($limit)) {
			$query['limit'] = $limit;
		}

		return $this->find('all', $query);
	}

	/**
	 * Function to get the count of unread notifications of a user
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getUserUnreadNotificationsCount($userId) {
		$this->recursive = -1;
		$userIdSearchRegExp = $this->__getListSearchRegExp($userId);
		$query = array(
			'conditions' => array(
				'recipient_id REGEXP' => $userIdSearchRegExp,
				'read_recipients NOT REGEXP' => $userIdSearchRegExp
			),
		);
		return $this->find('count', $query);
	}

	/**
	 * Function to get the content for the notification
	 * 
	 * @param array $notificationData
	 * @param array $currentUser
	 * @return string
	 */
	public function getContent($notificationData, $currentUser) {
		$this->__currentUserId = $currentUser['id'];
		$notification = $notificationData['Notification'];
		if (!is_null($notification['additional_info'])) {
			$additionalInfo = json_decode($notification['additional_info'], true);
			if (isset($additionalInfo['event_name'])) {
				$eventName = h($additionalInfo['event_name']);
			}
			if (isset($additionalInfo['community_name'])) {
				$communityName = h($additionalInfo['community_name']);
			}
			if (isset($additionalInfo['team_name'])) {
				$teamName = h($additionalInfo['team_name']);
			}
			if (isset($additionalInfo['disease_name'])) {
				$diseaseName = h($additionalInfo['disease_name']);
			}
			if (isset($additionalInfo['profile_username'])) {
				$profileName = h($additionalInfo['profile_username']);
			}
		}

		if ($notification['object_type'] === self::OBJECT_TYPE_EVENT) {
			$href = sprintf('/event/details/index/%d', $notification['object_id']);
		} elseif ($notification['object_type'] === self::OBJECT_TYPE_COMMUNITY) {
			$href = sprintf('/community/details/index/%d', $notification['object_id']);
		} elseif ($notification['object_type'] === self::OBJECT_TYPE_PROFILE) {
			$href = Common::getUserProfileLink($notificationData['Recipient']['username'], TRUE);
		} elseif (($notification['object_type'] === self::OBJECT_TYPE_POST) || ($notification['object_type'] === self::OBJECT_TYPE_POLL_POST)) {
			$href = sprintf('/post/details/index/%d', $notification['object_id']);
		} elseif ($notification['object_type'] === self::OBJECT_TYPE_TEAM) {
			$href = sprintf('/myteam/%d', $notification['object_id']);
		} elseif ($notification['object_type'] === self::OBJECT_TYPE_DISEASE) {
			$href = sprintf('/condition/index/%d', $notification['object_id']);
		}

		$content = '';
		switch ($notification['activity_type']) {
			case self::ACTIVITY_INVITE:
				$text = __('invited you to the ');
				if ($notification['object_type'] === self::OBJECT_TYPE_EVENT) {
					$text .= __('event "%s"', $eventName);
				} elseif ($notification['object_type'] === self::OBJECT_TYPE_COMMUNITY) {
					$text .= __('community "%s"', $communityName);
				}
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_UPDATE:
				$content = array(
					'text' => __('updated the event "%s"', $eventName),
					'href' => $href
				);
				break;
			case self::ACTIVITY_DELETE:
				$text = __('deleted the ');
				if ($notification['object_type'] === self::OBJECT_TYPE_EVENT) {
					$text .= __('event "%s"', $eventName);
					$url = '/notification/notification/markEventNotificationRead/%d';
				} elseif ($notification['object_type'] === self::OBJECT_TYPE_COMMUNITY) {
					$text .= __('community "%s"', $communityName);
					$url = '/notification/notification/markCommunityNotificationRead/%d';
				}

				$content = array(
					'text' => $text,
					'href' => sprintf($url, $notification['object_id']),
					'disable' => true
				);
				break;
			case self::ACTIVITY_REMINDER:
				$eventStartDateTime = $additionalInfo['start_date'];
				$timezone = $currentUser['timezone'];
				$eventTime = CakeTime::nice($eventStartDateTime, $timezone, '%l:%M %p');
				$content = array(
					'text' => __('Event today at %s: %s', $eventTime, $eventName),
					'href' => $href
				);
				break;
			case self::ACTIVITY_EVENT_RSVP:
				$status = $additionalInfo['status'];
				$content = array(
					'text' => __('updated RSVP for the event "%s" as %s', $eventName, $status),
					'href' => $href
				);
				break;
			case self::ACTIVITY_COMMUNITY_JOIN_REQUEST:
				$href.='/members';
				$content = array(
					'text' => __('wants to join your community "%s"', $communityName),
					'href' => $href
				);
				break;
			case self::ACTIVITY_POST:
				if (isset($additionalInfo['post_type'])) {
					$postType = $additionalInfo['post_type'];
					$this->Post = ClassRegistry::init('Post');
					$postTypeText = ($postType === Post::POST_TYPE_POLL) ? 'poll' : 'post';
					$postTypeText = ($postType === Post::POST_TYPE_ECARD) ? 'e-Card' : 'post';
				} else {
					$postTypeText = 'post';
				}
				$text = __('added a %s ', $postTypeText);
				if(isset($additionalInfo['post_type']) && $additionalInfo['post_type'] == Post::POST_TYPE_ECARD) {
					$text = __('added an %s ', $postTypeText);
				}
				if ($notification['activity_in_type'] === self::ACTIVITY_IN_EVENT) {
					$text .= __('to the "%s" event', $eventName);
				} elseif ($notification['activity_in_type'] === self::ACTIVITY_IN_COMMUNITY) {
					$text .= __('to the "%s" community', $communityName);
				} elseif ($notification['activity_in_type'] === self::ACTIVITY_IN_PROFILE) {
					$text .= __('on your profile');
				} elseif ($notification['activity_in_type'] === self::ACTIVITY_IN_TEAM) {
					$text .= __('to "%s" team', $teamName);
					$href.='/discussion';
				} elseif ($notification['activity_in_type'] === self::ACTIVITY_IN_DISEASE) {
					$text .= __('to the "%s" disease', $diseaseName);
				} elseif ($notification['activity_in_type'] === self::ACTIVITY_IN_OTHER_PROFILE) {
					$text .= __('on profile of "%s"', $profileName);
					$href = Common::getUserProfileLink($profileName, TRUE);
				}

				$postJSON = $additionalInfo['post'];
				$post = json_decode($postJSON, true);
				if (isset($post['title']) && $post['title'] !='') {
					$postText = h(String::truncate($post['title'], 80, array('exact' => false)));
					$text .= __(': "%s"', $postText);
				} elseif (isset($post['description'])) {
					$postText = h(String::truncate($post['description'], 80, array('exact' => false)));
					$text .= __(': "%s"', $postText);
				}

				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_QUESTION:
				$question = h($additionalInfo['question']);
				$text = __('asked a question in %s: %s', $diseaseName, $question);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_ANSWERED_QUESTION:
				$possessivePronoun = $this->__getPossessivePronoun($notificationData);
				$answerText = h(String::truncate($additionalInfo['answer'], 80, array('exact' => false)));
				$text = __('answered "%s" to %s question "%s"', $answerText, $possessivePronoun, h($additionalInfo['question']));
				if (!empty($additionalInfo['disease_name'])) {
					$text .= __(' in "%s"', $additionalInfo['disease_name']);
				}
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_COMMENT:
				$comment = $additionalInfo['comment'];
				$possessivePronoun = $this->__getPossessivePronoun($notificationData);
				$text = __('commented on %s post', $possessivePronoun);

				if ($notification['activity_in_type'] === self::ACTIVITY_IN_EVENT) {
					if (!empty($notificationData['Event'])) {
						$eventName = $notificationData['Event']['name'];
						$text .= __(' in %s event', $eventName);
					}
				} elseif ($notification['activity_in_type'] === self::ACTIVITY_IN_COMMUNITY) {
					if (!empty($notificationData['Community'])) {
						$communityName = $notificationData['Community']['name'];
						$text .= __(' in %s community', $communityName);
					}
				} elseif ($notification['activity_in_type'] === self::ACTIVITY_IN_TEAM) {
					if (!empty($notificationData['Team'])) {
						$teamName = $notificationData['Team']['name'];
						$text .= __(' in %s team', $teamName);
					}
				}

				$commentText = h(String::truncate($comment, 80, array('exact' => false)));
				$text .= __(': "%s"', $commentText);

				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_LIKE:
				$post = $additionalInfo['post'];
				$text = $this->__getLikedUsersText($post['liked_users_list'], $notification['sender_id']);
				$possessivePronoun = $this->__getPossessivePronoun($notificationData);
				$text .= __('likes %s post', $possessivePronoun);

				if ($notification['activity_in_type'] === self::ACTIVITY_IN_EVENT) {
					if (!empty($notificationData['Event'])) {
						$eventName = $notificationData['Event']['name'];
						$text .= __(' in %s event', $eventName);
					}
				} elseif ($notification['activity_in_type'] === self::ACTIVITY_IN_COMMUNITY) {
					if (!empty($notificationData['Community'])) {
						$communityName = $notificationData['Community']['name'];
						$text .= __(' in %s community', $communityName);
					}
				} elseif ($notification['activity_in_type'] === self::ACTIVITY_IN_TEAM) {
					if (!empty($notificationData['Team'])) {
						$teamName = $notificationData['Team']['name'];
						$text .= __(' in %s team', $teamName);
					}
				}

				if (isset($post['description'])) {
					$postText = h(String::truncate($post['description'], 80, array('exact' => false)));
					$text .= __(': "%s"', $postText);
				}

				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_SITE_EVENT:
				$text = __('added an event "%s"', $eventName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_SITE_COMMUNITY:
				$text = __('added a community "%s"', $communityName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_ACCEPT_TEAM_JOIN_INVITATION:
				$text = __('has accepted the invitation to join the team "%s"', $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_DECLINE_TEAM_JOIN_INVITATION:
				$text = __('has declined the invitation to join the team "%s"', $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_JOIN_INVITATION:
				$text = __('has invited you to join the team "%s"', $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_REMOVED_FROM_TEAM:
				$text = __('has removed you from the team "%s"', $teamName);
				if (!empty($additionalInfo['reason'])) {
					$text .= __('. Reason: %s', $additionalInfo['reason']);
				}
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_CARE_REQUEST:
				$careType = $additionalInfo['care_type'];
				$text = __('has requested for care "%s" in the team "%s"', $careType, $teamName);
				$taskId = $notification['activity_id'];
				$href .="/task/{$taskId}";
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_CARE_REQUEST_CHANGE:
				$careType = $additionalInfo['care_type'];
				$text = __('has changed the request for care "%s" in the team "%s"', $careType, $teamName);
				$taskId = $notification['activity_id'];
				$href .="/task/{$taskId}";
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_HEALTH_STATUS_CHANGE:
				$healthStatus = $additionalInfo['health_status'];
				$newHealthStatus = $additionalInfo['new_health_status'];
				App::uses('HealthStatus', 'Utility');
				$text = __('of ');
				if (isset($additionalInfo['team_name'])) {
					$teamName = $additionalInfo['team_name'];
					$text.= __('team "%s"', h($teamName));
				} elseif (isset($additionalInfo['team_names'])) {
					$teamNamesList = $additionalInfo['team_names'];
					$teamNamesText = $this->__getTeamNamesDisplayText($teamNamesList);
					$text .= __('teams %s', h($teamNamesText));
				}
				$text .= __(' has changed the health status ');
				if (!empty($healthStatus)) {
					$statusText = HealthStatus::getHealthStatusText($healthStatus);
					$text .= __('from "%s" ', $statusText);
				}
				$newStatusText = HealthStatus::getHealthStatusText($newHealthStatus);
				$text .= __('to "%s"', $newStatusText);
				$href = Common::getUserProfileLink($notificationData['Sender']['username'], TRUE);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_CREATE_TEAM:
				$text = __('has created a team "%s" to support you', $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_APPROVED:
				$text = __('has approved the team "%s"', $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_DECLINED:
				$text = __('has declined the team "%s"', $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_TASK_REMINDER:
				$task = $additionalInfo['task'];
				$taskStartDateTime = $task['start_date'];
				$timezone = $currentUser['timezone'];
				$taskTime = CakeTime::nice($taskStartDateTime, $timezone, '%l:%M %p');
				$text = __('Task "%s" of type "%s" today at %s in team "%s"', h($task['name']), $task['type'], $taskTime, $teamName);
				$href.='/calendar';
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_ROLE_APPROVED:
				$roleName = $additionalInfo['role_name'];
				$text = __('has approved the role "%s" in team "%s"', $roleName, $teamName);
				$href.='/members';
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_ROLE_DECLINED:
				$roleName = $additionalInfo['role_name'];
				$text = __('has declined the role "%s" in team "%s"', $roleName, $teamName);
				$href.='/members';
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_ROLE_INVITATION:
				$roleName = $additionalInfo['role_name'];
				$text = __('has invited you to the role "%s" in team "%s"', $roleName, $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_PRIVACY_CHANGE:
				$oldPrivacy = $additionalInfo['old_privacy'];
				$newPrivacy = $additionalInfo['new_privacy'];
				$text = __('has changed the privacy of the team "%s" from "%s" to "%s"', $teamName, $oldPrivacy, $newPrivacy);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_PRIVACY_CHANGE_REQUEST:
				$oldPrivacy = $additionalInfo['old_privacy'];
				$newPrivacy = $additionalInfo['new_privacy'];
				$text = __('has requested to change the privacy of the team "%s" from "%s" to "%s"', $teamName, $oldPrivacy, $newPrivacy);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_PRIVACY_CHANGE_REQUEST_REJECTED:
				$oldPrivacy = $additionalInfo['old_privacy'];
				$newPrivacy = $additionalInfo['new_privacy'];
				$text = __('has rejected your request to change the privacy of the team "%s" from "%s" to "%s"', $teamName, $oldPrivacy, $newPrivacy);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_TEAM_JOIN_REQUEST:
				$text = __('has requested to join the team "%s"', $teamName);
				$href.='/members';
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_ACCEPT_TEAM_JOIN_REQUEST:
				$text = __('has accepted your request to join the team "%s"', $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_DECLINE_TEAM_JOIN_REQUEST:
				$text = __('has declined your request to join the team "%s"', $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_FRIEND_REQUEST_APPROVED:
				$text = __('has approved your friend request');
				$href = Common::getUserProfileLink($notificationData['Sender']['username'], true);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_DEMOTE_ORGANIZER:
				$text = __('has removed your organizer privilege from the team "%s"', $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
			case self::ACTIVITY_REGISTER:
				$text = __('Congrats for signing up to '. Configure::read ( 'App.name' ) .'. Please check your inbox for the activation link which will expire in 24 hours.');
				$url = '/notification/notification/markNotificationRead/%d';
				$content = array(
					'text' => $text,
					'href' => sprintf($url, $notification['id']),
					'disable' => true
				);
				break;
			case self::ACTIVITY_MEDICATION_REMINDER:
				$medicationName = $additionalInfo['name'];
				$text = __('Reminder: Take "%s"', $medicationName);
				$url = '/notification/notification/markNotificationRead/%d';
				$content = array(
					'text' => $text,
					'href' => sprintf($url, $notification['id']),
					'popup' => true,
					'activity_type' => $notification['activity_type'],
					'additional_info' => $additionalInfo,
					'icon' => '<img src="/theme/App/img/reminder_icon.png" class="user_x_small_thumb media-object" />'
				);
				break;
			case self::ACTIVITY_TEAM_REQUEST_CANCEL:
				$text = __('has cancelled the team "%s"', $teamName);
				$content = array(
					'text' => $text,
					'href' => $href
				);
				break;
		}

		// check if user has read the notification
		$readRecipients = $notification['read_recipients'];
		$readRecipientsArr = array();
		if (strpos($readRecipients, ',') !== false) {
			$readRecipientsArr = explode(',', $readRecipients);
		} else {
			$readRecipientsArr[] = $readRecipients;
		}
		$isRead = (!empty($readRecipientsArr) && in_array($this->__currentUserId, $readRecipientsArr)) ? true : false;
		$readClass = ($isRead === true) ? 'read' : 'unread';
		$classList[] = $readClass;
		if (isset($content['disable'])) {
			$classList[] = 'disabled';
		}
		if (isset($content['popup'])) {
			$classList[] = 'popup';
		}
		$content['class'] = join(' ', $classList);
		return $content;
	}

	/**
	 * Function to get the notification display text for a list of team names
	 * 
	 * @param array $teamNamesList
	 * @return string
	 */
	private function __getTeamNamesDisplayText($teamNamesList) {
		$maxLength = 100;
		$teamNamesCount = count($teamNamesList);
		$lastIndex = $teamNamesCount - 1;
		$teamNamesStr = '';
		$includedNamesCount = 0;
		foreach ($teamNamesList as $index => $teamName) {
			$nameLength = strlen($teamName);
			$namesLength = strlen($teamNamesStr);
			if ($namesLength + $nameLength <= $maxLength) {
				if ($index > 0) {
					$teamNamesStr .=($teamNamesCount > 2) ? ', ' : ' ';
					if ($index === $lastIndex) {
						$teamNamesStr .= 'and ';
					}
				}
				$teamNamesStr.='"' . $teamName . '"';
				$includedNamesCount++;
			} else {
				break;
			}
		}
		if ($includedNamesCount < $teamNamesCount) {
			$teamNamesStr.=', etc.';
		}
		return $teamNamesStr;
	}

	/**
	 * Function to get liked users text
	 * 
	 * @param string $likedUsersList
	 * @param int $senderId
	 * @return string
	 */
	private function __getLikedUsersText($likedUsersList, $senderId) {
		$text = '';
		$currentUserId = $this->__currentUserId;
		if (strpos($likedUsersList, ',') !== false) {
			$likedUsers = explode(',', $likedUsersList);

			// remove the current user from the list
			$currentUserKey = array_search($currentUserId, $likedUsers);
			if ($currentUserKey !== false) {
				unset($likedUsers[$currentUserKey]);
				$likedUsers = array_values($likedUsers);
			}

			// remove the sender id from the list
			$senderKey = array_search($senderId, $likedUsers);
			if ($senderKey !== false) {
				unset($likedUsers[$senderKey]);
				$likedUsers = array_values($likedUsers);
			}

			// prepare the text
			if (!empty($likedUsers)) {
				$lastLikedUserId = end($likedUsers);
				$this->User = ClassRegistry::init('User');
				$lastLikedUser = $this->User->getUserDetails($lastLikedUserId);
				$lastLikedUserName = $lastLikedUser['user_name'];
				$likedUsersCount = count($likedUsers);
				if ($likedUsersCount === 1) {
					$text = __('and %s', $lastLikedUserName);
				} else {
					$otherLikedUsersCount = $likedUsersCount - 1;
					$othersText = __n('other', 'others', $otherLikedUsersCount);
					$text = __(', %s and %d %s', $lastLikedUserName, $otherLikedUsersCount, $othersText);
				}
				$text .= ' ';
			}
		}

		return $text;
	}

	/**
	 * Function to get the possessive pronoun to be displayed in the notification
	 * 
	 * @param array $notificationData
	 * @return string
	 */
	private function __getPossessivePronoun($notificationData) {
		$notification = $notificationData['Notification'];
		$objectOwner = $notificationData['ObjectOwner'];

		$isAnonymousObject = false;
		if (!empty($notification['additional_info'])) {
			$additionalInfo = json_decode($notification['additional_info'], true);
			if (isset($additionalInfo['is_anonymous_object']) && $additionalInfo['is_anonymous_object'] === true) {
				$isAnonymousObject = true;
			}
		}

		if ($notification['object_owner_id'] === $this->__currentUserId) {
			$possessivePronoun = __('your');
		} else {
			if ($isAnonymousObject === true || $notification['is_anonymous'] === true) {
				$possessivePronoun = 'an anonymous';
			} else {
				if ($notification['object_owner_id'] === $notification['sender_id']) {
					$possessivePronoun = ($objectOwner['gender'] === 'F') ? 'her' : 'his';
				} else {
					$possessivePronoun = __("%s's", $objectOwner['username']);
				}
			}
		}

		return $possessivePronoun;
	}

	/**
	 * Function to mark the notifications related to an event as read by a user
	 * 
	 * @param int $eventId
	 * @param int $userId
	 */
	public function markEventNotificationsReadByUser($eventId, $userId) {
		$objectId = $eventId;
		$objectType = self::OBJECT_TYPE_EVENT;
		$this->markObjectNotificationsReadByUser($objectId, $objectType, $userId);
	}

	/**
	 * Function to mark the notifications related to a community as read by a user
	 * 
	 * @param int $communityId
	 * @param int $userId
	 */
	public function markCommunityNotificationsReadByUser($communityId, $userId) {
		$objectId = $communityId;
		$objectType = self::OBJECT_TYPE_COMMUNITY;
		$this->markObjectNotificationsReadByUser($objectId, $objectType, $userId);
	}

	/**
	 * Function to mark the notifications related to a team as read by the user
	 * 
	 * @param int $communityId
	 * @param int $userId
	 */
	public function markTeamNotificationsReadByUser($teamId, $userId) {
		$objectId = $teamId;
		$objectType = self::OBJECT_TYPE_TEAM;
		$this->markObjectNotificationsReadByUser($objectId, $objectType, $userId);
	}

	/**
	 * Function to mark the notifications related to the profile as read by the user
	 * 
	 * @param int $profileId
	 * @param int $userId
	 */
	public function markProfileNotificationsReadByUser($profileId, $userId) {
		$objectId = $profileId;
		$objectType = self::OBJECT_TYPE_PROFILE;
		$this->markObjectNotificationsReadByUser($objectId, $objectType, $userId);
	}

	/**
	 * Function to mark the notifications related to a post as read by a user
	 * 
	 * @param int $postId
	 * @param int $userId
	 */
	public function markPostNotificationsReadByUser($postId, $userId) {
		$userIdSearchRegExp = $this->__getListSearchRegExp($userId);
		$conditions = array(
			'AND' => array(
				array(
					'recipient_id REGEXP' => $userIdSearchRegExp,
					'read_recipients NOT REGEXP' => $userIdSearchRegExp
				),
				array(
					'OR' => array(
						array(
							'object_id' => $postId,
							'object_type' => array(
								self::OBJECT_TYPE_POST,
								self::OBJECT_TYPE_POLL_POST,
							)
						),
						array(
							'activity_id' => $postId,
							'activity_type' => self::ACTIVITY_POST,
						)
					)
				)
			)
		);
		$query = array('conditions' => $conditions);
		$this->recursive = -1;
		$postNotifications = $this->find('all', $query);
		$this->_markNotificationsReadByUser($postNotifications, $userId);
		$this->decrementUserNotificationsCount($postNotifications, $userId);
	}

	/**
	 * Function to decrement the notification count of a user
	 * 
	 * Decrements the count of notifications that came after the user viewed 
	 * the notification count.
	 * 
	 * @param array $notifications
	 * @param int $userId 
	 */
	public function decrementUserNotificationsCount($notifications, $userId) {
		$this->NotificationSetting = ClassRegistry::init('NotificationSetting');

		$notificationSetting = $this->NotificationSetting->findByUserId($userId);
		if (!empty($notificationSetting)) {
			$lastViewed = $notificationSetting['NotificationSetting']['notification_last_viewed'];
			$lastViewedTimeStamp = strtotime($lastViewed);
			$count = $notificationSetting['NotificationSetting']['notification_count'];
			$countChanged = false;
			foreach ($notifications as $notification) {
				$modified = $notification['Notification']['modified'];
				$modifiedTimeStamp = strtotime($modified);
				if ($modifiedTimeStamp > $lastViewedTimeStamp) {
					$count = $count - 1;
					$countChanged = true;
				}
			}

			if ($countChanged === true) {
				$data = array(
					'id' => $notificationSetting['NotificationSetting']['id'],
					'notification_count' => $count
				);
				if ($this->NotificationSetting->save($data, false)) {
					$data['user_id'] = $userId;
					$this->__realTimeNotifyUser($data);
				}
			}
		}
	}

	/**
	 * Function to mark the notifications of an object as read by a user
	 * 
	 * @param int $objectId
	 * @param int $objectType
	 * @param int $userId
	 */
	public function markObjectNotificationsReadByUser($objectId, $objectType, $userId) {
		$userIdSearchRegExp = $this->__getListSearchRegExp($userId);
		$query = array(
			'conditions' => array(
				'object_id' => $objectId,
				'object_type' => $objectType,
				'recipient_id REGEXP' => $userIdSearchRegExp,
				'read_recipients NOT REGEXP' => $userIdSearchRegExp
			)
		);
		$this->recursive = -1;
		$objectNotifications = $this->find('all', $query);
		$this->_markNotificationsReadByUser($objectNotifications, $userId);
		$this->decrementUserNotificationsCount($objectNotifications, $userId);
	}

	private function _markNotificationsReadByUser($notifications, $userId) {
		if (!empty($notifications)) {
			$notificationData = array();
			foreach ($notifications as $notificationObject) {
				$notification = $notificationObject['Notification'];
				$notificationId = $notification['id'];
				$readRecipients = $notification['read_recipients'];
				$readRecipientsArr = array();
				if ($readRecipients === '') {
					$readRecipientsArr = array();
				} elseif (strpos($readRecipients, ',') !== false) {
					$readRecipientsArr = explode(',', $readRecipients);
				} else {
					$readRecipientsArr[] = $readRecipients;
				}

				// if not already marked as read, add the user to the
				// notification read recipients list for the notification
				if (!in_array($userId, $readRecipientsArr)) {
					$readRecipientsArr[] = $userId;
					if (count($readRecipientsArr) > 1) {
						$readRecipients = join(',', $readRecipientsArr);
					} else {
						$readRecipients = $readRecipientsArr[0];
					}
					$notificationData[] = array(
						'id' => $notificationId,
						'read_recipients' => $readRecipients,
						'modified' => false
					);
				}
			}

			if (!empty($notificationData)) {
				$this->saveMany($notificationData);
			}
		}
	}

	/**
	 * Function to add event reminder notifications for an event
	 * 
	 * @param int $userId
	 * @param array $events
	 */
	public function addEventReminderNotifications($event, $userIds) {
		if (!empty($event) && !empty($userIds)) {
			$activityType = self::ACTIVITY_REMINDER;
			foreach ($userIds as $userId) {
				$params = array(
					'event_id' => $event['id'],
					'event_name' => $event['name'],
					'start_date' => $event['start_date'],
					'sender_id' => $event['created_by'],
					'recipient_id' => $userId
				);
				$this->_addEventNotification($activityType, $params);
			}
		}
	}

	/**
	 * Function to add notifications when a vote is made for a poll
	 * 
	 * @param array $pollData
	 * @param array $postData
	 * @param array $pollVoteData
	 * @return boolean Success
	 */
	public function addPollVoteNotifications($pollData, $postData, $pollVoteData) {
		$poll = $pollData['Poll'];
		$votedUserId = $pollVoteData['user_id'];
		$recipientsList = $this->_getPollVoteNotificationRecipients($poll, $votedUserId);
		if (!empty($recipientsList)) {
			$recipients = join(',', $recipientsList);
			$post = $postData['Post'];
			$postId = $post['id'];
			$question = $poll['title'];
			$selectedOptionId = $pollVoteData['choice_id'];
			$pollChoices = $pollData['PollChoices'];
			foreach ($pollChoices as $pollChoice) {
				if ($pollChoice['id'] === $selectedOptionId) {
					$answer = $pollChoice['option'];
					break;
				}
			}
			$additionalInfo = array(
				'question' => $question,
				'answer' => $answer,
				'is_anonymous_object' => $post['is_anonymous']
			);

			$data = array(
				'activity_type' => self::ACTIVITY_ANSWERED_QUESTION,
				'object_id' => $postId,
				'object_type' => self::OBJECT_TYPE_POLL_POST,
				'object_owner_id' => $post['post_by'],
				'sender_id' => $votedUserId,
				'recipient_id' => $recipients,
				'additional_info' => json_encode($additionalInfo)
			);
			$this->create();
			$success = $this->save($data);

			// send email notifications
			$additionalInfo['post'] = $post;
			$additionalInfo['votedUserId'] = $votedUserId;
			$this->__addPollVoteEmailNotifications($recipientsList, $additionalInfo);

			return $success;
		} else {
			return true;
		}
	}

	private function __addPollVoteEmailNotifications($recipientList, $additionalInfo) {
		$post = $additionalInfo['post'];
		$postedUserId = $post['post_by'];

		if (isset($this->__pollCommunity) && !empty($this->__pollCommunity)) {
			$community = $this->__pollCommunity;
			$emailRecipients = $this->__getCommunityActivityEmailNotificationRecepientList($community, $recipientList);
		} elseif (isset($this->__pollTeam) && !empty($this->__pollTeam)) {
			$team = $this->__pollTeam;
			$emailRecipients = $this->__getTeamActivityEmailNotificationRecepientList($team, $recipientList);
		} else {
			$emailRecipients = $this->__getPollVoteEmailNotificationRecepientList($postedUserId, $recipientList);
		}

		if (!empty($emailRecipients)) {
			$Api = new ApiController;
			$Api->constructClasses();
			$votedUserId = $additionalInfo['votedUserId'];
			$senderUser = $this->User->getUserDetails($votedUserId);
			$senderUserName = $senderUser['user_name'];
			$postedUser = $this->User->getUserDetails($postedUserId);
			$postedUserName = $postedUser['user_name'];
			$postLink = Router::Url('/', TRUE) . "post/details/index/" . $post['id'];
			$question = $additionalInfo['question'];
			$answer = $additionalInfo['answer'];

			$isAnonymousPoll = false;
			if (isset($additionalInfo['is_anonymous_object']) && $additionalInfo['is_anonymous_object'] === true) {
				$isAnonymousPoll = true;
			}

			foreach ($emailRecipients as $receiverUser) {
				if (!empty($receiverUser['User'])) {
					if ($postedUserId === $receiverUser['User']['id']) {
						$possessivePronoun = __('your');
					} else {
						if ($post['is_anonymous'] === true) {
							$possessivePronoun = 'an anonymous';
						} else {
							if ($postedUserId === $votedUserId) {
								$possessivePronoun = ($postedUser['gender'] === 'F') ? 'her' : 'his';
							} else {
								$possessivePronoun = __("%s's", $postedUserName);
							}
						}
					}
				}
				$receiverUserName = $receiverUser['User']['username'];
				$toEmail = $receiverUser['User']['email'];
				$emailData = array(
					'username' => $receiverUserName,
					'name' => $senderUserName,
					'poll_user' => $possessivePronoun,
					'question' => $question,
					'answer' => $answer,
					'post_link' => $postLink
				);

				$Api->sendHTMLMail(EmailTemplateComponent::POLL_VOTE_NOTIFICATION, $emailData, $toEmail);
			}
		}
	}

	private function __getPollVoteEmailNotificationRecepientList($postedUserId, $recipientList) {
		$emailRecipients = array();
		$this->User = ClassRegistry::init('User');
		$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
		foreach ($recipientList as $recipientId) {
			$settingName = ($postedUserId === $recipientId) ? 'answered_my_question' : 'answered_same_question';
			$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($recipientId, $settingName);
			if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
				$receiverUser = $this->User->findById($recipientId);
				$emailRecipients[] = $receiverUser;
			}
		}

		return $emailRecipients;
	}

	/**
	 * Function to get the recipients of poll vote notification
	 * 
	 * @param array $poll
	 * @param int $votedUserId
	 * @return array
	 */
	protected function _getPollVoteNotificationRecipients($poll, $votedUserId) {
		$recipients = array();

		$this->Post = ClassRegistry::init('Post');
		if ($poll['posted_in_type'] === Post::POSTED_IN_TYPE_COMMUNITIES) {
			$communityId = $poll['posted_in'];
			$this->Community = ClassRegistry::init('Community');
			$this->Community->recursive = -1;
			$community = $this->Community->getCommunity($communityId);
			$this->__pollCommunity = $community;
			$recipients = $this->__getCommunityPostNotificationRecepientList($community);
		} elseif ($poll['posted_in_type'] === Post::POSTED_IN_TYPE_TEAM) {
			$teamId = $poll['posted_in'];
			$this->Team = ClassRegistry::init('Team');
			$team = $this->Team->getTeam($teamId);
			$this->__pollTeam = $team;
			$recipients = $this->__getTeamPostNotificationRecepientList($team);
		} else {
			// add the poll created user to the recipients list
			$recipients[] = $poll['created_by'];

			// add already voted users to the recipients list
			$pollId = $poll['id'];
			$this->PollVote = ClassRegistry::init('PollVote');
			$pollVotes = $this->PollVote->getPollVoteDetails($pollId);
			if (!empty($pollVotes)) {
				foreach ($pollVotes as $pollVoteData) {
					$pollVote = $pollVoteData['PollVote'];
					$recipients[] = $pollVote['user_id'];
				}
			}
		}

		// remove the currently voted user from the recipients list
		$recipients = $this->__filterRecipientsList($recipients, $votedUserId);

		return $recipients;
	}

	/**
	 * Function to add notifications for a post
	 * 
	 * @param type $post
	 * @return boolean Success
	 */
	public function addPostNotifications($post) {
		$this->Post = ClassRegistry::init('Post');
		switch ($post['posted_in_type']) {
			case Post::POSTED_IN_TYPE_EVENTS:
				$success = $this->_addEventPostNotifications($post);
				break;
			case Post::POSTED_IN_TYPE_COMMUNITIES:
				$success = $this->_addCommunityPostNotifications($post);
				break;
			case Post::POSTED_IN_TYPE_USERS:
				$success = $this->_addProfilePostNotification($post);
				$this->_addProfilePostFollowerNotification($post);
				break;
			case Post::POSTED_IN_TYPE_TEAM:
				$success = $this->_addTeamPostNotifications($post);
				break;
			case Post::POSTED_IN_TYPE_DISEASES:
				$success = $this->_addDiseasePostNotifications($post);
				break;
			default :
				$success = true;
		}

		return $success;
	}

	/**
	 * Function to get team post notification recepient list 
	 * 
	 * @param array $team
	 * @return array
	 */
	private function __getTeamPostNotificationRecepientList($team) {
		$recipientList = array();

		$TeamMember = ClassRegistry::init('TeamMember');
		$teamMembers = $TeamMember->getApprovedTeamMemberIds($team['id']);
		$recipientList = array_values($teamMembers);

		return $recipientList;
	}

	/**
	 * Function to add team post notifications
	 * 
	 * @param array $post
	 * @return boolean
	 */
	protected function _addTeamPostNotifications($post) {
		$teamId = $post['posted_in'];
		$this->Team = ClassRegistry::init('Team');
		$team = $this->Team->getTeam($teamId);
		$postedUserId = $post['post_by'];
		$recipientList = $this->__getTeamPostNotificationRecepientList($team);
		$recipientList = $this->__filterRecipientsList($recipientList, $postedUserId);
		if (!empty($recipientList)) {
			// add site notifications
			$recipients = join(',', $recipientList);
			$teamOwnerId = $team['created_by'];
			$additionalInfo = array(
				'post' => $post['content'],
				'post_type' => $post['post_type'],
				'team_name' => $team['name']
			);
			$data = array(
				'activity_type' => self::ACTIVITY_POST,
				'activity_id' => $post['id'],
				'object_id' => $teamId,
				'object_type' => self::OBJECT_TYPE_TEAM,
				'sender_id' => $postedUserId,
				'recipient_id' => $recipients,
				'activity_in' => $teamId,
				'activity_in_type' => self::ACTIVITY_IN_TEAM,
				'object_owner_id' => $teamOwnerId,
				'additional_info' => json_encode($additionalInfo),
				'is_anonymous' => $post['is_anonymous']
			);
			$this->create();
			$success = $this->save($data);

			// add email notifications
			$this->__addTeamPostEmailNotifications($recipientList, $post, $team);

			return $success;
		} else {
			return true;
		}
	}

	/**
	 * Function to add team post email notifications
	 * 
	 * @param array $recipientList
	 * @param array $post
	 * @param array $team
	 */
	private function __addTeamPostEmailNotifications($recipientList, $post, $team) {
		$emailRecipients = $this->__getTeamActivityEmailNotificationRecepientList($team, $recipientList);
		if (!empty($emailRecipients)) {
			$Api = new ApiController;
			$Api->constructClasses();
			$postedUserId = $post['post_by'];

			if ($post['is_anonymous'] === true) {
				$senderUserName = Common::getAnonymousUsername();
			} else {
				$senderUser = $this->User->getUserDetails($postedUserId);
				$senderUserName = $senderUser['user_name'];
			}

			$postLink = Router::Url('/', TRUE) . "post/details/index/" . $post['id'];
			$link = Router::Url('/', TRUE) . "myteam/" . $team['id'] . '/discussion';
			$linkText = 'See Team';
			$postType = $post['post_type'];
			$this->Post = ClassRegistry::init('Post');
			$postTypeText = ($postType === Post::POST_TYPE_POLL) ? 'poll' : 'post';
			$content = __('added a %s to the "%s" team.', $postTypeText, $team['name']);
			$postLinkText = __('See %s', $postTypeText);
			foreach ($emailRecipients as $receiverUser) {
				$receiverUserName = $receiverUser['User']['username'];
				$toEmail = $receiverUser['User']['email'];
				$emailData = array(
					'username' => $receiverUserName,
					'name' => $senderUserName,
					'content' => $content,
					'post_link' => $postLink,
					'post_link_text' => $postLinkText,
					'post_type' => $postTypeText,
					'link' => $link,
					'link_text' => $linkText
				);

				$Api->sendHTMLMail(EmailTemplateComponent::POST_NOTIFICATION, $emailData, $toEmail);
			}
		}
	}

	/**
	 * Function to get team activity email notification recepient list
	 * 
	 * @param array $team
	 * @param array $recipientList
	 * @return array
	 */
	private function __getTeamActivityEmailNotificationRecepientList($team, $recipientList) {
		$emailRecipients = array();
		$this->User = ClassRegistry::init('User');
		$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
		foreach ($recipientList as $recipientId) {
			$isEmailNotificationOn = true;
			if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
				$receiverUser = $this->User->findById($recipientId);
				$emailRecipients[] = $receiverUser;
			}
		}

		return $emailRecipients;
	}

	protected function _addEventPostNotifications($post) {
		$eventId = $post['posted_in'];
		$this->Event = ClassRegistry::init('Event');
		$this->Event->recursive = -1;
		$event = $this->Event->getEvent($eventId);
		$postedUserId = $post['post_by'];
		$recipientList = $this->__getEventPostNotificationRecepientList($event);
		$recipientList = $this->__filterRecipientsList($recipientList, $postedUserId);
		if (!empty($recipientList)) {
			// add site notifications
			$recipients = join(',', $recipientList);
			$eventOwnerId = $event['created_by'];
			$additionalInfo = array(
				'post' => $post['content'],
				'post_type' => $post['post_type'],
				'event_name' => $event['name']
			);
			$data = array(
				'activity_type' => self::ACTIVITY_POST,
				'activity_id' => $post['id'],
				'object_id' => $eventId,
				'object_type' => self::OBJECT_TYPE_EVENT,
				'sender_id' => $postedUserId,
				'recipient_id' => $recipients,
				'activity_in' => $eventId,
				'activity_in_type' => self::ACTIVITY_IN_EVENT,
				'object_owner_id' => $eventOwnerId,
				'additional_info' => json_encode($additionalInfo),
				'is_anonymous' => $post['is_anonymous']
			);
			$this->create();
			$success = $this->save($data);

			// add email notifications
			$this->__addEventPostEmailNotifications($recipientList, $post, $event);

			return $success;
		} else {
			return true;
		}
	}

	/**
	 * Function to add disease Notification.
	 * 
	 * @param array $post
	 * @return boolean
	 */
	protected function _addDiseasePostNotifications($post) {
		$diseaseId = $post['posted_in'];
		$this->Disease = ClassRegistry::init('Disease');
		$this->Disease->recursive = -1;
		$disease = $this->Disease->get_disease_details_by_id($diseaseId);

		$postedUserId = $post['post_by'];
		$recipientList = $this->__getDiseasePostNotificationRecepientList($diseaseId);
		$recipientList = $this->__filterRecipientsList($recipientList, $postedUserId);
		if (!empty($recipientList)) {
			// add site notifications
			$recipients = join(',', $recipientList);
			$additionalInfo = array(
				'post' => $post['content'],
				'post_type' => $post['post_type'],
				'disease_name' => $disease['Disease']['name']
			);
			$data = array(
				'activity_type' => self::ACTIVITY_POST,
				'activity_id' => $post['id'],
				'object_id' => $diseaseId,
				'object_type' => self::OBJECT_TYPE_DISEASE,
				'sender_id' => $postedUserId,
				'recipient_id' => $recipients,
				'activity_in' => $diseaseId,
				'activity_in_type' => self::ACTIVITY_IN_DISEASE,
				'additional_info' => json_encode($additionalInfo),
				'is_anonymous' => $post['is_anonymous']
			);
			$this->create();
			$success = $this->save($data);

			return $success;
		} else {
			return true;
		}
	}

	private function __addEventPostEmailNotifications($recipientList, $post, $event) {
		$emailRecipients = $this->__getEventActivityEmailNotificationRecepientList($event, $recipientList);
		if (!empty($emailRecipients)) {
			$Api = new ApiController;
			$Api->constructClasses();
			$postedUserId = $post['post_by'];

			if ($post['is_anonymous'] === true) {
				$senderUserName = Common::getAnonymousUsername();
			} else {
				$senderUser = $this->User->getUserDetails($postedUserId);
				$senderUserName = $senderUser['user_name'];
			}

			$postLink = Router::Url('/', TRUE) . "post/details/index/" . $post['id'];
			$link = Router::Url('/', TRUE) . "event/details/index/" . $event['id'];
			$linkText = 'See event';
			$postType = $post['post_type'];
			$this->Post = ClassRegistry::init('Post');
			$postTypeText = ($postType === Post::POST_TYPE_POLL) ? 'poll' : 'post';
			$content = __('added a %s to the "%s" event.', $postTypeText, $event['name']);
			$postLinkText = __('See %s', $postTypeText);
			foreach ($emailRecipients as $receiverUser) {
				$receiverUserName = $receiverUser['User']['username'];
				$toEmail = $receiverUser['User']['email'];
				$emailData = array(
					'username' => $receiverUserName,
					'name' => $senderUserName,
					'content' => $content,
					'post_link' => $postLink,
					'post_link_text' => $postLinkText,
					'post_type' => $postTypeText,
					'link' => $link,
					'link_text' => $linkText
				);

				$Api->sendHTMLMail(EmailTemplateComponent::POST_NOTIFICATION, $emailData, $toEmail);
			}
		}
	}

	private function __getEventActivityEmailNotificationRecepientList($event, $recipientList) {
		$emailRecipients = array();
		$this->User = ClassRegistry::init('User');
		$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
		foreach ($recipientList as $recipientId) {
			$settingName = ($event['created_by'] === $recipientId) ? 'my_event_activity' : 'other_event_activity';
			$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($recipientId, $settingName);
			if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
				$receiverUser = $this->User->findById($recipientId);
				$emailRecipients[] = $receiverUser;
			}
		}

		return $emailRecipients;
	}

	protected function _addCommunityPostNotifications($post) {
		$communityId = $post['posted_in'];
		$this->Community = ClassRegistry::init('Community');
		$this->Community->recursive = -1;
		$community = $this->Community->getCommunity($communityId);
		$postedUserId = $post['post_by'];
		$recipientList = $this->__getCommunityPostNotificationRecepientList($community);
		$recipientList = $this->__filterRecipientsList($recipientList, $postedUserId);
		if (!empty($recipientList)) {
			// add site notifications
			$recipients = join(',', $recipientList);
			$communityOwnerId = $community['created_by'];
			$additionalInfo = array(
				'post' => $post['content'],
				'post_type' => $post['post_type'],
				'community_name' => $community['name']
			);
			$data = array(
				'activity_type' => self::ACTIVITY_POST,
				'activity_id' => $post['id'],
				'object_id' => $communityId,
				'object_type' => self::OBJECT_TYPE_COMMUNITY,
				'sender_id' => $postedUserId,
				'recipient_id' => $recipients,
				'activity_in' => $communityId,
				'activity_in_type' => self::ACTIVITY_IN_COMMUNITY,
				'object_owner_id' => $communityOwnerId,
				'additional_info' => json_encode($additionalInfo),
				'is_anonymous' => $post['is_anonymous']
			);
			$this->create();
			$success = $this->save($data);

			// add email notifications
			$this->__addCommunityPostEmailNotifications($recipientList, $post, $community);

			return $success;
		} else {
			return true;
		}
	}

	private function __addCommunityPostEmailNotifications($recipientList, $post, $community) {
		$emailRecipients = $this->__getCommunityActivityEmailNotificationRecepientList($community, $recipientList);
		if (!empty($emailRecipients)) {
			$Api = new ApiController;
			$Api->constructClasses();
			$postedUserId = $post['post_by'];

			if ($post['is_anonymous'] === true) {
				$senderUserName = Common::getAnonymousUsername();
			} else {
				$senderUser = $this->User->getUserDetails($postedUserId);
				$senderUserName = $senderUser['user_name'];
			}

			$postLink = Router::Url('/', TRUE) . "post/details/index/" . $post['id'];
			$link = Router::Url('/', TRUE) . "community/details/index/" . $community['id'];
			$linkText = 'See Community';
			$postType = $post['post_type'];
			$this->Post = ClassRegistry::init('Post');
			$postTypeText = ($postType === Post::POST_TYPE_POLL) ? 'poll' : 'post';
			$content = __('added a %s to the "%s" community.', $postTypeText, $community['name']);
			$postLinkText = __('See %s', $postTypeText);
			foreach ($emailRecipients as $receiverUser) {
				$receiverUserName = $receiverUser['User']['username'];
				$toEmail = $receiverUser['User']['email'];
				$emailData = array(
					'username' => $receiverUserName,
					'name' => $senderUserName,
					'content' => $content,
					'post_link' => $postLink,
					'post_link_text' => $postLinkText,
					'post_type' => $postTypeText,
					'link' => $link,
					'link_text' => $linkText
				);

				$Api->sendHTMLMail(EmailTemplateComponent::POST_NOTIFICATION, $emailData, $toEmail);
			}
		}
	}

	private function __getCommunityActivityEmailNotificationRecepientList($community, $recipientList) {
		$emailRecipients = array();
		$this->User = ClassRegistry::init('User');
		$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
		foreach ($recipientList as $recipientId) {
			$settingName = ($community['created_by'] === $recipientId) ? 'my_group_activities' : 'other_group_activities';
			$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($recipientId, $settingName);
			if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
				$receiverUser = $this->User->findById($recipientId);
				$emailRecipients[] = $receiverUser;
			}
		}

		return $emailRecipients;
	}

	protected function _addProfilePostNotification($post) {
		$profileUserId = $post['posted_in'];
		$postedUserId = $post['post_by'];
		if ($postedUserId !== $profileUserId) {
			$additionalInfo = array(
				'post' => $post['content'],
				'post_type' => $post['post_type']
			);
			$data = array(
				'activity_type' => self::ACTIVITY_POST,
				'activity_id' => $post['id'],
				'object_id' => $profileUserId,
				'object_type' => self::OBJECT_TYPE_PROFILE,
				'sender_id' => $postedUserId,
				'recipient_id' => $profileUserId,
				'activity_in' => $profileUserId,
				'activity_in_type' => self::ACTIVITY_IN_PROFILE,
				'object_owner_id' => $profileUserId,
				'additional_info' => json_encode($additionalInfo),
				'is_anonymous' => $post['is_anonymous']
			);
			$this->create();
			$success = $this->save($data);

			// add email notifications
			$this->__addProfilePostEmailNotification($profileUserId, $post);

			return $success;
		} else {
			return true;
		}
	}
	
	/**
	 * Function to implement profile follower
	 * 
	 * @param array $post
	 * @return boolean
	 */
	protected function _addProfilePostFollowerNotification($post) {
		$profileUserId = $post['posted_in'];
		$postedUserId = $post['post_by'];
		$this->User = ClassRegistry::init('User');
		$profile_username = $this->User->getUsername($profileUserId);

		$recipientList = $this->__getProfilePostNotificationRecepientList($profileUserId);
		$recipientList = $this->__filterProfileFollowRecipientsList($recipientList, $postedUserId, $profileUserId);
		if (!empty($recipientList)) {
			// add site notifications
			$recipients = join(',', $recipientList);
			$additionalInfo = array(
				'post' => $post['content'],
				'post_type' => $post['post_type'],
				'profile_username' => $profile_username
			);
			$data = array(
				'activity_type' => self::ACTIVITY_POST,
				'activity_id' => $post['id'],
				'object_id' => $profileUserId,
				'object_type' => self::OBJECT_TYPE_PROFILE,
				'sender_id' => $postedUserId,
				'recipient_id' => $recipients,
				'activity_in' => $profileUserId,
				'activity_in_type' => self::ACTIVITY_IN_OTHER_PROFILE,
				'object_owner_id' => $profileUserId,
				'additional_info' => json_encode($additionalInfo),
				'is_anonymous' => $post['is_anonymous']
			);
			$this->create();
			$success = $this->save($data);

			return $success;
		} else {
			return true;
		}
	}

	private function __addProfilePostEmailNotification($recipientId, $post) {
		$this->User = ClassRegistry::init('User');
		$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
		$settingName = 'post_on_wall';
		$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($recipientId, $settingName);
		if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
			$receiverUser = $this->User->findById($recipientId);
			$postedUserId = $post['post_by'];

			if ($post['is_anonymous'] === true) {
				$senderUserName = Common::getAnonymousUsername();
			} else {
				$senderUser = $this->User->getUserDetails($postedUserId);
				$senderUserName = $senderUser['user_name'];
			}

			$postLink = Router::Url('/', TRUE) . "post/details/index/" . $post['id'];
			$linkText = 'See Profile';
			$postType = $post['post_type'];
			$this->Post = ClassRegistry::init('Post');
			$postTypeText = ($postType === Post::POST_TYPE_POLL) ? 'poll' : 'post';
			$content = __('added a %s on your profile.', $postTypeText);
			$postLinkText = __('See %s', $postTypeText);
			$receiverUserName = $receiverUser['User']['username'];
			$link = Router::Url('/', TRUE) . "profile/" . $receiverUserName;
			$toEmail = $receiverUser['User']['email'];
			$emailData = array(
				'username' => $receiverUserName,
				'name' => $senderUserName,
				'content' => $content,
				'post_link' => $postLink,
				'post_link_text' => $postLinkText,
				'post_type' => $postTypeText,
				'link' => $link,
				'link_text' => $linkText
			);

			$Api = new ApiController;
			$Api->constructClasses();
			$Api->sendHTMLMail(EmailTemplateComponent::POST_NOTIFICATION, $emailData, $toEmail);
		}
	}

	/**
	 * FUnction to add post comment notifications
	 * 
	 * @param array $commentData
	 * @return boolean Success
	 */
	public function addPostCommentNotifications($commentData) {
		$post = $commentData['Post'];
		$this->Post = ClassRegistry::init('Post');
		$comment = $commentData['Comment'];
		$commentedUserId = $comment['created_by'];
		$recipientList = $this->__getPostCommentNotificationRecepientList($post, $commentedUserId);
		if (!empty($recipientList)) {
			// add site notifications
			$recipients = join(',', $recipientList);
			$additionalInfo = array(
				'comment' => $comment['comment_text'],
				'is_anonymous_object' => $post['is_anonymous']
			);
			$postId = $post['id'];
			$postedUserId = $post['post_by'];

			if ($post['posted_in_type'] === Post::POSTED_IN_TYPE_COMMUNITIES) {
				$activityInType = self::ACTIVITY_IN_COMMUNITY;
			} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_EVENTS) {
				$activityInType = self::ACTIVITY_IN_EVENT;
			} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_USERS) {
				$activityInType = self::ACTIVITY_IN_PROFILE;
			} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_TEAM) {
				$activityInType = self::ACTIVITY_IN_TEAM;
			}

			$data = array(
				'activity_type' => self::ACTIVITY_COMMENT,
				'activity_id' => $comment['id'],
				'object_id' => $postId,
				'object_type' => self::OBJECT_TYPE_POST,
				'sender_id' => $commentedUserId,
				'recipient_id' => $recipients,
				'activity_in' => $post['posted_in'],
				'activity_in_type' => $activityInType,
				'object_owner_id' => $postedUserId,
				'additional_info' => json_encode($additionalInfo),
				'is_anonymous' => $comment['is_anonymous']
			);
			$this->create();
			$success = $this->save($data);

			// send email notifications
			$this->__addPostCommentEmailNotifications($recipientList, $post, $comment);

			return $success;
		} else {
			return true;
		}
	}

	private function __addPostCommentEmailNotifications($recipientList, $post, $comment) {
		if ($post['posted_in_type'] === Post::POSTED_IN_TYPE_EVENTS) {
			$eventId = $post['posted_in'];
			$this->Event = ClassRegistry::init('Event');
			$this->Event->recursive = -1;
			$event = $this->Event->getEvent($eventId);
			$emailRecipients = $this->__getEventActivityEmailNotificationRecepientList($event, $recipientList);
		} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_COMMUNITIES) {
			$communityId = $post['posted_in'];
			$this->Community = ClassRegistry::init('Community');
			$this->Community->recursive = -1;
			$community = $this->Community->getCommunity($communityId);
			$emailRecipients = $this->__getCommunityActivityEmailNotificationRecepientList($community, $recipientList);
		} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_TEAM) {
			$teamId = $post['posted_in'];
			$this->Team = ClassRegistry::init('Team');
			$team = $this->Team->getTeam($teamId);
			$emailRecipients = $this->__getTeamActivityEmailNotificationRecepientList($team, $recipientList);
		} else {
			$postedUserId = $post['post_by'];
			$emailRecipients = $this->__getPostCommentEmailNotificationRecepientList($postedUserId, $recipientList);
		}

		if (!empty($emailRecipients)) {
			$Api = new ApiController;
			$Api->constructClasses();
			$commentedUserId = $comment['created_by'];

			if ($comment['is_anonymous'] === true) {
				$senderUserName = Common::getAnonymousUsername();
			} else {
				$senderUser = $this->User->getUserDetails($commentedUserId);
				$senderUserName = $senderUser['user_name'];
			}

			$postedUserId = $post['post_by'];
			$postedUser = $this->User->getUserDetails($postedUserId);
			$postedUserName = $postedUser['user_name'];
			$postLink = Router::Url('/', TRUE) . "post/details/index/" . $post['id'];
			foreach ($emailRecipients as $receiverUser) {
				if (!empty($receiverUser['User'])) {
					if ($postedUserId === $receiverUser['User']['id']) {
						$possessivePronoun = __('your');
					} else {
						if (($post['is_anonymous'] === true) || ($comment['is_anonymous'] === true)) {
							$possessivePronoun = 'an anonymous';
						} else {
							if ($postedUserId === $commentedUserId) {
								$possessivePronoun = ($postedUser['gender'] === 'F') ? 'her' : 'his';
							} else {
								$possessivePronoun = __("%s's", $postedUserName);
							}
						}
					}

					$content = __('commented on %s post', $possessivePronoun);
					if ($post['posted_in_type'] === Post::POSTED_IN_TYPE_EVENTS) {
						$eventName = $event['name'];
						$content .= __(' in %s event', $eventName);
					} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_COMMUNITIES) {
						$communityName = $community['name'];
						$content .= __(' in %s community', $communityName);
					} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_TEAM) {
						$teamName = $team['name'];
						$content .= __(' in %s team', $teamName);
					}

					$receiverUserName = $receiverUser['User']['username'];
					$toEmail = $receiverUser['User']['email'];
					$emailData = array(
						'username' => $receiverUserName,
						'name' => $senderUserName,
						'content' => $content,
						'post_link' => $postLink,
						'comment' => $comment['comment_text']
					);

					$Api->sendHTMLMail(EmailTemplateComponent::POST_COMMENT_NOTIFICATION, $emailData, $toEmail);
				}
			}
		}
	}

	private function __getPostCommentEmailNotificationRecepientList($postedUserId, $recipientList) {
		$emailRecipients = array();
		$this->User = ClassRegistry::init('User');
		$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
		foreach ($recipientList as $recipientId) {
			$settingName = ($postedUserId === $recipientId) ? 'comment_on_post' : 'post_i_follow';
			$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($recipientId, $settingName);
			if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
				$receiverUser = $this->User->findById($recipientId);
				$emailRecipients[] = $receiverUser;
			}
		}

		return $emailRecipients;
	}

	private function __getPostCommentNotificationRecepientList($post, $commentedUserId) {
		$recipientList = array();
		switch ($post['posted_in_type']) {
			case Post::POSTED_IN_TYPE_EVENTS:
				$eventId = $post['posted_in'];
				$this->Event = ClassRegistry::init('Event');
				$this->Event->recursive = -1;
				$event = $this->Event->getEvent($eventId);
				$recipientList = $this->__getEventPostNotificationRecepientList($event);
				break;
			case Post::POSTED_IN_TYPE_COMMUNITIES:
				$communityId = $post['posted_in'];
				$this->Community = ClassRegistry::init('Community');
				$this->Community->recursive = -1;
				$community = $this->Community->getCommunity($communityId);
				$recipientList = $this->__getCommunityPostNotificationRecepientList($community);
				break;
			case Post::POSTED_IN_TYPE_USERS:
				$profileUserId = $post['posted_in'];
				$recipientList[] = $profileUserId;
				break;
			case Post::POSTED_IN_TYPE_TEAM:
				$teamId = $post['posted_in'];
				$this->Team = ClassRegistry::init('Team');
				$team = $this->Team->getTeam($teamId);
				$recipientList = $this->__getTeamPostNotificationRecepientList($team);
				break;
		}

		// add the owner of the post to the recipients list
		$recipientList[] = $post['post_by'];

		// add the already commented users to the recipients list
		if ($post['comment_count'] > 0) {
			$postId = $post['id'];
			$commentedUserIds = $this->__getPostCommentedUserIds($postId);
			$recipientList = array_merge($recipientList, $commentedUserIds);
		}

		$recipientList = $this->__filterRecipientsList($recipientList, $commentedUserId);

		return $recipientList;
	}

	private function __getPostLikeNotificationRecepientList($post) {
		$recipientList = array();
		App::uses('Post', 'Model');
		switch ($post['posted_in_type']) {
			case Post::POSTED_IN_TYPE_EVENTS:
				$eventId = $post['posted_in'];
				$this->Event = ClassRegistry::init('Event');
				$this->Event->recursive = -1;
				$event = $this->Event->getEvent($eventId);
				$recipientList = $this->__getEventPostNotificationRecepientList($event);
				break;
			case Post::POSTED_IN_TYPE_COMMUNITIES:
				$communityId = $post['posted_in'];
				$this->Community = ClassRegistry::init('Community');
				$this->Community->recursive = -1;
				$community = $this->Community->getCommunity($communityId);
				$recipientList = $this->__getCommunityPostNotificationRecepientList($community);
				break;
			case Post::POSTED_IN_TYPE_TEAM:
				$teamId = $post['posted_in'];
				$this->Team = ClassRegistry::init('Team');
				$team = $this->Team->getTeam($teamId);
				$recipientList = $this->__getTeamPostNotificationRecepientList($team);
				break;
			case Post::POSTED_IN_TYPE_USERS:
				$profileUserId = $post['posted_in'];
				$recipientList[] = $profileUserId;
				break;
		}

		// add the owner of the post to the recipients list
		$recipientList[] = $post['post_by'];

		return array_unique($recipientList);
	}

	/**
	 * Function to get the id of the users who commented on a post
	 * 
	 * @param int $postId
	 * @return array
	 */
	private function __getPostCommentedUserIds($postId) {
		$this->Comment = ClassRegistry::init('Comment');
		$commentedUserIds = $this->Comment->getPostCommentedUserIds($postId);
		return array_unique(array_values($commentedUserIds));
	}

	/**
	 * FUnction to save like notification for a post
	 * 
	 * @param array $post post data
	 * @param array $likedUsersArray
	 * @param boolean $isLike is like or unlike
	 */
	public function savePostLikeNotification($post, $likedUsersArray, $likedUserId) {
		$recipientList = $this->__getPostLikeNotificationRecepientList($post);
		$postId = $post['id'];
		$additionalInfo = json_encode(array(
			'post' => $post['content'],
			'is_anonymous_object' => $post['is_anonymous']
				));
		$notification = $this->getPostLikeNotification($postId);
		if (empty($notification) && ($likedUserId > 0)) {
			// remove liked user from the recipients list
			$likedUserKey = array_search($likedUserId, $recipientList);
			if ($likedUserKey !== false) {
				unset($recipientList[$likedUserKey]);
				$recipientList = array_values($recipientList);
			}

			if (!empty($recipientList)) {
				$recipients = join(',', $recipientList);

				// add new notification if first like for a post
				$this->create();
				if ($post['posted_in_type'] === Post::POSTED_IN_TYPE_COMMUNITIES) {
					$activityInType = self::ACTIVITY_IN_COMMUNITY;
				} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_EVENTS) {
					$activityInType = self::ACTIVITY_IN_EVENT;
				} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_USERS) {
					$activityInType = self::ACTIVITY_IN_PROFILE;
				} elseif ($post['posted_in_type'] === Post::POSTED_IN_TYPE_TEAM) {
					$activityInType = self::ACTIVITY_IN_TEAM;
				} else {
					$activityInType = '';
				}

				$data = array(
					'activity_type' => self::ACTIVITY_LIKE,
					'object_id' => $postId,
					'object_type' => self::OBJECT_TYPE_POST,
					'recipient_id' => $recipients,
					'sender_id' => $likedUserId,
					'object_owner_id' => $post['post_by'],
					'activity_in' => $post['posted_in'],
					'activity_in_type' => $activityInType,
					'additional_info' => $additionalInfo,
				);
				$this->save($data);
			}
		} else {
			if (empty($likedUsersArray)) {
				// delete the notification when all likes are removed
				if (!empty($notification)) {
					$notificationId = $notification['Notification']['id'];
					$this->delete($notificationId);
				}
			} else {
				if (!empty($notification) && !empty($recipientList)) {
					// update the existing notification on new like or unlike
					$data = $notification['Notification'];
					$data['additional_info'] = $additionalInfo;
					$recipients = join(',', $recipientList);
					$data['recipient_id'] = $recipients;

					unset($data['modified']);
					unset($data['created']);

					if ($likedUserId === null) {
						// if unlike activity, avoid automatic update of the
						// modified field to avoid display on top in notifications
						$data['modified'] = false;

						// update sender id field with last liked user id
						$lastLikedUserId = end($likedUsersArray);
						$data['sender_id'] = $lastLikedUserId;
					} else {
						$data['sender_id'] = $likedUserId;
						$data['read_recipients'] = $likedUserId;
						$this->__likeUpdated = true;
						$this->__likedUserId = $likedUserId;
					}

					$this->save($data);
				}
			}
		}
	}

	/**
	 * Function to get the notification for a post like
	 * 
	 * @param int $postId
	 * @return array
	 */
	public function getPostLikeNotification($postId) {
		$this->recursive = -1;
		$query = array(
			'conditions' => array(
				'activity_type' => self::ACTIVITY_LIKE,
				'object_id' => $postId,
				'object_type' => self::OBJECT_TYPE_POST
			)
		);
		return $this->find('first', $query);
	}

	private function __getEventPostNotificationRecepientList($event) {
		$recipientList = array();

		if ($event['attending_count'] > 1) {
			$EventMember = ClassRegistry::init('EventMember');
			$eventAttendingMembers = $EventMember->getAttendingMembers($event['id']);
			$recipientList = array_values($eventAttendingMembers);
		} else {
			$recipientList[] = $event['created_by'];
		}

		return $recipientList;
	}

	/**
	 * Function to get users who are following a disease and need notification.
	 * 
	 * @param int $diseaseId
	 * @return array recipient list of users
	 */
	private function __getDiseasePostNotificationRecepientList($diseaseId) {
		$recipientList = array();

		$FollowingPage = ClassRegistry::init('FollowingPage');
		$type = FollowingPage::DISEASE_TYPE;
		$diseaseNotificationMembers = $FollowingPage->getNotificationMembers($type, $diseaseId);
		$recipientList = array_values($diseaseNotificationMembers);

		return $recipientList;
	}
	
	/**
	 * Function to get users who are following a disease and need notification.
	 * 
	 * @param array $disease disease details
	 * @return array recipient list of users
	 */
	private function __getProfilePostNotificationRecepientList($profileUserId) {
		$recipientList = array();
		
		$FollowingPage = ClassRegistry::init('FollowingPage');
		$type = FollowingPage::USER_TYPE;
		$profileNotificationMembers = $FollowingPage->getNotificationMembers($type, $profileUserId);
		$recipientList = array_values($profileNotificationMembers);

		return $recipientList;
	}

	private function __getCommunityPostNotificationRecepientList($community) {
		$recipientList = array();

		if ($community['member_count'] > 1) {
			$CommunityMember = ClassRegistry::init('CommunityMember');
			$communityMembers = $CommunityMember->getApprovedCommunityMemberIds($community['id']);
			$recipientList = array_values($communityMembers);
		} else {
			$recipientList[] = $community['created_by'];
		}

		return $recipientList;
	}

	/**
	 * Function to filter recipients list 
	 * 
	 * Filter out duplicate recipients
	 * Removes senderid if it exists in the recipients list 
	 * 
	 * @param array $recipients
	 * @param int $senderId
	 * @return array
	 */
	private function __filterRecipientsList($recipients, $senderId) {
		if (!empty($recipients)) {
			$recipients = array_unique($recipients);
			foreach ($recipients as $key => $recipientId) {
				if ($senderId === $recipientId) {
					unset($recipients[$key]);
					$recipients = array_values($recipients);
					break;
				}
			}
		}

		return array_filter($recipients);
	}
	/**
	 * Function to apply user permission and filter for profile follow.
	 * 
	 * @param array $recipients
	 * @param int $senderId
	 * @param int $profileUserId
	 * @return array $recipients array of filtered users
	 */
	private function __filterProfileFollowRecipientsList($recipients, $senderId, $profileUserId) {
		if (!empty($recipients)) {
			$recipients = array_unique($recipients);
			$privacy = new UserPrivacySettings($profileUserId);
			$this->MyFriends = ClassRegistry::init('MyFriends');

			foreach ($recipients as $key => $recipientId) {
				$isFriend = $this->MyFriends->getFriendStatus($profileUserId, $recipientId);
				$viewSetting = array($privacy::PRIVACY_PUBLIC);
				if ($isFriend == MyFriends::STATUS_CONFIRMED) {
					array_push($viewSetting, $privacy::PRIVACY_FRIENDS);
				}
				if ($senderId === $recipientId) {
					unset($recipients[$key]);
					$recipients = array_values($recipients);
					continue;
				}
				if (!in_array($privacy->__get('view_your_activity'), $viewSetting)) {
					unset($recipients[$key]);
					$recipients = array_values($recipients);
			}			
		}
		}

		return array_filter($recipients);
	}
	
	/**
	 * 
	 * @param type $recipients
	 * @param type $senderId
	 * @param type $profileUserId
	 * @return type
	 */
	private function __filterPermissionRecipientsList($recipients, $senderId, $profileUserId) {
		if (!empty($recipients)) {
			$recipients = array_unique($recipients);
			$privacy = new UserPrivacySettings($profileUserId);
			$viewSetting = array($privacy::PRIVACY_PUBLIC);
			
			foreach ($recipients as $key => $recipientId) {
				if ($senderId === $recipientId) {
					unset($recipients[$key]);
					$recipients = array_values($recipients);
					break;
				}
			/**
             * Permission check code for profile tab.
             */
            if (!in_array($privacy->__get('view_your_activity'), $viewSetting)) {
                unset($menuItems[3]);
                $viewFriends = false;
            }
			}
		}

		return array_filter($recipients);
	}

	/**
	 * Deletes the site notifications related to a community
	 * 
	 * @param int $communityId
	 */
	public function deleteCommunityNotifications($communityId) {
		$conditions = array(
			'OR' => array(
				array(
					'object_id' => $communityId,
					'object_type' => self::OBJECT_TYPE_COMMUNITY,
				), array(
					'activity_in' => $communityId,
					'activity_in_type' => self::ACTIVITY_IN_COMMUNITY,
				)
			)
		);
		$this->__deletedNotifications($conditions);
	}

	/**
	 * Deletes the site notifications related to an event
	 * 
	 * @param int $eventId
	 */
	public function deleteEventNotifications($eventId) {
		$conditions = array(
			'OR' => array(
				array(
					'object_id' => $eventId,
					'object_type' => self::OBJECT_TYPE_EVENT,
				), array(
					'activity_in' => $eventId,
					'activity_in_type' => self::ACTIVITY_IN_EVENT,
				)
			)
		);
		$this->__deletedNotifications($conditions);
	}

	/**
	 * Deletes the site notifications related to a post
	 * 
	 * @param int $postId
	 */
	public function deletePostNotifications($postId) {
		$conditions = array(
			'OR' => array(
				array(
					'object_id' => $postId,
					'object_type' => array(
						self::OBJECT_TYPE_POST,
						self::OBJECT_TYPE_POLL_POST,
					)
				), array(
					'activity_id' => $postId,
					'activity_type' => self::ACTIVITY_POST,
				)
			)
		);
		$this->__deletedNotifications($conditions);
	}

	/**
	 * Deletes the site notifications related to a comment
	 * 
	 * @param int $commentId
	 */
	public function deleteCommentNotifications($commentId) {
		$conditions = array(
			'activity_id' => $commentId,
			'activity_type' => self::ACTIVITY_COMMENT
		);
		$this->__deletedNotifications($conditions);
	}

	/**
	 * Function to delete the notifications satisfying the conditions
	 * 
	 * @param array $conditions 
	 */
	private function __deletedNotifications($conditions) {
		// decrement the count of the notifications to be deleted
		$this->__decrementDeletedNotificationsCount($conditions);

		// delete the notifications
		$cascade = false;
		$this->deleteAll($conditions, $cascade);
	}

	/**
	 * Function to decrement the notification count after deleting notification
	 * 
	 * @param array $conditions 
	 */
	private function __decrementDeletedNotificationsCount($conditions) {
		$this->recursive = -1;
		$query = array(
			'conditions' => $conditions
		);
		$notifications = $this->find('all', $query);
		if (!empty($notifications)) {
			$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
			foreach ($notifications as $notificationRecord) {
				$notification = $notificationRecord['Notification'];
				$recipientsList = $notification['recipient_id'];
				$recipients = $this->listToArray($recipientsList);
				$readRecipientsList = $notification['read_recipients'];
				$readRecipients = $this->listToArray($readRecipientsList);
				$unreadRecipients = array_diff($recipients, $readRecipients);
				if (!empty($unreadRecipients)) {
					// if there are any unread recipients for this notification
					$modifiedTimeStamp = strtotime($notification['modified']);
					foreach ($unreadRecipients as $userId) {
						if (!isset($data[$userId])) {
							$userNotificationSetting = $this->NotificationSetting->findByUserId($userId);
							if (!empty($userNotificationSetting)) {
								$notificationSetting = $userNotificationSetting['NotificationSetting'];
								$userData[$userId] = array(
									'id' => $notificationSetting['id'],
									'count' => $notificationSetting['notification_count'],
									'timestamp' => strtotime($notificationSetting['notification_last_viewed'])
								);
							}
						}

						if (isset($userData[$userId])) {
							$lastViewedTimeStamp = $userData[$userId]['timestamp'];
							$count = $userData[$userId]['count'];
							if ($modifiedTimeStamp > $lastViewedTimeStamp) {
								$count = $count - 1;

								if (!isset($data[$userId])) {
									$data[$userId]['id'] = $userData[$userId]['id'];
									$data[$userId]['user_id'] = $userId;
								}

								$data[$userId]['notification_count'] = $count;
								$userData[$userId]['count'] = $count;
							}
						}
					}
				}
			}

			if (isset($data) && !empty($data)) {
				$data = array_values($data);
				$options['validate'] = false;
				if ($this->NotificationSetting->saveMany($data, $options)) {
					$this->__realTimeNotifyUsers($data);
				}
			}
		}
	}

	/**
	 * Function to convert a comma separated list to an array
	 * 
	 * @param string $list
	 * @return array 
	 */
	public function listToArray($list) {
		if ($list === '' || $list === null) {
			$array = array();
		} else if (strpos($list, ',') !== false) {
			$array = array_filter(explode(',', $list));
		} else {
			$array[] = $list;
		}

		return $array;
	}

	/**
	 * Function to get the regular expresssion to search for a value in a 
	 * comma separated list of values
	 * 
	 * @param int $value
	 * @return string
	 */
	private function __getListSearchRegExp($value) {
		$regExp = "^$value,|,$value,|,$value$|^$value$";
		return $regExp;
	}

	/**
	 * Update notification count of the user, after adding a notification
	 * 
	 * @param boolean $created
	 * @param array $options 
	 */
	public function afterSave($created, $options = array()) {
		parent::afterSave($created, $options);
		if (($created === true) || ($this->__likeUpdated === true)) {
			$recipientsList = $this->data['Notification']['recipient_id'];

			$recipients = array();
			if (strpos($recipientsList, ',') !== false) {
				$recipients = explode(',', $recipientsList);
			} else {
				$recipients[] = $recipientsList;
			}

			if (!empty($recipients)) {
				$data = array();
				$recipients = array_filter($recipients);
				$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
				foreach ($recipients as $userId) {

					if (($this->__likeUpdated === true) && ($userId === $this->__likedUserId)) {
						$updateNotificationCount = false;
					} else {
						$updateNotificationCount = true;
					}

					if ($updateNotificationCount === true) {
						$record = $this->NotificationSetting->findByUserId($userId);
						$recordData = array();
						if (!empty($record)) {
							// if existing, update
							$recordData['id'] = $record['NotificationSetting']['id'];
							$recordData['user_id'] = $userId;
							$count = $record['NotificationSetting']['notification_count'];
							$recordData['notification_count'] = $count + 1;
						} else {
							// create
							$recordData['user_id'] = $userId;
							$recordData['notification_count'] = 1;
						}
						$data[] = $recordData;
					}
				}

				if (!empty($data)) {
					$this->NotificationSetting->saveMany($data);
					$this->__realTimeNotifyUsers($data);
				}
			}
		}
	}

	/**
	 * Function to realtime notify users on receiving a new notification
	 * 
	 * Emit 'notify_user' event to users socket for realtime notification
	 * 
	 * @param array $data
	 */
	private function __realTimeNotifyUsers($data) {
		App::import('Vendor', 'elephantio/client');
		$elephant = new ElephantIO\Client(Configure::read('SOCKET.URL'), 'socket.io', 1, false, true, true);
		$elephant->init();
		foreach ($data as $userData) {
			$elephant->emit('notify_user', array(
				'user_id' => $userData['user_id'],
				'notification_name' => 'unread_notification_count',
				'notification_count' => $userData['notification_count']
			));
		}
		$elephant->close();
	}

	/**
	 * Function to realtime notify a user
	 * 
	 * Emit 'notify_user' event to user socket for realtime notification
	 * 
	 * @param array $data
	 */
	private function __realTimeNotifyUser($data) {
		App::import('Vendor', 'elephantio/client');
		$elephant = new ElephantIO\Client(Configure::read('SOCKET.URL'), 'socket.io', 1, false, true, true);
		$elephant->init();
		$elephant->emit('notify_user', array(
			'user_id' => $data['user_id'],
			'notification_name' => 'unread_notification_count',
			'notification_count' => $data['notification_count']
		));
		$elephant->close();
	}

	/**
	 * Function to get the notifications of a user (last 2 weeks)
	 * 
	 * @param int $userId
	 * @param int $limit
	 * @return array
	 */
	public function getUserNotifications($userId, $limit = null) {
		$userIdSearchRegExp = $this->__getListSearchRegExp($userId);
		$query = array(
			'conditions' => array(
				'recipient_id REGEXP' => $userIdSearchRegExp,
				'OR' => array(
					'Notification.modified > DATE_SUB(CURDATE(), INTERVAL 2 WEEK)',
					'read_recipients NOT REGEXP' => $userIdSearchRegExp
				)
			),
			'joins' => array(
				array('table' => 'events',
					'alias' => 'Event',
					'type' => 'LEFT',
					'conditions' => array(
						"{$this->alias}.activity_in = Event.id",
						"{$this->alias}.activity_in_type" => self::ACTIVITY_IN_EVENT,
					)
				),
				array('table' => 'communities',
					'alias' => 'Community',
					'type' => 'LEFT',
					'conditions' => array(
						"{$this->alias}.activity_in = Community.id",
						"{$this->alias}.activity_in_type" => self::ACTIVITY_IN_COMMUNITY,
					)
				),
				array('table' => 'teams',
					'alias' => 'Team',
					'type' => 'LEFT',
					'conditions' => array(
						"{$this->alias}.activity_in = Team.id",
						"{$this->alias}.activity_in_type" => self::ACTIVITY_IN_TEAM,
					)
				),
			),
			'fields' => array(
				"{$this->alias}.*",
				"Sender.id",
				"Sender.type",
				"Sender.username",
				"ObjectOwner.gender",
				"ObjectOwner.username",
				"Recipient.username",
				"Event.name",
				"Community.name",
				"Team.name"
			),
			'order' => array(
				"{$this->alias}.modified DESC",
				"{$this->alias}.id DESC"
			)
		);

		if (!is_null($limit)) {
			$query['limit'] = $limit;
		}

		$result = $this->find('all', $query);
		return $result;
	}

	/**
	 * Function to add notifications in a team
	 * 
	 * @param array $team
	 * @param array $params
	 * @return boolean Success
	 */
	public function addTeamNotification($team, $params) {
		$additionalInfo = array('team_name' => $team['name']);
		if (isset($params['additional_info'])) {
			$additionalInfo+= $params['additional_info'];
		}
		$data = array(
			'activity_in' => $team['id'],
			'activity_in_type' => self::ACTIVITY_IN_TEAM,
			'additional_info' => json_encode($additionalInfo)
		);

		$dataParams = array('activity_type', 'activity_id', 'object_id',
			'object_type', 'sender_id', 'recipient_id', 'object_owner_id'
		);
		foreach ($dataParams as $paramName) {
			if (isset($params[$paramName])) {
				$data[$paramName] = $params[$paramName];
			}
		}

		if (isset($params['recipients']) && is_array($params['recipients'])) {
			$data['recipient_id'] = join(',', $params['recipients']);
		}

		if (!isset($params['object_id'])) {
			$data['object_id'] = $team['id'];
		}

		if (!isset($params['object_type'])) {
			$data['object_type'] = self::OBJECT_TYPE_TEAM;
		}

		if (!isset($params['object_owner_id'])) {
			$data['object_owner_id'] = $team['patient_id'];
		}

		$this->create();
		return $this->save($data);
	}

	/**
	 * Function to add friend request approved notification 
	 * 
	 * @param int $userId
	 * @param int $friendId
	 * @return bool 
	 */
	public function addFriendRequestApprovedNotification($userId, $friendId) {
		$data = array(
			'activity_type' => self::ACTIVITY_FRIEND_REQUEST_APPROVED,
			'object_id' => $userId,
			'object_type' => self::OBJECT_TYPE_PROFILE,
			'activity_in' => $userId,
			'activity_in_type' => self::ACTIVITY_IN_PROFILE,
			'recipient_id' => $friendId,
			'sender_id' => $userId,
			'object_owner_id' => $userId
		);

		$this->create();
		return $this->save($data);
	}

	/**
	 * Function to add notification on register
	 * 
	 * @param int $userId
	 * @return bool 
	 */
	public function addRegisterNotification($userId) {
		$data = array(
			'activity_type' => self::ACTIVITY_REGISTER,
			'recipient_id' => $userId
		);

		$this->create();
		return $this->save($data);
	}

	/**
	 * Function to mark a notification as read by a user
	 * 
	 * @param int $notificationId
	 * @param int $userId 
	 */
	public function markNotificationRead($notificationId, $userId) {
		$data = array(
			'id' => $notificationId,
			'read_recipients' => $userId,
			'modified' => false
		);
		$this->save($data);
	}

	/**
	 * Function to delete the team join invitation notification for a user
	 * 
	 * @param int $teamId
	 * @param int $userId
	 */
	public function deleteTeamJoinInvitationNotification($teamId, $userId) {
		$conditions = array(
			'activity_type' => self::ACTIVITY_TEAM_JOIN_INVITATION,
			'object_id' => $teamId,
			'recipient_id' => $userId
		);
		$this->__deletedNotifications($conditions);
	}

	/**
	 * Function to add notifications for a question
	 * 
	 * @param type $post
	 * @return boolean Success
	 */
	public function addQuestionNotifications($post) {
		$diseaseId = $post['posted_in'];
		$postedUserId = $post['post_by'];
		$recipientList = $this->__getDiseasePostNotificationRecepientList($diseaseId);
		$recipientList = $this->__filterRecipientsList($recipientList, $postedUserId);
		if (!empty($recipientList)) {
			$this->User = ClassRegistry::init('User');
			$this->Disease = ClassRegistry::init('Disease');
			$this->Disease->recursive = -1;
			$disease = $this->Disease->findById($diseaseId);

			// add site notifications
			$recipients = join(',', $recipientList);
			$content = json_decode($post['content'], true);
			$additionalInfo = array(
				'question' => $content['question'],
				'disease_name' => $disease['Disease']['name']
			);
			$data = array(
				'activity_type' => self::ACTIVITY_QUESTION,
				'activity_id' => $post['id'],
				'object_id' => $diseaseId,
				'object_type' => self::OBJECT_TYPE_DISEASE,
				'sender_id' => $postedUserId,
				'recipient_id' => $recipients,
				'activity_in' => $diseaseId,
				'activity_in_type' => self::ACTIVITY_IN_DISEASE,
				'additional_info' => json_encode($additionalInfo),
				'is_anonymous' => $post['is_anonymous']
			);
			$this->create();
			$success = $this->save($data, false);

			// add email notifications
			$this->__addDiseaseQuestionEmailNotifications($recipientList, $post, $diseaseId, $additionalInfo);

			return $success;
		} else {
			return true;
		}
	}

	private function __addDiseaseQuestionEmailNotifications($recipientList, $post, $diseaseId, $additionalInfo) {
		if (!empty($recipientList)) {
			$emailRecipients = $this->__getEmailNotificationRecepientList($recipientList);
			$Api = new ApiController;
			$Api->constructClasses();
			$postedUserId = $post['post_by'];

			if ($post['is_anonymous'] === true) {
				$senderUserName = Common::getAnonymousUsername();
			} else {
				$senderUser = $this->User->getUserDetails($postedUserId);
				$senderUserName = $senderUser['user_name'];
			}

			$postLink = Router::Url('/', TRUE) . "post/details/index/" . $post['id'];
			$link = Router::Url('/', TRUE) . "condition/index/" . $diseaseId;
			foreach ($emailRecipients as $receiverUser) {
				$receiverUserName = $receiverUser['User']['username'];
				$toEmail = $receiverUser['User']['email'];
				$emailData = array(
					'username' => $receiverUserName,
					'name' => $senderUserName,
					'post_link' => $postLink,
					'link' => $link,
					'question' => h($additionalInfo['question']),
					'disease_name' => h($additionalInfo['disease_name'])
				);
				$Api->sendHTMLMail(EmailTemplateComponent::QUESTION_NOTIFICATION, $emailData, $toEmail);
			}
		}
	}

	/**
	 * Function to get email notification recepient list
	 * 
	 * @param array $team
	 * @param array $recipientList
	 * @return array
	 */
	private function __getEmailNotificationRecepientList($recipientList) {
		$emailRecipients = array();
		foreach ($recipientList as $recipientId) {
			$isEmailNotificationOn = true;
			if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
				$receiverUser = $this->User->findById($recipientId);
				$emailRecipients[] = $receiverUser;
			}
		}

		return $emailRecipients;
	}

	/**
	 * Function to add notifications when a question is answered in a disease page
	 * 
	 * @param array $answerData
	 * @return boolean Success
	 */
	public function addQuestionAnswerNotifications($answerData) {
		$answerObj = $answerData['Answer'];
		$answeredUserId = (int) $answerObj['created_by'];
		$post = $answerData['Post'];
		$postedBy = (int) $post['post_by'];

		if ($answeredUserId !== $postedBy) {
			$recipientId = $postedBy;
			$postId = $post['id'];
			$content = json_decode($post['content'], true);
			$question = $content['question'];
			$answer = $answerObj['answer'];
			$diseaseId = $post['posted_in'];
			$this->Disease = ClassRegistry::init('Disease');
			$this->Disease->recursive = -1;
			$disease = $this->Disease->findById($diseaseId);

			// add site notifications
			$additionalInfo = array(
				'question' => $question,
				'answer' => $answer,
				'disease_name' => $disease['Disease']['name'],
				'is_anonymous_object' => $post['is_anonymous']
			);
			$data = array(
				'activity_type' => self::ACTIVITY_ANSWERED_QUESTION,
				'activity_id' => $answerObj['id'],
				'object_id' => $postId,
				'object_type' => self::OBJECT_TYPE_POST,
				'object_owner_id' => $postedBy,
				'sender_id' => $answeredUserId,
				'recipient_id' => $recipientId,
				'activity_in' => $diseaseId,
				'activity_in_type' => self::ACTIVITY_IN_DISEASE,
				'additional_info' => json_encode($additionalInfo)
			);
			$this->create();
			$success = $this->save($data);

			// send email notifications
			$this->User = ClassRegistry::init('User');
			$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
			$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($recipientId, 'answered_my_question');
			if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
				$recipient = $this->User->findById($recipientId);
				if (!empty($recipient['User'])) {
					$Api = new ApiController;
					$Api->constructClasses();
					$postLink = Router::Url('/', TRUE) . "post/details/index/" . $postId;
					if ($answerObj['is_anonymous'] === true) {
						$senderUserName = 'Anonymous';
					} else {
						$senderUserName = $answerData['User']['username'];
					}
					$recipientUserName = $recipient['User']['username'];
					$toEmail = $recipient['User']['email'];
					$emailData = array(
						'username' => $recipientUserName,
						'name' => $senderUserName,
						'question' => h($question),
						'answer' => h($answer),
						'disease_name' => h($additionalInfo['disease_name']),
						'post_link' => $postLink
					);
					$Api->sendHTMLMail(EmailTemplateComponent::QUESTION_ANSWER_NOTIFICATION, $emailData, $toEmail);
				}
			}
			return $success;
		} else {
			return true;
		}
	}

	/**
	 * Deletes the site notifications related to an answer
	 * 
	 * @param int $answerId
	 */
	public function deleteAnswerNotifications($answerId) {
		$conditions = array(
			'activity_id' => $answerId,
			'activity_type' => self::ACTIVITY_ANSWERED_QUESTION,
			'object_type' => self::OBJECT_TYPE_POST
		);
		$this->__deletedNotifications($conditions);
	}
}
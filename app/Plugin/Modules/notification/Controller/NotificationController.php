<?php

/**
 * NotificationController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('NotificationAppController', 'Notification.Controller');
App::uses('Date', 'Utility');
App::uses('UserPrivacySettings', 'Lib');

/**
 * NotificationAppController for header notificarions.
 * 
 * NotificationAppController is used for header notificarions.
 *
 * @author      Ajay Arjunan
 * @package 	Communitys
 * @category	Controllers 
 */
class NotificationController extends NotificationAppController {

    public $uses = array(
        'User',
        'MyFriends',
        'UserMessage',
        'Notification',
        'NotificationSetting'
    );
    public $components = array(
        'Session',
        'RecommendedFriend'
    );

    function getMessageNotifications() {
        $user_id = $this->Auth->user('id');
        $timezone = $this->Auth->user('timezone');
        $messages = NULL;
        $messages = $this->UserMessage->getInboxMessages($user_id);
        $this->set(compact('messages','timezone'));
        $this->layout = "ajax";
        $View = new View($this, false);
        $messages_count = $this->UserMessage->getInboxMessagesCount($user_id);
        $response['notification_counts'] = $messages_count;
        $response['html_content'] = $View->element('Message/message_notification_header');

        print_r(json_encode($response));
        exit;
    }

	/**
	 * List all the unread notifications for a user
	 */
	public function index() {
		$userId = $this->Auth->user('id');
		$notifications = $this->Notification->getUserNotifications($userId);
		$notificationData = $this->_getNotificationsData($notifications);
		$this->set(compact('notificationData'));
	}

	/**
	 * List the notifications
	 */
	public function getNotifications() {
		$userId = $this->Auth->user('id');
		$this->NotificationSetting->unsetUserNotificationCount($userId);
		$limit = (int) $this->request->data['limit'];
		if ($limit === 0) {
			$limit = 3;
			$append = 'false';
		} else {
			$append = $this->request->data['append'];
		}
		$notifications = $this->Notification->getUserNotifications($userId, $limit);
		$notificationData = $this->_getNotificationsData($notifications);
		$this->set(compact('notificationData'));
		$View = new View($this, false);
		$element = ($append === 'true') ? 'notification_items' : 'notification_list';
		$response['html_content'] = $View->element("Notification.$element");
		$this->autoRender = false;
		$this->layout = 'ajax';
		echo json_encode($response);
	}

	/**
	 * Function to get the display data for the notifications
	 * 
	 * @param type $notifications
	 * @return type
	 */
	protected function _getNotificationsData($notifications) {
		$notificationData = array();
		if (!empty($notifications)) {
			$currentUser = $this->Auth->user();
			$timezone = $currentUser['timezone'];
			foreach ($notifications as $notification) {
				if (isset($notification['Notification']['is_anonymous']) && 
                                    $notification['Notification']['is_anonymous'] === true) {
					$senderThumb = Common::getAnonymousUserThumb('x_small', 'media-object');
					$senderUsername = __('Anonymous');
				} else {
					$sender = $notification['Sender'];
					if (!empty($sender['id'])) {
						$senderThumb = Common::getUserThumb($sender['id'], $sender['type'], 'x_small', 'media-object');
						$senderUsername = $sender['username'];
					} else {
						$senderThumb = null;
						$senderUsername = null;
					}
				}
				$modified = CakeTime::nice($notification['Notification']['modified'], $timezone, '%B %e, %Y at %l:%M%P');
				$content = $this->Notification->getContent($notification, $currentUser);
				$notificationData[] = array(
					'userThumb' => $senderThumb,
					'modified' => $modified,
					'username' => $senderUsername,
					'content' => $content,
				);
			}
		}
		return $notificationData;
	}

	function getFriendRequestsNotifications() {
        $user_id = $this->Auth->user('id');
        $pendingRequests = NULL;
        $pendingRequests = $this->MyFriends->getPendingFriendsList($user_id);
        if (isset($pendingRequests) && $pendingRequests != NULL) {
            $pendingUsersDetails = $this->setUserFulldetails($pendingRequests);
        } else {
            $pendingUsersDetails = NULL;
        }
        $this->set(compact('pendingUsersDetails'));
        $this->layout = "ajax";
        $View = new View($this, false);
        $pending_friend_requests_count = $this->MyFriends->getFriendsStatusCount($user_id, MyFriends::STATUS_REQUEST_RECIEVED);
        
        $response['notification_counts'] = $pending_friend_requests_count;
        $response['html_content'] = $View->element('Friends/friend_requests_header');

        print_r(json_encode($response));
        exit;
    }

    function getPeopleMayKnowNotifications() {
        $user_id = $this->Auth->user('id');
        $recommendedUsers = NULL;
        $recommendedUsers = $this->RecommendedFriend->paginateRecommendedFriends($user_id, 10);
        if (isset($recommendedUsers) && $recommendedUsers != NULL) {
            $recommendedUsersDetails = array();
            foreach ($recommendedUsers as $recommendedUser) {
				$privacy = new UserPrivacySettings($recommendedUser['User']['id']);
				$diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');
				
				if (
						$diseaseViewPermittedTo === $privacy::PRIVACY_PUBLIC 
						|| $recommendedUser['User']['id'] == $user_id
				) {
					$recommendedUsersDetails[] = array(
                    'user' => $recommendedUser['User'],
                    'diseases' => $this->User->getUserDiseases($recommendedUser['User']['id'])
                );
				} elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
					$friendStatus = (int) $this->MyFriends->getFriendStatus($user_id, $recommendedUser['User']['id']);
					if (($friendStatus === MyFriends::STATUS_CONFIRMED)) {
						$recommendedUsersDetails[] = array(
                    'user' => $recommendedUser['User'],
                    'diseases' => $this->User->getUserDiseases($recommendedUser['User']['id'])
                );
					}
				} else {
					$recommendedUsersDetails[] = array(
                    'user' => $recommendedUser['User'],
                    'diseases' => NULL
                );
				}
            }
        } else {
            $recommendedUsersDetails = NULL;
        }
        $this->set(compact('recommendedUsersDetails'));
        $this->layout = "ajax";
        $View = new View($this, false);
        $response['html_content'] = $View->element('Friends/users_may_know_header');
        print_r(json_encode($response));
        exit;
    }

    function setUserFulldetails($userIdList) {
        if (isset($userIdList) && $userIdList != NULL) {
			$loginUserId = $this->Auth->user('id');

			foreach ($userIdList as $userId) {

				// add user disease based on that users privacy settings
				$disease = NULL;
				$privacy = new UserPrivacySettings($userId);
				$diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');

				if (
						$diseaseViewPermittedTo === $privacy::PRIVACY_PUBLIC || $userId == $loginUserId
				) {
					$disease = $this->User->getUserDiseases($userId);
				} elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
					$friendStatus = (int) $this->MyFriends->getFriendStatus($loginUserId, $userId);
					if (($friendStatus === MyFriends::STATUS_CONFIRMED)) {
						$disease = $this->User->getUserDiseases($userId);
					}
				}

				$userData[] = array(
					'user' => $this->User->getUserDetails($userId),
					'diseases' => $disease
				);
			}
			
			return $userData;
		}
    }

    function getNotificationCounts() {
        $this->autoRender = false;
        $loggedIn = $this->Auth->loggedIn();
        $notificationCounts = array();
        if ($loggedIn) {
            $loggedin_userid = $this->Auth->user('id');
            $pending_friend_requests_count = $this->MyFriends->getFriendsStatusCount($loggedin_userid, MyFriends::STATUS_REQUEST_RECIEVED);
            $unread_message_count = $this->UserMessage->getInboxMessagesCount($loggedin_userid);
			$unreadNotificationsCount = $this->NotificationSetting->getUserNotificationCount($loggedin_userid);
            $notificationCounts['success'] = TRUE;
            $notificationCounts['data'][] = array(
                'notification_name' => "pending_friend_requests_count",
                'notification_count' => $pending_friend_requests_count
            );
            $notificationCounts['data'][] = array(
                'notification_name' => "unread_message_count",
                'notification_count' => $unread_message_count
            );
            $notificationCounts['data'][] = array(
				'notification_name' => "unread_notification_count",
				'notification_count' => $unreadNotificationsCount
			);
        } else {
            $notificationCounts['success'] = FALSE;
        }
        if ($this->request->is('ajax')) {
            print_r(json_encode($notificationCounts));
            exit;
        } else {
//            return $notificationCounts;
        }
	}

	/**
	 * Function to mark the notifications related to an event as read by the 
	 * logged in user
	 * 
	 * @param int $eventId
	 */
	public function markEventNotificationRead($eventId) {
		$this->autoRender = false;
		$userId = $this->Auth->user('id');
		$this->Notification->markEventNotificationsReadByUser($eventId, $userId);
	}

	/**
	 * Function to mark the notifications related to a community as read by the 
	 * logged in user
	 * 
	 * @param int $communityId
	 */
	public function markCommunityNotificationRead($communityId) {
		$this->autoRender = false;
		$userId = $this->Auth->user('id');
		$this->Notification->markCommunityNotificationsReadByUser($communityId, $userId);
	}

	/**
	 * Function to mark a notification as read by the logged in user
	 * 
	 * @param int $notificationId
	 */
	public function markNotificationRead($notificationId) {
		$this->autoRender = false;
		$userId = $this->Auth->user('id');
		$this->Notification->markNotificationRead($notificationId, $userId);
	}
}
<?php

/**
 * FriendController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');
App::import('Controller', 'Api');
App::uses('UserPrivacySettings', 'Lib');
App::uses('FollowingPage', 'Model');

/**
 * FriendController for the frontend
 *
 * FriendController is used for to carry out operations on friends
 *
 * @author     Ajay Arjunan
 * @package 	User
 * @category	Controllers
 */
class FriendsController extends ProfileController {

    /**$userId
     * Models needed in the Controller
     *
     * @var array
     */
    protected $_mergeParent = 'ProfileController';
    public $uses = array(
        'User',
        'MyFriends',
        'CsvImportForm',
        'NotificationSetting',
        'Notification'
    );
    public $components = array(
        'Session',
        'EmailTemplate',
        'ImportContacts',
        'EmailInvite',
        'RecommendedFriend',
        'Csv',
        'VCard'
	);

	/**
	 * Profile -> Friends
	 */
	public function index($username = null) {
		$this->_setUserProfileData();
                if(isset($this->_requestedUser['id'])) {
                    $this->set('title_for_layout',$this->_requestedUser['username']."'s friends");
                } else {
                    $this->set('title_for_layout',$this->Auth->user('username')."'s friends");
                }
                
		if ($this->_requestedUser['id'] != $this->_currentUser['id'])
		{
			$privacy = new UserPrivacySettings($this->_requestedUser['id']);
			$isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'],
				$this->_currentUser['id']);
			$viewSettings = array($privacy::PRIVACY_PUBLIC);
			if ($isFriend == MyFriends::STATUS_CONFIRMED)
			{
				array_push($viewSettings, $privacy::PRIVACY_FRIENDS);
			}
			if (!in_array($privacy->__get('view_your_friends'), $viewSettings))
			{
				//redirect to profile page
				$this->redirect(Common::getUserProfileLink( $this->_requestedUser['username'], true));
			}
		}

		$friends = $this->MyFriends->getFriendsList($this->_requestedUser['id']);
		$friends_array = $this->MyFriends->getFriendsListFullDetails($this->_requestedUser['id'], $this->_currentUser['id']);

		$friendsListJson = json_encode(array(
			'friends' => array(
				'friend' => $friends
			)
		));

		if ($this->_requestedUser['id'] === $this->_currentUser['id']) {
			/*
			 * Get the Pending friends count of the logged in user
			 */
			$pending_count = $this->MyFriends->
					getFriendsStatusCount($this->_requestedUser['id'], MyFriends::STATUS_REQUEST_RECIEVED);
		}
		$this->set(compact('friends', 'friends_array', 'friendsListJson', 'pending_count'));
	}

	/**
	 * Function to add friends
	 */
    public function addFriend() {

		$this->autoRender = FALSE;
		$status = FALSE;
		$user_id = $this->Auth->user('id');
		
		if (!empty($this->request->query['token'])) {
			$token = $this->request->query['token'];
			$tokenData = json_decode(base64_decode($token), true);
			$friend_id = $tokenData['friend_id'];
			$search = $tokenData['search'];
			$redirect = true;
		} else {
			$friend_id = $this->request->data['friend_id'];
			$search = $this->request->data['search'];
		}
		
        $friendUserData = $this->User->findById($friend_id);

        $friends = $this->MyFriends->getAllFriendsList($user_id);

        if (!empty($friends)) {
            foreach ($friends as $friend) {
                if (($friend['user_id'] == $friend_id)) {
                    $status = TRUE;
                    switch ($friend['status']) {
                        case MyFriends::STATUS_REQUEST_SENT:
                            if ($search === 'false') {
                                $this->Session->setFlash(__('You have already sent friend request.'), 'error');
                            }
                            break;
                        case MyFriends::STATUS_CONFIRMED:
                            if ($search === 'false') {
                                $this->Session->setFlash(__("You're already a friend"), 'error');
                            }
                            break;
                        case MyFriends::STATUS_REQUEST_RECIEVED:
                            if ($search === 'false') {
                                $this->Session->setFlash(__('Please wait for the user to confirm'), 'error');
                            }
                            break;
                    }
                }
            }
        }

        if (!$status) {
            $this->MyFriends->addFriend($user_id, $friend_id);
			
			$this->loadModel('FollowingPage');
			//Profile follow data
			$profileData = array(
				'type' => FollowingPage::USER_TYPE,
				'page_id' => $friend_id,
				'user_id' => $user_id,
				'notification' => FollowingPage::NOTIFICATION_ON
			);
			$this->FollowingPage->followPage($profileData);
		
			
            if ($search === 'false') {
                $this->Session->setFlash(__('An invitation has been sent to ' .
                                Common::getUsername($friendUserData['User']['username'], $friendUserData['User']['username'], $friendUserData['User']['username'])
                        ), 'success');
            }
            //check friend's email preference setting before sending mail
			$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($friend_id, 'friend_request');
			if ($isEmailNotificationOn && (!$this->User->isUserOnline($friend_id))) {
				$this->__sendFriendRequestMail($user_id, $friend_id, EmailTemplateComponent::ADD_FRIEND_TEMPLATE);
			}
        }
		
		if (isset($redirect) && ($redirect === true)) {
			$friendUserName = $friendUserData['User']['username'];
			$friendProfile = Common::getUserProfileLink($friendUserName, true);
			$this->redirect($friendProfile);
		}
    }

    /*
     * Function to approve friends request
     *
     * @param int $friend_id
     * @param string search
     */

    public function approveFriend($friend_id = NULL, $search = 'email') {

        $this->autoRender = FALSE;
        $user = $this->Auth->user();
        $friend = $this->User->findById($friend_id);
        $status = $this->MyFriends->getFriendStatus($user['id'], $friend_id);
		$this->loadModel('FollowingPage');
			//Profile follow data
		$profileData = array(
				'type' => FollowingPage::USER_TYPE,
				'page_id' => $friend_id,
				'user_id' => $user['id'],
				'notification' => FollowingPage::NOTIFICATION_ON
			);
		$this->FollowingPage->followPage($profileData);

        switch ($status) {
            case 0:
                if ($search === 'false' || $search === 'email') {
                    $this->Session->setFlash(__('Something went wrong please refresh'), 'error');
                }
                break;
            case MyFriends::STATUS_REQUEST_SENT:
                if ($search === 'false' || $search === 'email') {
                    $this->Session->setFlash(__('You have already send friend request.'), 'error');
                }
                break;
            case MyFriends::STATUS_CONFIRMED:
                if ($search === 'false' || $search === 'email') {
                    $this->Session->setFlash(__('You have already approved the friend request.'), 'error');
                }
                break;
            case MyFriends::STATUS_REQUEST_RECIEVED:
                $this->MyFriends->approveFriend($user['id'], $friend_id);				
			
			
				$this->NotificationSetting->removeUserFromRecommendedUsers($friend_id, $user['id']);
                if ($search === 'false' || $search === 'email') {
                    $this->Session->setFlash(__('You are now friend with ' .
                                    Common::getUsername($friend['User']['username'], $friend['User']['username'], $friend['User']['username'])
                            ), 'success');
                }
				
			
				
                //check friend's email preference setting before sending mail
				$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($friend_id, 'friend_request_approval');
				if ($isEmailNotificationOn && (!$this->User->isUserOnline($friend_id))) {
					$this->__sendMail($user['id'], $friend_id, EmailTemplateComponent::APPROVE_FRIEND_INVITE_TEMPLATE);
				}
				$this->Notification->addFriendRequestApprovedNotification($user['id'], $friend_id);
				break;
		}
        if ($search == 'email') {
            $this->redirect( Common::getUserProfileLink(  $friend['User']['username'], true) );
        }
    }

    /*
     * Function to reject friends request
     *
     * @param int $friend_id
     * @param string search
     */

    public function rejectFriend($friend_id = NULL, $search = 'email') {

        $this->autoRender = FALSE;

        $user = $this->Auth->user();
        $status = $this->MyFriends->getFriendStatus($user['id'], $friend_id);

        $friend = $this->User->findById($friend_id);

        switch ($status) {
            case 0:
                if ($search === 'false' || $search === 'email') {
                    $this->Session->setFlash(__('Something went wrong please refresh'), 'error');
                }
                break;
            case MyFriends::STATUS_REQUEST_SENT:
                if ($search === 'false' || $search === 'email') {
                    $this->Session->setFlash(__('You have already send friend request.'), 'error');
                }
                break;
            case MyFriends::STATUS_CONFIRMED:
                if ($search === 'false' || $search === 'email') {
                    $this->Session->setFlash(__('You have already approved the friend request.'), 'error');
                }
                break;
            case MyFriends::STATUS_REQUEST_RECIEVED:
                $this->MyFriends->rejectFriend($user['id'], $friend_id);
                if ($search === 'false' || $search === 'email') {
                    $this->Session->setFlash(__('Invitation has been rejected'), 'success');
                }
                break;
        }
        if ($search == 'email') {
            $this->redirect( Common::getUserProfileLink( $friend['User']['username'], true));
            return TRUE;
        }
    }

    /*
     * Function to remove friend
     */

    public function removeFriend() {
        $this->autoRender = FALSE;
        $user_id = $this->Auth->user('id');
        $friend_id = $this->request->data['friend_id'];
        $search = $this->request->data['search'];
        $status = $this->MyFriends->getFriendStatus($user_id, $friend_id);
        $user = $this->User->findById($friend_id);
        $response = array();
        switch ($status) {
            case 0:
                if ($search === 'true') {
                    $response['message'] = 'Something went wrong please refresh.';
                    $response['type'] = 'error';
                }
                break;
            case MyFriends::STATUS_REQUEST_SENT:
                if ($search === 'true') {
                    $response['message'] = 'You have already send friend request.';
                    $response['type'] = 'error';
                }
                break;
            case MyFriends::STATUS_CONFIRMED:
				$this->MyFriends->removeFriend($user_id, $friend_id);
				$this->loadModel('FollowingPage');
				$this->FollowingPage->unFollowPage(array(
					'type' => FollowingPage::USER_TYPE,
					'page_id' => $friend_id,
					'user_id' => $user_id
				));
				$this->FollowingPage->unFollowPage(array(
					'type' => FollowingPage::USER_TYPE,
					'page_id' => $user_id,
					'user_id' => $friend_id
				));
                if ($search === 'true') {
                    $response['message'] = 'Removed ' .
                            Common::getUsername(
                                    $user['User']['username'], $user['User']['username'], $user['User']['username']);
                    $response['type'] = 'success';
                }
                break;
            case MyFriends::STATUS_REQUEST_RECIEVED:
                if ($search === 'true') {
                    $response['message'] = 'You have already approved the friend request.';
                    $response['type'] = 'error';
                }
                break;
        }

        echo json_encode($response);
    }

    /*
     * Function to send mails to friends
     *
     * @param int $user_id
     * @param int $friend_id
     * @param int $template id of email template
     */

    public function __sendMail($user_id, $friend_id, $template) {
        $Api = new ApiController;
        $Api->constructClasses();

        $user = $this->User->findById($user_id);
        $friend = $this->User->findById($friend_id);

        $toEmail = $friend['User']['email'];
        $emailData = array(
            'username' => Common::getUsername($friend['User']['username'], $friend['User']['username'], $friend['User']['username']),
            'friend_username' => Common::getUsername($user['User']['username'], $user['User']['username'], $user['User']['username']),
            'link' => Router::Url('/', TRUE) . 'profile/' .
            urlencode( Common::getUsername($user['User']['username'], $user['User']['username'], $user['User']['username']) )
        );
        $Api->sendHTMLMail($template, $emailData, $toEmail);
    }

    /*
     * Function to send friend request mail
     *
     * @param int $user_id
     * @param int $friend_id
     * @param int $template id of email template
     */

    public function __sendFriendRequestMail($user_id, $friend_id, $template) {
        $Api = new ApiController;
        $Api->constructClasses();

        $user = $this->User->findById($user_id);
        $friend = $this->User->findById($friend_id);
        $toEmail = $friend['User']['email'];
		
        $emailData = array(
            'username' => Common::getUsername($friend['User']['username'], $friend['User']['username'], $friend['User']['username']),
            'friend_username' => Common::getUsername($user['User']['username'], $user['User']['username'], $user['User']['username']),
            'link' => Router::Url('/', TRUE) . 'profile/' .
            urlencode( Common::getUsername($user['User']['username'], $user['User']['username'], $user['User']['username']) ),
            'accept_link' => Router::Url('/', TRUE) . 'user/friends/approveFriend/'
            . $user_id,
            'reject_link' => Router::Url('/', TRUE) . 'user/friends/rejectFriend/'
            . $user_id
        );
		
        $Api->sendHTMLMail($template, $emailData, $toEmail);
    }

    /*
     * Function to get mutual friends
     */

    public function getMutualFriends() {
        $this->autoRender = FALSE;

        $user_id = $this->request->data['user_id'];
        $friend_id = $this->request->data['friend_id'];
        $count = FALSE;
        $mutual_friends_json = array(
            'friends' => array(
                'friend' => array()
            )
        );

        $mutual_friends = $this->MyFriends->getMutualFriends($user_id, $friend_id, $count);

        $i = 0;
        foreach ($mutual_friends as $friend) {
            $mutual_friends_json['friends']['friend'][$i]['friend_id'] = $friend['User']['id'];
            $mutual_friends_json['friends']['friend'][$i]['friend_name'] = $friend['User']['username'];
            $i++;
        }

        $this->set(compact('mutual_friends'));
        $this->layout = "ajax";
        $View = new View($this, false);
        $response['html_content'] = $View->element('Friends/mutual_friends');
        $response['json'] = json_encode($mutual_friends_json);

        echo json_encode($response);
    }

    /**
     * Function to import contacts from Google
     */
    public function inviteGoogleContacts() {
        $result = $this->ImportContacts->importGoogleContacts();
//        $result = $this->ImportContacts->getDummyContacts();
        if (isset($result['success'])) {
            $data = array();
            if (isset($result['contacts']) && !empty($result['contacts'])) {
                $contacts = $result['contacts'];
                $data = $this->ImportContacts->getContactsInfo($contacts);
                if ($data['contactsCount'] > 0) {
                    $this->set($data);
                } else {
                    $errorMsg = __('No contacts to invite.');
                }
            } else {
                $errorMsg = __('No contacts imported.');
            }
        } elseif ($result['error']) {
            $errorMsg = $result['message'];
        }

        if (isset($errorMsg)) {
            $this->set('error', true);
            $this->Session->setFlash($errorMsg, 'error');
        }
        $this->view = 'import_contacts';
    }


      /**
     * Function to import contacts from Google
     */
    public function invitefbContacts() {
        $result = $this->ImportContacts->importfbContacts();
//        var_dump($result);
    }

    /**
     * Function to add imported users as friends, who are already in our
     * application.
     */
    public function addExistingConnections() {
        if (isset($this->request->data['existing_contacts'])) {
            $existingContacts = $this->request->data['existing_contacts'];
            $userId = $this->Auth->user('id');
            $invitedCount = 0;
            foreach ($existingContacts as $contactUserId) {
                $contactFriendshipStatus = $this->MyFriends->getFriendStatus($userId, $contactUserId);
                if ($contactFriendshipStatus === 0) {
                    $this->MyFriends->addFriend($userId, $contactUserId);
                    $this->__sendFriendRequestMail($userId, $contactUserId, EmailTemplateComponent::ADD_FRIEND_TEMPLATE);
                    $invitedCount++;
                }
            }
            $result = array(
                'success' => true,
                'message' => sprintf('Friend request sent to %d people.', $invitedCount)
            );
        } else {
            $result = array(
                'error' => true,
                'message' => 'No contacts selected.'
            );
        }
        $this->autoRender = false;
        echo json_encode($result);
        exit();
    }

    /**
     * Function to add imported users as friends, who are not in our application
     */
    public function addNewConnections() {
        if (isset($this->request->data['new_contacts'])) {
            $contactEmails = $this->request->data['new_contacts'];
            $userId = $this->Auth->user('id');
            $result = $this->EmailInvite->inviteNewContacts($contactEmails, $userId);
            if (!empty($result)) {
                if (isset($result['messages']['success'])) {
                    $successMsg = $result['messages']['success'];
                    if (isset($result['messages']['error'])) {
                        $successMsg.= ' ' . $result['messages']['error'];
                    }
                    $this->Session->setFlash($successMsg, 'success');
                    $result = array(
                        'success' => true
                    );
                } elseif (isset($result['messages']['error'])) {
                    $errorMsg = $result['messages']['error'];
                    $result = array(
                        'error' => true,
                        'message' => $errorMsg
                    );
                }
            } else {
                $result = array(
                    'error' => true,
                    'message' => 'No contacts invited.'
                );
            }
        } else {
            $result = array(
                'error' => true,
                'message' => 'No contacts selected.'
            );
        }
        $this->autoRender = false;
        echo json_encode($result);
        exit();
    }

    public function csvContactImport() {
        if (!empty($this->data)) {
            $this->CsvImportForm->create($this->request->data);

            if ($this->CsvImportForm->validates()) {
                // validation was successful, but no data was actually saved
                $filename = WWW_ROOT . 'uploads' . DS . time() . $this->data["CsvImportForm"]['csv_file']['name'];

                move_uploaded_file($this->data["CsvImportForm"]['csv_file']['tmp_name'], $filename);

                if ($this->request->data['CsvImportForm']['csv_file']['type'] == 'text/vcard') {
                    $formatedContacts = $this->VCard->importNameEmail($filename);
                } else { //CSV file
                    $records = $this->Csv->import($filename);
                    if (isset($records) && (count($records) > 0)) {
                        $formatedContacts = $this->ImportContacts->formatCSVContactInfo($records);
                    } else {
                        $this->set('error', true);
                        $this->Session->setFlash("No contacts to import", 'error');
                    }
                }

                if (isset($formatedContacts) && (count($formatedContacts) > 0)) {
                    $data = $this->ImportContacts->getContactsInfo($formatedContacts);
                    $this->set($data);
                    unlink($filename);
                    $this->view = 'import_contacts';
                } else {
                    unlink($filename);
                    $this->set('error', true);
                    $this->Session->setFlash("Invalid or corrupted file", 'error');
                }
            }
        }
    }
}
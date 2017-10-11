<?php

/**
 * ApiController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Controller', 'Controller');
App::import('Controller', 'Api');
App::uses('Validation', 'Utility');
App::uses('Common', 'Utility');
App::uses('CakeTime', 'Utility');
App::uses('ScriptLockComponent', 'Controller/Component');

/**
 * ApiController for the application.
 *
 * ApiController is used for API calls common to frontend and backend.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	app.Controller
 * @category	Controllers
 */
class ApiController extends Controller {

	/**
	 * Models used by this controller
	 *
	 * @var array
	 */
	public $uses = array('City',
		'Country',
		'State',
		'User',
		'Disease',
		'Treatment',
		'MyFriends',
		'Event',
		'EventMember',
		'Community',
		'CommunityMember',
		'CommunityDisease',
		'Disease',
		'Email',
		'Media',
		'Post',
		'EmailsHistory',
		'Symptom',
		'Treatment',
		'InvitedUser',
		'ActionToken',
		'HealthReading',
		'Timezone',
		'NotificationSetting',
		'PatientDisease',
		'UserTreatment',
		'TeamMember',
		'Team',
		'EventDisease',
		'DiseaseSymptom',
                'Photo'
	);
	public $components = array(
		'EmailTemplate',
                'Paginator',
                'EmailQueue',
                'VideoProcessing',
                'RadiusSearch',
                'Otp',
                'Uploader',
                'Session',
                'RecommendedFriend'
        );

	const BATCH_LIMIT = 100;
	const ADMIN_USER = 1;
        
        /**
         * Variable to store the minimum image size
         */
        public $minimumImageSize = array(
                'User' => array('554', '260'),
                'Community' => array('780', '350'),
                'Event' => array('780', '350')
            );

	/**
	 * Array to store API output data
	 *
	 * @var array
	 */
	public $data = array();
	
	/**
	 * Array of email template ids where unsubscribe link is needed in email footer
	 *
	 * @var array
	 */
	public $emailTemplateIds = array(4,5,6,7,9,10,13,14,18,21,22,24,25,26,28,31,32,72,75);

	/**
	 * Disable auto render
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->autoRender = false;
	}

	/**
	 * Output JSON data
	 */
	public function afterFilter() {
		parent::afterFilter();
		echo json_encode($this->data);
	}

	/**
	 * Temporary function to add friends
	 *
	 * (Will be deleted when friends functionality is in place)
	 */
	public function addMyFriends() {
		// truncate
		$this->MyFriends->query('TRUNCATE TABLE `my_friends`');

		// fetch users who are active and not admin
		$users = $this->User->find('list', array(
			'fields' => array('id'),
			'conditions' => array('is_admin' => 0, 'status' => 1)
		));

		foreach ($users as $userId) {
			$otherUsers = $this->__getOtherUsers($userId, $users);
			$friends = array();
			$friendsData = array();
			foreach ($otherUsers as $otherUser) {
				$friends[] = array(
					'user_id' => $otherUser,
					'status' => 2
				);
			}

			// json
			$friendsData['friends'] = $friends;
			$friendsJSON = json_encode($friendsData);

			// data to save
			$data[] = array(
				'my_id' => $userId,
				'friends' => $friendsJSON
			);
		}

		// save
		$status = $this->MyFriends->saveAll($data);

		echo '<pre>';
		print_r($data);
		echo PHP_EOL;
		echo ($status === true) ? 'success' : 'failed';
		echo '</pre>';
		exit();
	}

	/**
	 * Temporary function (used by addFriends function above)
	 *
	 * (Will be deleted when friends functionality is in place)
	 */
	private function __getOtherUsers($userId, $users) {
		unset($users[$userId]);
		return $users;
	}

	/**
	 * Temporary function to update member count of existing communitys
	 */
	public function updateCommunityMemberCount() {

		$communities = $this->Community->find('all');

		foreach ($communities as $community) {
			$communityId = $community['Community']['id'];
			$approvedMembersCount = $this->CommunityMember->find('count', array(
				'conditions' => array(
					'CommunityMember.community_id' => $communityId,
					'CommunityMember.status' => CommunityMember::STATUS_APPROVED
				)
			));

			// data to save
			$data[] = array(
				'id' => $communityId,
				'member_count' => $approvedMembersCount,
			);
		}

		// save
		$status = $this->Community->saveAll($data);

		echo '<pre>';
		print_r($data);
		echo PHP_EOL;
		echo ($status === true) ? 'success' : 'failed';
		echo '</pre>';
		exit();
	}

	/**
	 * Get all countries
	 */
	public function getAllCountries() {
		$this->data = $this->Country->getAllCountries();
	}

	/**
	 * Get all cities
	 */
	public function getAllCities() {
		$this->data = $this->City->find('list', array(
			'fields' => array('id', 'description'),
		));
	}

	/**
	 * Get all states
	 */
	public function getAllStates() {
		$this->data = $this->State->find('list', array(
			'fields' => array(
				'id',
				'description'
			)
		));
	}

	/**
	 * Get all states in a country
	 */
	public function getCountryStates($countryId) {
		$data = $this->Country->getCountryStates($countryId);

		$this->data = $data;
	}

	/**
	 * Get all cities in a state
	 */
	public function getStateCities($stateId) {
		$data = $this->State->getStateCities($stateId);
		$this->data = $data;
	}

	/**
	 * Checks if username already exists or not
	 */
	public function checkExistingUsername() {
		$username = $this->request->data['username'];
		$user = $this->User->findByUsername($username);
		if (!empty($user ['User'])) {
			if (isset($this->request->data['id']) && $this->request->data['id'] > 0) {
				$userId = intval($this->request->data['id']);
				$userUserId = intval($user['User']['id']);
				$this->data = ($userUserId === $userId) ? true : false;
			} else {
				$this->data = false;
			}
		} else {
			$this->data = true;
		}
	}

	/**
	 * Checks if email already exists or not
	 */
	public function checkExistingEmail() {
		$email = $this->request->data['email'];
		if (Validation::email($email, true)) {
			$user = $this->User->findByEmail($email);
			if (!empty($user['User'])) {
				if (isset($this->request->data['id']) && $this->request->data['id'] > 0) {
					$userId = intval($this->request->data['id']);
					$userUserId = intval($user['User']['id']);
					$this->data = ($userUserId === $userId) ? true : false;
				} else {
					$this->data = false;
				}
			} else {
				$this->data = true;
			}
		} else {
			$this->data = false;
		}
	}

	/**
	 * Checks if given US zipcode exist or not.
	 * 
	 * return Boolean
	 */
	public function checkValidUSZip() {
		$cityName = urlencode($this->request->data['cityName']);
		$cityId = $this->request->data['cityId'];
		$zipcode = $this->request->data['zipcode'];
		$stateName = urlencode($this->request->data['stateName']);		

		$smartyStreetConfig = Configure::read('API.SmartyStreets');
		$auth_id = $smartyStreetConfig['AUTH_ID'];
		$auth_token = $smartyStreetConfig['AUTH_TOKEN'];

		$this->loadModel('City');
		$cityData = $this->City->findById($cityId);
		$matchFlag = false;

		if (empty($cityData['City']['smartystreet_data'])) {
			$url = "https://api.smartystreets.com/zipcode?city=$cityName&state=$stateName&auth-id=$auth_id&auth-token=$auth_token";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_PROXYPORT, 80);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response);
			$this->City->id = $cityId;
			$this->City->saveField('smartystreet_data', $response); //save json to our db - reuse			
		} else {
			$response_a = json_decode($cityData['City']['smartystreet_data']);
		}
		
		foreach ($response_a[0]->zipcodes as $zipcodeObj) {
			if ($zipcodeObj->zipcode === $zipcode) {
				$matchFlag = true;
				break;
			}
		}

		$this->data = $matchFlag;
	}
	
	/**
	 * Get user id from email address
	 * return 0 if not an existing user
	 */
	public function getUserIdFromEmailAddress($email = NULL) {
		$user_id = 0;
		if ($email != NULL) {
			if (Validation::email($email, true)) {
				$user = $this->User->findByEmail($email);
				if (!empty($user['User'])) {
					$user_id = $user['User']['id'];
				}
			}
		}
		return $user_id;
		exit();
	}
        
        /**
     * Search diseases by name
     */
    public function searchDisease() {
        $searchStr = $this->request->query['term'];
        $data = $this->Disease->find('list', array(
            'conditions' => array('Disease.name LIKE' => "%{$searchStr}%"))
        );
        if (!empty($data)) {
            //auto complete section
            $items = array();
            foreach ($data as $id => $name) {
                $items[] = array(
                    'label' => $name,
                    'value' => $name,
                    'id' => $id
                );
            }
            $this->data = $items;
        } else {
            //Did you mean section with spell suggestion
            $first_word = substr($searchStr, 0, 1);
            $searchStr = strtolower($searchStr);

            $data = $this->Disease->find('list', array(
            'conditions' => array('Disease.name LIKE' => "%{$first_word}%"))
           );
            if (!empty($data)) {
                $items = array();
                $items[0] = array(
                    'label' => 'Did you mean ?',
                    'value' => '',
                    'id' => '',
                    'percentage' => 110
                ); //gave % more than 100 to display it 1st while sorting.
                $have_value = 0;
                foreach ($data as $id => $name) {
                    $disease_name = strtolower($name);
                    similar_text($disease_name, $searchStr, $percent); // get similar text.
                    //Take only words having a certain %
                    if ($percent > 40) {
                        $have_value = 1;
                        $items[] = array(
                            'label' => $name,
                            'value' => $name,
                            'id' => $id,
                            'percentage' => $percent
                        );
                    }
                }

                if ($have_value) {

                    function cmp($a, $b) {
                        return $b["percentage"] - $a["percentage"];
                    }

                    @usort($items, "cmp"); //used @ to suppress warning due to a bug in php version.
                } else { // no value for suggestion so unset the did you mean.
                    unset($items[0]);
                }
                
                if( strlen($searchStr) <= 50) {
                    $addKeywordText = array(
                        'label' => $searchStr . ' isn\'t in our system. Submit to add it',
                        'value' => $searchStr,
                        'id' => '0'
                    ); //new disease so value -1
                    array_unshift($items, $addKeywordText);
                }
                $this->data = $items;
            }
        }
	}

	/**
	 * Search treatments by name
	 */
	public function searchTreatments() {
		if (isset($this->request->query['q'])) {
			$searchStr = $this->request->query['q'];
			$scenario = 'facelist';
		} elseif (isset($this->request->query['term'])) {
			$searchStr = $this->request->query['term'];
			$scenario = 'autocomplete';
		}
		$data = $this->Treatment->find('list', array(
			'conditions' => array('Treatment.name LIKE' => "{$searchStr}%"))
		);
		if (!empty($data)) {
			if ($scenario === 'facelist') {
				foreach ($data as $id => $name) {
					echo strip_tags("$name|$id\n");
				}
			} elseif ($scenario === 'autocomplete') {
				$items = array();
				foreach ($data as $id => $name) {
					$items[] = array(
						'label' => $name,
						'value' => $name,
						'id' => $id
					);
				}
				$this->data = $items;
			}
		} else {
			if ($scenario === 'autocomplete') {
				$items[] = array(
					'label' => $searchStr . ' isn\'t in our system.',
					'value' => $searchStr,
					'id' => 0
				);
				$this->data = $items;
			}
		}

		if ($scenario === 'facelist') {
			exit();
		}
	}

	/**
	 * Search diseases by name
	 */
	public function searchDiseaseNames() {
		if (isset($this->request->query['q'])) {
			$searchStr = $this->request->query['q'];
			$data = $this->Disease->find('list', array(
				'conditions' => array('Disease.name LIKE' => "%{$searchStr}%"))
			);
			if (!empty($data)) {
				foreach ($data as $id => $name) {
					echo strip_tags("$name|$id\n");
				}
			}
		}
		exit();
	}

	/**
	 * Search symptom by name
	 */
	public function searchSymptomNames() {
		if (isset($this->request->query['q'])) {
			$searchStr = $this->request->query['q'];
			$data = $this->Symptom->find('list', array(
				'conditions' => array('Symptom.name LIKE' => "%{$searchStr}%"))
			);
			if (!empty($data)) {
				foreach ($data as $id => $name) {
					echo strip_tags("$name|$id\n");
				}
			}
		}
		exit();
	}
	
	/**
	 * Search city by name
	 */
	public function searchCityNames() {
		if (isset($this->request->query['q'])) {
			$searchStr = $this->request->query['q'];
			$data = $this->City->searchCityLocations($searchStr);
			if (!empty($data)) {
				foreach ($data as $id => $name) {
					echo strip_tags("$name|$id\n");
				}
			}
		}
		exit();
	}

	/**
	 * Function to search for friends
	 */
	public function getFriendList($user_id = NULL) {

		if ($this->User->exists($user_id)) {
			$my_confirmed_friends = $this->MyFriends->getFriendsList($user_id);
		}
		$this->set('friends_confirmed', json_encode($my_confirmed_friends));
		return ($my_confirmed_friends);
	}
	/*
	 * Reusabe function to invite friends to the event
	 */

	public function _invite_memebers_to_event($event_id, $user_list = array(), $invited_user_id) {
		$result = array();
		try {
			if (isset($event_id) && $event_id > 0) {
				$event = $this->Event->getEvent($event_id);
				if (isset($event)) {
					if (!empty($user_list)) {
						foreach ($user_list as $userId) {
							$this->EventMember->create();
							$save = $this->EventMember->save($data = array(
								'event_id' => $event_id,
								'user_id' => $userId,
								'status' => 0,
								'invited_by' => $invited_user_id
							));
						}

						// add event invite site notifications
						$this->loadModel('Queue.QueuedTask');
						$this->QueuedTask->createJob('EventInviteNotification', array(
							'event_id' => $event_id,
							'sender_id' => $invited_user_id,
							'recipients' => $user_list,
							'event_name' => $event['name']
						));

						foreach ($user_list as $userId) {
							//get email notification setting for the user before sending the email
							$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($userId, 'event_invitation');
							if ($isEmailNotificationOn && (!$this->User->isUserOnline($userId))) {
								$userDetail = $this->User->getUserDetails($userId);
								$emailData = array(
									'username' => Common::getUsername($userDetail['user_name'], $userDetail['first_name'], $userDetail['last_name']),
									'link' => Router::Url('/', TRUE) . 'event/details/index/' . $event_id,
									'eventname' => h($event['name'])
								);

								//Getting email template from database
								$emailManagement = $this->EmailTemplate->getEmailTemplate(EmailTemplateComponent::EVENT_INVITES_TEMPLATE, $emailData);

								// data to be saved
								$mailData = array(
									'subject' => $emailManagement['EmailTemplate']['template_subject'],
									'to_name' => $userDetail['user_name'],
									'to_email' => $userDetail['email'],
									'content' => json_encode($emailData),
									'email_template_id' => EmailTemplateComponent::EVENT_INVITES_TEMPLATE,
									'module_info' => 'Invite to event',
									'priority' => Email::DEFAULT_SEND_PRIORITY
								);

								$this->EmailQueue->createEmailQueue($mailData);
							}
						}
						$result['success'] = true;
						$result['message'] = 'Invitation has been sent';
						$result['message_type'] = 'success';
					}
				}
			}
		} catch (Exception $e) {
			$result['success'] = false;
			$result['message'] = $e->getMessage();
			$result['message_type'] = 'warning';
		}
		return $result;
	}
	/*
	 * Function to handle the friends invite from the popup
	 */

	public function eventInvites($eventId = NULL, $invited_by = NULL, $users = NULL) {

		if ($eventId == NULL) {
			$eventId = $this->request->data['id'];
		}
		if ($invited_by == NULL) {
			$invited_by = $this->request->data['invited_by'];
		}
		if ($users == NULL) {
			$users = $this->request->data['users'];
		}

		$result = $this->_invite_memebers_to_event($eventId, $users, $invited_by);

		$this->data = $result;
	}

	/**
	 * Function to send event update mail.
	 *
	 * @param array $emailData
	 * @param string $toEmail
	 */
	public function sendEventUpdateMail($emailData, $toEmail) {
		$this->sendHTMLMail(EmailTemplateComponent::UPDATE_EVENT_TEMPLATE, $emailData, $toEmail);
	}
	/*
	 * function to collect all upcoming event data and the RSVP changes on the current day.
	 */

	public function dailyReportToEventCreator() {
		ScriptLockComponent::lock(__FUNCTION__);
		
		$now = date("Y-m-d");
		//getting all upcoming events including the current day as event end date.
		$events = $this->Event->find('all', array(
			'conditions' => array(
				'Event.end_date >=' => $now,
			),
			'fields' => array('Event.id, Event.name, Event.created_by')
				)
		);

		if (isset($events) && $events != NULL) {
			$combinedList = array();
			foreach ($events as $event) {
				$combinedList[$event['Event']['created_by']][] = $event['Event'];
			}
			$eventMembersUpdates = array();
			foreach ($combinedList as $cList) {
				foreach ($cList as $eventList) {
					$eventMembersUpdates = $this->EventMember->find('all', array(
						'conditions' => array(
							'EventMember.event_id' => $eventList['id'],
							'EventMember.modified >=' => $now
						)
					));
					if (isset($eventMembersUpdates) && $eventMembersUpdates != NULL) {
						foreach ($eventMembersUpdates as $updates) {
							$user_details = $this->User->getUserDetails($updates['EventMember']['user_id']);
							$rsvp_details[$updates['EventMember']['id']]['user_name'] = $user_details['user_name'];
							switch ($updates['EventMember']['status']) {
								case 0:
									$rsvp_details[$updates['EventMember']['id']]['user_status'] = 'Invited';
									break;
								case 1:
									$rsvp_details[$updates['EventMember']['id']]['user_status'] = 'Attending';
									break;
								case 2:
									$rsvp_details[$updates['EventMember']['id']]['user_status'] = 'Not Attending';
									break;
								case 3:
									$rsvp_details[$updates['EventMember']['id']]['user_status'] = ' Might be Attending';
									break;
							}
						}

						$result[$eventList['created_by']]['creator'] = $this->User->getUserDetails($eventList['created_by']);
						$result[$eventList['created_by']]['events'][] = array(
							"event_id" => $eventList['id'],
							"event_name" => h($eventList['name']),
							"updated_rsvps" => $rsvp_details
						);
						$eventMembersUpdates = NULL;
						$rsvp_details = NULL;
					}
				}
			}
			if (isset($result) && $result != NULL) {
//				echo '<pre>';
//				print_r("thre is no updates for the day for selected events. <br />");
//                print_r($result);
//                exit;
				$this->sendDailyReportMailToEventCreator($result);
			} else {
				print_r("thre is no updates for the day for selected events");
			}
		} else {
			print_r("there is no upcooming events");
		}
	}
	/*
	 * send mails to all pending event creators with the resulting data from dailyReportToEventCreatexit();or() method.
	 */

	function sendDailyReportMailToEventCreator($result = NULL) {

		if (isset($result) && $result != NULL) {
			$mailCount = 1;
			$onlineBaseUrl = "patients4life.qburst.com";
			$baseUrl = Configure::read('App.fullBaseUrl');
			if (!isset($baseUrl) || $baseUrl == NULL) {
				$baseUrl = $onlineBaseUrl;
			}
			foreach ($result as $users) {
				$mailContent = ' ';
				foreach ($users['events'] as $evnts) {
					$mailContent = $mailContent . '<br /><div style="font-weight: bold; font-size: 20px; color: black;">' . $evnts['event_name'] . '</div><br /><table cellspacing="0" style="width: 100%;"><th style="width: 50%; border-bottom: 1px solid #D5E4ED; padding: 8px 15px; color: black; text-align: left; background: #F2F2F2;">Username</th><th style="width: 50%; border-bottom: 1px solid #D5E4ED; padding: 8px 15px; color: black; text-align: left; background: #F2F2F2;">RSVP status</th>';
					foreach ($evnts['updated_rsvps'] as $rsvpUpdates) {
						$mailContent = $mailContent . '<tr><td style="width: 50%; border-bottom: 1px solid #D5E4ED; padding: 8px 15px;">' . $rsvpUpdates['user_name'] . '</td><td style="width: 50%; border-bottom: 1px solid #D5E4ED; padding: 8px 15px;">' . $rsvpUpdates['user_status'] . '</td></tr>';
					}
					$mailContent = $mailContent . '</table> <br /><a href="' . $baseUrl . '/event/details/index/' . $evnts['event_id'] . '">More details on this event</a>';
				}
				$email = $users['creator']['email'];
				$emailData = array(
					'event_creator_username' => $users['creator']['user_name'],
					'date' => date("m/d/Y"),
					'daily_mail_data' => $mailContent
				);
				$emailManagement = $this->EmailTemplate->getEmailTemplate(EmailTemplateComponent::DAILY_EVENT_REPORT_TEMPLATE, $emailData);


				// email queue to be saved
				$mailData = array(
					'subject' => $emailManagement['EmailTemplate']['template_subject'],
					'to_name' => $emailData['event_creator_username'],
					'to_email' => $email,
					'content' => json_encode($emailData),
					'email_template_id' => EmailTemplateComponent::DAILY_EVENT_REPORT_TEMPLATE,
					'module_info' => 'DailyReportMailToEventCreator',
					'priority' => Email::DEFAULT_SEND_PRIORITY
				);

				$this->EmailQueue->createEmailQueue($mailData);

				echo 'mail sent  ' . $mailCount . '   ' . $email;
				$mailCount++;
			}
		}
		exit;
	}
	/*
	 * Function to handle the friends invite from the popup
	 */

	public function communityInvites($communityId = NULL, $users = NULL) {

		if ($communityId == NULL) {
			$communityId = $this->request->data['id'];
		}
		if ($users == NULL) {
			$users = $this->request->data['users'];
		}

		$invitedBy = $this->request->data['invited_by'];

		$result = $this->inviteMembersToCommunity($communityId, $users, $invitedBy);

		$this->data = $result;
	}

	/**
	 * Function to invite members to community
	 *
	 * @param int $communityId community id
	 * @param array $members member ids
	 * @param int $invitedBy invited user id
	 * @return array
	 */
	public function inviteMembersToCommunity($communityId, $members, $invitedBy) {
		try {
			if (isset($communityId) && $communityId > 0) {
				$community = $this->Community->findById($communityId);
				if (!empty($community)) {
					if (!empty($members)) {
						// add members to community
						$this->CommunityMember->invited_by = $invitedBy;
						$this->CommunityMember->inviteCommunityMembers($communityId, $members);

						// send invitation mail to the members
						$communityDetailLink = Router::Url('/', TRUE) . 'community/details/index/' . $communityId;
						$communityName = h($community['Community']['name']);
						$emailData = array(
							'link' => $communityDetailLink,
							'communityname' => $communityName
						);
						foreach ($members as $userId) {
							// check user preference before sending mail
							$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($userId, 'community_invitation');
							if ($isEmailNotificationOn && (!$this->User->isUserOnline($userId))) {
								$userDetail = $this->User->getUserDetails($userId);
								$username = Common::getUsername($userDetail['user_name'], $userDetail['first_name'], $userDetail['last_name']);
								$emailData['username'] = $username;
								$toEmail = $userDetail['email'];
								$this->__sendCommunityInvitationMail($emailData, $toEmail);
							}
						}

						// add community invite notification task to job queue
						$this->loadModel('Queue.QueuedTask');
						$this->QueuedTask->createJob('CommunityInviteNotification', array(
							'community_id' => $communityId,
							'sender_id' => $invitedBy,
							'recipients' => $members,
							'community_name' => $community['Community']['name']
						));

						$result['success'] = true;
						$result['message'] = 'Invitation mail has been sent';
						$result['message_type'] = 'success';
					}
				}
			}
		} catch (Exception $e) {
			$result['success'] = false;
			$result['message'] = $e->getMessage();
			$result['message_type'] = 'warning';
		}
		return $result;
	}

	/**
	 * Function to send community invitation mail
	 *
	 * @param array $emailData email data
	 * @param string $toEmail to email
	 */
	private function __sendCommunityInvitationMail($emailData, $toEmail) {
		$this->sendHTMLMail(EmailTemplateComponent::INVITE_COMMUNITY_MEMBER_TEMPLATE, $emailData, $toEmail);
	}

	/**
	 * Function to send HTML mail using templates stored in database.
	 *
	 * @param int $templateId template id
	 * @param array $templateData template data
	 * @param string $toEmail to email
	 * @param array $settings email settings
	 */
	public function sendHTMLMail($templateId, $templateData, $toEmail, $settings = array()) {
		// getting email template from database
		$emailTemplateData = $this->EmailTemplate->getEmailTemplate($templateId, $templateData);
		$emailTemplate = $emailTemplateData['EmailTemplate'];

		// email queue to be saved
		$mailData = array(
			'subject' => $emailTemplate['template_subject'],
			'to_name' => $templateData['username'],
			'to_email' => $toEmail,
			'content' => json_encode($templateData),
			'email_template_id' => $templateId,
			'module_info' => 'API Email',
			'priority' => Email::DEFAULT_SEND_PRIORITY
		);

		if (isset($settings['priority'])) {
			$mailData['priority'] = $settings['priority'];
		}

		if (isset($settings['module_info'])) {
			$mailData['module_info'] = $settings['module_info'];
		}
		
		return $this->EmailQueue->createEmailQueue($mailData);
	}

	/**
	 * Function to process email queue and to send HTML mail using templates stored in database.
	 *
	 */
	public function processEmailQueue() {
		ScriptLockComponent::lock(__FUNCTION__);
		
		$emails = $this->Email->find('all', array(
			'conditions' => array(
			    'Email.status' => Email::STATUS_NOT_SEND,
			    'Email.instance_id' => ''
			),
			'recursive' => 1,
			'order' => array('Email.priority DESC'),
			'limit' => self::BATCH_LIMIT
				)
		);		
		// creating new email object
		$cakeEmail = new CakeEmail();

		$date = new DateTime();

		foreach ($emails as $emailData) {

			$cakeEmail->reset();

			$priorityValue = intval($emailData["Email"]["priority"]) + 1;
			//Getting email template from database
			$emailManagement = $this->EmailTemplate->getEmailTemplate($emailData["Email"]["email_template_id"], json_decode($emailData["Email"]["content"], TRUE));
			// setting email configurations, and sending email
			
			// setting unsubscribe url in the mail footer
			$templateId = $emailData["Email"]["email_template_id"];
			if (in_array($templateId, $this->emailTemplateIds)) {
				$autoLoginToken = $this->Otp->createOTP(array(
					'email' => $emailData["Email"]["to_email"]
				));
				$email = base64_encode($emailData["Email"]["to_email"]);
				$unsubscribeUrl = Router::Url('/', TRUE) . 'unsubscribe?setting=' .$templateId. '&auto_login_token=' . $autoLoginToken .'&email=' . $email ;
			} else {
				$unsubscribeUrl = '';
			}
			
			try {
				$cakeEmail->config('smtp')
						->template('default')
						->viewVars(array('unsubscribe' => $unsubscribeUrl ))
						->emailFormat('html')
						->to($emailData["Email"]["to_email"])
						->subject($emailManagement['EmailTemplate']['template_subject'])
						->setHeaders(array('List-Unsubscribe' => $unsubscribeUrl))
						->send($emailManagement['EmailTemplate']['template_body']);
				$this->Email->set(array(
					'id' => $emailData["Email"]["id"],
					'sent_date' => $date->format('Y-m-d h:i:s'),
					'status' => Email::STATUS_SEND
				));
				$this->Email->save();
			} catch (Exception $e) {
				$this->Email->set(array(
					'id' => $emailData["Email"]["id"],
					'priority' => $priorityValue,
					'status' => Email::STATUS_NOT_SEND
				));
				$this->Email->save();
			}
		}
		$this->_moveMailsToHistory();
	}
	
	/**
	 * Function to process email queue and to send HTML mail using templates stored in database.
	 *
	 */
	public function processNewsletterQueue() {
		ScriptLockComponent::lock(__FUNCTION__);
		
		$emails = $this->Email->find('all', array(
			'conditions' => array(
			    'Email.status' => Email::STATUS_NOT_SEND,
			    'Email.instance_id !=' => ''
			),
			'recursive' => 1,
			'order' => array('Email.priority DESC'),
			'limit' => self::BATCH_LIMIT
				)
		);		

		
		// creating new email object
		$cakeEmail = new CakeEmail();

		$date = new DateTime();

		foreach ($emails as $emailData) {

			$cakeEmail->reset();

			$priorityValue = intval($emailData["Email"]["priority"]) + 1;

			$emailContent = json_decode($emailData["Email"]["content"], TRUE);

			// setting email configurations, and sending email
			try {
				$cakeEmail->config('smtp')
//						->template('default')
						->emailFormat('html')
						->to($emailData["Email"]["to_email"])
						->subject($emailData["Email"]['subject'])
						->send($emailContent["content"]);
				$this->Email->set(array(
					'id' => $emailData["Email"]["id"],
					'sent_date' => $date->format('Y-m-d h:i:s'),
					'status' => Email::STATUS_SEND
				));
				$this->Email->save();
			} catch (Exception $e) {
				$this->Email->set(array(
					'id' => $emailData["Email"]["id"],
					'priority' => $priorityValue,
					'status' => Email::STATUS_NOT_SEND
				));
				$this->Email->save();
			}
		}
		$this->_moveMailsToHistory();
	}

	/**
	 * Moves sent mails to history table
	 */
	private function _moveMailsToHistory() {
		$emails = $this->Email->find('all', array(
			'conditions' => array('Email.status' => Email::STATUS_SEND),
			'recursive' => 1
				)
		);
		foreach ($emails as $emailData) {
			$mailData = array(
				'subject' => $emailData["Email"]["subject"],
				'to_name' => $emailData["Email"]["to_name"],
				'to_email' => $emailData["Email"]["to_email"],
				'from_email' => $emailData["Email"]["from_email"],
				'from_name' => $emailData["Email"]["from_name"],
				'content' => json_encode($emailData["Email"]["content"]),
				'module_info' => $emailData["Email"]["module_info"],
				'email_template_id' => $emailData["Email"]["email_template_id"],
				'sent_date' => $emailData["Email"]["sent_date"],
				'priority' => $emailData["Email"]["priority"],
				'status' => $emailData["Email"]["status"],
				'attachment' => $emailData["Email"]["attachment"],
				'instance_id' => $emailData["Email"]["instance_id"]
			);
			$this->EmailsHistory->save($mailData);
		}

		$this->Email->deleteAll(array(
			'Email.status' => Email::STATUS_SEND
				), FALSE);
	}

	/**
	 * Checks if community name already exists or not
	 */
	public function checkExistingCommunityName() {
		$name = $this->request->data['name'];
		$community = $this->Community->findByName($name);
		if (!empty($community['Community'])) {
			$id = $this->request->data['id'];
			if ($id > 0) {
				// if editing community, valid if community ids match
				$communityId = $community['Community']['id'];
				$valid = ($id == $communityId) ? true : false;
			} else {
				$valid = false;
			}
		} else {
			$valid = true;
		}

		$this->data = $valid;
	}

	/**
	 * Checks if event name already exists or not
	 */
	public function checkExistingEventName() {
		$name = $this->request->data['name'];
		$event = $this->Event->findByName($name);
		if (!empty($event['Event'])) {
			$mainEventTypes = array(Event::EVENT_TYPE_PRIVATE, Event::EVENT_TYPE_PUBLIC, Event::EVENT_TYPE_SITE);
			if (in_array($event['Event']['event_type'], $mainEventTypes)) {
				$id = $this->request->data['id'];
				if ($id > 0) {
					// if editing event, valid if event ids match
					$eventId = $event['Event']['id'];
					$valid = ($id == $eventId) ? true : false;
				} else {
					$valid = false;
				}
			} else {
				$valid = true;
			}
		} else {
			$valid = true;
		}

		$this->data = $valid;
	}

	/**
	 * Search location (Country) by name
	 */
	public function searchLocation() {
		$searchStr = $this->request->query['term'];
		$data = $this->Country->find('list', array(
			'conditions' => array('Country.short_name LIKE' => "{$searchStr}%"),
			'fields' => array('id', 'short_name'))
		);
		if (!empty($data)) {
			$items = array();
			foreach ($data as $id => $short_name) {
				$items[] = array(
					'label' => $short_name,
					'value' => $short_name,
					'id' => $id
				);
			}
			$this->data = $items;
		}
	}

	/**
	 * Checks if disease name already exists or not
	 */
	public function checkExistingDiseaseName($name = NULL, $id = 0) {
		if (isset($this->request->data ['name'])) {
			$name = trim($this->request->data ['name']);
		}
		$disease = $this->Disease->findByName($name);
		if (!empty($disease['Disease'])) {
			if (isset($this->request->data ['id'])) {
				$id = $this->request->data ['id'];
			}
			if ($id > 0) {
				// if editing disease, valid if disease ids match
				$diseaseId = $disease['Disease']['id'];
				$valid = ($id == $diseaseId) ? true : false;
			} else {
				$valid = false;
			}
		} else {
			$valid = true;
		}
		if (!isset($this->request->data ['name'])) {
			return $valid;
			exit();
		} else {
			$this->data = $valid;
		}
	}

	/**
	 * Function to update the status of videos.
	 *
	 * This function checks if there are any videos which are under
	 * processing status, and gets the thumbnail of those videos.
	 * If the transcoding is complete, updates the status of the video as ready
	 * and updates the thumbnail url info.
	 */
	public function updateVideoStatus() {
		ScriptLockComponent::lock(__FUNCTION__);
		$this->VideoProcessing->updateVideoStatus();
	}

	/**
	 * Search Symptom by name
	 */
	public function searchSymptom() {
		$searchStr = $this->request->query['term'];
		$data = $this->Symptom->find('list', array(
			'conditions' => array('Symptom.name LIKE' => "%{$searchStr}%"))
		);
		if (!empty($data)) {
			$items = array();
			foreach ($data as $id => $name) {
				$items[] = array(
					'label' => $name,
					'value' => $name,
					'id' => $id
				);
			}
			$this->data = $items;
		}
	}      
   
	/**
	 * Search Treatment by name
	 */
	public function searchTreatment() {
		$searchStr = $this->request->query['term'];
		$data = $this->Treatment->find('list', array(
			'conditions' => array('Treatment.name LIKE' => "%{$searchStr}%"))
		);
		if (!empty($data)) {
			$items = array();
			foreach ($data as $id => $name) {
				$items[] = array(
					'label' => $name,
					'value' => $name,
					'id' => $id
				);
			}
			$this->data = $items;
		}
	}

	/**
	 * Functin to send reminder mails
	 */
	public function send_remainderMails() {
		ScriptLockComponent::lock(__FUNCTION__);
		$this->send_invitationRemainderMails();
		$this->send_pendingFriendRequestRemainderMails();
	}
	
	/**
	 * Functin to send team reminder mails
	 */
	public function send_teamRemainderMails() {
		ScriptLockComponent::lock(__FUNCTION__);
		$this->__sendTeamJoinInvitationReminderNotifications();
		$this->__sendTeamPatientApprovalReminderNotification();
		$this->__sendTeamMemberRolePromotionReminderNotification();
	}

	/**
	 * Function to send reminder invitation mail
	 */
	public function send_invitationRemainderMails() {
		// fetch all invitatins from inviteduser table
		$invitedUsers = $this->InvitedUser->find('all');
		// send mail to each email address
		foreach ($invitedUsers as $invitedUser) {
			$email = $invitedUser ['InvitedUser'] ['email'];
			$inviterListJSON = $invitedUser ['InvitedUser'] ['invited_user_list'];
			$this->__send_reminderMailToNonRegisteredUser($email, $inviterListJSON, EmailTemplateComponent::INVITATION_REMINDER_TEMPLATE);
		}
	}

	/**
	 * Function to send reminder mail to non registerd user
	 */
	private function __send_reminderMailToNonRegisteredUser($toEmail, $inviterListJSON, $template) {
		$inviterList = json_decode($inviterListJSON, true);
		$emailBody = "";
		$inviter_name = "";
		$first = true;

		// create html data for each inviter
		foreach ($inviterList ['user_list'] as $inviter) {
			$user = $this->User->findById($inviter ['user_id']);
			$actionTokenEntry = $this->ActionToken->findById($inviter ['token_id']);
			$actionToken = $actionTokenEntry ['ActionToken'] ['token'];
			$emailBody .= $this->__create_invitedUserMailData($actionToken, $user, $toEmail);
			// choose mail subject name
			if ($first) {
				$inviter_name = $user ['User'] ['username'];
				$first = false;
			}
		}

		/*
		 * Formatting the email subject based on
		 *  the number of inviters
		 *  $inviter_body : used for set the data in mail body
		 *  $inviter_name : used for set the mail subject
		 */
		$inviterCount = count($inviterList ['user_list']);

		if ($inviterCount > 2) {
			$inviter_name .= ' and ' . ($inviterCount - 1) . ' others,';
			$inviter_body = $inviter_name . ' want to connect with You on ' . Configure::read ( 'App.name' );
			$inviter_name.= ' have ';
		} else if ($inviterCount == 2) {
			$inviter_name .= ' and ' . ($inviterCount - 1) . ' other,';
			$inviter_body = $inviter_name . ' want to connect with You on ' . Configure::read ( 'App.name' );
			$inviter_name.= ' have ';
		} else {
			$inviter_body = $inviter_name . ' wants to connect with You on ' . Configure::read ( 'App.name' );
			$inviter_name.= ' has ';
		}

		$emailData = array(
			'username' => $toEmail,
			'invitation_reminder_body' => $emailBody,
			'inviter_subject' => $inviter_name,
			'inviter_body' => $inviter_body,
			'link' => Router::Url('/', TRUE) . 'register'
		);

		$this->sendHTMLMail($template, $emailData, $toEmail);
		echo __(PHP_EOL . ' Remainder invitation mail sent to ' . $toEmail . PHP_EOL);
	}

	/**
	 * Function to create invited users html data
	 * For non registered user
	 * @param string $actionToken
	 * @param array $user
	 * @return string html
	 */
	private function __create_invitedUserMailData($actionToken, $user, $toEmail) {

		$email = urlencode($toEmail);  // urlencoding the email. !important
		$accept_link = Router::Url('/', TRUE) . 'register?token=' . $actionToken . '&e=' . $email;
		$reject_link = Router::Url('/', TRUE) . 'register?rej=true&token=' . $actionToken . '&e=' . $email;
		$link = Router::Url('/', TRUE) . 'register?profile=true&token=' . $actionToken . '&e=' . $email;
		$location = $this->User->getUserLocation($user['User']['id']);

		// load a view file for create html content for one inviter
		$this->set(compact('user', 'accept_link', 'reject_link', 'link', 'location'));
		$View = new View($this, false);

		// return html
		return $View->element('reminder_user_list');
	}

	/**
	 * Function to send pending friend request notification
	 */
	public function send_pendingFriendRequestRemainderMails() {

		// find all users have pending request
		$pendingFriendsUserList = $this->MyFriends->find('list', array(
			'conditions' => array(
				'pending_request_count >' => 0
			),
			'fields' => array(
				'my_id'
			)
		));

		foreach ($pendingFriendsUserList as $userId) {
			// get notification  preferenece for user
			if ($this->NotificationSetting->isEmailNotificationOn($userId, 'friends_request_reminder')) {
				$pendingFriends = $this->MyFriends->getPendingFriendsList($userId);
				if (!empty($pendingFriends)) {
					$this->__send_pendingReminderMailToRegisteredUser($userId, $pendingFriends, EmailTemplateComponent::PENDING_REQUEST_REMINDER_TEMPLATE);
				}
			}
		}
	}

	/**
	 * Function to send pending team join invitation reminder notifications
	 */
	private function __sendTeamJoinInvitationReminderNotifications() {
		$teamMembers = $this->TeamMember->getMembersForTeamJoinInvitationReminder();
		if (!empty($teamMembers)) {
			foreach ($teamMembers as $teamMemberData) {
				$this->__sendTeamJoinInvitationReminderMail($teamMemberData);
			}
		}
	}

	/**
	 * Function to send pending team join invitation reminder notification email
	 * 
	 * @param array $teamMemberData
	 */
	private function __sendTeamJoinInvitationReminderMail($teamMemberData) {
		$team = $teamMemberData['Team'];
		$invitedUser = $teamMemberData['InvitedBy'];
		$member = $teamMemberData['User'];
		$teamMember = $teamMemberData['TeamMember'];
		$weekCount = $this->TeamMember->getWeekDifference($teamMember['id']);
		$emailData = array(
			'username' => $member['username'],
			'name' => $invitedUser['username'],
			'team_name' => $team['name'],
			'weekcount' => $weekCount,
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $member['email'];
		$templateId = EmailTemplateComponent::TEAM_INVITATION_REMINDER_EMAIL_TEMPLATE;
		$this->sendHTMLMail($templateId, $emailData, $toEmail);
	}

	/**
	 * Function to send pending friend request notification to registerd user
	 * @param int $userId
	 * @param array $pendingFriends
	 * @param int $template
	 */
	private function __send_pendingReminderMailToRegisteredUser($userId, $pendingFriends, $template) {

		$user = $this->User->findById($userId);
		
		// exit function if user does not exist
		if (empty($user)) {
			return;
		}
		
		$toEmail = $user['User']['email'];
                $username = $user['User']['username'];
		$emailBody = "";
		$inviter_name = "";
		$first = true;

		foreach ($pendingFriends as $friendId) {
			$friend = $this->User->findById($friendId);
			$emailBody .= $this->__createPendingFriendsMailData($friend, $userId);
			if ($first) {
				$inviter_name = $friend ['User'] ['username'];
				$first = false;
			}
		}

		/*
		 * Formatting the email subject based on
		 * the number of inviters
		 * $inviter_body : used for set the data in mail body
		 * $inviter_name : used for set the mail subject
		 */
		$pendingCount = count($pendingFriends);

		if ($pendingCount > 2) {
			$inviter_name .= ' and ' . ($pendingCount - 1) . ' others,';
			$inviter_body = $inviter_name . ' want to connect with You on ' . Configure::read ( 'App.name' );
		} else if ($pendingCount == 2) {
			$inviter_name .= ' and ' . ($pendingCount - 1) . ' other,';
			$inviter_body = $inviter_name . ' want to connect with You on ' . Configure::read ( 'App.name' );
		} else {
			$inviter_body = $inviter_name . ' wants to connect with You on ' . Configure::read ( 'App.name' );
		}

		$emailData = array(
			'username' => $username,
			'invitation_reminder_body' => $emailBody,
			'inviter_subject' => $inviter_name,
			'inviter_body' => $inviter_body,
			'link' => Router::Url('/', TRUE) . 'login'
		);
		
		$this->sendHTMLMail($template, $emailData, $toEmail);
		echo __(PHP_EOL . 'Pending request remainder mail sent to ' . $toEmail . PHP_EOL);
	}

	/**
	 * Function to create html for pending friend request reminder
	 * @param array $user
	 * @param int $userId
	 * @return string html
	 */
	private function __createPendingFriendsMailData($user, $userId) {
		Common::getUserProfileLink( $user['User']['username'], TRUE);
		$friendId = $user['User']['id'];
		$accept_link = Router::Url('/', TRUE) . 'user/friends/approveFriend/' . $friendId;
		$reject_link = Router::Url('/', TRUE) . 'user/friends/rejectFriend/' . $friendId;
		$link = Router::Url('/', TRUE) . 'profile/' . urlencode ($user['User']['username'] );

		$location = $this->User->getUserLocation($user['User']['id'], true);

		$diseases = array();
		$medications = array();
		if ($this->__canPublicViewUserCondition($user['User'])) {
			$diseases = $this->User->getUserDiseases($friendId, true);
			$medications = $this->UserTreatment->getUserTreatmentNames($friendId);
		}

		$this->set(compact('user', 'accept_link', 'reject_link', 'link', 'location', 'diseases', 'medications'));
		$View = new View($this, false);

		// return html data
		return $View->element('reminder_user_list');
	}
	/* For Update City table with latitude and longitude values *//* starting */

	/**
	 * Function to get the lat long values for given location using google maps api.
	 *
	 * @param string $country
	 * @param string $state
	 * @param string $city
	 * @return array
	 */
	public function getLatLongPoints($country = NULL, $state = NULL, $city = NULL) {
//        $country = "Afghanistan";
//        $state = "Daykondi";
//        $city = "Hukumati Gizab";
		$address = $city . "+" . $state . "+" . $country;
		$address2 = $state . "+" . $country;
		$result = NULL;
		$url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_PROXYPORT, 80);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response);
		if ($response_a->status == 'OVER_QUERY_LIMIT') {
			print_r("status : OVER_QUERY_LIMIT. max limit reached.");
			exit;
		} elseif ($response_a->status == 'OK') {
//            echo 'first time <br />';
			$lat = $response_a->results[0]->geometry->location->lat;
			$long = $response_a->results[0]->geometry->location->lng;
			$result = array(
				'lat' => $lat,
				'long' => $long
			);
		} else {
			$url = "http://maps.google.com/maps/api/geocode/json?address=$address2&sensor=false";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_PROXYPORT, 80);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response);
			if ($response_a->status == 'OK') {
//                echo 'second time <br />';
				$lat = $response_a->results[0]->geometry->location->lat;
				$long = $response_a->results[0]->geometry->location->lng;
				$result = array(
					'lat' => $lat,
					'long' => $long
				);
			}
		}


		return $result;
//        echo '<pre>';
//        print_r($result);
//        echo '<br />';
//        print_r($response_a->status);
//        exit;
	}

	/**
	 * Function to update the cities tale with lat long values.
	 * A cron job is running every minute with limit of 25 records.
	 *
	 */
	public function updateCitiesTable() {
		$joins = array(
			array(
				'table' => 'states',
				'type' => 'LEFT',
				'alias' => 'state',
				'conditions' => 'City.state_id = state.id'
			),
			array(
				'table' => 'countries',
				'type' => 'LEFT',
				'alias' => 'Country',
				'conditions' => 'state.country_id = Country.id'
			)
		);
		$locations = $this->City->find('all', array(
			'conditions' => array(
				'City.latitude' => '',
//                'City.id >=' => 28286
//                'City.state_id ' => 1254, //kerala for testing
				'City.state_id >=' => 3519 // us states: 3506 to 3556
//                'City.id <' => 4050
			),
			'limit' => 50,
			'joins' => $joins,
			'fields' => array('City.*', 'state.*', 'Country.*')
		));
//        echo '<pre>';
//        print_r($locations);
//        exit;
		$latLongArray = array();
		foreach ($locations as $location) {
			if ($location['City']['latitude'] == NULL) {
				$city = $location['City']['description'];
				$state = $location['state']['description'];
				$country = $location['Country']['short_name'];
				$latlong = $this->getLatLongPoints($country, $state, $city);
				$latLongArray[] = array(
					'city_id' => $location['City']['id'],
					'location' => $city . ' + ' . $state . ' + ' . $country,
					'latlong' => $latlong
				);
				$this->City->id = $location['City']['id'];
				$this->City->set(
						array(
							'latitude' => $latlong['lat'],
							'longitude' => $latlong['long']
						)
				);
				$this->City->save(); //Save latitude longitude count in city table.
//                echo '<pre>';
//                print_r($latLongArray);
//                $latLongArray = null;
			}
		}
		echo '<pre>';
		print_r($latLongArray);
		exit;
	}

	/**
	 * Funcion to get the cities in the circle of given radius with given center.
	 */
	public function getRadiusSearchResults($logged_in_user_id = NULL) {
		echo '<pre>';
		$logged_in_user_id = 2;
		$logged_in_user_city = $this->User->getFullUserDetails($logged_in_user_id, 'id');
		$logged_in_user_city = $logged_in_user_city[0]['User']['city'];
		$requested_redius = '25';
		$requested_limit = '20';
		$cityArray = array();
		$nearByCities = $this->City->getRadiusSearchResults($logged_in_user_city, $requested_redius, $requested_limit);
		foreach ($nearByCities as $city) {
			$cityArray[] = $city['cities'] ['id'];
		}
		$cityArray = array(25285, 25286, 25287);
		$nearByUsers = $this->User->find('all', array(
			'conditions' => array(
				"User.city" => $cityArray
			),
			'fields' => array('User.id')
		));
		$nearByEvents = $this->Event->find('all', array(
			'conditions' => array(
				"Event.city" => $cityArray
			),
			'fields' => array('Event.id')
		));
		$nearByCommunities = $this->Community->find('all', array(
			'conditions' => array(
				"Community.city" => $cityArray
			),
			'fields' => array('Community.id')
		));
		$result = array(
			'nearByUsers' => $nearByUsers,
			'nearByEvents' => $nearByEvents,
			'nearByCommunities' => $nearByCommunities
		);
		echo '<pre>';
		print_r($result);
		exit;
	}

	public function getNearByCities($city) {
		$logged_in_user_city = $city; //alpy = 25285;
		$requested_redius = 300;
		$requested_limit = 500;
		$cityArray = array();
		$nearByCities = $this->City->getRadiusSearchResults($logged_in_user_city, $requested_redius, $requested_limit);
		foreach ($nearByCities as $city) {
			$conditions = array(
				'User.city' => $city['cities'] ['id'],
			);
			if ($this->User->hasAny($conditions)) {
				$cityArray[] = $city['cities'] ['id'];
			}
		}
		if ($cityArray == NULL) {
			$cityArray[] = $logged_in_user_city;
		}
//        $cityArray = array(25285, 25286, 25287);//dummy values to be used till the city table get updated.;
//        return $cityArray;
		echo '<pre>';
		print_r(count($cityArray) . 'cities found');
		echo '<br />';
		print_r($cityArray);
		exit;
	}

	function getNearByUsers() {
		$requested_limit = 20;
		$requested_redius = 300;
		$logged_in_user_id = 2;
		$nearByUses = $this->RadiusSearch->getNearByUsers($logged_in_user_id, $requested_redius, $requested_limit);
		echo '<pre>';
		print_r($nearByUses);
		exit;
	}

	function getNearByEvents() {
		$requested_limit = 20;
		$requested_redius = 25;
		$logged_in_user_id = 2;
		$nearByUses = $this->RadiusSearch->getNearByEvents($logged_in_user_id, $requested_redius, $requested_limit);
		echo '<pre>';
		print_r($nearByUses);
		exit;
	}

	function getNearByCommunities() {
		$requested_limit = 20;
		$requested_redius = 25;
		$logged_in_user_id = 2;
		$nearByUses = $this->RadiusSearch->getNearByCommunities($logged_in_user_id, $requested_redius, $requested_limit);
		echo '<pre>';
		print_r($nearByUses);
		exit;
	}

	// Temparary function for updating pennding invitatin count in my_friends table
	function updatePendingInviteCount() {
		$pendingFriendsUserList = $this->MyFriends->find('all');

		foreach ($pendingFriendsUserList as $user) {
			$pendingFriendsCount = $this->MyFriends->getFriendsStatusCount(
					$user['MyFriends']['my_id'], MyFriends::STATUS_REQUEST_RECIEVED
			);

			$user['MyFriends']['pending_request_count'] = $pendingFriendsCount;
			debug($pendingFriendsCount);

			$this->MyFriends->id = $user['MyFriends']['id'];
			$status = $this->MyFriends->save($user);

			debug($status);
		}
		exit;
	}

	/**
	 * Function to send reminder emails to update health status for the day
	 * ( For patients only ) daily email
	 */
	public function sendHealthStatusUpdateReminders() {
		ScriptLockComponent::lock(__FUNCTION__);
		$reminderTime = 9;
		$timezones = $this->Timezone->getTimezonesWhereHourIs($reminderTime);
		if (!empty($timezones)) {
			printf('Selected %d timezone(s) for sending health status reminder..', count($timezones));
			echo PHP_EOL;
			$users = $this->User->getPatientUsersInTimezones($timezones);
			if (!empty($users)) {
				printf('Selected %d user(s) for sending health status reminder..', count($users));
				echo PHP_EOL;
				foreach ($users as $userData) {
					$user = $userData['User'];
					$userId = $user['id'];
					$timezone = $user['timezone'];
					//get email setting preference for user
					if ($this->NotificationSetting->isEmailNotificationOn($userId, 'how_am_i_feeling')) {
						$isHealthStatusSet = $this->HealthReading->isHealthStatusSetToday($userId, $timezone);
						if ($isHealthStatusSet === false) {
							$this->_sendHealthStatusUpdateReminder($user);
						}
					}
				}
			} else {
				echo 'No users in selected timezones.' . PHP_EOL;
			}
		} else {
			echo 'No timezones in specified time.' . PHP_EOL;
		}
	}
	
	/**
	 * Function to send reminder emails to update health status for the day
	 * ( For non patients only ) weekly email
	 */
	public function sendHealthStatusRemindersNonPatients() {
		ScriptLockComponent::lock(__FUNCTION__);
		$reminderTime = 9;
		$timezones = $this->Timezone->getTimezonesWhereHourIs($reminderTime);
		if (!empty($timezones)) {
			printf('Selected %d timezone(s) for sending health status reminder..', count($timezones));
			echo PHP_EOL;
			$users = $this->User->getNonPatientUsersInTimezones($timezones);
			if (!empty($users)) {
				printf('Selected %d user(s) for sending health status reminder..', count($users));
				echo PHP_EOL;
				foreach ($users as $userData) {
					$user = $userData['User'];
					$userId = $user['id'];
					$timezone = $user['timezone'];
					//get email setting preference for user
					if ($this->NotificationSetting->isEmailNotificationOn($userId, 'how_am_i_feeling')) {
						$isHealthStatusSet = $this->HealthReading->isHealthStatusSetWeekly($userId, $timezone);
						if ($isHealthStatusSet === false) {
							$this->_sendHealthStatusUpdateReminder($user);
						}
					}
				}
			} else {
				echo 'No users in selected timezones.' . PHP_EOL;
			}
		} else {
			echo 'No timezones in specified time.' . PHP_EOL;
		}
	}


	/**
	 * Function to send reminder email to a user to update the health status
	 *
	 * @param array $user
	 */
	protected function _sendHealthStatusUpdateReminder($user) {
		$templateId = EmailTemplateComponent::HEALTH_STATUS_UPDATE_REMINDER_TEMPLATE;
		$toEmail = $user['email'];
		$autoLoginToken = $this->Otp->createOTP(array(
			'email' => $toEmail
		));
		$email = base64_encode($toEmail);
		$emailData = array(
			'username' => $user['username'],
			'auto_login_url' => Router::Url('/', TRUE) . '?auto_login_token=' . $autoLoginToken . '&email=' . $email.'&promptHealthStatusUpdate=true',
		);

		$this->sendHTMLMail($templateId, $emailData, $toEmail);
	}

	//Temprary function to check pending invite count
	function checkPendingInviteCount() {
		$pendingFriendsUserList = $this->MyFriends->find('all');

		foreach ($pendingFriendsUserList as $user) {
			$pendingFriendsCount = $this->MyFriends->getFriendsStatusCount(
					$user['MyFriends']['my_id'], MyFriends::STATUS_REQUEST_RECIEVED
			);
			if ($user['MyFriends']['pending_request_count'] != $pendingFriendsCount) {
				echo "User Id: " . $user['MyFriends']['my_id'];
				echo " current_request_count: " . $user['MyFriends']['pending_request_count'];
				echo " real_count: " . $pendingFriendsCount;
				echo "<br>";
			}
		}
		exit;
	}

	/**
	 * Function to detect timezone id from offset and dst and return as JSON
	 */
	public function detected_timezone_id_JSON() {
		if (isset($this->request->data['offset'])) {
			$offset = $this->request->data['offset'];
			$dst = $this->request->data['dst'];
		} else {
			$offset = -6;
			$dst = 0;
		}

		$this->data = $this->Timezone->detect_timezone_id($offset, $dst);
	}

	//Temporary  function to update timezone

	function updateTimezone() {
		$timezones = $this->User->find('list', array(
			'fields' => array(
				'timezone'
			)
		));
		$timezone_list = $this->Timezone->get_timezone_list();
		echo "-----------Changing user table-------------<br>";
		$i = 1;
		foreach ($timezones as $id => $timezone) {
			$is_exist = false;

			foreach ($timezone_list as $timezoneId) {
				if ($timezoneId['value'] == $timezone) {
					$is_exist = true;
				}
			}
			if (!$is_exist) {

				$this->User->id = $id;
				$this->User->saveField('timezone', 'America/Chihuahua');
				echo 'id:' . $id . " " . $timezone . ' => America/Chihuahua<br>';
			}
		}

		$timezones = $this->Event->find('list', array(
			'fields' => array(
				'timezone'
			)
		));
		$timezone_list = $this->Timezone->get_timezone_list();
		echo "-----------Changing events table-------------<br>";
		foreach ($timezones as $id => $timezone) {
			$is_exist = false;

			foreach ($timezone_list as $timezoneId) {
				if ($timezoneId['value'] == $timezone) {
					$is_exist = true;
				}
			}
			if (!$is_exist) {

				$this->Event->id = $id;
				$this->Event->saveField('timezone', 'America/Chihuahua');
				echo 'id:' . $id . " " . $timezone . ' => America/Chihuahua<br>';
			}
		}
	}

	/**
	 * Function to send event reminder emails and site notifications
	 * 
	 * This function is run by the cron at every 5 minutes interval
	 * This fetches the events with start time after 1 hour within this interval
	 * and sends reminders to the attendees
	 */
	public function sendEventReminders() {
		ScriptLockComponent::lock(__FUNCTION__);
		echo '<pre>';
		$oneMinute = 60;
		$config = array(
			'reminderTime' => 60 * $oneMinute, // 1 hr
			'cronInterval' => 5 * $oneMinute // 5 mins
		);
		$MYSQL_FORMAT = 'Y-m-d H:i:s';

		$currentTime = time();
		$currentDateTimeStr = date($MYSQL_FORMAT, $currentTime);
		$currentTimeSeconds = substr($currentDateTimeStr, -2);
		$currentTime = $currentTime - $currentTimeSeconds;
		$currentDateTimeStr = date($MYSQL_FORMAT, $currentTime);
		echo 'current time: ' . $currentDateTimeStr . PHP_EOL;

		$rangeStartTime = $currentTime + $config['reminderTime'];
		$rangeEndTime = $rangeStartTime + $config['cronInterval'];
		$rangeStartTime = $rangeStartTime + $oneMinute;

		$eventTimeRangeFrom = date($MYSQL_FORMAT, $rangeStartTime);
		$eventTimeRangeTo = date($MYSQL_FORMAT, $rangeEndTime);
		printf('range: (%s) - (%s)', $eventTimeRangeFrom, $eventTimeRangeTo);
		echo PHP_EOL;

		$repeatEventsAll = $this->Event->getRecurringEventsWithStartTimeBetween($eventTimeRangeFrom, $eventTimeRangeTo);
                $repeatEventsInstances = $this->getRepeatEventOccurenceArray($repeatEventsAll, $eventTimeRangeFrom, $eventTimeRangeTo);
                $repeatEvents = $repeatEventsInstances['result'];
//		$events = $this->Event->getEventsWithStartTimeBetween($eventTimeRangeFrom, $eventTimeRangeTo);
		$nonRecurringEvents = $this->Event->getEventsWithStartTimeBetween($eventTimeRangeFrom, $eventTimeRangeTo);
                $events = array_merge_recursive($nonRecurringEvents, $repeatEvents);

		if (!empty($events)) {
			printf('Found %d event(s)', count($events));
			$this->__sendEventReminderNotifications($events);
		} else {
			echo 'No events found';
		}
		exit();
	}

	/**
	 * Send reminder notifications for the events
	 * 
	 * @param array $events 
	 */
	private function __sendEventReminderNotifications($events) {
		foreach ($events as $eventRecord) {
			$event = $eventRecord['Event'];
			$eventHref = Router::Url('/', TRUE) . sprintf('event/details/index/%d', $event['id']);
			$templateId = EmailTemplateComponent::EVENT_REMINDER_TEMPLATE;
			$templateData = array(
				'eventname' => $event['name'],
				'link' => $eventHref
			);

			$eventAttendingMembers = $eventRecord['EventMember'];
			$attendingMemberIds = array();
			foreach ($eventAttendingMembers as $eventMember) {
				$userId = $eventMember['user_id'];
				$attendingMemberIds[] = $userId;

				// check email setting preference for user
				$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($userId, 'event_reminder');
				if ($isEmailNotificationOn && (!$this->User->isUserOnline($userId))) {
					$user = $eventMember['User'];
					$timezone = $user['timezone'];
					$eventStartDateTime = CakeTime::nice($event['start_date'], $timezone, '%a %b %e, %G %l:%M %P');
					$eventEndTime = CakeTime::nice($event['end_date'], $timezone, '%l:%M %P');
					$eventDateTime = sprintf('%s - %s', $eventStartDateTime, $eventEndTime); // Eg: Mon Nov 11, 2013 5:15pm – 5:45pm
					$timeOffset = Date::getTimeZoneOffsetText($timezone);
					$timeZoneOffset = "(GMT " . $timeOffset . ")";

					$templateData['event_datetime'] = $eventDateTime;
					$templateData['username'] = $user['username'];
					$templateData['timezone_offset'] = $timeZoneOffset;
					$toEmail = $user['email'];
					$emailSettings['priority'] = Email::HIGH_PRIORITY;
					$this->sendHTMLMail($templateId, $templateData, $toEmail, $emailSettings);
				}
			}

			$this->Notification = ClassRegistry::init('Notification');
			$this->Notification->addEventReminderNotifications($event, $attendingMemberIds);
		}
	}

	/**
	 * Temp function to update unread notification count in notification setting table
	 */
	public function updateNotificationCount() {
		$this->loadModel('Notification');
		$activeUsers = $this->User->find('all', array('conditions' => array('status' => 1)));
		foreach ($activeUsers as $activeUser) {
			$userId = $activeUser['User']['id'];
			$count = $this->Notification->getUserUnreadNotificationsCount($userId);

			// data to be saved
			$data = array(
				'notification_count' => $count
			);

			$record = $this->NotificationSetting->findByUserId($userId);
			if (!empty($record)) {
				// if existing, update
				$data['id'] = $record['NotificationSetting']['id'];
			} else {
				// create
				$this->NotificationSetting->create();
				$data['user_id'] = $userId;
			}

			$this->NotificationSetting->save($data, false);
		}
	}

	/**
	 * Checks if symptom name already exists or not
	 */
	public function checkExistingSymptomName($name = NULL, $id = 0) {
		if (isset($this->request->data ['name'])) {
			$name = trim($this->request->data ['name']);
		}
		$symptom = $this->Symptom->findByName($name);
		if (!empty($symptom['Symptom'])) {
			if (isset($this->request->data ['id'])) {
				$id = $this->request->data ['id'];
			}
			if ($id > 0) {
				// if editing disease, valid if disease ids match
				$diseaseId = $symptom['Symptom']['id'];
				$valid = ($id == $diseaseId) ? true : false;
			} else {
				$valid = false;
			}
		} else {
			$valid = true;
		}
		if (!isset($this->request->data ['name'])) {
			return $valid;
			exit();
		} else {
			$this->data = $valid;
		}
	}
	
	/**
	 * Search symptoms by name
	 */
	public function searchSymptoms() {
		$searchStr = $this->request->query['q'];
		$data = $this->Symptom->find ( 'list', array (
				'conditions' => array (
						'Symptom.name LIKE' => "{$searchStr}%" 
				),
				'limit' => 10 
		) );
		if (!empty($data)) {
			foreach ($data as $id => $name) {
				echo strip_tags("$name|$id\n");
			}
		}
		exit();
	}
	/*
	 * Function to update the count of treatments
	 */

	public function getCountTreatment() {
		$treatmentIds = $this->Treatment->find('list');
//            $this->Treatment->updateTreatmentUsersCount(5, 10);
//            $count = $this->PatientDisease->getTreatmentCount(5);
		foreach ($treatmentIds as $id => $name) {
			$count = $this->Analytics->getTreatmentCount($id);
			$this->Treatment->updateTreatmentUsersCount($id, $count);
		}
	}

	/**
	 * Function to check is current password enetered is same as the user's password
	 */
	public function checkCurrentPassword() {
		$userId = $this->request->data['id'];
		$currentPassword = $this->request->data['current_password'];
		$this->User->recursive = -1;
		$user = $this->User->findById($userId);

		if ($user['User']['password'] === AuthComponent::password($currentPassword)) {
			$this->data = true;
		} else {
			$this->data = false;
		}
	}
        
	/**
	 * Function to check is current password enetered is same as the user's password
	 */
	public function updateTreatment() {
		
    		$treatments = $this->PatientDisease->find('all');
                $to_save = array();
		foreach ($treatments as $treatment) {
		
                    echo "<pre>";
                   // print_r($treatment);
                    $data_to_save = array(
                             'user_id' => $treatment['PatientDisease']['patient_id'],
                             'patient_disease_id' => $treatment['PatientDisease']['id']
                     );
                    
                    if($treatment['PatientDisease']['user_treatments'] != ""){
                        $user_treat_temp = explode(',', $treatment['PatientDisease']['user_treatments']);
                         foreach($user_treat_temp as $user_treat) {
                             if($user_treat != "") {
                                $data_to_save_2['treatment_id'] = $user_treat;
                                $to_save[] = array_merge($data_to_save, $data_to_save_2);
                             }
                         }
                         
                    }
		}

                // truncate
		$this->UserTreatment->query('TRUNCATE TABLE `user_treatments`');
                
		// save
		$status = $this->UserTreatment->saveAll($to_save);

		echo '<pre>';
		print_r($to_save);
		echo PHP_EOL;
		echo '</pre>';
		exit();
	}       
        
        /**
         * Search diseases by name
         */
        public function searchConditions() {
		$searchStr = $this->request->query['q'];
		$data = $this->Disease->find ( 'list', array (
				'conditions' => array (
						'Disease.name LIKE' => "{$searchStr}%" 
				),
				'limit' => 10 
		) );
		if (!empty($data)) {
			foreach ($data as $id => $name) {
				echo strip_tags("$name|$id\n");
			}
		}
		exit();
	}

	/**
	 * Function to send care calendar reminders
	 */
	public function sendCareCalendarReminders() {
		ScriptLockComponent::lock(__FUNCTION__);
		$reminderTime = 4;
		$timezones = $this->Timezone->getTimezonesWhereHourIs($reminderTime);
		if (!empty($timezones)) {
			printf('Selected %d timezone(s) for sending care calendar reminder..', count($timezones));
			echo PHP_EOL;
			$this->CareCalendarEvent = ClassRegistry::init('CareCalendarEvent');
			$this->Team = ClassRegistry::init('Team');
			$events = $this->CareCalendarEvent->getTodayEventsAssignedToUsersInTimezones($timezones);
			if (!empty($events)) {
				$count = count($events);
				printf('Selected %d care calendar event(s) for sending reminder..', $count);
				$groupedEvents = $this->__getGroupedCareCalendarEvents($events);
				foreach ($groupedEvents as $userCareCalendarEvents) {
					$this->__sendCareCalendarReminderEmail($userCareCalendarEvents);
				}
				$this->__sendCareCalendarReminderSiteNotifications($groupedEvents);
			} else {
				echo 'No care calendar events in selected timezones.' . PHP_EOL;
			}
		} else {
			echo 'No timezones in specified time.' . PHP_EOL;
		}
	}

	/**
	 * Function to get care calendar events grouped by assignee and team
	 * 
	 * @param array $careCalendarEvents
	 * @return array
	 */
	private function __getGroupedCareCalendarEvents($careCalendarEvents) {
		$groupedEvents = array();
		foreach ($careCalendarEvents as $careCalendarEventData) {
			$careCalendarEvent = $careCalendarEventData['CareCalendarEvent'];
			$event = $careCalendarEventData['Event'];
			$eventData = array_merge($event, $careCalendarEvent);
			$assignee = $careCalendarEvent['assigned_to'];
			$groupedEvents[$assignee]['User'] = $careCalendarEventData['User'];
			$teamId = $eventData['team_id'];
			if (!isset($groupedEvents[$assignee]['Events'][$teamId])) {
				$team = $this->Team->getTeam($teamId);
				$teamMember = $this->TeamMember->getTeamMember($assignee, $teamId);
				$groupedEvents[$assignee]['Events'][$teamId]['Team'] = $team;
				$groupedEvents[$assignee]['Events'][$teamId]['TeamMember'] = $teamMember;
			}
			$groupedEvents[$assignee]['Events'][$teamId]['TeamEvents'][] = $eventData;
		}
		return $groupedEvents;
	}

	/**
	 * Function to send care calendar reminder email to a user
	 * 
	 * @param array $userCareCalendarEvents
	 */
	protected function __sendCareCalendarReminderEmail($userCareCalendarEvents) {
		$templateId = EmailTemplateComponent::CARE_CALENDAR_TASK_REMINDER_TEMPLATE;
		$user = $userCareCalendarEvents['User'];
		$events = $userCareCalendarEvents['Events'];

		foreach ($events as $teamId => $eventData) {
			$teamMember = $eventData['TeamMember'];
			if ($teamMember['email_notification'] === false) {
				unset($events[$teamId]);
			}
		}

		if (!empty($events)) {
			$emailBody = $this->__prepareCareCalendarReminderEmailBody($user, $events);
			$toEmail = $user['email'];
			App::uses('Date', 'Utility');
			$today = Date::getUSFormatDate(Date::getCurrentDate());
			$emailData = array(
				'date' => $today,
				'username' => $user['username'],
				'care_calendar_reminder_body' => $emailBody
			);
			$this->sendHTMLMail($templateId, $emailData, $toEmail);
		}
	}

	/**
	 * Prepare care calendar reminder email body
	 * 
	 * @param array $user
	 * @param array $events
	 * @return string
	 */
	private function __prepareCareCalendarReminderEmailBody($user, $events) {
		$this->set(compact('user', 'events'));
		$View = new View($this, false);
		return $View->element('care_calendar_reminder_email_body');
	}

	/**
	 * Function to send care calendar reminder site notifications
	 *  
	 * @param array $groupedEvents 
	 */
	private function __sendCareCalendarReminderSiteNotifications($groupedEvents) {
		$this->Notification = ClassRegistry::init('Notification');
		foreach ($groupedEvents as $userId => $userCareCalendarEvents) {
			$user = $userCareCalendarEvents['User'];
			$events = $userCareCalendarEvents['Events'];
			foreach ($events as $teamId => $eventData) {
				$teamMember = $eventData['TeamMember'];
				if ($teamMember['site_notification'] === true) {
					$team = $eventData['Team'];
					$teamEvents = $eventData['TeamEvents'];
					foreach ($teamEvents as $event) {
						$additionalInfo = array('task' => $event);
						$params = array(
							'activity_type' => Notification::ACTIVITY_TEAM_TASK_REMINDER,
							'sender_id' => $event['created_by'],
							'recipient_id' => $userId,
							'additional_info' => $additionalInfo,
						);
						$this->Notification->addTeamNotification($team, $params);
					}
				}
			}
		}
	}

	/**
	 * Function to send care calendar daily digest to members in each team 
	 * that specifies who has signed up for what and what tasks remain open 
	 * for the day
	 */
	public function sendCareCalendarDailyDigest() {
		ScriptLockComponent::lock(__FUNCTION__);
		echo '<pre>';
		$reminderTime = 4;
		$timezones = $this->Timezone->getTimezonesWhereHourIs($reminderTime);
		if (!empty($timezones)) {
			printf('Selected %d timezone(s) for sending all care calendar task reminders..', count($timezones));
			echo PHP_EOL;
			$this->Team = ClassRegistry::init('Team');
			$this->TeamMember = ClassRegistry::init('TeamMember');
			$this->CareCalendarEvent = ClassRegistry::init('CareCalendarEvent');
			$this->Event = ClassRegistry::init('Event');
			$users = $this->User->getUsersInTimezones($timezones);
			if (!empty($users)) {
				printf('Selected %d user(s) for sending all care calendar task reminders..', count($users));
				echo PHP_EOL;
				foreach ($users as $userData) {
					$user = $userData['User'];
					$userId = $user['id'];
					$timezone = $user['timezone'];
					$userTeams = $this->TeamMember->getUserApprovedTeams($userId);
					if (!empty($userTeams)) {
						foreach ($userTeams as $userTeam) {
							$team = $userTeam['Team'];
							if ($userTeam['TeamMember']['email_notification'] === true) {
								$teamTasks = $this->CareCalendarEvent->getTeamTasksForToday($team['id'], $timezone);
								if (!empty($teamTasks)) {
									$this->__sendTeamTasksListEmailToUser($team, $user, $teamTasks);
								}
						}
					}
				}
				}
			} else {
				echo 'No users in specified timezone.' . PHP_EOL;
			}
		} else {
			echo 'No timezones in specified time.' . PHP_EOL;
		}
	}

	/**
	 * Function to send the list of tasks in a team to a user
	 * 
	 * @param array $team
	 * @param array $user
	 * @param array $teamTasks 
	 */
	private function __sendTeamTasksListEmailToUser($team, $user, $teamTasks) {
		$templateId = EmailTemplateComponent::CARE_CALENDAR_DAILY_DIGEST_TEMPLATE;
		$emailBody = $this->__prepareTeamTasksListEmailBody($teamTasks);
		App::uses('Date', 'Utility');
		$today = Date::getUSFormatDate(Date::getCurrentDate());
		$emailData = array(
			'date' => $today,
			'username' => $user['username'],
			'team_name' => $team['name'],
			'team_task_list' => $emailBody,
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $user['email'];
		$this->sendHTMLMail($templateId, $emailData, $toEmail);
	}

	/**
	 * Prepare team task list email body
	 * 
	 * @param array $user
	 * @param array $events
	 * @return string
	 */
	private function __prepareTeamTasksListEmailBody($teamTasks) {
		foreach ($teamTasks as $teamTask) {
			$task = $teamTask['Event'] + $teamTask['CareCalendarEvent'];
			$task['status'] = $this->CareCalendarEvent->getTaskStatusText($task['status']);
			if (!empty($teamTask['Assignee']['username'])) {
				$task['assignee'] = $teamTask['Assignee']['username'];
			}
			$tasks[] = $task;
		}
		$this->set(compact('tasks'));
		$View = new View($this, false);
		return $View->element('team_task_list_email_body');
	}
	
	/**
	 * Function to send pending team approval reminder notifications to patients
	 */
	private function __sendTeamPatientApprovalReminderNotification() {
		$patients = $this->Team->getPatientsForTeamApprovalReminder();
		if (!empty($patients)) {
			foreach ($patients as $patientData) {
				$this->__sendTeamPatientApprovalReminderMail($patientData);
			}
		}
	}
	
	/**
	 * Function to send pending team approval reminder notification email
	 * 
	 * @param array $patientData
	 */
	private function __sendTeamPatientApprovalReminderMail($patientData) {
		$team = $patientData['Team'];
		$patient = $patientData['Patient'];
		$organizer = $patientData['Organizer'];
		$weekCount = $this->Team->getWeekDifference($team['id']);
		$emailData = array(
			'username' => $patient['username'],
			'name' => $organizer['username'],
			'team_name' => $team['name'],
			'weekcount' => $weekCount,
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $patient['email'];
		$templateId = EmailTemplateComponent::TEAM_PATIENT_APPROVAL_REMINDER_EMAIL_TEMPLATE;
		$this->sendHTMLMail($templateId, $emailData, $toEmail);
	}
	
	/**
	 * Function to send pending role promotion approval reminder notifications to members
	 */
	private function __sendTeamMemberRolePromotionReminderNotification() {
		$members = $this->TeamMember->getMembersForRolePromotionReminder();
		if (!empty($members)) {
			foreach ($members as $memberData) {
				$this->__sendTeamMemberRolePromotionReminderMail($memberData);
			}
		}
	}
	
	/**
	 * Function to send pending role promotion reminder notification email
	 * 
	 * @param array $teamMemberData
	 */
	private function __sendTeamMemberRolePromotionReminderMail($teamMemberData) {
		$team = $teamMemberData['Team'];
		$invitedUser = $teamMemberData['RoleInvitedBy'];
		$member = $teamMemberData['User'];
		$teamMember = $teamMemberData['TeamMember'];
		$newRoleName = $this->TeamMember->getMemberRoleName($teamMember['new_role']);
		$weekCount = $this->TeamMember->getWeekDifference($teamMember['id']);
		$emailData = array(
			'username' => $member['username'],
			'name' => $invitedUser['username'],
			'team_name' => $team['name'],
			'role' => $newRoleName,
			'weekcount' => $weekCount,
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $member['email'];
		$templateId = EmailTemplateComponent::TEAM_MEMBER_ROLE_PROMOTION_REMINDER_EMAIL_TEMPLATE;
		$this->sendHTMLMail($templateId, $emailData, $toEmail);
	}

	/**
	 * Temp function to blur existing dashboard images
	 */
	public function blurDashboardPhotos() {
		$this->Photo = ClassRegistry::init('Photo');
		$query = array(
			'conditions' => array(
				'type' => Photo::TYPE_DASHBOARD
			),
			'order' => array('created ASC')
		);
		$dashboardPhotos = $this->Photo->find('all', $query);
		if (!empty($dashboardPhotos)) {
			$photoPath = Configure::read('App.DASHBOARD_IMG_PATH') . DS;
			App::import('Vendor', 'ImageTool');
			foreach ($dashboardPhotos as $dashboardPhoto) {
				$imgPath = $photoPath . $dashboardPhoto['Photo']['file_name'];
				if (file_exists($imgPath)) {
					$imageType = ImageTool::getImageType($imgPath);
					switch ($imageType) {
						case 'jpg':
							$blurImgPath = str_replace('.jpg', '_blur.jpg', $imgPath);
							break;
						case 'png':
							$blurImgPath = str_replace('.png', '_blur.png', $imgPath);
							break;
						case 'gif':
							$blurImgPath = str_replace('.gif', '_blur.gif', $imgPath);
							break;
					}
					if (!file_exists($blurImgPath)) {
						ImageTool::gaussianBlurBottom($imgPath);
					}
				}
			}
		}
	}
        
        /**
         * Temp function to update Duplicate entries in disease table
         */
        function  updateDiseaseDuplicate(){
            $data = $this->Disease->query('SELECT id, diseases.`name` FROM diseases
                                    INNER JOIN (SELECT  `name` FROM diseases
                                    GROUP BY name HAVING count(id) > 1) dup ON diseases.name = dup.name');
            $realName = null;
            echo "Duplicate entries<br>-------------------------";
            foreach( $data as $disease) {
                $name = $disease['diseases']['name'];
                $id = $disease['diseases']['id'];
                echo "<br>".$id . " ----- ".$name;
                /*
                 * If it is a duplicate
                 */
                if( $name == $realName) {
                    echo '<br>----------------------------------------------------';
                    echo '<br> Updating '.$id . " ----- ".$name;
                    $duplicateId = $disease['diseases']['id'];
                    $this->__updateDuplicateFromUser( $duplicateId, $replaceId );
                    $this->__updateDuplicateCommunityUser( $duplicateId, $replaceId );
                    $this->__updateDuplicateEventUser( $duplicateId, $replaceId );
                    $this->__updateDiseaseSymptom( $duplicateId, $replaceId );
                    echo "<br>Removing ".$id .' --------';
                    $this->Disease->delete($id);
                    echo '<br>-------------------------';
                    
                } else {
                    /*
                     * First time occuring
                     */
                    $realName = $name;
                    $replaceId = $disease['diseases']['id'];
                }                   
            }
        }
        
        /*
         * Temp function to update userDiseases
         */
        private function __updateDuplicateFromUser( $duplicateId, $replaceId ) {
            $users = $this->PatientDisease->find('all',array(
                    'conditions' => array('disease_id' => $duplicateId)
            ));
            echo '<br>Updateing user -----';
            foreach( $users  as $user) {
                
                /*
                 * If the user has the original entry
                 */
               $diseases = $this->PatientDisease->find('list', array(
                    'conditions' => array('patient_id' => $user['PatientDisease']['patient_id'],
                        'disease_id' => $replaceId),
                    'fields' => array('disease_id')
                ));
               
               /*
                * remove the duplicate entry for a single usre
                */
                if (!empty($diseases)) {
                    $recordId = $user['PatientDisease']['id'];
                    // removing duplicate entry                    
                    $this->PatientDisease->delete($recordId);
                    
                    echo '<br>Removing Duplicate for Single user PatientDisease: '. $recordId;
                    /*
                     * Update User treatments table for this id
                     */
                    $userTreatments = $this->UserTreatment->find('list', array(
                        'conditions' => array('patient_disease_id' => $recordId),
                        'fields' => array('patient_disease_id')
                    ));
                    foreach ($userTreatments as $id => $userTreatment) {
                        
                        $this->UserTreatment->id = $id;
                        $data['UserTreatment']['patient_disease_id'] = key($diseases);
                        if ( $this->UserTreatment->save($data) ) {
                            echo "<br>Updated UserTreatment ".$id. ' with '.$recordId. ' to '.key($diseases);
                        } else {
                            echo '<br>Error -----in '.$id .' Saving '.$replaceId;
                        }
                    }
                    
                    
                } else {
                    /*
                     * Replace with original
                     */
                    echo "<br>Replacing with ".$replaceId;
                    $this->PatientDisease->id = $user['PatientDisease']['id'];
                    $data['PatientDisease']['disease_id'] = $replaceId; 
                    if ( $this->PatientDisease->save( $data ) ) {
                        echo '<br>Changed ' . $duplicateId .' to ' .$replaceId .' for PatientDiseaseId ' . $user['PatientDisease']['id'];
                    }else {
                        echo '<br>Error -----in '.$user['PatientDisease']['id'] .' Saving '.$replaceId;
                    }
                    
                } 
            }
           
        }
        
        /*
         * Temp function 
         */
        private function __updateDuplicateCommunityUser( $duplicateId, $replaceId ) {
            
            $CommunityDiseases = $this->CommunityDisease->findCommunitiesWithDisease($duplicateId);
            
            echo '<br>Updateing Community -----';
            foreach ($CommunityDiseases as $CommunityDisease){
                
                $id = $CommunityDisease['CommunityDisease']['id'];
                $this->CommunityDisease->id = $id;
                $data['CommunityDisease']['disease_id'] = $replaceId;
                if( $this->CommunityDisease->save($data) ) {
                    echo '<br>Saving CommunityDisease :'.$id.', DiseaseID '. $duplicateId.' to '.$replaceId;
                } else {
                    echo '<br>Error -----in '.$id .' Saving '.$replaceId;
                }
               
            }    
        }
        
        /*
         * Temp function
         */
        private function __updateDuplicateEventUser( $duplicateId, $replaceId ) {
            $EventDiseases = $this->EventDisease->findEventsWithDisease($duplicateId);
          
            echo '<br>Updateing Events -----';
            foreach ($EventDiseases as $EventDisease){
                
                $id = $EventDisease['EventDisease']['id'];
                $this->EventDisease->id = $id;
                $data['EventDisease']['disease_id'] = $replaceId;
                if( $this->EventDisease->save($data) ) {
                    echo '<br>Saving EventDisease :'.$id.', DiseaseID '. $duplicateId.' to '.$replaceId;
                } else {
                    echo '<br>Error -----in '.$id .' Saving '.$replaceId;
                }
               
            }  
        }
        
        /*
         * Temp function
         */
        private function __updateDiseaseSymptom( $duplicateId, $replaceId ) {
            $DiseaseSymptoms = $this->DiseaseSymptom->find('list', array(
                'conditions' => array('disease_id' => $duplicateId ),
                'fields' => array('disease_id')
            ));
            echo '<br>Updateing DiseaseSymptom -----';
            foreach ($DiseaseSymptoms as $id =>$DiseaseSymptom) {
                $DiseaseSymptoms = $this->DiseaseSymptom->id = $id;
                $data['DiseaseSymptom']['disease_id'] = $replaceId;
                if ( $this->DiseaseSymptom->save($data)) {
                    echo '<br>Saving DiseaseSymptom :'.$id.', DiseaseID '. $duplicateId.' to '.$replaceId;
                } else {
                     echo '<br>Error -----in '.$id .' Saving '.$replaceId;
                }
            }
        }


	/**
	 * Function to send account activation reminders
	 */
	public function sendAccountActivationReminders() {
		ScriptLockComponent::lock(__FUNCTION__);
		$reminderTime = 9;
		$reminderInterval = 3; //days
		$timezones = $this->Timezone->getTimezonesWhereHourIs($reminderTime);
		if (!empty($timezones)) {
			printf('Selected %d timezone(s) for sending account activation reminder..', count($timezones));
			echo PHP_EOL;
			$users = $this->User->getInactiveUsersInTimezones($timezones);
			if (!empty($users)) {
				printf('Selected %d user(s) for sending account activation reminder..', count($users));
				echo PHP_EOL;
				foreach ($users as $userData) {
					$user = $userData['User'];
					$currentDateTime = new DateTime();
					$createdDateTime = new DateTime($user['created']);
					$interval = $createdDateTime->diff($currentDateTime);
					$dayDiff = (int) $interval->d;
					if (($dayDiff > 0) && ($dayDiff % $reminderInterval === 0)) {
						$this->__sendAccountActivationReminderMail($user);
					}
				}
			} else {
				echo 'No users in selected timezones.' . PHP_EOL;
			}
		} else {
			echo 'No timezones in specified time.' . PHP_EOL;
		}
	}

	/**
	 * Function to send account activation reminder mail to a user
	 * 
	 * @param array $user 
	 */
	private function __sendAccountActivationReminderMail($user) {
		$link = $this->User->generateActivationLink($user);
		if (!empty($link)) {
			$templateId = EmailTemplateComponent::ACCOUNT_ACTIVATION_REMINDER_TEMPLATE;
			$toEmail = $user['email'];
			$emailData = array(
				'username' => $user['username'],
				'link' => $link
			);
			$this->sendHTMLMail($templateId, $emailData, $toEmail);
		}
	}

	/**
	 * Function to save cover image for profile, community and events 
	 * 
	 */
	public function saveImageSettings() {
		$result = array();
		$this->Photo = ClassRegistry::init('Photo');
		if (isset($this->request->data['User'])) {
			$typeId = $this->request->data['User']['id'];
			$type = Photo::TYPE_PROFILE_COVER;
			$model = 'User';
			$permissionCheckMethod = '__hasUserCoverEditPermission';
			$slideShowSaveMethod = 'saveUserCoverSlideshowStatus';
		} else if (isset($this->request->data['Event'])) {
			$typeId = $this->request->data['Event']['id'];
			$type = Photo::TYPE_EVENT_COVER;
			$model = 'Event';
			$permissionCheckMethod = '__hasEventCoverEditPermission';
			$slideShowSaveMethod = 'saveEventCoverSlideshowStatus';
		} else if (isset($this->request->data['Community'])) {
			$typeId = $this->request->data['Community']['id'];
			$type = Photo::TYPE_COMMUNITY_COVER;
			$model = 'Community';
			$permissionCheckMethod = '__hasCommunityCoverEditPermission';
			$slideShowSaveMethod = 'saveCommunityCoverSlideshowStatus';
		}

		if (isset($typeId) && isset($type)) {
			$loggedInUserId = $this->Session->read('Auth.User.id');
			if ($this->$permissionCheckMethod($loggedInUserId, $typeId)) {
				if (!empty($this->request->data[$model]['is_cover_slideshow_enabled'])) {
					$status = $this->request->data[$model]['is_cover_slideshow_enabled'];
				} else {
					$status = 0;
				}
				if ($this->$model->$slideShowSaveMethod($typeId, $status)) {
					$result['success'] = true;
				}
				$this->Photo->createdUserId = $loggedInUserId;
				$coverImgResult = $this->__saveCoverImages($model, $typeId, $type, $status);
				$result = array_merge($result, $coverImgResult);
			}
		}

		$this->autoRender = false;
		echo json_encode($result);
		exit;
	}

	/**
	 * Functiont to check the logged in user has edit cover permission
	 * for the event
	 *  
	 * @return boolean
	 */
	private function __hasEventCoverEditPermission($loggedInUserId, $eventId) {
		$event = $this->Event->getEvent($eventId);
		return $event['created_by'] == $loggedInUserId;
	}

	/**
	 * Functiont to check the logged in user has edit cover permission
	 * for the community
	 *  
	 * @return boolean
	 */
	private function __hasCommunityCoverEditPermission($loggedInUserId, $communityId) {
		return $this->CommunityMember->hasManagePermission($loggedInUserId, $communityId);
	}

	/**
	 * Functiont to check the logged in user has edit cover permission
	 * for the requesetd profile
	 *  
	 * @return boolean
	 */
	private function __hasUserCoverEditPermission($loggedInUserId, $userId) {
		return $loggedInUserId == $userId;
	}

	private function __saveCoverImages($model, $typeId, $type, $status) {
		$result=array();
		$defaultImgName = '';
		if (intval($status) === $model::COVER_SLIDESHOW_DISABLED) {
			if ($this->request->data[$model]['default_photo_id'] > 0) {
				$defaultPhotoId = $this->request->data[$model]['default_photo_id'];
				$this->Photo->unsetDefaultCoverPhoto($typeId, $type);
				$this->Photo->makePhotoDefault($defaultPhotoId);
			} elseif ($this->request->data[$model]['default_photo'] !== '') {
				$defaultImgSrc = $this->request->data[$model]['default_photo'];
				$defaultImgParts = explode('/', $defaultImgSrc);
				$defaultImgRawName = end($defaultImgParts);
				$defaultImgName = substr($defaultImgRawName, 0, strpos($defaultImgRawName, '?'));
			}
		}

		if (isset($this->request->data[$model]['images'])) {
			$images = $this->request->data[$model]['images'];
			$photos = $this->Photo->addCoverPhotos($images, $typeId, $defaultImgName, $type);
			if (!isset($defaultPhotoId)) {
				$defaultPhoto = $this->Photo->getCoverDefaultPhoto($typeId, $type);
				if (!empty($defaultPhoto)) {
					$defaultPhotoId = $defaultPhoto['Photo']['id'];
				}
			}
			if (!empty($photos)) {
				$result = array(
					'success' => true,
					'photos' => $photos
				);
			}
		}

		if (isset($defaultPhotoId)) {
			$result['defaultPhotoId'] = $defaultPhotoId;
		}

		if (isset($this->request->data[$model]['deleted_photos'])) {
			$photoList = $this->request->data[$model]['deleted_photos'];
			if ($this->Photo->deletePhotos($photoList)) {
				$result['deletedPhotos'] = true;
			}
		}

		return $result;
	}

	public function cropPhoto() {
		$this->autoRender = false;
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method === 'POST') {
			try {
				$uploadPath = Configure::read('App.UPLOAD_PATH');
				$uploadUrl = Configure::read('App.UPLOAD_PATH_URL');
				$data = $this->request->data;

				$x1 = $data["x1"];
				$y1 = $data["y1"];
				$width = $data["w"];
				$height = $data["h"];
                                $model = $data['model'];
                                
                                /*
                                 * Set default model as User
                                 */
                                if ( !($model == 'Community' || $model == 'Event' || $model == 'User')) {
                                    $model == 'User';
                                }

				if ($width <= 0) {
					$width = $this->minimumImageSize[$model][0];
					$x1 = 0;
				}

				if ($height <= 0) {
					$height = $this->minimumImageSize[$model][1];
					$y1 = 0;
				}

				if ($width > 0 && $height > 0) {
					App::import('Vendor', 'ImageTool');
					$fileName = $data['fileName'];
					$imgPath = $uploadPath . DIRECTORY_SEPARATOR . $fileName;
					$cropOptions = array(
						'input' => $imgPath,
						'output' => $imgPath,
						'width' => $width,
						'height' => $height,
						'enlarge' => false,
						'keepRatio' => false,
						'paddings' => false,
						'output_width' => $this->minimumImageSize[$model][0],
						'output_height' => $this->minimumImageSize[$model][1],
						'top' => $y1,
						'left' => $x1,
					);
					ImageTool::crop($cropOptions);



					$result['success'] = true;
					$result['fileUrl'] = $uploadUrl . '/tmp/' . $fileName;
					$result['fileName'] = $fileName;
				} else {
					throw new Exception("Image Not cropped");
				}
			} catch (Exception $e) {
				$result['success'] = false;
				$result['message'] = $e->getMessage();
			}

			$result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
			echo $result;
		} else {
			header('HTTP/1.0 405 Method Not Allowed');
		}
		exit;
	}
       


	/**
	 * Function to debug ajax calls to this controller
	 */
	private function __debug() {
		$view = new View($this, false);
		echo $view->element('ajax_debug');
	}

	/**
	 * Function to send friends recommendations emails
	 */
	public function sendFriendRecommendationEmails() {
		ScriptLockComponent::lock(__FUNCTION__);
		set_time_limit(0);
		$this->RecommendedFriend->sendFriendRecommendationEmails();
	}

	/**
	 * Function to check if public can view the condition of a user
	 * 
	 * @param array $user
	 */
	private function __canPublicViewUserCondition($user) {
		$viewDisease = false;
		$privacySettings = unserialize($user['privacy_settings']);
		if (!empty($privacySettings['view_your_disease'])) {
			$viewDiseaseBy = (int) $privacySettings['view_your_disease'];
			if ($viewDiseaseBy === UserPrivacySettings::PRIVACY_PUBLIC) {
				$viewDisease = true;
			}
		}
		
		return $viewDisease;
	}

	/**
	 * Function to send friend recommendations email to a user
	 */
	public function sendFriendRecommendationsEmailToUser($user, $recommendedUsers) {
		App::uses('UserPrivacySettings', 'Lib');
		foreach ($recommendedUsers as &$recommendedUser) {
			if ($this->__canPublicViewUserCondition($recommendedUser['User'])) {
				$patientId = $recommendedUser['User']['id'];
				$recommendedUser['diseases'] = $this->User->getUserDiseases($patientId, true);
				$recommendedUser['medications'] = $this->UserTreatment->getUserTreatmentNames($patientId);
			}
		}
		
		$View = new View($this, false);
		$View->set(compact('recommendedUsers'));
		$emailBody = $View->element('friend_recommendation_email_body');
		$recommendationCount = count($recommendedUsers);
		if ($recommendationCount === 1) {
			$recommendedNames = $recommendedUsers[0]['User']['username'];
		} elseif ($recommendationCount === 2) {
			$recommendedNames = $recommendedUsers[0]['User']['username'];
			$recommendedNames.=', and ' . $recommendedUsers[1]['User']['username'];
		} elseif ($recommendationCount >= 3) {
			$recommendedNames = $recommendedUsers[0]['User']['username'];
			$recommendedNames.=', ' . $recommendedUsers[1]['User']['username'];
			$recommendedNames.=', and ' . $recommendedUsers[2]['User']['username'];
		}
		$emailData = array(
			'username' => $user['username'],
			'friend_recommendation_email_body' => $emailBody,
			'recommended_names' => $recommendedNames,
			'link' => Router::Url('/', TRUE) . 'search?type=people'
		);
		$templateId = EmailTemplateComponent::FRIEND_RECOMMENDATION_EMAIL_TEMPLATE;
		$toEmail = $user['email'];
		$this->sendHTMLMail($templateId, $emailData, $toEmail);
	}

	/**
	 * Function to send medication reminder email and site notifications
	 */
	public function sendMedicationReminders() {
		ScriptLockComponent::lock(__FUNCTION__);
		$timestamp = time();
		$date = Date::getCurrentDate();
		$this->MedicationSchedule = ClassRegistry::init('MedicationSchedule');
		$this->MedicationSchedulerForm = ClassRegistry::init('MedicationSchedulerForm');
		$this->Notification = ClassRegistry::init('Notification');
		$medications = $this->MedicationSchedule->getReminderMedicationsByDate($date);
		foreach ($medications as $medication) {
			$user = $medication['User'];
			$timezone = $user['timezone'];
			$medicationSchedule = $medication['MedicationSchedule'];
			$rrule = $medicationSchedule['rrule'];
			$rruleArray = Date::parseRRule($rrule);
			$isMedicationOnDate = MedicationSchedule::isMedicationOnDate($date, $medicationSchedule, $rruleArray);
			if ($isMedicationOnDate === true) {
				$medicationTimes = $rruleArray['TIME_LIST'];
				$userTime = CakeTime::format('h:i a', $timestamp, false, $timezone);
				$isMedicationAtTime = in_array($userTime, $medicationTimes);
				if ($isMedicationAtTime === true) {
					$userId = $medicationSchedule['user_id'];
					$usersMedications[$userId]['User'] = $user;
					$usersMedications[$userId]['time'] = $userTime;
					$medicationData = $medication['MedicationSchedule'];
					$medicationData['name'] = $medication['Treatment']['name'];
					$usersMedications[$userId]['Medications'][] = $medicationData;
				}
			}
		}

		if (!empty($usersMedications)) {
			foreach ($usersMedications as $userId => $userMedicationData) {
				$this->__sendMedicationReminderNotificationsToUser($userId, $userMedicationData);
			}
		}
	}

	/**
	 * Function to send medication reminder email and site notifications to user
	 * 
	 * @param int $userId
	 * @param array $userMedicationData 
	 */
	private function __sendMedicationReminderNotificationsToUser($userId, $userMedicationData) {
		$user = $userMedicationData['User'];
		$time = $userMedicationData['time'];
		$medications = $userMedicationData['Medications'];
		$emailBody = '';
		$notifications = array();
		foreach ($medications as $medication) {
			$dose = $medication['dosage'] . ' ' . $medication['dosage_unit'];
			$medication['dose'] = $dose;
			if (!empty($medication['form'])) {
				$medication['form'] = MedicationSchedulerForm::getMedicineFormName($medication['form']);
			}
			if (!empty($medication['route'])) {
				$medication['route'] = MedicationSchedulerForm::getMedicineRouteName($medication['route']);
			}

			$tokenData = array(
				'id' => $medication['id'],
				'user_id' => $medication['user_id']
			);
			$token = base64_encode(json_encode($tokenData));
			$siteUrl = Router::url('/', true);
			$medication['stop_reminder_link'] = "{$siteUrl}user/scheduler/stopMedicationReminder?token={$token}";

			// prepare email body
			$View = new View($this, false);
			$View->set(compact('medication'));
			$emailBody .= $View->element('medication_reminder_email_body');

			// prepare notification data
			$notificationAdditionalInfo = array();
			$notificationInfoFields = array('id', 'name', 'dose', 'form', 'amount', 'route');
			foreach ($notificationInfoFields as $field) {
				if (!empty($medication[$field])) {
					$notificationAdditionalInfo[$field] = $medication[$field];
				}
			}
			$notifications[] = array(
				'activity_type' => Notification::ACTIVITY_MEDICATION_REMINDER,
				'recipient_id' => $userId,
				'additional_info' => json_encode($notificationAdditionalInfo)
			);
		}
		
		// send email notification
		$emailData = array(
			'username' => $user['username'],
			'time' => $time,
			'medication_reminder_email_body' => $emailBody
		);
		$templateId = EmailTemplateComponent::MEDICATION_REMINDER_EMAIL_TEMPLATE;
		$toEmail = $user['email'];
		$emailSettings['priority'] = Email::HIGH_PRIORITY;
		$this->sendHTMLMail($templateId, $emailData, $toEmail, $emailSettings);

		// send site notifications
		$this->Notification->saveMany($notifications);
	}

	/**
	 * Temp function to delete invalid records from my friends table
	 */
	public function deleteInvalidFriendRecords() {
		echo '<pre>';
		$users = $this->User->find('list');
		$userIds = array_keys($users);

		// Delete records of non existent users
		$notExistingUserFriendsCondition = array(
			"NOT" => array(
				"MyFriends.my_id" => $userIds
			)
		);
		$this->MyFriends->deleteAll($notExistingUserFriendsCondition);

		// Delete non existent users from friends json
		$myFriends = $this->MyFriends->find('all');
		if (!empty($myFriends)) {
			foreach ($myFriends as $myFriend) {
				$userFriendsJSON = $myFriend['MyFriends']['friends'];
				$userFriendsList = json_decode($userFriendsJSON, true);
				$userFriends = $userFriendsList['friends'];
				$nonExistentFriendExists = false;
				foreach ($userFriends as $index => $friendData) {
					$friendId = $friendData['user_id'];
					if (!in_array($friendId, $userIds)) {
						$nonExistentFriendExists = true;
						unset($userFriends[$index]);
					}
				}
				if ($nonExistentFriendExists === true) {
					$newJSON = json_encode(array('friends' => $userFriends));
					$userId = $myFriend['MyFriends']['my_id'];
					$existingData[] = $myFriend['MyFriends'];
					$data[] = array(
						'id' => $myFriend['MyFriends']['id'],
						'my_id' => $userId,
						'friends' => $newJSON,
					);
				}
			}
			if (!empty($data)) {
				echo 'Existing invalid data:' . PHP_EOL;
				print_r($existingData);
				$this->MyFriends->saveMany($data);
				foreach ($data as $key => $record) {
					$userId = $record['my_id'];
					$pendingFriendsCount = $this->MyFriends->getFriendsStatusCount($userId, MyFriends::STATUS_REQUEST_RECIEVED);
					$data[$key]['pending_request_count'] = $pendingFriendsCount;
				}
				echo 'Valid data:' . PHP_EOL;
				print_r($data);
				$this->MyFriends->saveMany($data);
			} else {
				echo 'No invalid records.';
			}
		}
		exit();
	}

	/**
	 * Temp function to add following pages for existing friends, communities, events
	 */
	public function addFollowingPages() {
		set_time_limit(0);
		$this->FollowingPage = ClassRegistry::init('FollowingPage');
		$this->FollowingPage->deleteAll(array(
			'FollowingPage.type' => array(
				FollowingPage::USER_TYPE,
				FollowingPage::EVENT_TYPE,
				FollowingPage::COMMUNITY_TYPE,
			)
		));
		$users = $this->User->find('all');
		foreach ($users as $user) {
			$userId = $user['User']['id'];
			$this->__addFollowingProfiles($userId);
			$this->__addFollowingEvents($userId);
			$this->__addFollowingCommunities($userId);
		}
	}
	
	/**
	 * Temp function to add following pages for diseases of existing users.
	 */
	public function addFollowingDiseasePages() {
		$this->FollowingPage = ClassRegistry::init('FollowingPage');
		$users = $this->User->find('all');
		foreach ($users as $user) {
			$userId = $user['User']['id'];
			$this->__addFollowingDiseases($userId);
		}		
	}

	/**
	 * Temp function to follow existing friends
	 */
	private function __addFollowingProfiles($userId) {
		$friends = $this->MyFriends->getUserConfirmedFriendsIdList($userId);
		if (!empty($friends)) {
			foreach ($friends as $friendId) {
				$data[] = array(
					'type' => FollowingPage::USER_TYPE,
					'page_id' => $friendId,
					'user_id' => $userId,
					'notification' => FollowingPage::NOTIFICATION_ON
				);
			}
			$this->FollowingPage->saveMany($data);
		}
	}

	/**
	 * Temp function to follow existing events
	 */
	private function __addFollowingEvents($userId) {
		$this->EventMember = ClassRegistry::init('EventMember');
		$events = $this->EventMember->getEvents($userId, array(
			EventMember::STATUS_ATTENDING,
			EventMember::STATUS_MAYBE_ATTENDING
		));
		if (!empty($events)) {
			foreach ($events as $eventId) {
				$data[] = array(
					'type' => FollowingPage::EVENT_TYPE,
					'page_id' => $eventId,
					'user_id' => $userId,
					'notification' => FollowingPage::NOTIFICATION_ON
				);
			}
			$this->FollowingPage->saveMany($data);
		}
	}

	/**
	 * Temp function to follow existing communities
	 */
	private function __addFollowingCommunities($userId) {
		$this->CommunityMember = ClassRegistry::init('CommunityMember');
		$communities = $this->CommunityMember->getCommunityList($userId, CommunityMember::STATUS_APPROVED);
		if (!empty($communities)) {
			foreach ($communities as $communityId) {
				$data[] = array(
					'type' => FollowingPage::COMMUNITY_TYPE,
					'page_id' => $communityId,
					'user_id' => $userId,
					'notification' => FollowingPage::NOTIFICATION_ON
				);
			}
			$this->FollowingPage->saveMany($data);
		}
	}
	
	/**
	 * Temp function to follow existing diseases
	 */
	private function __addFollowingDiseases($userId) {
		$this->PatientDisease = ClassRegistry::init('PatientDisease');
		$diseases = $this->PatientDisease->findDiseases($userId);
		if (!empty($diseases)) {
			foreach ($diseases as $diseaseId) {
				$data = array(
					'type' => FollowingPage::DISEASE_TYPE,
					'page_id' => $diseaseId,
					'user_id' => $userId,
					'notification' => FollowingPage::NOTIFICATION_ON
				);
				$this->FollowingPage->followPage($data);
			}
			
		}
	}

	/**
	 * Temp function to unfollow non friends of all users
	 */
	public function unfollowNonFriends() {
		set_time_limit(0);
		$this->FollowingPage = ClassRegistry::init('FollowingPage');
		$this->FollowingPage->deleteAll(array(
			'FollowingPage.type' => FollowingPage::USER_TYPE
		));
		$users = $this->User->find('all');
		foreach ($users as $user) {
			$userId = $user['User']['id'];
			$this->__addFollowingProfiles($userId);
		}
	}
        
                /**
         * Function to save cover image for profile, community and events 
         * 
         */
        public function saveProfileCoverBg() {
		$photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/user_profile/';
                if($this->request->is('ajax') ) {                        
                   
                    $data = $this->__saveUserProfileCoverBg();
                    
                    if ( !empty($data) ) {
                        $result = array(
                            'success' => true, 
                            'fileUrl' => $photoPath.$data
                        );
                    } else {
                        $result = array(
                            'success' => false                                    
                        );
                    }
                    
                    $this->autoRender = false;
                    $this->data = $result;
                        
                } 
        }
        
        private function __saveUserProfileCoverBg(){
                
            $userId =  $this->Session->read('Auth.User.id');            
            $fileName = $this->request->data('image');
            $profileBg = $this->Photo->getUserProfileBg($userId);
            
            if ( !empty($profileBg )) {
                $id = $profileBg['Photo']['id'];
                $result = $this->Photo->updateUserProfileBg($id, $userId, $fileName);
            } else {
                $result =$this->Photo->createUserProfileBg($userId, $fileName);
            }
            
            return $result;
        }

	/**
	 * Temp function to update the latest comments json in existing posts
	 */
	public function tmpUpdateExistingLatestComments() {
		set_time_limit(0);
		$posts = $this->Post->find('list', array('fields' => array('id')));
		$this->Posting = $this->Components->load('Posting');
		foreach ($posts as $postId) {
			$this->Posting->tmpUpdatePostLatestComments($postId);
		}
	}
        
        
        /**
         * Function to get the recurring events  that happens in between the given time.
         * @param array $eventsArray
         * @param date $from
         * @param date $to
         * @return array
         */
                
        public function getRepeatEventOccurenceArray($eventsArray, $from, $to) {
            
         /**
          * Geting Repeat interval values in text type.
          */
         $getRepeatIntervalTexts = $this->Event->getRepeatIntervalText();
         
        /**
         * Calculating the first occurance of the given event in the given date range.
         * 
         */
        $returnArray = array();
        $resultArray = array();
         
        foreach ($eventsArray as $event){
            $event_start = $event['Event']['start_date'];
            $event_start_obj = $event_start;            

            $firstStartDateFound = $from;
            $dateNoToAdd = $event['Event']['repeat_interval'];
            $repeatMode = $event['Event']['repeat_mode'];
            
            $eventEndTypeArray = array(Event::REPEAT_END_NEVER,Event::REPEAT_END_DATE);
            $eventRepeatModeArray = array(Event::REPEAT_MODE_DAILY, Event::REPEAT_MODE_MONTHLY,Event::REPEAT_MODE_WEEKLY,Event::REPEAT_MODE_YEARLY);
            
            $gotFirstDay = FALSE;
            
            if(!in_array($event['Event']['repeat_end_type'], $eventEndTypeArray)){
                $gotFirstDay = FALSE; 
            } elseif(!in_array($repeatMode, $eventRepeatModeArray)){
                $gotFirstDay = FALSE;  
            } elseif (($event['Event']['start_date'] > $event['Event']['end_date']) && ($event['Event']['end_date'] != '0000-00-00 00:00:00')){
                $gotFirstDay = FALSE; 
            } else {
                 if ($event_start_obj == $from) {
                    $firstStartDateFound = $event_start_obj;
                    $gotFirstDay = TRUE;
                 } else {
                     while (strtotime($event_start_obj) <= strtotime($to)) {
                         if ((strtotime($event_start_obj) >= strtotime($from)) && (strtotime($event_start_obj) <= strtotime($to))
                                 || strtotime($event_start_obj) == strtotime($from)) {
                            $firstStartDateFound = $event_start_obj;
                            $gotFirstDay = TRUE;
                             break 1;
                         } else {
                            $event_start_obj = date('Y-m-d H:i:s', strtotime($event_start_obj . ' + ' . $dateNoToAdd . ' ' . $getRepeatIntervalTexts[$repeatMode]));
                         }
                     }
                 }
             }

             if ($gotFirstDay == TRUE) {
                 $event['Event']['start_date'] = $firstStartDateFound;
                $resultArray[] = $event;
             }

             $returnArray = array(
                 'result' => $resultArray,
             );
        }
         return $returnArray;
        }
    }
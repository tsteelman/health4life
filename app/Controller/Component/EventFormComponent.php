<?php

/**
 * EventFormComponent class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('Event', 'Model');
App::uses('Date', 'Utility');
App::import('Controller', 'Api');
App::import('Vendor', 'ImageTool');

/**
 * EventFormComponent for adding and editing events.
 *
 * This class is used for event add and edit form display and save.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Controller.Component
 * @category	Component
 */
class EventFormComponent extends Component {

	/**
	 * Event community id
	 *
	 * @var int
	 */
	public $communityId = null;

	/**
	 * Variable to check if event is community event
	 *
	 * @var boolean
	 */
	protected $_isCommunityEvent = false;

	/**
	 * Variable to check if event is site wide community event
	 *
	 * @var boolean
	 */
	protected $_isSiteWideCommunityEvent = false;

	/**
	 * Constructor
	 *
	 * Initialises the models
	 */
	public function __construct() {
		$this->Event = ClassRegistry::init('Event');
		$this->EventMember = ClassRegistry::init('EventMember');
		$this->EventDisease = ClassRegistry::init('EventDisease');
		$this->Country = ClassRegistry::init('Country');
		$this->User = ClassRegistry::init('User');
		$this->CommunityMember = ClassRegistry::init('CommunityMember');
		$this->Community = ClassRegistry::init('Community');
		$this->NotificationSetting = ClassRegistry::init('NotificationSetting');
		$this->FollowingPage = ClassRegistry::init('FollowingPage');
        $this->CareCalendarEvent = ClassRegistry::init('CareCalendarEvent');
        $this->Post = ClassRegistry::init('Post');
	}

	/**
	 * Initialises the component
	 *
	 * @param Controller $controller
	 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->user = $controller->Auth->user();
	}

	/**
	 * Function to set event community status
	 */
	private function __setEventCommunityStatus() {
		// check if event is in a community
		$this->_isCommunityEvent = (!is_null($this->communityId)) ? true : false;

		// check if event is in a site wide community
		if ($this->_isCommunityEvent === true) {
			$community = $this->Community->findById($this->communityId);
			if (!empty($community)) {
				if (intval($community['Community']['type']) === Community::COMMUNITY_TYPE_SITE) {
					$this->_isSiteWideCommunityEvent = true;
				}
			}
		}
	}

	/**
	 * Sets the event form data on the controller
	 */
	public function setFormData($eventId = null) {
		$this->__setEventCommunityStatus();
		$formId = 'eventForm';
		$inputDefaults = array(
			'label' => false,
			'div' => false,
			'class' => 'form-control'
		);

		$Api = new ApiController;
		$user = $this->user;
		$friendsList = $Api->getFriendList($user['id']);

		$finishBtnTxt = __('Create');
		if (!is_null($eventId) && $eventId > 0) {
                    
                        $eventEditDetails = $this->Event->findById($eventId);
                        $eventEditDetails = $eventEditDetails['Event'];
			// btn title
			$finishBtnTxt = __('Update');

			// if edit event, set the already invited friends active by default
			$eventMembers = $this->EventMember->getEventMemberIds($eventId); // Members who have already been invited.
			$friendsListStatus = array();

			if (!empty($friendsList)) {
				foreach ($friendsList as $friend) {
					foreach ($eventMembers as $member) {
						if ($friend ['friend_id'] == $member ['EventMember'] ['user_id']) {
							$friend ['status'] = 'invited'; // Adding key status to know if the user is already invited to the event.
							break 1;
						} else {
							$friend ['status'] = 'not invited';
						}
					}
					$friendsListStatus [] = $friend;
				}
				$friendsList = $friendsListStatus;
			}
                        $eventEditData = array();
                        
                        if($eventEditDetails['repeat'] == 1) {
                            
                                $startDateTime = $eventEditDetails['start_date'];
                                $eventEditData['start_date_time'] = Date::MySqlDateTimeToJSDate($startDateTime, $eventEditDetails['timezone']);
                                $eventEditData['start_date_timeonly'] = Date::MySqlDateTimeoJSTime($startDateTime, $eventEditDetails['timezone']); 

                            
                                $eventEditData['repeat'] = $eventEditDetails['repeat'];  
                                $eventEditData['repeat_occurrences'] = $eventEditDetails['repeat_occurrences']; // span_date // repeat_mode //repeat_occurrences 
                                                                                                //repeat_end_type //repeat_interval //repeats_on //repeats_by 
                                
                                if(!empty($eventEditDetails['span_date']) && $eventEditDetails['span_date'] != '0000-00-00 00:00:00') {
                                    $eventEditData['upto_date'] = Date::MySqlDateTimeToJSDate($eventEditDetails['span_date'], $eventEditDetails['timezone']); 
                                    $eventEditData['upto_timeonly'] = Date::MySqlDateTimeoJSTime($eventEditDetails['span_date'], $eventEditDetails['timezone']);
                                }
                                
                                $eventEditData['span_date'] = Date::MySqlDateTimeToJSDate($eventEditDetails['span_date'], $eventEditDetails['timezone']); 
                                $eventEditData['span_time'] = Date::MySqlDateTimeoJSTime($eventEditDetails['span_date'], $eventEditDetails['timezone']);
                                
                                if(!empty($eventEditDetails['end_date']) && $eventEditDetails['end_date'] != '0000-00-00 00:00:00' && $eventEditDetails['repeat_end_type'] == Event::REPEAT_END_DATE) {
                                    $eventEditData['end_date'] = Date::MySqlDateTimeToJSDate($eventEditDetails['end_date'], $eventEditDetails['timezone']);
                                }
                                $eventEditData['repeat_mode'] = $eventEditDetails['repeat_mode']; 
                                $eventEditData['repeat_interval'] = $eventEditDetails['repeat_interval']; 
                                $eventEditData['repeat_interval_text'] = $eventEditDetails['repeat_interval']; 
                                $eventEditData['repeat_end_type'] = $eventEditDetails['repeat_end_type']; 
                                $eventEditData['repeats_by'] = $eventEditDetails['repeats_by']; 
//                                $eventEditData['repeats_on'] = isset($eventEditDetails['repeats_on']) ? explode(',', $eventEditDetails['repeats_on']) : ''; 
//                                $eventEditData['repeats_on_text_help'] = array();
//                                if(!empty($eventEditData['repeats_on'])) {
//                                    $parent_array = $eventEditData['repeats_on'];
//                                    $eventEditData['repeats_on_text_help']['MON'] = (in_array("MON", $parent_array)) ? TRUE : FALSE;
//                                    $eventEditData['repeats_on_text_help']['TUE'] = (in_array("TUE", $parent_array)) ? TRUE : FALSE;
//                                    $eventEditData['repeats_on_text_help']['WED'] = (in_array("WED", $parent_array)) ? TRUE : FALSE;
//                                    $eventEditData['repeats_on_text_help']['THU'] = (in_array("THU", $parent_array)) ? TRUE : FALSE;
//                                    $eventEditData['repeats_on_text_help']['FRI'] = (in_array("FRI", $parent_array)) ? TRUE : FALSE;
//                                    $eventEditData['repeats_on_text_help']['SAT'] = (in_array("SAT", $parent_array)) ? TRUE : FALSE;
//                                    $eventEditData['repeats_on_text_help']['SUN'] = (in_array("SUN", $parent_array)) ? TRUE : FALSE;
//                                }
                        } else {
//                                $eventEditData['repeats_on '] = '';
                        }
                        
		} elseif ($this->_isCommunityEvent) {
			// if community event, set the community members active by default
			$communityMembers = $this->CommunityMember->getApprovedCommunityMemberIds($this->communityId);
			$friendsListStatus = array();
			if (!empty($friendsList)) {
				foreach ($friendsList as $friend) {
					if (!empty($communityMembers)) {
						if (in_array($friend ['friend_id'], $communityMembers)) {
							$friend ['status'] = 'invited';
						} else {
							$friend ['status'] = 'not invited';
						}
					}
					$friendsListStatus [] = $friend;
				}
				$friendsList = $friendsListStatus;
			}
		}
		/**
		 * Get community joined by the login user.
		 */
		$communitiesIds = $this->CommunityMember->getCommunityList($user['id'], CommunityMember::STATUS_APPROVED);

		$communities = array();
		foreach ($communitiesIds as $communityId) {
			$communities[]['Community'] = $this->Community->getCommunity($communityId);
		}					
		
		/**
		 * event types
		 */
		if ($this->_isCommunityEvent) {
			$eventType = ($this->_isSiteWideCommunityEvent) ? Event::EVENT_TYPE_SITE : Event::EVENT_TYPE_PUBLIC;
		} else {
			$eventTypes = Event::getEventTypes();
			$eventTypeHintList = array(
				__('Private Event will be visible only to the invitees.'),
				__('Public Event will be open for anyone to attend.')
			);
			if ($this->user['is_admin']) {
				$eventTypeHintList[] = __('Site wide Event will be open for anyone to attend.');
			} else {
				unset($eventTypes[Event::EVENT_TYPE_SITE]);
			}
		}

		$eventLocations = Event::getEventLocations();
		$repeatModes = Event::getRepeatModes();
		$defaultRepeatMode = Event::REPEAT_MODE_WEEKLY;
		$repeatIntervalList = Event::getRepeatIntervalList();
		$repeatIntervalText = Event::getRepeatIntervalText();
		$defaultStartDate = (isset($eventEditData['start_date_time'])) ? $eventEditData['start_date_time'] : date('m/d/Y');
		$uptoStartDate = (isset($eventEditData['upto_date'])) ? $eventEditData['upto_date'] : $defaultStartDate;
		$countries = $this->Country->getAllCountries();
		$model = 'Event';
		$validations = $this->$model->validate;
		$this->controller->JQValidator->addValidation($model, $validations, $formId);

		// for implementing search in friends list.
		$friendsListJson = json_encode(array('friends' => array('friend' => $friendsList)));

		$diagnosisVisibilityClass = '';
		$onlineEventFieldsVisibilityClass = 'hide';
		$onsiteEventFieldsVisibilityClass = 'hide';
		$states = array();
		$cities = array();
		$stateDisabled = true;
		$cityDisabled = true;
		$isCommunityEvent = $this->_isCommunityEvent;
		$step3SiteWideVisibilityClass = ($this->_isSiteWideCommunityEvent) ? '' : 'hide';
		$step3CommonVisibilityClass = ($this->_isSiteWideCommunityEvent) ? 'hide' : '';
		$this->controller->set(compact('friendsListJson', 'friendsList', 'communities', 'user', 'formId', 'inputDefaults', 'eventTypes', 'eventLocations', 'repeatModes', 'defaultRepeatMode', 'repeatIntervalList', 'countries', 'defaultStartDate', 'diagnosisVisibilityClass', 'onlineEventFieldsVisibilityClass', 'onsiteEventFieldsVisibilityClass', 'states', 'cities', 'stateDisabled', 'cityDisabled', 'finishBtnTxt', 'eventType', 'isCommunityEvent', 'eventTypeHintList', 'step3SiteWideVisibilityClass', 'step3CommonVisibilityClass','repeatIntervalText','eventEditData','uptoStartDate'));
	}

	/**
	 * Saves an event
	 */
	public function saveEvent() {
		$this->_isCommunityEvent = (!is_null($this->communityId)) ? true : false;
		$userId = $this->user['id'];
		$postData = $this->controller->request->data;	
		$eventPostData = $postData['Event'];
		$eventData = array();		
		
		$this->isNewRecord = true;
		if (isset($eventPostData['id']) && $eventPostData['id'] > 0) {
			$eventData['id'] = $eventPostData['id'];
			$this->isNewRecord = false;
		}
		
		// event created user id
		$eventData['created_by'] = $userId;

		$isRepeatEvent = (intval($eventPostData['repeat']) === 1) ? true : false;
		if ($isRepeatEvent) {
			$eventData = $this->__getRepeatEventData($eventPostData);
                        $eventData['created_by'] = $userId;
                        $eventData['timezone'] = $eventPostData['timezone'];
                        $eventData['id'] = $eventPostData['id'];
		} else {
			// timezone
			$eventData['timezone'] = $eventPostData['timezone'];

			// start date time
			$startDateStr = $eventPostData['start_date'];
			$startDate = Date::JSDateToMySQL($startDateStr);
			$startTime = Date::JSTimeToMySQL($eventPostData['start_time']);
			$eventStartDate = $startDate . ' ' . $startTime;
			$eventData['start_date'] = CakeTime::toServer($eventStartDate, $eventPostData['timezone']);

			// end date time
			$endDate = $startDate; // end date = start date, for one day event
			$endTime = Date::JSTimeToMySQL($eventPostData['end_time']);
			$eventEndDate = $endDate . ' ' . $endTime;
			$eventData['end_date'] = CakeTime::toServer($eventEndDate, $eventPostData['timezone']);
		}

		// virtual event check
		if (isset($eventPostData['virtual_event']) && $eventPostData['virtual_event'] !== '') {
			if (intval($eventPostData['virtual_event']) === Event::VIRTUAL_EVENT) {
				$eventData['virtual_event'] = Event::VIRTUAL_EVENT;
				$eventData['online_event_details'] = $eventPostData['online_event_details'];
			} else {
                                $eventData['online_event_details'] = null;
				$eventData['virtual_event'] = Event::ORDINARY_EVENT;
				$eventData['location'] = $eventPostData['location'];
				$eventData['country'] = $eventPostData['country'];
				$eventData['state'] = $eventPostData['state'];
				$eventData['city'] = $eventPostData['city'];
				$eventData['zip'] = $eventPostData['zip'];
			}
		}

		// other details
		$eventData['name'] = $eventPostData['name'];
		$eventData['description'] = $eventPostData['description'];
		if ($this->_isCommunityEvent) {
			$eventData['community_id'] = $this->communityId;
		}
		$eventData['event_type'] = $eventPostData['event_type'];
		$eventData['guest_can_invite'] = $eventPostData['guest_can_invite'];
		$eventData['repeat'] = $eventPostData['repeat'];
		$eventData['image'] = $eventPostData['image'];
		$eventData['tags'] = $eventPostData['tags'];

		// Set the existing event data in a temp variable, when editing an event
		if (!$this->isNewRecord) {

			$eventId = $eventData['id'];
			$this->EventData = $this->Event->findById($eventId);

			if (isset($postData['friend_id'])) {
				$eventData['invited_count'] = $this->EventData['Event']['invited_count'] + count($postData['friend_id']);
			} else {
				$eventData['invited_count'] = $this->EventData['Event']['invited_count'];
			}

			$eventData['attending_count'] = $this->EventData['Event']['attending_count'];
			$eventData['maybe_count'] = $this->EventData['Event']['maybe_count'];
			$eventData['not_attending_count'] = $this->EventData['Event']['not_attending_count'];
		} else {

			$eventData['attending_count'] = 1;

			if (isset($postData['friend_id'])) {
				$eventData['invited_count'] = $this->EventData['Event']['invited_count'] + count($postData['friend_id']);
			} else {
				$eventData['invited_count'] = 0;
			}
		}

		// check if there are any notable changes in the event
		$isEventChangedNotably = $this->__isEventChangedNotably($eventData);

		// save data
		if ($this->Event->save($eventData, array('validate' => false))) {
			$isEventDataChanged = $this->__isEventDataChanged($eventData);
			$isEventTypeChanged = $this->__isEventTypeChanged($eventData);
			$isEventUpdateMailSent = false;
			$isNewInviteesExist = false;
			$eventId = $this->Event->id;
			$this->__saveEventDiseases($eventId);
			$this->__saveEventImage($eventId, $eventData['image']);

			$Api = new ApiController;
			$Api->constructClasses();

			$eventData['id'] = $eventId;
			if ($this->isNewRecord === true) {
				$this->__addNewEventPost($eventData);
				$this->EventMember->addEventAttendingMember($eventId, $userId);
				
				//Event follow data
				$followEventData = array(
					'type' => FollowingPage::EVENT_TYPE,
					'page_id' => $eventId,
					'user_id' => $userId,
					'notification' => FollowingPage::NOTIFICATION_ON
				);
				$this->FollowingPage->followPage($followEventData);
				if (intval($eventData['event_type']) === Event::EVENT_TYPE_SITE) {
					// add site wide event notification task to job queue
					ClassRegistry::init('Queue.QueuedTask')->createJob('SiteWideEventNotification', $eventData);
				}
			} else {
				if ($isEventDataChanged) {
					$this->__updateEventPosts($eventData);

					if (($isEventTypeChanged === true) && (intval($eventData['event_type']) === Event::EVENT_TYPE_SITE)) {
						// add site wide event notification task to job queue
						ClassRegistry::init('Queue.QueuedTask')->createJob('SiteWideEventNotification', $eventData);
					}
				}

				$isEventChanged = ($isEventDataChanged || $this->isDiseaseDataChanged);
				if ($isEventChangedNotably === true) {
					$eventData['old_name'] = $eventPostData['old_name'];
					$isEventUpdateMailSent = $this->__sendEventUpdateMail($eventData);
				}
			}

			$eventType = intval($eventData['event_type']);
			if ($this->isNewRecord === false && $eventType === Event::EVENT_TYPE_PRIVATE && !empty($this->diseases)) {
				$this->Post->deleteEventDiseasesPosts($eventId, $this->diseases);
			} else {
				if (!empty($this->newDiseases)) {
					$this->__addDiseaseEventPosts($eventData);
				}
			}

			if (intval($eventData['event_type']) !== Event::EVENT_TYPE_SITE) {
				$isNewInviteesExist = $this->__inviteMembers($eventId, $postData);
			}

			// set flash message on update
			if (!$this->isNewRecord) {
				if ($isEventChanged === true) {
					$message = 'The event has been updated successfully';
				} else {
					$message = 'No changes were made to the event';
				}
				if (!$isEventUpdateMailSent && !$isNewInviteesExist) {
					$message .= __('.');
				} elseif (!$isEventUpdateMailSent && $isNewInviteesExist) {
					$message .= __(' and invitations have been mailed to the new invitees.');
				} elseif ($isEventUpdateMailSent && !$isNewInviteesExist) {
					$message .= __(' and all the invitees have been notified.');
				} elseif ($isEventUpdateMailSent && $isNewInviteesExist) {
					$message .= __(' and all the invitees have been notified. Invitations have been mailed to the new invitees.');
				}
				$this->controller->Session->setFlash($message, 'success');
			}
                        if($this->controller->request->is('ajax')) {
                             return true;  
                        } else {
                            if(isset($eventPostData['refer'])) {
                                if(substr($eventPostData['refer'], 1, 8) == 'calendar') {
                                    $refer = 'c';
                                } else if(substr($eventPostData['refer'], 1, 9) == 'condition') {
									$refer = 'd';
								}
                                return $this->controller->redirect('/event/details/index/' . $eventId . '?f=' . $refer);                           
                            } else {
                                return $this->controller->redirect('/event/details/index/' . $eventId);
                            }
                        }                       
		}
	}

	/**
	 * Function to get the invitees of selected communities and event community
	 * 
	 * @param array $postData
	 * @return array
	 */
	private function __getCommunityInvitees($postData) {
		$invitees = array();

		$communities = array();
		if (!empty($postData['community_id'])) {
			$communities = array_values($postData['community_id']);
		}
		if ($this->_isCommunityEvent) {
			$communities[] = $this->communityId;
		}

		if (!empty($communities)) {
			$communities = array_unique($communities);
			foreach ($communities as $communityId) {
				$communityMemberIds = $this->CommunityMember->getApprovedCommunityMemberIds($communityId);
				$invitees = array_merge($invitees, $communityMemberIds);
			}
		}

		return $invitees;
	}

	/**
	 * Function to invite new members to the event
	 * 
	 * @param int $eventId
	 * @param array $postData
	 * @return boolean
	 */
	private function __inviteMembers($eventId, $postData) {
		$newInviteesExist = false;
		$inviteeList = array();
		
		$communityInvitees = $this->__getCommunityInvitees($postData);
		if (!empty($communityInvitees)) {
			$inviteeList = array_merge($inviteeList, $communityInvitees);
		}
		
		if (!empty($postData['friend_id'])) {
			$selectedFriends = $postData['friend_id'];
			$inviteeList = array_merge($inviteeList, $selectedFriends);
		}

		if (!empty($inviteeList)) {
			// remove duplicate user ids
			$inviteeList = array_unique($inviteeList);
			
			// exclude event creator
			$invitedUserId = $this->user['id'];
			$invitedUserIndex = array_search($invitedUserId, $inviteeList);
			if ($invitedUserIndex > -1) {
				unset($inviteeList[$invitedUserIndex]);
			}
			
			if (!$this->isNewRecord) {
				// remove existing members from the invitee list
				$eventMembers = $this->EventMember->getEventMemberIds($eventId);
				if (!empty($eventMembers)) {
					$existingMembersList = array();
					foreach ($eventMembers as $eventMemberData) {
						$eventMember = $eventMemberData['EventMember'];
						$existingMembersList[] = $eventMember['user_id'];
					}
					if (!empty($existingMembersList)) {
						$inviteeList = array_diff($inviteeList, $existingMembersList);
					}
				}
			}

			//sort the keys and filter out empty values
			$inviteeList = array_filter(array_values($inviteeList));

			// if invitee list is not empty, send invitations
			if (!empty($inviteeList)) {
				$Api = new ApiController;
				$Api->constructClasses();
				$Api->_invite_memebers_to_event($eventId, $inviteeList, $invitedUserId);
				$newInviteesExist = true;
			}
		}

		return $newInviteesExist;
	}

	/**
	 * Function to check if event type changed or not
	 * 
	 * @param array $eventData 
	 * @return boolean
	 */
	private function __isEventTypeChanged($eventData) {
		$isEventTypeChanged = false;
		if (isset($this->EventData) && !empty($this->EventData)) {
			$existingEventData = $this->EventData;
			$existingEvent = $existingEventData['Event'];
			if (isset($eventData['event_type']) && $existingEvent['event_type'] !== $eventData['event_type']) {
				$isEventTypeChanged = true;
			}
		}

		return $isEventTypeChanged;
	}

	/**
	 * Function to add a post indicating that a new event is added
	 *
	 * @param array $eventData
	 */
	private function __addNewEventPost($eventData) {
		$clientIp = $this->controller->request->clientIp();
		$this->Post->addNewEventPost($eventData, $clientIp);
	}

	/**
	 * Function to update the posts of an event
	 *
	 * @param array $eventData
	 */
	private function __updateEventPosts($eventData) {
		$this->Post->updateEventPosts($eventData);
	}

	/**
	 * Function to add posts in disease page about event
	 * 
	 * @param array $eventData
	 */
	private function __addDiseaseEventPosts($eventData) {
		$clientIp = $this->controller->request->clientIp();
		$this->Post->addDiseaseEventPosts($eventData, $clientIp, $this->newDiseases);
	}

	/**
	 * Function to send event update email to existing members of the event
	 *
	 * @param array $eventData
	 */
	private function __sendEventUpdateMail($eventData) {
		$eventId = $eventData['id'];
		$userId = $this->user['id'];

		// get members who were already invited/attending/maybe to the event
		$members = $this->EventMember->getExistingMembers($eventId, $userId);
		if (!empty($members)) {
			$emailData = array(
				'link' => Router::Url('/', TRUE) . 'event/details/index/' . $eventId,
				'eventname' => $eventData['old_name']
			);
			$Api = new ApiController;
			$Api->constructClasses();
			foreach ($members as $existingMember) {
				$eventMember = $existingMember['EventMember'];
				$memberId = $eventMember['user_id'];
				$memberIdArr[] = $memberId;

				//check setting before sending email
				$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($memberId, 'event_update');
				if ($isEmailNotificationOn && (!$this->User->isUserOnline($memberId))) {
					// send mail
					$userDetail = $this->User->getUserDetails($memberId);
					$emailData['username'] = Common::getUsername($userDetail['user_name'], $userDetail['first_name'], $userDetail['last_name']);
					$Api->sendEventUpdateMail($emailData, $userDetail['email']);
				}
			}

			// add event update site notification task to job queue
			ClassRegistry::init('Queue.QueuedTask')->createJob('EventUpdateNotification', array(
				'event_id' => $eventId,
				'sender_id' => $userId,
				'recipients' => $memberIdArr,
				'event_name' => $eventData['old_name']
			));

			return true;
		}

		return false;
	}

	/**
	 * Function to save event image to a permanent folder
	 *
	 * @param int $eventId
	 * @param string $imageName
	 * @return array
	 * @throws Exception
	 */
	private function __saveEventImage($eventId, $imageName) {
		$result['success'] = false;
		try {
			if (isset($imageName) && !empty($imageName)) {

				$uploadPath = Configure::read("App.UPLOAD_PATH");
				$thumbnailPath = Configure::read("App.EVENT_IMG_PATH");
				$tmpImage = $uploadPath . DIRECTORY_SEPARATOR . $imageName;

				if (file_exists($tmpImage)) {
					$tmpFile = new File($tmpImage);
					if (!file_exists($thumbnailPath)) {
						mkdir($thumbnailPath, 0777);
					}

					/*
					 * Move the cropped image to permanent folder
					 */
					$originalThumb = $thumbnailPath . DIRECTORY_SEPARATOR . Common::getEventThumbName($eventId);
					$tmpFile->copy($originalThumb, true);

					/*
					 * remove the initial image
					 */
					$tmpFile->delete();

					$result['success'] = true;
				} else {
					throw new Exception("Uploaded file does not exist");
				}
			}
		} catch (Exception $e) {
			$result['success'] = false;
			$result['msg'] = $e->getMessage();
		}

		return $result;
	}

	/**
	 * Get the details of repeating event from post data
	 *
	 * @param array $eventPostData
	 * @return array
	 */
	private function __getRepeatEventData($eventPostData) {
//            echo '<pre>';
//                print_r($eventPostData);
//                exit;
		$data = array();

		// user timezone
		$eventData['timezone'] = $eventPostData['timezone'];

		// start date
//		$startDateStr = $eventPostData['start_date_time'];
//		$eventStartDate = Date::JSDateToMySQL($startDateStr);
//		$data['start_date'] = CakeTime::toServer($eventStartDate, $eventPostData['timezone']);
                
                
			// start date time
                        if(isset($eventPostData['start_date_time']) && !empty($eventPostData['start_date_time'])) {
                            $startDateStr = $eventPostData['start_date_time'];
                            $startDate = Date::JSDateToMySQL($startDateStr);
                            $startTime = Date::JSTimeToMySQL($eventPostData['start_date_timeonly']);
                            $eventStartDate = $startDate . ' ' . $startTime;
                            $data['start_date'] = CakeTime::toServer($eventStartDate, $eventPostData['timezone']);
                        }
                        if(!isset($eventPostData['is_full_day'])){
                            $uptoDateStr = $eventPostData['upto_date'];
                            $uptoDate = Date::JSDateToMySQL($uptoDateStr);
                            $uptoTime = Date::JSTimeToMySQL($eventPostData['upto_timeonly']);
                            $eventuptoDate = $uptoDate . ' ' . $uptoTime;
                            $data['span_date'] = CakeTime::toServer($eventuptoDate, $eventPostData['timezone']);
                        } else {
                            $data['span_date'] = '0000-00-00 00:00:00';
                        }
//                        exit;

		// repeat mode
		$repeatMode = intval($eventPostData['repeat_mode']);
		$data['repeat_mode'] = $eventPostData['repeat_mode'];

//		switch ($repeatMode) {
//			case Event::REPEAT_MODE_WEEKLY:
//				if (isset($eventPostData['repeats_on'])) {
//					$data['repeats_on'] = join(',', $eventPostData['repeats_on']);
//				}
//				break;
//			case Event::REPEAT_MODE_MONTHLY:
//				$data['repeats_by'] = $eventPostData['repeats_by'];
//				break;
//		}
                    
		// repeat_interval
		$intervalRepeatModes = array(
			Event::REPEAT_MODE_DAILY,
			Event::REPEAT_MODE_WEEKLY,
			Event::REPEAT_MODE_MONTHLY,
			Event::REPEAT_MODE_YEARLY,
		);
		if (in_array($repeatMode, $intervalRepeatModes)) {
			$data['repeat_interval'] = $eventPostData['repeat_interval'];
		}
                
//                print_r($startDate);
//                exit;
                // repeat_end_type
		$repeatEndType = intval($eventPostData['repeat_end_type']);
		$data['repeat_end_type'] = $eventPostData['repeat_end_type'];

		switch ($repeatEndType) {
			case Event::REPEAT_END_DATE:
				$endDateStr = $eventPostData['end_date'];
				$eventEndDate = Date::JSDateToMySQL($endDateStr);
                                $eventEndDateTime = $eventEndDate. ' 23:59:59';
				$data['end_date'] = CakeTime::toServer($eventEndDateTime, $eventPostData['timezone']);
				break;
			case Event::REPEAT_END_NEVER:
				$data['end_date'] = '0000-00-00 00:00:00';
				break;
		}
                
                
//                echo '<pre>';
//                print_r($data);
//                exit;
                
		// return data
		return $data;
	}

	/**
	 * Saves event diseases
	 *
	 * @param int $eventId
	 */
	private function __saveEventDiseases($eventId) {
		$updatedDiseaseIdArray = array();
		$oldDiseaseIdArray = array();
		$newDiseases = array();

		// if edit event, save existing diseases in temporary variable
		if (!$this->isNewRecord) {
			$existingDiseases = $this->EventDisease->findAllByEventId($eventId);
		}

		// save edited and new diseases
		if (isset($this->controller->request->data['EventDisease'])) {
			$eventDiseases = $this->controller->request->data['EventDisease'];
			foreach ($eventDiseases as $eventDisease) {
				if ($eventDisease['disease_id'] > 0) {
					// data to be saved
					$diseaseId = $eventDisease['disease_id'];
					$eventDiseaseId = isset($eventDisease['id']) ? $eventDisease['id'] : null;
					$data[] = array(
						'id' => $eventDiseaseId, // to update existing
						'disease_id' => $diseaseId,
						'event_id' => $eventId,
					);
					$updatedDiseaseIdArray[] = $diseaseId;
				}
			}

			// save multiple records
			if (!empty($data)) {
				$this->diseases = $updatedDiseaseIdArray;
				$this->EventDisease->saveMany($data, array('validate' => false));
			}
		}

		// if edit event, delete removed diseases
		if (isset($existingDiseases)) {
			if (!empty($existingDiseases)) {
				foreach ($existingDiseases as $eventDisease) {
					$oldDiseaseId = $eventDisease['Disease']['id'];
					$oldDiseaseIdArray[] = $oldDiseaseId;
					if (!empty($updatedDiseaseIdArray)) {
						if (!in_array($oldDiseaseId, $updatedDiseaseIdArray)) {
							$deletedDiseaseIdArray[] = $oldDiseaseId;
						}
					} else {
						$deletedDiseaseIdArray[] = $oldDiseaseId;
					}
				}

				if (!empty($deletedDiseaseIdArray)) {
					$this->EventDisease->deleteAll(array(
						'Event.id' => $eventId,
						'Disease.id' => $deletedDiseaseIdArray), false);
					$this->Post->deleteEventDiseasesPosts($eventId, $deletedDiseaseIdArray);
				}
			}
		} else {
			$newDiseases = $updatedDiseaseIdArray;
		}
		
		// check if disease data has changed
		$diffOldUpdated = array_diff($oldDiseaseIdArray, $updatedDiseaseIdArray);
		$diffUpdatedOld = array_diff($updatedDiseaseIdArray, $oldDiseaseIdArray);
		
		if (empty($newDiseases) && !empty($diffUpdatedOld)) {
			$newDiseases = $diffUpdatedOld;
		}
		
		if (!empty($diffOldUpdated) || !empty($diffUpdatedOld)) {
			$isDiseaseDataChanged = true;
		} else {
			$isDiseaseDataChanged = false;
		}
		$this->isDiseaseDataChanged = $isDiseaseDataChanged;
		$this->newDiseases = $newDiseases;
	}

	/**
	 * Function to check if event has notable changes
	 */
	private function __isEventChangedNotably($eventData) {
		$isEventChanged = false;
		if (isset($this->EventData) && !empty($this->EventData)) {
			$existingEventData = $this->EventData;
			$existingEvent = $existingEventData['Event'];
			$fields = array('name', 'description', 'event_type', 'image',
				'start_date', 'end_date', 'virtual_event',
				'online_event_details', 'location', 'country', 'state',
				'city', 'zip', 'guest_can_invite'
			);
			foreach ($fields as $field) {
				if (isset($eventData[$field])) {
					if ($existingEvent[$field] != $eventData[$field]) {
						$isEventChanged = true;
						break;
					}
				}
			}
		}
		return $isEventChanged;
	}

	/**
	 * Function to check if event data has changed
	 */
	private function __isEventDataChanged($eventData) {
		$isEventDataChanged = false;
		if (isset($this->EventData) && !empty($this->EventData)) {
			$existingEventData = $this->EventData;
			$existingEvent = $existingEventData['Event'];
			$fields = array('name', 'description', 'event_type', 'image',
				'start_date', 'end_date', 'virtual_event',
				'online_event_details', 'location', 'country', 'state',
				'city', 'zip', 'guest_can_invite', 'tags'
			);
			foreach ($fields as $field) {
				if (isset($eventData[$field])) {
					if ($existingEvent[$field] != $eventData[$field]) {
						$isEventDataChanged = true;
						break;
					}
				}
			}
		}
		return $isEventDataChanged;
	}

	/**
	 * Function to change the count of attending invited and not attending users
	 *
	 * @param int $eventId
	 */
	private function __changeEventInviteCount($eventId) {

		$invited_count = $this->EventMember->find('count', array(
			'conditions' => array(
				'EventMember.status' => 0,
				'EventMember.event_id' => $eventId
			)
		));

		if ($invited_count == null) {
			$invited_count = 0;
		}

		$this->Event->id = $eventId;
		$this->Event->saveField('invited_count', $invited_count);
	}

	public function saveCalendarReminderEvent($postData) {
                $postDataDetails = $postData;
                if(isset($postData['repeat']) && $postData['repeat'] == 1){
                        $repeatData = $this->__getRepeatEventData($postDataDetails);
                        $postData['end_date'] = $repeatData['end_date'];
                } else {
                        $postData['repeat_mode'] =  0;
                        $postData['repeat_interval'] =  0;
                        $postData['repeat_end_type'] =  0;
                        $postData['repeat_occurrences'] =  0;
                }

		if (isset($postData['id']) && $postData['id'] != NULL) {
			$this->Event->id = $postData['id'];
			if ($this->Event->save($postData, array('validate' => false))) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			if ($this->Event->save($postData, array('validate' => false))) {
				$eventId = $this->Event->id;
//                $this->EventMember->addEventAttendingMember($eventId, $postData['created_by']);
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}
        
        /**
         * Function to save care calendar event
         * @param array $postData
         * @param int $teamId
         * @return boolean
         */
        public function saveCareCalendarEvent( $postData, $teamId = 0, $event_id = 0) {
            
            /*
             * boolean to return
             */
            $result = false;
            
            /*
             * Is edititng 
             */
            $is_editing = false;
            
            /*
             * Event created user details
             * id, timezone
             */
            $userId = $this->user['id'];
            $userTimezone = $this->user['timezone'];
            
            /*
             * Post data from form element
             */
            $eventPostData = $postData['Event'];
            
            // start date time
            $startDateStr = $eventPostData['start_date'];
            $startDate = Date::JSDateToMySQL($startDateStr);
            $startTime = Date::JSTimeToMySQL($eventPostData['start_time']);
            $eventStartDate = $startDate . ' ' . $startTime;
            
            // end date time
            $endDate = $startDate; // end date = start date, for one day event
            $endTime = Date::JSTimeToMySQL($eventPostData['end_time']);
            $eventEndDate = $endDate . ' ' . $endTime;
            
            /*
             * Data array for saving to events table
             */
            $eventData = array();
            
            
            /*
             * Data array for saveing to care_calendar_events table
             */
            $careCalendarEventData = array();
            
            if( !empty($eventPostData['id'])){                
                $eventDetails = $this->CareCalendarEvent->getTaskDetailsFromTaskId($eventPostData['id']);
                $eventData['id'] = $eventDetails['Event']['id'];   
                $careCalendarEventData['id'] = $eventPostData['id'];
                $is_editing = true;
            }
            $eventData['name'] = $eventPostData['name'];
            $eventData['description'] = $eventPostData['description'];
            $eventData['created_by'] = $userId;
            $eventData['event_type'] = Event::EVENT_TYPE_CARE_CALENDAR_EVENT;
            $eventData['section'] = Event::SECTION_EVENT_IN_TEAM;
            $eventData['section_id'] = $teamId;
            $eventData['start_date'] = CakeTime::toServer($eventStartDate, $userTimezone);
            $eventData['end_date'] = CakeTime::toServer($eventEndDate, $userTimezone);
            
            /*
             * Save data in to events table
             */
            if ($this->Event->save($eventData, array('validate' => false))) {
                $eventId =  $this->Event->id;
                
                $careCalendarEventData['event_id'] = $eventId;                
                $careCalendarEventData['type'] = $eventPostData['type'];
                
                /*
                 * If it is the task creation 
                 */
                if( ! $is_editing ) {
                        if ( isset( $eventPostData['assigned_to'] ) && $eventPostData['assigned_to'] != 0) {
                            $careCalendarEventData['assigned_to'] = $eventPostData['assigned_to'];

                            /*
                             * Self assign
                             */
                            if($eventPostData['assigned_to'] == $userId) {
                                $careCalendarEventData['status'] = CareCalendarEvent::STATUS_ASSIGNED;
                            } else {
                                $careCalendarEventData['status'] = CareCalendarEvent::STATUS_WAITING_FOR_APPROVAL;
                            }

                            /*
                             * Crate assingee history
                             */
                            $careCalendarEventData['history'] = $this->CareCalendarEvent->createHistory(
                                CareCalendarEvent::ACTION_CREATION, null, 
                                null, $userId, 0, $eventPostData['assigned_to']);

                        } else {
                            $careCalendarEventData['status'] = CareCalendarEvent::STATUS_OPEN;
                            /*
                             * Cration history
                             */
                            $careCalendarEventData['history'] = $this->CareCalendarEvent->createHistory( 
                                CareCalendarEvent::ACTION_CREATION, null, null, $userId);
                        }
                
                    // if it is editig an exising task
                } else {
                    
                        $giverId = 0;
                        $receiverId = 0;
                   
                        /*
                         * If the assignee is changed
                         */
                        if ( $eventDetails['CareCalendarEvent']['assigned_to'] != $eventPostData['assigned_to'] ) {
                            $careCalendarEventData['assigned_to'] = $eventPostData['assigned_to'];

                            $giverId = $eventDetails['CareCalendarEvent']['assigned_to'];
                            $receiverId = $eventPostData['assigned_to'];
                            
                            /*
                             * If assignee is set to '0' it is an OPEN task,
                             * If it is self assigned then it is an ASSIGNED task,
                             * If the assignee is a member status is WAITING FOR APPROVAL 
                             */
                            if ( $eventPostData['assigned_to'] == 0) {
                                $careCalendarEventData['status'] = CareCalendarEvent::STATUS_OPEN;
                            } else if ($eventPostData['assigned_to'] == $userId) {
                                $careCalendarEventData['status'] = CareCalendarEvent::STATUS_ASSIGNED;
                            } else {
                                $careCalendarEventData['status'] = CareCalendarEvent::STATUS_WAITING_FOR_APPROVAL;
                            }
                            echo "changed".$eventPostData['assigned_to'];
                        }
                        $historyJson =  $eventDetails['CareCalendarEvent']['history'];
                            $careCalendarEventData['history'] = $this->CareCalendarEvent->createHistory( 
                                CareCalendarEvent::ACTION_EDITING, $historyJson, null, $userId, $giverId, $receiverId);
                    
                }
                
                if ( isset( $eventPostData['times_per_day'] )) {
                     $careCalendarEventData['times_per_day'] = $eventPostData['times_per_day'];
                }
                if ( isset( $eventPostData['additional_notes'] )) {
                     $careCalendarEventData['additional_notes'] = $eventPostData['additional_notes'];
                }
                /*
                 * Save date to care calendar events table
                 */
                $result = $this->CareCalendarEvent->save($careCalendarEventData, array('validate' => false));
            }
	
            return $result;
        }
        
        /**
         * Functiont to format date array to stirng
         * @param array $date
         * @return string : date
         */
        private function __combainDateandTime($date) {
           
            $hour = $date ['hour'];
            
            if ( $date ['meridian'] == 'pm' && $date ['hour'] < 12) {
                $hour = $date ['hour'] + 12 ;
            } else if ( $date ['meridian'] == 'am' && $date ['hour'] == 12) {
                $hour = 00 ;
            }
            
            $string = $date ['month'] .'/'. $date ['day'] .'/'. $date ['year'] 
                    .' '. $hour .':'. $date ['min'] .':00';
            
            return $string;
        }
}
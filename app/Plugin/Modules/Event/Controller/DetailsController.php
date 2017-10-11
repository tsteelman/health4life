<?php

/**
 * DetailsController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @author    Varun Ashok <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('EventAppController', 'Event.Controller');
App::uses('Date', 'Utility');
App::import('Controller', 'Api');
App::uses('FollowingPage', 'Model');

/**
 * DetailsController for the event
 *
 * DetailsController is used for viewing event details.
 *
 * @author 		Ajay Arjunan
 * @author 		Varun Ashok
 * @package 	Event
 * @category	Controllers
 */
class DetailsController extends EventAppController {

    public $uses = array(
        'Event',
        'User',
        'Disease',
        'EventMember',
        'CommunityMember',
        'PatientDisease',
        'MyFriends',
        'Cities',
        'Countries',
        'States',
        'EmailTemplate',
        'EventDisease',
        'Post',
        'NotificationSetting',
        'Photo',
		'FollowingPage'
    );
    public $components = array(
        'Session',
        'Email',
        'EmailTemplate',
        'EmailQueue',
        'Posting',
        'Paginator',
        'Ads'
    );

    function index($id = null) {
        $is_sharing_enabled = $this->getSharingOptions();
        $this->set('is_sharing_enabled', $is_sharing_enabled);
        if (!$this->Event->exists($id)) {
            if (!$this->request->is('ajax')) {
                $this->Session->setFlash(__($this->invalidMessage), 'error');
				$this->redirect('/event');
            } else {
                $this->Session->setFlash(__('The event has been deleted by sponsor.'), 'error');
                $response = 'false';
                echo $response;
                exit();
            }
        } else {
            $eventId = $id;
            $options = array(
                'conditions' => array(
                    'Event.' . $this->Event->primaryKey => $id
                )
            );

            $event = $this->Event->find('first', $options);
			$this->set('title_for_layout', $event['Event']['name']);
            
            $refer = '';
            
            if($this->Session->check('refer')) {
                $refer = $this->Session->read('refer');
                $this->Session->delete('refer');
            }
            
            $this->set('refer', $refer);
            $this->set('coverModel', 'Event');
            $this->set('roomId', $eventId);

            $event_type = array(
                Event::EVENT_TYPE_PRIVATE,
                Event::EVENT_TYPE_PUBLIC,
                Event::EVENT_TYPE_SITE
            );

            if (in_array($event['Event']['event_type'], $event_type)) {
                $Api = new ApiController ();
                $user = $this->Auth->user();

                // mark the event notifications as read by the logged in user
                $this->Notification = ClassRegistry::init('Notification');
                $this->Notification->markEventNotificationsReadByUser($eventId, $user['id']);

                $friendsList = $Api->getFriendList($user ['id']);
                $eventMembers = $this->EventMember->getEventMemberIds($id); // Members who have already been invited.

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

                $currentUserId = $this->Auth->user('id');
                $now = date("Y-m-d H:i:s");
                $user = array(
                    'user_id' => $currentUserId,
                    'first_name' => $this->Auth->user('first_name')
                );

                $hasManagePermission = false;
                if ($event['Event']['created_by'] == $currentUserId) {
                    $hasManagePermission = true;
                }

                $isCommunityEvent = false;
                $isCommunityMember = false;
                $isApprovedCommunityMember = false;
                $isInvited = false;

                // community event
                if ($event['Event']['community_id'] > 0) {
                    $isCommunityEvent = true;
                    $community = $event['Community'];
                    $communityId = $community['id'];
                    $data['communityId'] = $communityId;
                    $data['communityName'] = h($community['name']);
                                      
                    $communityMemberStatus = $this->CommunityMember->getCommunityMemberStatus($currentUserId, $communityId);
                    if (!is_null($communityMemberStatus)) {
                        $isCommunityMember = true;
                        if ($communityMemberStatus == CommunityMember::STATUS_APPROVED) {
                            $isApprovedCommunityMember = true;
                        }
                        
                        if ($communityMemberStatus == CommunityMember::STATUS_INVITED) {
                            $isInvited = true;
                        }
                    }

                    $hasCommunityManagePermission = $this->CommunityMember->hasManagePermission($currentUserId, $communityId);
                    if ($hasCommunityManagePermission) {
                        $hasManagePermission = true;
                    }
                    $this->set($data);
                }

                $currentAttendanceStatus = $this->EventMember->find('all', array(
                    'conditions' => array(
                        'EventMember.event_id' => $id,
                        'EventMember.user_id' => $currentUserId
                    )
                ));
                if (isset($currentAttendanceStatus) && count($currentAttendanceStatus) > 0) {
                    $currentAttendanceOfUser = $currentAttendanceStatus ['0'] ['EventMember'] ['status'];
                } else {
                    $currentAttendanceOfUser = null;
                    if (!$isCommunityEvent) {
                        if ($event['Event']['event_type'] == 2 && $event['Event']['created_by'] != $currentUserId) {
                            $this->Session->setFlash(__('You do not have permission to view this event'), 'error');
                            $this->redirect('/event');
                        }
                    }
                }

                $eventCreatedBy = $this->User->getUserDetails($event ['Event'] ['created_by']);
                if (isset($event ['Event'] ['city'])) {
                    $city = $this->Cities->find('first', array(
                        'conditions' => array(
                            'Cities.id' => $event ['Event'] ['city']
                        ),
                        'fields' => array(
                            'description'
                        )
                    ));
                    $city = $city ['Cities'] ['description'];
                } else {
                    $city = null;
                }
                if (isset($event ['Event'] ['state'])) {
                    $state = $this->States->find('first', array(
                        'conditions' => array(
                            'States.id' => $event ['Event'] ['state']
                        ),
                        'fields' => array(
                            'description'
                        )
                    ));
                    $state = $state ['States'] ['description'];
                } else {
                    $state = null;
                }
                if (isset($event ['Event'] ['country'])) {
                    $country = $this->Countries->find('first', array(
                        'conditions' => array(
                            'Countries.id' => $event ['Event'] ['country']
                        ),
                        'fields' => array(
                            'short_name'
                        )
                    ));
                    $country = $country ['Countries'] ['short_name'];
                } else {
                    $country = null;
                }
                $eventLocation = array(
                    'city' => $city,
                    'state' => $state,
                    'country' => $country,
                    'location' => $event['Event'] ['location']
                );


                if (isset($this->request->params ['named'] ['attendance'])) {
                    $status = $this->request->params ['named'] ['attendance'];
                    if ( !$isCommunityEvent || ($isCommunityEvent && $isApprovedCommunityMember) ) {
                            if (isset($currentAttendanceStatus) && count($currentAttendanceStatus) > 0) {
                                $attendance_id = $currentAttendanceStatus ['0'] ['EventMember'] ['id'];
                                if (isset($attendance_id)) {
                                    $updateAttendance = array(
                                        'EventMember' => array(
                                            'id' => $attendance_id,
                                            'status' => $status
                                        )
                                    );
                                    $this->EventMember->save($updateAttendance);
                                    $result = array(
                                        'success' => true,
                                        'message' => 'updated your attendance.'
                                    );
                                                                if (
                                                                                ($status == EventMember::STATUS_MAYBE_ATTENDING) ||
                                                                                ($status == EventMember::STATUS_ATTENDING)
                                                                ) {
                                                                        //Event follow data
                                                                        $followEventData = array(
                                                                                'type' => FollowingPage::EVENT_TYPE,
                                                                                'page_id' => $eventId,
                                                                                'user_id' => $currentUserId,
                                                                                'notification' => FollowingPage::NOTIFICATION_ON
                                                                        );
                                                                        $this->FollowingPage->followPage($followEventData);
                                                                }

                                                                if ($status == EventMember::STATUS_NOT_ATTENDING) {
                                                                        //Event unfollow data
                                                                        $followEventData = array(
                                                                                'type' => FollowingPage::EVENT_TYPE,
                                                                                'page_id' => $eventId,
                                                                                'user_id' => $currentUserId
                                                                        );
                                                                        $this->FollowingPage->unFollowPage($followEventData);
                                                                }

                                    $event_count = $this->Event->find('first', array(
                                        'conditions' => array('Event.id' => $eventId),
                                        'fields' => array('Event.attending_count', 'Event.not_attending_count', 'Event.maybe_count', 'Event.invited_count',)
                                    ));

                                    $this->Event->id = $eventId;
                                    if (isset($status) && $status == EventMember::STATUS_ATTENDING) {
                                        $status_text = 'Attending';
                                        $this->Event->saveField('attending_count', ($event_count['Event']['attending_count'] + 1));
                                    } elseif (isset($status) && $status == EventMember::STATUS_NOT_ATTENDING) {
                                        $status_text = 'Not Attending';
                                        $this->Event->saveField('not_attending_count', ($event_count['Event']['not_attending_count'] + 1));
                                    } elseif (isset($status) && $status == EventMember::STATUS_MAYBE_ATTENDING) {
                                        $status_text = 'Maybe Attending';
                                        $this->Event->saveField('maybe_count', ($event_count['Event']['maybe_count'] + 1));
                                    }

                                    //Updating event table counts
                                    if ($currentAttendanceStatus['0']['EventMember']['status'] == EventMember::STATUS_PENDING) {
                                        $this->Event->saveField('invite_count', ($event_count['Event']['invited_count'] - 1));
                                    } elseif ($currentAttendanceStatus['0']['EventMember']['status'] == EventMember::STATUS_ATTENDING) {
                                        $this->Event->saveField('attending_count', ($event_count['Event']['attending_count'] - 1));
                                    } elseif ($currentAttendanceStatus['0']['EventMember']['status'] == EventMember::STATUS_NOT_ATTENDING) {
                                        $this->Event->saveField('not_attending_count', ($event_count['Event']['not_attending_count'] - 1));
                                    } elseif ($currentAttendanceStatus['0']['EventMember']['status'] == EventMember::STATUS_MAYBE_ATTENDING) {
                                        $this->Event->saveField('maybe_count', ($event_count['Event']['maybe_count'] - 1));
                                    }

                                    $isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($eventCreatedBy['user_id'], 'event_rsvp');
                                    if ($isEmailNotificationOn && (!$this->User->isUserOnline($eventCreatedBy['user_id']))) {
                                        $emailData = array(
                                            'name' => Common::getUsername($eventCreatedBy['user_name'], $eventCreatedBy['first_name'], $eventCreatedBy['last_name']),
                                            'username' => Common::getUsername($this->Auth->user('username'), $this->Auth->user('first_name'), $this->Auth->user('last_name')),
                                            'status' => $status_text,
                                            'eventname' => h($event['Event']['name'])
                                        );
                                        $this->sendMailToCreator($eventCreatedBy ['email'], $emailData);
                                    }

                                    // add event rsvp notification task to job queue
                                    $this->loadModel('Queue.QueuedTask');
                                    $this->QueuedTask->createJob('EventRSVPNotification', array(
                                        'sender_id' => $this->Auth->user('id'),
                                        'recipient_id' => $eventCreatedBy['user_id'],
                                        'event_id' => $eventId,
                                        'event_name' => $event['Event']['name'],
                                        'event_owner_id' => $eventCreatedBy['user_id'],
                                        'status' => $status_text
                                    ));

                                    $this->setAttendees($eventId);
                                }
                            } else {
                                $currentAttendanceOfUser = $status;
                                $this->EventMember->create();
                                $addAttendance = array(
                                    'EventMember' => array(
                                        'event_id' => $eventId,
                                        'user_id' => $currentUserId,
                                        'status' => $status
                                    )
                                );
                                $this->EventMember->save($addAttendance, array(
                                    'validate' => false
                                ));
                                                        if (
                                                                        ($status == EventMember::STATUS_MAYBE_ATTENDING) ||
                                                                        ($status == EventMember::STATUS_ATTENDING)
                                                        ) {
                                                                //Event follow data
                                                                $followEventData = array(
                                                                        'type' => FollowingPage::EVENT_TYPE,
                                                                        'page_id' => $eventId,
                                                                        'user_id' => $currentUserId,
                                                                        'notification' => FollowingPage::NOTIFICATION_ON
                                                                );
                                                                $this->FollowingPage->followPage($followEventData);
                                                        }

                                $result = array(
                                    'success' => true,
                                    'message' => 'updated your attendance.'
                                );
                                $this->setAttendees($eventId);
                            }
                    } else {
                           
                            echo 'false';
                            exit;
                    }
                }

                /*
                 * To get the attendance count 
                 * 
                 * @request : Ajax
                 */
                if ( isset( $this->request->params ['named'] ['attendance_count'])) {
                        $event_count = $this->Event->find('first', array(
                                'conditions' => array('Event.id' => $eventId),
                                'fields' => array('Event.attending_count', 'Event.maybe_count')
                        ));
                        $attendanceCount['success'] = true;
                        $attendanceCount['attending_count']  = $event_count['Event']['attending_count'];
                        $attendanceCount['maybe_count']  = $event_count['Event']['maybe_count'];
                        echo json_encode($attendanceCount);
                        exit();
                }
               
                // For implementing search in friends list.
                $friendsListJson = json_encode(array(
                    'friends' => array(
                        'friend' => $friendsList
                    )
                ));

                //event video
                $url = $event['Event']['online_event_details'];
                $embedCode = $this->__getEmbedPlayer($url);
                if (!$embedCode) {
                    $onlineEventDetails = $url;
                } else {
                    $onlineEventDetails = '';
                }

                // user timezone
                $timezone = $this->Auth->user('timezone');
                //event timezone
                $timeOffset = Date::getTimeZoneOffsetText($timezone);
                $timeZoneOffset = "(GMT " . $timeOffset . ")";
                
                if(isset($event['EventDisease'][0]['disease_id'])) {
                    $eventDisease = $this->Disease->findById($event['EventDisease'][0]['disease_id']);
                }
                
                $refer = '';
                
                if(isset($this->request->query['f'])) {
                    if($this->request->query['f'] == 'c') {
                        $refer = 'Calendar';
                    } else if($this->request->query['f'] == 'd') {
						$refer = 'Condition';
					}
                }
                
                $repeatModeTextArray = $this->Event->getRepeatModes();
                $repeatIntervalTextArray = $this->Event->getRepeatIntervalText();
                    
                /*
                 * set cover photos
                 */
                $this->__setCoverPhotoData($eventId, $event['Event']['is_cover_slideshow_enabled']);
                
                
                $this->set(compact('now', 'friendsListJson', 'friendsList', 
                        'user', 'eventLocation', 'eventCreatedBy', 'event', 
                        'pendingApprovalUsersList', 'joinedUsersList', 
                        'maybeJoinUsersList', 'currentAttendanceOfUser', 
                        'timezone', 'hasManagePermission', 'isCommunityEvent', 
                        'isCommunityMember', 'isApprovedCommunityMember', 
                        'timeZoneOffset', 'embedCode', 'onlineEventDetails', 
                        'eventDisease','refer', 'isInvited','repeatModeTextArray',
                        'repeatIntervalTextArray'));

                /* share meta data */
                $this->set('meta_og_image', Common::getEventThumb($event['Event']['id']));
                $this->set('meta_og_title', h($event['Event']['name']));
                $this->set('meta_og_disc', h($event['Event']['description']));

                // set posting permissions
                $eventType = $event['Event']['event_type'];
                $hasPostPermission = false;
                $hasLikePermission = false;
                $hasCommentPermission = false;
                $hasFilterPermission = false;
                if ($isCommunityEvent && $isApprovedCommunityMember) {
                    $hasPostPermission = true;
                    $hasLikePermission = true;
                    $hasCommentPermission = true;
                    $hasFilterPermission = true;
                } elseif ($eventType == Event::EVENT_TYPE_PUBLIC) {
                    $rsvp = array(
                        EventMember::STATUS_ATTENDING,
                        EventMember::STATUS_MAYBE_ATTENDING
//                        EventMember::STATUS_NOT_ATTENDING
                    );
                    if (in_array($currentAttendanceOfUser, $rsvp)) {
                        $hasPostPermission = true;
                        $hasLikePermission = true;
                        $hasCommentPermission = true;
                        $hasFilterPermission = true;
                    }
                } elseif ($eventType == Event::EVENT_TYPE_PRIVATE) {
                    if (!is_null($currentAttendanceOfUser)) {
                        $hasPostPermission = true;
                        $hasLikePermission = true;
                        $hasCommentPermission = true;
                        $hasFilterPermission = true;
                    }
                } elseif ($eventType == Event::EVENT_TYPE_SITE) {
					$hasPostPermission = true;
					$hasLikePermission = true;
					$hasCommentPermission = true;
					$hasFilterPermission = true;
				}

                $this->set('hasPostPermission', $hasPostPermission);
                $this->set('hasFilterPermission', $hasFilterPermission);
                $this->Posting->hasLikePermission = $hasLikePermission;
                $this->Posting->hasCommentPermission = $hasCommentPermission;

                if ($this->request->is('ajax') && isset($this->request->params['named']['page'])) {
                    $this->layout = 'ajax';
                    $this->view = 'ajax_index';
                    $this->__loadPosts($id);
                    return;
                }

                $this->__setPostingData($eventId);

                if (isset($this->request->params['named']['refresh'])) {
                    $this->layout = 'ajax';
                    $view = new View($this, false);
                    $data['loggedin_userid'] = $this->Auth->user('id');
                    $data['loggedin_user_type'] = $this->Auth->user('type');
                    $view->set($data);
                    echo $view->element('Post.content');
                    exit();
                }
            } else {
                if (!$this->request->is('ajax')) {
                    $this->Session->setFlash(__($this->invalidMessage), 'error');
                    $this->redirect('/event');
                } else {
                    $this->Session->setFlash(__('The event has been deleted by sponsor.'), 'error');
                    $response = 'false';
                    echo $response;
                    exit();
                }
            }
        }
    }

    /**
     * Function to set data for the posting area
     *
     * @param int $eventId
     */
    private function __setPostingData($eventId) {
        $options = array('event_id' => $eventId);
        $this->Posting->setFormData($options);
        $filterOptions = $this->Posting->getFilterOptions();
        $this->set('filterOptions', $filterOptions);
        $this->__loadPosts($eventId);
    }

    /**
     * Loads the posts for n event and sets them on view
     *
     * @param int $eventId
     */
    private function __loadPosts($eventId) {
        $this->Paginator->settings = array(
            'conditions' => array(
                'Post.posted_in' => $eventId,
                'Post.posted_in_type' => Post::POSTED_IN_TYPE_EVENTS,
                'Post.is_deleted' => Post::NOT_DELETED,
                'Post.status' => Post::STATUS_NORMAL
            ),
            'order' => array(
                'Post.created' => 'DESC'
            ),
            'limit' => PostingComponent::POSTS_PER_PAGE
        );
        $posts = $this->Paginator->paginate('Post');
        $postsData = array();
        if (!empty($posts)) {
			$displayPage = Post::POSTED_IN_TYPE_EVENTS;
            foreach ($posts as $post) {
                $postsData[] = $this->Posting->getPostDisplayData($post, $displayPage);
            }
        }
        $this->set('posts', $postsData);
    }

    function sendMailToCreator($email = NULL, $emailData = NULL) {
        $emailManagement = $this->EmailTemplate->getEmailTemplate(EmailTemplateComponent::MAIL_TO_EVENT_CREATOR_TEMPLATE, $emailData);

        // email queue to be saved
        $mailData = array(
            'subject' => $emailManagement['EmailTemplate']['template_subject'],
            'to_name' => $emailData['username'],
            'to_email' => $email,
            'content' => json_encode($emailData),
            'email_template_id' => EmailTemplateComponent::MAIL_TO_EVENT_CREATOR_TEMPLATE,
            'module_info' => 'Mail To Creator',
            'priority' => Email::DEFAULT_SEND_PRIORITY
        );

        $this->EmailQueue->createEmailQueue($mailData);
    }

    /**
     * set/update attendees list.
     */
    function setAttendees($id) {
        $eventMembers = $this->EventMember->getEventMembers($id);
        $pendingApprovalUsersList1 = array();
        $joinedUsersList1 = array();
        $maybeJoinUsersList1 = array();

        $options = array(
            'conditions' => array(
                'Event.' . $this->Event->primaryKey => $id
            )
        );
        $event = $this->Event->find('first', $options);		
        $eventType = $event ['Event'] ['event_type'];
		$currentUserId = (int) $this->Auth->user('id');		
		
        foreach ($eventMembers as $member) {
			$status = $member ['EventMember'] ['status'];
			$userId = $member ['EventMember'] ['user_id'];
			$userDetails = $this->User->getUserDetails($userId);

			if (isset($userDetails) && $userDetails != NULL) {
				$userDetails ['profile_picture'] = Common::getUserThumb($userId, $userDetails['type'], 'x_small', 'media-object');
				$viewDisease = false;
				$privacy = new UserPrivacySettings($userId);
				$diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');
				
				if (
						$diseaseViewPermittedTo === $privacy::PRIVACY_PUBLIC 
						|| $userId == $currentUserId
				) {
					$viewDisease = true;
				} elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
					$friendStatus = (int) $this->MyFriends->getFriendStatus($currentUserId, $userId);
					if (($friendStatus === MyFriends::STATUS_CONFIRMED)) {
						$viewDisease = true;
					}
				}
				
				if ($viewDisease) {
					$disease_ids = $this->PatientDisease->findDiseases($userId);
					if (isset($disease_ids) && ($disease_ids != NULL)) {
						$disease_names = $this->Disease->find('all', array(
							'conditions' => array(
								'Disease.id' => $disease_ids
							)
						));
						$userDetails ['disease'] = $disease_names;
						
					} else {
						$userDetails ['disease'] = NULL;
					}
				} else {
					$userDetails ['disease'] = NULL;
				}
			}
			
			if ($status == EventMember::STATUS_PENDING) {
				$pendingApprovalUsersList1 [] = $userDetails;
			} elseif ($status == EventMember::STATUS_ATTENDING) {
				$joinedUsersList1 [] = $userDetails;
			} elseif ($status == EventMember::STATUS_MAYBE_ATTENDING) {
				$maybeJoinUsersList1 [] = $userDetails;
			}
		}
        $this->set(compact('maybeJoinUsersList1', 'joinedUsersList1', 'pendingApprovalUsersList1', 'eventType'));
        $this->layout = "ajax";
        $View = new View($this, false);
        $response = $View->element('Event.list_attendees');
        echo $response;
        exit();
    }

    /**
     * Delete event
     */
    public function delete($id = null) {
        $redirectUrl = '/event';

        if (!$id) {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect($redirectUrl);
        }
        $this->Event->id = $id;
        if ($this->Event->exists()) {
            $options = array(
                'conditions' => array(
                    'Event.' . $this->Event->primaryKey => $id
                )
            );
            $event = $this->Event->find('first', $options);
            $currentUserId = $this->Auth->user('id');
            $isCommunityEvent = ($event['Event']['community_id'] > 0) ? true : false;
            if ($isCommunityEvent === true) {
                $redirectUrl = '/community';
            }

            $hasDeletePermission = $this->__userHasDeletePermission($event, $currentUserId);
            if ($hasDeletePermission) {
                if ($this->Event->delete($id)) {
                    $this->EventMember->deleteAll(array('EventMember.event_id' => $id));
                    $this->EventDisease->deleteAll(array('EventDisease.event_id' => $id));
                    $this->Session->setFlash(__('The event has been deleted and the attendees will be notified.'), 'success');

                    // delete the site notifications related to the event
                    $this->Notification = ClassRegistry::init('Notification');
                    $this->Notification->deleteEventNotifications($id);
					
                    // delete the posts related to the event
                    $this->Post->deleteEventPosts($id);
					
					//delete all followers of event
					$followEventData = array(
						'type' => FollowingPage::EVENT_TYPE,
						'page_id' => $id
					);
					$this->FollowingPage->deleteAllTypeFollowers($followEventData);


                    $this->__sendEventDeleteMail($event, $currentUserId);
                    $this->redirect($redirectUrl);
                }
            }
        }

        $this->Session->setFlash(__('Event was not deleted'), 'error');
        $this->redirect($redirectUrl);
    }

    /**
     * Function to check if a user has delete permission to an event
     *
     * @param array $event
     * @param int $userId
     * @return boolean
     */
    private function __userHasDeletePermission($event, $userId) {
        $hasDeletePermission = false;

        // only the creator of the event can delete the event
        if ($event['Event']['created_by'] == $userId) {
            $hasDeletePermission = true;
        }

        // if community event, community admin also can delete
        if ($event['Event']['community_id'] > 0) {
            $communityId = $event['Event']['community_id'];
            $hasCommunityManagePermission = $this->CommunityMember->hasManagePermission($userId, $communityId);
            if ($hasCommunityManagePermission) {
                $hasDeletePermission = true;
            }
        }

        return $hasDeletePermission;
    }

    /**
     * Function to send mail to event members on deleting an event
     *
     * @param array $event
     * @param int $currentUserId
     */
    private function __sendEventDeleteMail($event, $currentUserId) {
        // details of event members
        $eventMembers = $event['EventMember'];
        $eventMemberDetails = array();
        $memberIdArr = array();
        foreach ($eventMembers as $eventMember) {
            // no need to send mail to not attending members
            if ($eventMember['status'] != EventMember::STATUS_NOT_ATTENDING) {
                // no need to send mail to the deleting user
                if ($eventMember['user_id'] != $currentUserId) {
                    $memberIdArr[] = $eventMember['user_id'];

                    // check which user want to get notified by email
                    $isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($eventMember['user_id'], 'event_cancelation');
                    if ($isEmailNotificationOn && (!$this->User->isUserOnline($eventMember['user_id']))) {
                        $userDetail = $this->User->getUserDetails($eventMember['user_id']);
                        $eventMemberDetails[] = $userDetail;
                    }
                }
            }
        }

        $eventId = $event['Event']['id'];
        if (!empty($eventMemberDetails)) {
            // event name
            $eventName = h($event['Event']['name']);

            // deleted by
            if ($currentUserId == $event['Event']['created_by']) {
                $deletedBy = 'creator of the event';
            } elseif ($event['Event']['community_id'] > 0) {
                $deletedBy = 'community admin';
            }

            $Api = new ApiController;
            $Api->constructClasses();

            // send mail to event members
            foreach ($eventMemberDetails as $memberDetails) {
                $toEmail = $memberDetails['email'];
                $emailData = array(
                    'eventname' => $eventName,
                    'username' => Common::getUsername($memberDetails['user_name'], $memberDetails['first_name'], $memberDetails['last_name']),
                    'deletedBy' => $deletedBy
                );

                $Api->sendHTMLMail(EmailTemplateComponent::DELETE_EVENT_TEMPLATE, $emailData, $toEmail);
            }
        }

        if (!empty($memberIdArr)) {
            // add event delete notification task to job queue
            $this->loadModel('Queue.QueuedTask');
            $this->QueuedTask->createJob('EventDeleteNotification', array(
                'event_id' => $eventId,
                'sender_id' => $currentUserId,
                'recipients' => $memberIdArr,
                'event_name' => $event['Event']['name']
            ));
        }
    }

    private function __getEmbedPlayer($url) {

        App::uses('Crawler', 'Utility');
        $Crawler = new Crawler();
        $videoEmbedCode = $Crawler->getEmbedPlayer($url);
        return $this->Posting->getWmodeVideoEmbedCode($videoEmbedCode);
    }

    public function getAdsCount() {
        return 3;
    }
    
    /**
	 * Function to set cover photo
	 * 
	 * @param int $eventId
	 * @param int $slideShowStatus
	 */
	private function __setCoverPhotoData($eventId, $slideShowStatus) {
		$coverType = "event";
		$coverPhotos = $this->Photo->getEventCoverPhotos($eventId);
		$photos = array();
		$defaultPhotoId = 0;
		if (!empty($coverPhotos)) {
			$photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/event_image/';
			foreach ($coverPhotos as $coverPhoto) {
				$photoId = $coverPhoto['Photo']['id'];
				$photo = $photoPath . $coverPhoto['Photo']['file_name'];
				$photos[] = array(
					'id' => $photoId,
					'src' => $photo,
				);
				if (intval($coverPhoto['Photo']['is_default']) === 1) {
					$defaultPhoto = $photo;
					$defaultPhotoId = $photoId;
				}
			}
			if (!isset($defaultPhoto)) {
				$defaultPhoto = $photos[0]['src'];
			}
		} else {
			$defaultPhoto = '/theme/App/img/event_cover_bg.png';
		}

		// set data on view
		if ($slideShowStatus == Event::COVER_SLIDESHOW_ENABLED) {
			$isSlideShowEnabled = Event::COVER_SLIDESHOW_ENABLED;
		} else {
			$isSlideShowEnabled = Event::COVER_SLIDESHOW_DISABLED;
		}
		$this->request->data['Event']['is_cover_slideshow_enabled'] = $isSlideShowEnabled;
		$this->request->data['Event']['default_photo_id'] = $defaultPhotoId;
		$this->request->data['Event']['default_photo'] = $defaultPhoto;
		$this->set(compact('coverType', 'photos', 'defaultPhoto', 'defaultPhotoId', 'isSlideShowEnabled'));
	}
}
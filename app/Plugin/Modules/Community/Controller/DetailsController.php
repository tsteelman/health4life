<?php

/**
 * DetailsController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('CommunityAppController', 'Community.Controller');
App::import('Controller', 'Api');
App::uses('Event', 'Model');
App::uses('FollowingPage', 'Model');

/**
 * DetailsController for communities.
 *
 * DetailsController is used for displaying community details.
 *
 * @author 	Ajay Arjunan
 * @package 	Community
 * @category	Controllers
 */
class DetailsController extends CommunityAppController {

    public $uses = array('Community', 'CommunityMember', 'EmailTemplate', 
        'CommunityDisease', 'Cities', 'States', 'Countries', 'User', 
        'EventMember', 'Post', 'NotificationSetting', 'Disease', 'Photo', 'Event',
		'FollowingPage', 'MyFriends');
    public $components = array('session', 'Email', 'EmailTemplate', 'EmailQueue', 'Paginator', 'Posting');

    /*
     * Function to display community details.
     *
     * @param int community_id
     */

    public function index($communityId = NULL, $section = "post") {
        $is_sharing_enabled = $this->getSharingOptions();
        $this->set('is_sharing_enabled', $is_sharing_enabled);

        if (!$this->Community->exists($communityId)) {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect('/community');
        }

        $user = $this->Auth->user();

		// mark the community notifications as read by the logged in user
		$this->Notification = ClassRegistry::init('Notification');
		$this->Notification->markCommunityNotificationsReadByUser($communityId, $user['id']);

		$community = $this->Community->find('first', array(
            'conditions' => array(
                'Community.id' => $communityId
            )
        ));

        if (!empty($community)) {
			
			$this->set('title_for_layout', $community['Community']['name']);
            $userStatus = $this->CommunityMember->find('first', array(
                'conditions' => array(
                    'CommunityMember.user_id' => $user['id'],
                    'CommunityMember.community_id' => $communityId
                ),
                'fields' => array(
                    'CommunityMember.user_type',
                    'CommunityMember.status',
                    'CommunityMember.invited_by'
                )
            ));

            $this->set('coverModel', 'Community');
            $this->set('roomId', $communityId);
            // check if user has been invited to the community
            // and set the invited user details on view
            $isInvited = false;
            if (isset($userStatus['CommunityMember']['status']) && ($userStatus['CommunityMember']['status'] == CommunityMember::STATUS_INVITED)) {
                $isInvited = true;
                $invitedUserId = $userStatus['CommunityMember']['invited_by'];
                $invitedUser = $this->User->getUserDetails($invitedUserId);
                $invitedUserType = $invitedUser['type'];
                $invitedUserName = Common::getUsername($invitedUser['user_name'], $invitedUser['first_name'], $invitedUser['last_name']);
                $this->set(compact('invitedUserId', 'invitedUserType', 'invitedUserName'));
            }
            $this->set('isInvited', $isInvited);

            /*
             * Get the Community detail page url
             */
            $community_detail_url = "/" . $this->request->params['plugin'] . "/" .
                    $this->request->params['controller'] . "/" .
                    $this->request->params['action'] . "/" .
                    $communityId;

            /*
             * TODO
             * For checking if the current user have rights to view creator details.
             */
            //if ((!empty($userStatus) && $userStatus['CommunityMember']['status'] !== '2') || ($community['Community']['type'] === '1')) {

            $creator = $this->User->getUserDetails($community['Community']['created_by']);

            // user timezone
            $timezone = $this->Auth->user('timezone');

            /*
             * Check a valid the sub-module request coming
             */
            $section_list = array(
                "posts" => "Discussion",
                "events" => "Events",
                "members" => "Members"
            );
            /*
             * Set to the default one if an invalid request
             */
            if (!key_exists($section, $section_list)) {
                $section = "posts";
            }

            switch ($section) {
                case 'posts':
                    if (isset($userStatus['CommunityMember']['status']) && ($userStatus['CommunityMember']['status'] == CommunityMember::STATUS_APPROVED)) {
                        $hasPostPermission = true;
                        $hasLikePermission = true;
                        $hasCommentPermission = true;
                        $hasFilterPermission = true;
                    } else {
                        $hasPostPermission = false;
                        $hasLikePermission = false;
                        $hasCommentPermission = false;
                        $hasFilterPermission = false;
                    }
                    $this->set('hasPostPermission', $hasPostPermission);
                    $this->set('hasFilterPermission', $hasFilterPermission);
                    $this->Posting->hasLikePermission = $hasLikePermission;
                    $this->Posting->hasCommentPermission = $hasCommentPermission;

                    if ($this->request->is('ajax')) {
                        $this->layout = 'ajax';
                        $this->view = 'ajax_index';
                        $this->__loadPosts($communityId);
                        return;
                    }

                    $this->__setDiscussionTabData($communityId);
                    break;

                case 'events':
                    break;

                case 'members':
                    $this->getCommunityMembersTab($communityId);
                    break;
            }

            /* meta tags*/
            $this->set('meta_og_image', Common::getCommunityThumb($community['Community']['id']));
            $this->set('meta_og_title', h($community['Community']['name']));    
            $this->set('meta_og_disc', h($community['Community']['description']));
            
            $refer = '';
            $communityDisease = '';
            
            if(isset($this->request->query['f'])) {
                $refer = intval($this->request->query['f']);
                $communityDisease = $this->Disease->findById($refer);
            } else if(substr ($this->request->referer(1), 0, 10) == '/condition') {
                preg_match('~index/(.*?)/communities~', $this->request->referer(1), $disease_id);
                $refer = 'c';
				if (!empty($disease_id)) {
					$communityDisease = $this->Disease->findById($disease_id[1]);
				} elseif (isset($community['CommunityDisease'][0]['disease_id'])) {
					$communityDisease = $this->Disease->findById($community['CommunityDisease'][0]['disease_id']);
				}
            } else if(isset($community['CommunityDisease'][0]['disease_id'])) {
                $communityDisease = $this->Disease->findById($community['CommunityDisease'][0]['disease_id']);
            }
            
            /*
             * set cover photos
             */
            $this->__setCoverPhotoData($communityId, $community['Community']['is_cover_slideshow_enabled']);
            
            $onlineFriends = $this->getOnlineFriends();
            $onlineFriendsCount = $this->getOnlineUserCount($onlineFriends);
            $communityMemberIds = $this->__getMemberIds($communityId);
            $onlineMembersCount = $this->User->getOnlineUserCount($communityMemberIds);
            $onlineMembers = $this->getOnlineMemberWithStatus($communityMemberIds);
						
            $this->set(compact('country', 'state', 'city', 'community', 
                    'communityDisease', 'user', 'userStatus', 'creator', 
                    'timezone', 'section_list', 'section', 'community_detail_url', 
                    'communityId', 'refer', 'onlineFriends', 'onlineFriendsCount',
                    'onlineMembers', 'onlineMembersCount'));

//            /* getMembersTab
//             */
//            if ($section == 'members') {
//                $this->getCommunityMembersTab($communityId);
//            }


            /*
             * TODO
             * For displaying error message if the user doesn't have rights to view the community.
             */
            /* } else {

              $this->Session->setFlash(__('You do not have permission to view this community'), 'error');

              $this->redirect('/community');
              } */
        }
    }

    /**
     * Function to set data for the discussion tab
     *
     * @param int $communityId
     */
    private function __setDiscussionTabData($communityId) {
        $options = array('community_id' => $communityId);
        $this->Posting->setFormData($options);
        $filterOptions = $this->Posting->getFilterOptions();
        $this->set('filterOptions', $filterOptions);
        $this->__loadPosts($communityId);
    }

    /**
     * Loads the posts for a community and sets them on view
     *
     * @param int $communityId
     */
    private function __loadPosts($communityId) {
        $this->Paginator->settings = array(
            'conditions' => array(
                'Post.posted_in' => $communityId,
                'Post.posted_in_type' => Post::POSTED_IN_TYPE_COMMUNITIES,
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
			$displayPage = Post::POSTED_IN_TYPE_COMMUNITIES;
			foreach ($posts as $post) {
				$postsData[] = $this->Posting->getPostDisplayData($post, $displayPage);
            }
        }
        $this->set('posts', $postsData);
    }

    /**
     * function to get community members tab and details with respect to the current user status.
     * @param int $community_id
     */
    function getCommunityMembersTab($community_id = NULL) {
        if (isset($this->request->params ['named'] ['community_id']) && $this->request->params ['named'] ['community_id'] != NULL) {
            $community_id = $this->request->params ['named'] ['community_id'];
        }
        $current_user = $this->Auth->user('id');

        $Api = new ApiController ();
        $friendsList = $Api->getFriendList($current_user);

        $communityData = $this->Community->find('first', array(
            'conditions' => array('Community.id' => $community_id),
            'fields' => array('Community.created_by', 'Community.type', 'member_can_invite')
        ));
        $current_user_status = $this->CommunityMember->getCommunityMemberStatus($current_user, $community_id);
        $current_user_type = $this->CommunityMember->getCommunityMemberUserType($community_id, $current_user);
        $all_community_members = $this->CommunityMember->find('all', array(
            'conditions' => array(
                'CommunityMember.community_id' => $community_id,
            ),
            'joins' => array(
                array(
                    'table' => 'users',
                    'type' => 'INNER',
                    'alias' => 'user',
                    'conditions' => array(
                        'user.id = CommunityMember.user_id'
                    )
                )
            ),
            'order' => array('user.username' => 'asc')
                )
        );
        $not_approved_members = array();
        $all_approved_members = array();

        if (!empty($friendsList)) {

            foreach ($friendsList as $friend) {

                foreach ($all_community_members as $member) {

                    if ($friend ['friend_id'] == $member ['CommunityMember'] ['user_id']) {
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

        // For implementing search in friends list.
        $isCommunityEvent = NULL;
        $friendsListJson = json_encode(array(
            'friends' => array(
                'friend' => $friendsList
            )
        ));

        $i = 0;
        foreach ($all_community_members as $community_member) {
            $membersList[$i] = array(
                'friend_name' => Common::getUsername($community_member['User']['username'], $community_member['User']['first_name'], $community_member['User']['last_name']),
                'friend_id' => $community_member['User']['id'],
                'friend_image' => $community_member['User']['profile_picture']
            );
            if ($community_member['CommunityMember']['status'] == CommunityMember::STATUS_NOT_APPROVED) {
                $not_approved_members[] = $community_member['User'];
            } elseif ($community_member['CommunityMember']['status'] == CommunityMember::STATUS_APPROVED) {
                $all_approved_members[] = array(
                    'user' => $community_member['User'],
                    'admin_status' => $this->CommunityMember->getCommunityMemberUserType($community_id, $community_member['CommunityMember']['user_id'])
                );
            }
            $i++;
        }
        //for search members functonality.
        $membersListJson = json_encode(array(
            'friends' => array(
                'friend' => $membersList
            )
        ));
        $this->set(compact('not_approved_members', 'all_approved_members', 'communityData', 'community_id', 'current_user', 'current_user_status', 'current_user_type', 'friendsList', 'friendsListJson', 'membersListJson', 'isCommunityEvent'));
        if (isset($this->request->params ['named'] ['community_id']) &&
                $this->request->params ['named'] ['community_id'] != NULL) {
            $this->autoRender = FALSE;
            $this->layout = "ajax";
            $View = new View($this, false);
            $response = $View->element('Community.Details/members');
            echo json_encode($response);
            exit;
        }
    }

    /**
     * Function for accept/reject requests to join private community from users and make/remove secondary admins for communities by admin.
     */
    public function updateCommunityMemberStatus() {
        $community_id = $this->request->params ['named'] ['community_id'];
        $community_owner = $this->Community->find('first', array(
            'conditions' => array('Community.id' => $community_id),
            'fields' => array('Community.created_by')
        ));
        $community_owner = $community_owner['Community']['created_by'];
        $user_id = $this->request->params ['named'] ['user_id'];
        $status = $this->request->params ['named'] ['status'];
        $current_user = $this->Auth->user('id');
        $conditions = array(
            'CommunityMember.user_id' => $user_id,
            'CommunityMember.community_id' => $community_id
        );
        $current_user_type = $this->CommunityMember->getCommunityMemberUserType($community_id, $current_user);
        if ($this->CommunityMember->hasAny($conditions) && $current_user_type >= CommunityMember::USER_TYPE_ADMIN) {
            $communityMemberRecordId = $this->CommunityMember->find('first', array(
                'conditions' => $conditions,
                'fields' => array('CommunityMember.id')
            ));
            $communityMemberRecordId = $communityMemberRecordId['CommunityMember']['id'];
            $requested_user = $this->User->getUserDetails($user_id);
            $requested_user['user_name'] = Common::getUsername($requested_user['user_name'], $requested_user['first_name'], $requested_user['last_name']);
            switch ($status) {
                case 'add':
                    //setting staus to Approved.
					$set_status = 1;
					$updateCommunityMember = array(
						'CommunityMember' => array(
							'id' => $communityMemberRecordId,
							'status' => $set_status,
							'joined_on' => Date::getCurrentDateTime()
						)
					);
					$this->CommunityMember->save($updateCommunityMember);

					//Community follow data
					$followCommunityData = array(
						'type' => FollowingPage::COMMUNITY_TYPE,
						'page_id' => $community_id,
						'user_id' => $user_id,
						'notification' => FollowingPage::NOTIFICATION_ON
					);					
					$this->FollowingPage->followPage($followCommunityData);

					$updated_member_count = $this->Community->changeMemberCount($community_id, 1);
					$result_message = $requested_user['user_name'] . " has been added as a member to the community.";
					$result = array(
						'success' => 'success',
						'member_count' => $updated_member_count,
						'message' => $result_message
					);
                    break;
                case 'ignore':
                    //Reject the request. Delete the entry from table.
					if ($this->CommunityMember->delete($communityMemberRecordId)) {
						//Community unfollow data
						$followCommunityData = array(
							'type' => FollowingPage::COMMUNITY_TYPE,
							'page_id' => $community_id,
							'user_id' => $user_id
						);
						$this->FollowingPage->unFollowPage($followCommunityData);

						$result_message = $requested_user['user_name'] . " is denied from joining the community.";
						$result = array(
							'success' => 'success',
							'message' => $result_message
						);
					}
					break;
                case 'update_admin':
                    //update member type to admin or remove the existing secondary admin
                    if ($community_owner == $current_user && $user_id != $community_owner) {
                        $memberTypeNow = $this->CommunityMember->getCommunityMemberUserType($community_id, $user_id);
                        if ($memberTypeNow == CommunityMember::USER_TYPE_MEMBER) {
                            $setMemberType = CommunityMember::USER_TYPE_ADMIN;
                            $result_message = "You have set " . $requested_user['user_name'] . " as a secondary admin for this community.";
                        } else {
                            $setMemberType = CommunityMember::USER_TYPE_MEMBER;
                            $result_message = $requested_user['user_name'] . " is removed from the secondary admin list of this community.";
                        }
                        $updateCommunityMember = array(
                            'CommunityMember' => array(
                                'id' => $communityMemberRecordId,
                                'user_type' => $setMemberType
                            )
                        );
                        $this->CommunityMember->save($updateCommunityMember);
                        $this->sendMailToNewSecondaryAdmin($community_id, $user_id, $setMemberType); //send mail to new added secondary admin.
                        $result = array(
                            'success' => 'success',
                            'message' => $result_message
                        );
                    } else {
                        $result = array(
                            'success' => 'danger',
                            'message' => 'Cannot change member type'
                        );
                    }
                    break;
                default :
                    $result = array(
                        'success' => 'danger',
                        'message' => 'invalid status'
                    );
                    break;
                    exit;
            }
        } else {
            $result = array(
                'success' => 'danger',
                'message' => 'The user is not a member of the Community.'
            );
        }
        print_r(json_encode($result));
        exit;
    }

    /**
     * function for sending mails to selected / removed secondary admins.
     * @param int $community_id
     * @param int $user_id
     * @param int $admin_status
     */
    function sendMailToNewSecondaryAdmin($community_id, $user_id, $admin_status) {
        $memberDetails = NULL;
        $memberDetails = $this->User->getUserDetails($user_id);
        $email = $memberDetails['email'];
        $communityName = $this->Community->find('first', array(
            'conditions' => array('Community.id' => $community_id),
            'fields' => array('Community.name')
        ));
        $communityName = $communityName['Community']['name'];
        $link = Router::Url('/', TRUE) . 'community/details/index/' . $community_id;
        $emailData = array(
            'communityname' => $communityName,
            'username' => Common::getUsername($memberDetails['user_name'], $memberDetails['first_name'], $memberDetails['last_name']),
            'link' => $link
        );
        if ($admin_status == CommunityMember::USER_TYPE_MEMBER) {
            $template_id = EmailTemplateComponent::COMMUNITY_REMOVE_FROM_ADMIN_TEMPLATE;
        } elseif ($admin_status == CommunityMember::USER_TYPE_ADMIN) {
            $template_id = EmailTemplateComponent::COMMUNITY_ADD_AS_ADMIN_TEMPLATE;
        }
        $emailManagement = $this->EmailTemplate->getEmailTemplate($template_id, $emailData);

        // email queue to be saved
        $mailData = array(
            'subject' => $emailManagement['EmailTemplate']['template_subject'],
            'to_name' => $emailData['username'],
            'to_email' => $email,
            'content' => json_encode($emailData),
            'email_template_id' => $template_id,
            'module_info' => 'Secondary admins',
            'priority' => Email::DEFAULT_SEND_PRIORITY
        );

        $this->EmailQueue->createEmailQueue($mailData);
    }

    /*
     * Function to set the status of user (Joined/Left/Invited)
     *
     * @param int user_id
     * @param int event_id
     */

    public function setUserStatus() {

        $addMember = 1;
        $removeMember = 2;
        $addMemberClosedCommunity = 3;

        $this->autoRender = false;
        $user_id = $this->request->data['userId'];
        $community_id = $this->request->data['communityId'];
        $sentStatus = $this->request->data['status'];
        $status = $this->CommunityMember->getCommunityMemberStatus($user_id, $community_id);
        $community = $this->Community->find('first', array(
            'conditions' => array(
                'Community.id' => $community_id
            )
        ));

        if (!empty($community)) {

            /*
             * Check status has changed before send the request
             */
            if ($sentStatus == $status ) {
                    if (in_array($community['Community']['type'], array(Community::COMMUNITY_TYPE_OPEN, Community::COMMUNITY_TYPE_SITE))) {

                        if (!empty($status)) {

                            switch ($status) {
                                case 0:
                                    $this->CommunityMember->changeCommunityMemberStatus($user_id, $community_id, $addMember);
                                    $this->Session->setFlash(__('You are now a member of this community'), 'success');
                                    break;
                                case 1:
								$this->CommunityMember->changeCommunityMemberStatus($user_id, $community_id, $removeMember);
								$this->removeMemberFromCommunityEvents($community_id, $user_id);
								//Community unfollow data
								$followCommunityData = array(
									'type' => FollowingPage::COMMUNITY_TYPE,
									'page_id' => $community_id,
									'user_id' => $user_id
								);
								$this->FollowingPage->unFollowPage($followCommunityData);
								$this->Session->setFlash(__('You are no longer a member of this community.'), 'success');
								echo $member_count;
								break;
                            }
                        } else {
                            $this->CommunityMember->changeCommunityMemberStatus($user_id, $community_id, $addMember);
                            $this->Session->setFlash(__('You are now a member of this community'), 'success');
                        }
                    } else {

                        if (!empty($status)) {

                            switch ($status) {
                                case 0:
                                    $this->CommunityMember->changeCommunityMemberStatus($user_id, $community_id, $addMemberClosedCommunity);
                                    $this->Session->setFlash(__('The Community admin has been notified about your interest in joining the Community'));
                                    break;
                                case 1:
                                    $this->CommunityMember->changeCommunityMemberStatus($user_id, $community_id, $removeMember);
                                    $this->removeMemberFromCommunityEvents($community_id, $user_id);
                                    $this->Session->setFlash(__('You are no longer a member of this Community.'), 'success');
                                    break;
                            }
                        } else {
                                                $this->CommunityMember->changeCommunityMemberStatus($user_id, $community_id, $addMemberClosedCommunity);
                                                $this->Session->setFlash(__('The Community admin has been notified about your interest in joining the Community'), 'success');

                                                // add community join request notification task to job queue
                                                $this->loadModel('Queue.QueuedTask');
                                                $this->QueuedTask->createJob('CommunityJoinRequestNotification', array(
                                                        'community_id' => $community_id,
                                                        'sender_id' => $user_id,
                                                        'recipient_id' => $community['Community']['created_by'],
                                                        'community_name' => $community['Community']['name']
                                                ));
                                        }
                                }
                                exit;
            } else {
                $this->Session->setFlash('Sorry !, You have done this action already.', 'error');
            }
        } else {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
        }
    }

    /**
     * Delete Community
     */
    function delete($community_id = NULL) {
        if (!$community_id || $community_id == NULL) {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect('/community/');
        }
        if ($this->Community->exists($community_id)) {
            $options = array(
                'conditions' => array(
                    'Community.id' => $community_id
                )
            );
            $community = $this->Community->find('first', $options);
            $communityCreatedBy = $community['Community']['created_by'];
            $currentUserId = $this->Auth->user('id');
            if ($communityCreatedBy == $currentUserId) {
                if ($this->Community->deleteAll(array('Community.id' => $community_id))) {
                    $communityMembers = $this->CommunityMember->getAllCommunityMembers($community_id);
                    $this->CommunityMember->deleteAll(array('CommunityMember.community_id' => $community_id));
                    $this->CommunityDisease->deleteAll(array('CommunityDisease.community_id' => $community_id));
                    $this->Session->setFlash(__('Your Community has been deleted and the members will be notified.'), 'success');
                    $communityName = h($community['Community']['name']);
                    
					// delete community related site notifications
					$this->Notification = ClassRegistry::init('Notification');
					$this->Notification->deleteCommunityNotifications($community_id);

					// delete the posts related to the community
					$this->Post->deleteCommunityPosts($community_id);
					
					//delete all followers of community
					$followCommunityData = array(
						'type' => FollowingPage::COMMUNITY_TYPE,
						'page_id' => $community_id
					);
					$this->FollowingPage->deleteAllTypeFollowers($followCommunityData);

					// send community delete notifications
					$this->__addCommunityDeleteNotificationTaskQueue($communityMembers, $community_id, $communityName, $communityCreatedBy);

					$this->redirect('/community/');
				}
			}
			$this->Session->setFlash(__('Community was not deleted, permission denied.'), 'error');
            $this->redirect('/community/');
        } else {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
            $this->redirect('/community/');
        }
    }

	/**
	 * Function to add community delete notification task to queue
	 *
	 * @param array $communityMembers
	 * @param int $communityId
	 * @param String $communityName
	 * @param int $communityCreatedBy
	 */
	private function __addCommunityDeleteNotificationTaskQueue($communityMembers, $communityId, $communityName, $communityCreatedBy) {
		if (!empty($communityMembers)) {
			$memberIdArr = array();
			foreach ($communityMembers as $member) {
				if ($member['CommunityMember']['user_id'] != $communityCreatedBy) {
					$memberIdArr[] = $member['CommunityMember']['user_id'];
				}
			}

			if (!empty($memberIdArr)) {
				$this->loadModel('Queue.QueuedTask');
				$this->QueuedTask->createJob('CommunityDeleteNotification', array(
					'community_id' => $communityId,
					'sender_id' => $communityCreatedBy,
					'recipients' => $memberIdArr,
					'community_name' => $communityName
				));
			}
		}
	}

    /*
     * Function to update the recently added members list.
     *
     * @param int $communityId
     *
     * @return json array containing recently added members list
     */

    public function updateRecentMembersList($communityId) {

        $accepted = 1; //varable to set accepted status
        $limit = 5; //Limit the number of users displayed
        $this->autoRender = FALSE;
        $recent_members = array();
        $members = $this->CommunityMember->find('all', array(
            'conditions' => array(
                'CommunityMember.community_id' => $communityId,
                'CommunityMember.status' => $accepted
            ),
            'order' => array('CommunityMember.joined_on' => 'desc'),
            'fields' => array('User.*'),
            'limit' => $limit
        ));

        foreach ($members as $member) {
            $diseases = $this->User->find("all", array(
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
                    )
                ),
                'conditions' => array('User.id = ' . $member['User']['id']),
                'fields' => array('User.id', 'PatientDisease.patient_id', 'Disease.name'),
                'order' => array('User.username' => 'asc', 'Disease.name' => 'desc')
                    )
            );
            $disease_names = "";
            foreach ($diseases as $disease) {
                $disease_names .= ", " . $disease['Disease']['name'];
            }
            $disease_names = substr($disease_names, 2);
            $member['User']['diseases'] = $disease_names;
            $recent_members[] = $member;
        }

        $this->set(compact('recent_members'));

        $this->layout = "ajax";
        $View = new View($this, false);
        $response['htm_content'] = $View->element('Community.recent_members_list');

        echo json_encode($response);
    }

    /*
     * Function to get the list of events in a community
     *
     * @param int $community_id community id
     * @param int $page count of page
     */

    public function getEventList($community_id = NULL, $event_type = NULL, $page = NULL) {

        $this->autoRender = FALSE;

        $limit = 3; //Pagination limit
        $user = $this->Auth->user();

        $user_status = $this->CommunityMember->getCommunityMemberStatus($user['id'], $community_id);
        /*switch ($event_type) {
            case Event::UPCOMING_COMMUNITY_EVENTS:
                $this->Community->virtualFields = array(
                    'user_type' => 'CommunityMember.user_type'
                );
                $this->Paginator->settings = array(
                    'joins' => array(
                        array(
                            'table' => 'community_members',
                            'alias' => 'CommunityMember',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Event.community_id = CommunityMember.community_id',
                                'Event.created_by = CommunityMember.user_id'
                            )
                        )
                    ),
                    'limit' => $limit,
                    'conditions' => array(
                        'Event.community_id' => $community_id,
                        'Event.start_date >=' => Date::getCurrentDateTime()
                    ),
                    'fields' => array('Event.*', 'Community.*', 'CommunityMember.*'),
                    'order' => array('Community.user_type' => 'desc'),
                    'group' => array('Event.id')
                );
                break;
            case Event::PAST_COMMUNITY_EVENTS:
                $this->Paginator->settings = array(
                    'joins' => array(
                        array(
                            'table' => 'community_members',
                            'alias' => 'CommunityMember',
                            'type' => 'LEFT',
                            'conditions' => array(
                                'Event.community_id = CommunityMember.community_id',
                                'Event.created_by = CommunityMember.user_id'
                            )
                        )
                    ),
                    'limit' => $limit,
                    'conditions' => array(
                        'Event.community_id' => $community_id,
                        'Event.end_date <' => Date::getCurrentDateTime()
                    ),
                    'fields' => array('Event.*', 'Community.*', 'CommunityMember.*'),
                    'order' => array('Community.user_type' => 'desc'),
                    'group' => array('Event.id')
                );
                break;
        }*/
        
        $this->Paginator->settings = array(
            'joins' => array(
                array(
                    'table' => 'community_members',
                    'alias' => 'CommunityMember',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Event.community_id = CommunityMember.community_id',
                        'Event.created_by = CommunityMember.user_id'
                    )
                )
            ),
            'limit' => $limit,
            'conditions' => array(
                'Event.community_id' => $community_id
            ),
            'fields' => array('Event.*', 'Community.*', 'CommunityMember.*'),
            'order' => array('Event.end_date' => 'desc'),
            'group' => array('Event.id')
        );

        $events = $this->paginate('Event');
        $paginate = $this->params['paging']['Event'];

        $timezone = $this->Auth->user('timezone');

        $now = date("Y-m-d H:i:s");

        $user_communities = $this->CommunityMember->find('list', array(
            'conditions' => array(
                'CommunityMember.user_id' => $user['id'],
                'CommunityMember.status' => CommunityMember::STATUS_APPROVED
            ),
            'fields' => array('CommunityMember.community_id'),
        ));

        $eventIds = $this->EventMember->getEventIds($user['id']); //User's Event ids

        foreach ($eventIds as $eventId) {

            $status = $this->EventMember->getStatus($eventId, $user['id']); //Status of event associated with user

            switch ($status) {
                case '0':
                    $pendingEventIds[] = $eventId; //Events that are pending invitation
                    break;
                case '1':
                    $goingEventIds[] = $eventId;
                    $attendingEventIds[] = $eventId;
                    break;
                case '2':
                    $notAttendingEventIds[] = $eventId;
                    break;
                case '3':
                    $maybeEventIds[] = $eventId;
                    $attendingEventIds[] = $eventId; //Events that user is attending to
                    break;
            }
        }


        $this->set(compact('events', 'event_type', 'community_id', 'timezone', 'user_status', 'now', 'user_communities', 'goingEventIds', 'notAttendingEventIds', 'maybeEventIds', 'user'));

        $this->layout = 'ajax';
        $View = new View($this, FALSE);
        $response['htm_content'] = $View->element('Event.events_row');
        $response['paginator'] = $paginate;
        echo json_encode($response);
    }

    /**
     * Function to approve a community invitation by the invitee
     */
    public function approveInvitation() {
        $this->autoRender = false;
        $communityId = $this->request->data['communityId'];
        $community = $this->Community->findById($communityId);
        if (!empty($community)) {
            $userId = $this->Auth->user('id');
            $this->CommunityMember->approve($communityId, $userId);
			//Community follow data
			$followCommunityData = array(
				'type' => FollowingPage::COMMUNITY_TYPE,
				'page_id' => $communityId,
				'user_id' => $userId,
				'notification' => FollowingPage::NOTIFICATION_ON
			);
			$this->FollowingPage->followPage($followCommunityData);
			
            $this->Session->setFlash(__('You are now a member of this community.'), 'success');
        } else {
            $this->Session->setFlash(__($this->invalidMessage), 'error');
        }
    }

    /**
     * Function to reject a community invitation by the invitee
     */
    public function rejectInvitation() {
		$this->autoRender = false;
		$communityId = $this->request->data['communityId'];
		$community = $this->Community->findById($communityId);
		if (!empty($community)) {
			$userId = $this->Auth->user('id');
			$this->CommunityMember->reject($communityId, $userId);
			//Community unfollow data
			$followCommunityData = array(
				'type' => FollowingPage::COMMUNITY_TYPE,
				'page_id' => $communityId,
				'user_id' => $userId
			);
			$this->FollowingPage->unFollowPage($followCommunityData);
			$this->Session->setFlash(__('The invitation has been rejected.'), 'success');
		} else {
			$this->Session->setFlash(__($this->invalidMessage), 'error');
		}
	}
    
    /**
	 * Function to set cover photo
	 * 
	 * @param int $communityId
	 * @param int $slideShowStatus
	 */
	private function __setCoverPhotoData($communityId, $slideShowStatus) {
		$coverType = "community";
		$coverPhotos = $this->Photo->getCommunityCoverPhotos($communityId);
		$photos = array();
		$defaultPhotoId = 0;
		if (!empty($coverPhotos)) {
			$photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/community_image/';
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
			$defaultPhoto = '/theme/App/img/tmp/community_cover_bg.png';
		}

		// set data on view
		if ($slideShowStatus == Community::COVER_SLIDESHOW_ENABLED) {
			$isSlideShowEnabled = Community::COVER_SLIDESHOW_ENABLED;
		} else {
			$isSlideShowEnabled = Community::COVER_SLIDESHOW_DISABLED;
		}
		$this->request->data['Community']['is_cover_slideshow_enabled'] = $isSlideShowEnabled;
		$this->request->data['Community']['default_photo_id'] = $defaultPhotoId;
		$this->request->data['Community']['default_photo'] = $defaultPhoto;
		$this->set(compact('coverType', 'photos', 'defaultPhoto', 'defaultPhotoId', 'isSlideShowEnabled'));
	}
    
    public function removeMemberFromCommunityEvents($communityId, $userId) {
        
        $result = false;
        
        $communityEvents = $this->EventMember->getCommunityEvents($communityId, $userId);
        foreach ( $communityEvents as $communityEvent ) {
            
            $status = $communityEvent['EventMember']['status'];
            
            //Updating event table counts
            $this->Event->id = $communityEvent['Event']['id'];
            if ($status == EventMember::STATUS_PENDING) {
                $result =  $this->Event->saveField('invite_count', ($communityEvents['Event']['invited_count'] - 1));
            } elseif ($status == EventMember::STATUS_ATTENDING) {
                $result =  $this->Event->saveField('attending_count', ($communityEvents['Event']['attending_count'] - 1));
            } elseif ($status == EventMember::STATUS_NOT_ATTENDING) {
                $result =  $this->Event->saveField('not_attending_count', ($communityEvents['Event']['not_attending_count'] - 1));
            } elseif ($status == EventMember::STATUS_MAYBE_ATTENDING) {
                $result =  $this->Event->saveField('maybe_count', ($communityEvents['Event']['maybe_count'] - 1));
            }
        }
        
        $this->EventMember->removeMemberFromCommunityEvents($communityId, $userId);
        return $result;
        
    }
    
    function getOnlineFriends() {
        $user_id = $this->Auth->user('id');

        $myFrinedsUserIdList = $this->MyFriends->getUserConfirmedFriendsIdList($user_id);
        $onlineFriends = $this->User->checkOnlineUsers($myFrinedsUserIdList, $user_id);
        
        return $onlineFriends;
    }
    
    /**
     * Function to get Onlie friends count
     * @param array $onlineFriends
     * @return number
     */
    public function getOnlineUserCount($onlineFriends){
    	$count = 0;
    	foreach ($onlineFriends as $friend){
    		if($friend['is_online']){
    			$count ++;
    		}
    	}
    	return $count;
    }
    
    /**
     * Function to get member ids of a community
     * @param type $communityId
     */
    private function __getMemberIds($communityId){
        return $this->CommunityMember->getCommunityMemberIdsList($communityId);
    }
    
    /**
     * Function to get line friends list with online status
     * 
     * @param array $usersIds
     * @return array 
     */
    public function getOnlineMemberWithStatus($usersIds) {
		$user_id = $this->Auth->user('id');

		$usersDetails = $this->User->checkOnlineUsers($usersIds, $user_id);		

		return $usersDetails;
	}
	
	/**
     * Function to get diease member list with online status
     */
    public function getCommunityMembersHTML(){
        $this->autoRender = false;
        if ( $this->request->is('ajax') ) {
            $communityId = $this->request->data('communityId');
            
            if ( !empty( $communityId )) {
                $communityMemberIds = $this->__getMemberIds($communityId);
                $onlineMembers = $this->getOnlineMemberWithStatus($communityMemberIds);
               
                $this->set(compact('onlineMembers'));
                $view = new View($this, false);
                $HTML = $view->element('Community.Details/online_members');
                echo ($HTML);
            }
        }
    }
    
    /**
     * Function to get online friends count 
     */
    public function getCommunityMembersCountAjax(){
    	
    	$this->autoRender = false;
    	if ( $this->request->is('ajax') ) {
            $communityId = $this->request->data('communityId');
            
            if ( !empty( $communityId )) {
                $communityMemberIds = $this->__getMemberIds($communityId);
                echo $this->User->getOnlineUserCount($communityMemberIds);
            }
        }
    }
}

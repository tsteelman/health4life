<?php

/**
 * ProfileController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('UserPrivacySettings', 'Lib');
App::uses('HealthStatus', 'Utility');

/**
 * ProfileController for the frontend
 *
 * ProfileController is used for to show user profile details
 *
 * @author 		Ajay Arjunan
 * @package 	User
 * @category	Controllers
 */
class ProfileController extends UserAppController {

    /**
     * Models needed in the Controller
     *
     * @var array
     */
    public $uses = array('User', 'MyFriends', 'Post', 'Country', 'PatientDisease',
        'Treatment', 'HealthReading', 'CareGiverPatient', 
        'Disease', 'Team', 'TeamMember', 'Photo', 'UserTreatment');
    public $components = array('EmailInvite', 'Paginator', 'Posting', 'RecommendedFriend');

    /**
     * variable to store the requested user minimum details.
     *
     * @var string
     */
    protected $_requestedUser = NULL;

    /**
     * Variable to store the logged in user details
     *
     * @var type
     */
    protected $_currentUser = NULL;

    /**
     * variable to store the requested user full details.
     *
     * @var string
     */
    protected $_requestedUserFullDetails = NULL;
	
	/**
	 * Current user permissions in visited profile
	 * 
	 * @var array 
	 */
	protected $_permissions = array();
    
    /**
     * Override parent function to get the current dasboard item
     *
     * @param null
     * @return String
     */
    public function beforeFilter() {
        $this->_currentUser = $this->Auth->user();
        parent::beforeFilter();
    }

        
    /**
     * Override parent function to get the current dasboard item
     *
     * @param null
     * @return String
     */
    protected function getCurrentDashbaordItem() {
        return "profile";
    }

	/**
	 * Show the user profile page
	 */
	public function index($username = null) {
		$is_sharing_enabled = $this->getSharingOptions();
		$this->set('is_sharing_enabled', $is_sharing_enabled);
		$this->_setUserProfileData($username);
		if (isset($this->_requestedUser['id'])) {
			$this->set('title_for_layout', $this->_requestedUser['username']);
		} else {
			$this->set('title_for_layout', $this->Auth->user('username'));
		}
		$hasLikePermission = true;
		$hasCommentPermission = $this->_permissions['messaging'];
		$this->Posting->hasLikePermission = $hasLikePermission;
		$this->Posting->hasCommentPermission = $hasCommentPermission;
		$profileUserId = $this->_requestedUser['id'];
		if ($this->request->is('ajax')) {
			if (!is_null($username)) {
				$profileUser = $this->User->findByUsername($username);
				if (isset($profileUser['User']['id'])) {
					$profileUserId = $profileUser['User']['id'];
				}
			}
			$this->__loadPosts($profileUserId);
			$this->layout = 'ajax';
			$this->render('ajax_index');
		} else {
			$currentUserId = $this->_currentUser['id'];
			
			// mark the profile notifications as read by the visiting user
			$this->Notification = ClassRegistry::init('Notification');
			$this->Notification->markProfileNotificationsReadByUser($profileUserId, $currentUserId);
			
			$this->__setActivityTabData($profileUserId);
		}
	}

	/**
     * Sets the profile activity tab data on the view
     *
     * @param int $profileUserId
     */
    private function __setActivityTabData($profileUserId) {
		$currentUserId = (int) $this->Auth->user('id');
		$isNewsFeed = ((int) $profileUserId === $currentUserId) ? true : false;
		$options = array('user_id' => $profileUserId);
		$this->Posting->setFormData($options);
		
		// change anonymous posting checkbox based on visiting user permissions
		if ($this->viewVars['showAnonymousCheckbox'] === true) {
			$this->set('showAnonymousCheckbox', $this->_permissions['anonymous_messaging']);
		}
		
		if ($isNewsFeed === true) {
			$this->set('newsFeedUrl', "newsfeed/{$currentUserId}");
		}

		// disable anonymous posting in another user's profile
		$showAnonymousCheckbox = ($isNewsFeed === true) ? true : false;
		$this->set('showAnonymousCheckbox', $showAnonymousCheckbox);
		
		$hasFilterPermission = true;
		$filterOptions = $this->Posting->getFilterOptions(Post::POSTED_IN_TYPE_USERS);
		$this->set('filterOptions', $filterOptions);
		$this->set('hasFilterPermission', $hasFilterPermission);
		$this->__loadPosts($profileUserId);
	}

    /**
     * Loads the posts for a user and sets them on view
     *
     * @param int $userId
     */
    private function __loadPosts($profileUserId) {
		if ((int) $profileUserId === (int) $this->Auth->user('id')) {
			$this->Posting->newsFeedId = (int) $profileUserId;
			$this->Paginator->settings = $this->Posting->getNewsFeedQuerySettings();
		} else {
			$conditions = array(
				'Post.posted_in' => $profileUserId,
				'Post.posted_in_type' => Post::POSTED_IN_TYPE_USERS,
				'Post.is_deleted' => Post::NOT_DELETED,
				'Post.status' => Post::STATUS_NORMAL
			);
			if ($this->viewHealth === false) {
				$conditions['Post.post_type !='] = Post::POST_TYPE_HEALTH;
			}
			$this->Paginator->settings = array(
				'conditions' =>$conditions,
				'order' => array(
					'Post.created' => 'DESC'
				),
				'limit' => PostingComponent::POSTS_PER_PAGE
			);
		}
        $posts = $this->Paginator->paginate('Post');
        $postsData = array();
        if (!empty($posts)) {
			$displayPage = Post::POSTED_IN_TYPE_USERS;
			foreach ($posts as $post) {
				$postsData[] = $this->Posting->getPostDisplayData($post, $displayPage);
			}
		}
        $this->set('posts', $postsData);
	}

    private function userExists($username = null) {
        /*
         * Check if the user exists with the give username
         */
        if (isset($username)) {
            $user_details = $this->User->getFullUserDetails($username, 'username');

            /*
             * If requested username exists
             */
            if ($user_details[0]['User']) {
                $this->_requestedUserFullDetails = $user_details[0];
                return $user_details[0]['User'];
            } else {
                return false;
            }
        }
    }

    /**
     * Presets the data needed to display user profile data
     */
    protected function _presetUserProfileData($username = null) {
        if (isset($this->request->params['username'])) {
			$username = $this->request->params['username'];
		}
		if(!empty($username)){
            $user = $this->userExists($username);
            if (!empty($user)) { 
                $this->_requestedUser = $user;
            } else {
                if (!$this->request->is('ajax')) {
                    $this->Session->setFlash(__("The requested user does not exists"), 'error');
                    $this->redirect(array('controller' => 'profile', 'action' => 'index'));
                }
            }
        } else {
            $user_details = $this->User->getFullUserDetails($this->Auth->user('username'), 'username');
            $this->_requestedUser = $user_details[0]['User'];
        }
    }

    /**
     * Sets the common data needed to display user profile data
     */
    protected function _setUserProfileData($username = null) {
		$timezone = $this->Auth->user('timezone');
		$this->_presetUserProfileData($username);
		if (!is_null($this->_requestedUser)) {
			$user_details = $this->_requestedUser;
			$userId = $this->Auth->user('id');
			$profileId = $this->_requestedUser['id'];
			$this->_permissions = $this->User->getUserPermissionsInProfile($userId, $profileId);

			if ($this->_requestedUser['type'] === '3') {
				/**
				 * Commenting caregiver patient relation code,
				 * since this functionality is not present now
				 */
//				$patient_name = $this->CareGiverPatient->find('first', array(
//					'conditions' => array(
//						'CareGiverPatient.care_giver_id' => $this->_requestedUser['id']
//					),
//					'fields' => array('CareGiverPatient.first_name', 'CareGiverPatient.last_name')
//				));
//				$user_details['patient'] = $patient_name['CareGiverPatient']['first_name'] .
//						' ' . $patient_name['CareGiverPatient']['last_name'];
			}

            $menuItems = $this->__getMenuItems();
            $loggedInUserMenuItems = array(
                'pmr',
                'library',
                'videochat',
                'therapies',
                'team'
                );
			$i = 0;
			$controller = $this->request->params['controller'];

			foreach ($menuItems as $menuItem):
				if ((in_array($menuItem['name'], $loggedInUserMenuItems))) {
					continue;
				}
				$menu[] = $menuItem;
				$i++;
			endforeach;
			
			if ($i == 1 && $menu[0]['name'] == 'following' && !($controller == 'following')) {

				$this->redirect($menu[0]['url']);
			}	

						
            $this->_currentUser = $this->Auth->user();
            $status = 0;
            $logged_in_user = $this->_currentUser;
            
            $myDiseases = $this->PatientDisease->getUserDisease($profileId); 			
            
            /*
             * Set user treatment
             */
            $user_details['treatment'] = "";
            foreach ($myDiseases as $disease ) {
                if(isset($disease['Treatment']['name'])) {
                    $user_details['treatment'] = $disease['Treatment']['name'];
                    break;
                }
            }
           
            
            /*
             * Set user disease
             */
             $user_details['disease'] = '';
            foreach ($myDiseases as $disease ) {
                if(isset($disease['Diseases']['name'])) {
                    $user_details['disease'] = $disease['Diseases']['name'];
                    break;
                }
            }
            
            list($feeling, $latest_feeling) = $this->getUserHealthStatus();
                       
			$user_details['feeling'] = $feeling;
			$feelingCreatedDate = CakeTime::format('Y-m-d', $latest_feeling['created'], false, $timezone);

			$user_details['feeling_date'] = $feelingCreatedDate;

			if (is_null($user_details['country']) && ($user_details['country'] > 0)) {
				$country = $this->Country->findById($user_details['country']);
				$user_details['country'] = $country['Country']['short_name'];
			}

			if ($user_details['id'] === $logged_in_user['id']) {

				$this->setMyTeamDetails();

				$is_same = true;
				/*
				 * Get the Pending friends count of the logged in user
				 */
				$pending_count = $this->MyFriends->
						getFriendsStatusCount($user_details['id'], MyFriends::STATUS_REQUEST_RECIEVED);
			} else {
				$is_same = false;
				$status = $this->MyFriends->getFriendStatus($logged_in_user['id'], $user_details['id']);
			}
			//set RHS list.
//            $diseaseId = $this->PatientDisease->findDiseases($this->_currentUser['id']);
			$recommendedUsersDetails = $this->getPeopleYouMayKnowList();
//            $usersWithDisease = $this->getOnlinePtientsWithDisease($diseaseId);
			$onlineFriends = $this->getOnlineFriends();
			$onlineFriendsCount = $this->getOnlineUserCount($onlineFriends);

			if ($is_same) {
				$healthStatusList = HealthStatus::getHealthStatusList();
				$showHealthStatusSelector = true;
				$this->set(compact('menuItems', 'loggedInUserMenuItems', 'user_details', 'logged_in_user', 'is_same', 'status', 'timezone', 'healthStatusList', 'showHealthStatusSelector', 'usersWithDisease', 'onlineFriends', 'onlineFriendsCount', 'recommendedUsersDetails'));
			} else {
				$this->loadModel('FollowingPage');
				//get if login user is following this disease or not.
				$followStatus = $this->FollowingPage->getFollowStatus(
						$userId, FollowingPage::USER_TYPE, $profileId
				);
				$friend_status = $this->MyFriends->getFriendStatus($logged_in_user['id'], $user_details['id']);
				$this->set(compact('menuItems', 'friend_status', 'loggedInUserMenuItems', 'user_details', 'logged_in_user', 'is_same', 'status', 'timezone', 'usersWithDisease', 'onlineFriends', 'onlineFriendsCount', 'recommendedUsersDetails', 'followStatus'));
			}

			$diseaseId = $this->PatientDisease->findDiseases($this->_currentUser['id']);

            $currentUserDetails = array(
                "userDetails" => $this->User->getUsersData($user_details['id']),
                "userDiseaseDetails" => $user_details['disease'],
                "userTreatmentDetails" => $user_details['treatment'],
                "advertisementVideos" => $this->Disease->getDiseaseAdVideo($diseaseId)
            );  
			
			$this->set(compact('currentUserDetails'));

			$this->set('coverModel', 'User');
			$this->set('roomId', $user_details['id']);
			/*
			 * set cover photos
			 */
			$this->__setCoverPhotoData($this->_requestedUser['id'], $this->_requestedUser['is_cover_slideshow_enabled']);
			$this->__setProfileBg($user_details['id']);
		}
	}

    /**
     * Function to get the list of profile menu items
     *
     * @return array
     */
    private function __getMenuItems() {
        if (isset($this->request->params['username'])) {
            $username = $this->request->params['username'];
            $profileUrl = Common::getUserProfileLink( $username, true);
        } else {
            $profileUrl = '/profile';
        }

        $controller = $this->request->params['controller'];
		$loggedInUser = $this->Auth->user('id');
		$whoIsViewed = $this->_requestedUser['id'];
		$isOwnProfile = ($loggedInUser === $whoIsViewed);

        /**
         * Warning: if you change the order of menu, please change the offset in menu
         * permission check code for tab given a few lines below this code.
         * 
         * Make 'my' index to true, if 'my' to be displayed with menuitem in profile page.
         */
        $menuItems = array(
//            array(
//                'label' => __('Health'),
//                'name' => 'health',
//                'url' => "{$profileUrl}/myhealth",
//                'active' => ($controller === 'myhealth'),
//                'my' => true
//            ),
//            array(
//                'label' => __('Nutrition'),
//                'name' => 'nutrition',
//                'url' => "{$profileUrl}/mynutrition",
//                'active' => ($controller === 'mynutrition'),
//                'my' => true
//            ),
//            array(
//                'label' => __('Condition'),
//                'name' => 'condition',
//                'url' => "{$profileUrl}/mycondition",
//                'active' => ($controller === 'mycondition'),
//                'my' => true
//            ),
            array(
                'label' => __('Friends'),
                'name' => 'friends',
                'url' => "{$profileUrl}/friends",
                'active' => ($controller === 'friends'),
                'my' => true
            ),
            array(
                'label' => __('Team'),
                'name' => 'team',
                'url' => "{$profileUrl}/myteam",
                'active' => ($controller === 'myteam'),
                'my' => true
            ),
//            array(
//                'label' => __('Video Chat'),
//                'name' => 'videochat',
//                'url' => "{$profileUrl}/videochat",
//                'active' => ($controller === 'videochat'),
//                'my' => false
//            ),
            array(
                'label' => $isOwnProfile ? __('News Feed') : __('Activity'),
                'name' => 'activity',
                'url' => $profileUrl,
                'active' => ($controller === 'profile'),
                'my' => false
            ),
            array(
                'label' => __('Blog'),
                'name' => 'blog',
                'url' => "{$profileUrl}/blog",
                'active' => ($controller === 'blog'),
                'my' => false
            ),                          
            array(
                'label' => __('My Library'),
                'name' => 'library',
                'url' => "{$profileUrl}/mylibrary",
                'active' => ($controller === 'mylibrary'),
                'my' => false
            ),
                      
//            array(
//                'label' => __('Communities'),
//                'name' => 'communities',
//                'url' => "{$profileUrl}/communities",
//                'active' => ($controller === 'communities'),
//                'my' => false
//            ),
//            array(
//                'label' => __('Events'),
//                'name' => 'events',
//                'url' => "{$profileUrl}/events",
//                'active' => ($controller === 'events'),
//                'my' => true
//            ),
//            array(
//                'label' => __('PMR'),
//                'name' => 'pmr',
//                'url' => "http://pmr.qburst.com",
//                'active' => ($controller === 'videochat'),
//                'my' => false
//            ),
//            array(
//                'label' => __('Therapy'),
//                'name' => 'therapies',
//                'url' => "{$profileUrl}/therapies",
//                'active' => ($controller === ''),
//                'my' => false
//            ),
            array(
                'label' => __('Following'),
                'name' => 'following',
                'url' => "{$profileUrl}/following",
                'active' => ($controller === 'following'),
                'my' => false
            ),
            array(
                'label' => __('Therapy'),
                'name' => 'therapies',
                'url' => "#", // http://www.e-psychiatry.com/services.php
                'active' => ($controller === ''),
                'my' => false
            )
        );
        
        $privacy = new UserPrivacySettings($this->_requestedUser['id']);
       
        $viewFriends = $viewHealth = $viewActivity = $postOnWall = true;
        $viewNutrition = $viewCommunities = $viewEvents = $viewDisease = true;
        $viewTeam = $viewBlog =  true;

        if ($loggedInUser != $whoIsViewed) {
            
            /*
             * No need to show the team to other user
             */
            $viewTeam = false;
            
            
            $isFriend = $this->MyFriends->getFriendStatus($loggedInUser, $whoIsViewed);
            $viewSetting = array($privacy::PRIVACY_PUBLIC);

            if (($isFriend == MyFriends::STATUS_CONFIRMED)) {
                array_push($viewSetting, $privacy::PRIVACY_FRIENDS);
            }
            /**
             * Permission check code for profile tab.
             */
            if (!in_array($privacy->__get('view_your_friends'), $viewSetting)) {
                unset($menuItems[0]);
                $viewFriends = false;
            }

            if (!in_array($privacy->__get('view_your_health'), $viewSetting)) {
//                unset($menuItems[0]);
                $viewHealth = false;
            }

            if (!in_array($privacy->__get('view_your_nutrition'), $viewSetting)) {
//                unset($menuItems[1]);
                $viewNutrition = false;
            }

            if (!in_array($privacy->__get('view_your_communities'), $viewSetting)) {
//                unset($menuItems[8]);
                $viewCommunities = false;
            }

            if (!in_array($privacy->__get('view_your_events'), $viewSetting)) {
//                unset($menuItems[9]);
                $viewEvents = false;
            }
		
            if (!in_array($privacy->__get('view_your_disease'), $viewSetting)) {
//                unset($menuItems[1]);
                $viewDisease = false;
            }

            if (!in_array($privacy->__get('post_on_wall'), $viewSetting)) {
                unset($menuItems[2]);
                $viewActivity = $postOnWall = false;
            }
			
            if ($postOnWall === true) {
                $postOnWall = $this->_permissions['messaging'];
            }
            
            /*
             * Show blog tab of other user based on Privacy settings
             */            
            if (!in_array($privacy->__get('view_your_blog'), $viewSetting)) {
                unset($menuItems[3]);
                $viewBlog = false;
            }

        }
		
		$this->viewHealth = $viewHealth;
        $this->set('hasPostPermission', $postOnWall);
        $this->set(compact(
                'controller',
                'viewFriends',
                'viewHealth',
                'viewActivity',
                'viewNutrition',
                'viewCommunities',
                'viewEvents',
                'viewTeam',
                'viewBlog',
                'viewDisease')
                );

        return $menuItems;
    }

    /**
     * Show hovercard when hovering a user's link
     */
    public function hovercard() {
        $this->_presetUserProfileData();
        $is_same = false;
        $this->disableCache();
        $this->layout = "ajax";
        $View = new View($this, false);
        $requested_user = array();
        $isAjax = ($this->request->is('ajax')) ? true : false;
        $is_logged_in = $this->Auth->loggedIn();

        /*
         * Show the hovercard only if it is an Ajax request and the user is logged in
         */
        if ($isAjax && $is_logged_in) {

            /*
             * Logged-in user
             */
            $current_user = $this->Auth->user();

            /*
             * Requested user array
             */
            $requested_user = $this->_requestedUserFullDetails;
            $requestedUserId = $requested_user['User']['id'];
            
		
            /*
             * Requested user details
             */
            $user_details['id'] = $this->_requestedUserFullDetails['User']['id'];
            $user_details['username'] = $this->_requestedUserFullDetails['User']['username'];
            $user_details['type'] = $this->_requestedUserFullDetails['User']['type'];
            $user_details['created'] = $this->_requestedUserFullDetails['User']['created'];
            $user_details['last_login_datetime'] = $this->_requestedUserFullDetails['User']['last_login_datetime'];
            
            $user_details['location'] = $this->_requestedUserFullDetails['0']['location'];
            $user_details['user_role'] = Common::getUserRoleName($this->_requestedUserFullDetails['User']['type']);
            $user_details['about_me'] = $this->_requestedUserFullDetails['User']['about_me'];
            
            /*
             * Feeling Status of the user
             */
            $latestHealthStatus = $this->HealthReading->getLatestHealthStatus($requestedUserId);                        
            $user_details['feeling'] = HealthStatus::getFeelingSmileyClass($latestHealthStatus['health_status']);
            
            
            /*
             * Show the Diagnosis & Medication 
             * based on the privacy settings of the user
             */			
            $canViewDisease = false;
            $canViewDisease = $this->__canViewConditionOfUser($requestedUserId);			
            if($canViewDisease) {
                $user_details['disease'] = 
                        $this->_requestedUserFullDetails[0]['diseases'];
                
                $user_details['treatment'] = 
                        $this->UserTreatment->getUserTreatmentNamesList($requestedUserId);                
            }
 	            


            /*
             * Check if the hovercard is for the logged in user or if both of them are friends
             */
            if ($current_user['id'] == $requestedUserId) {
                $is_same = true;
            } else {
                $friend_status = $this->MyFriends->getFriendStatus($current_user['id'], $user_details['id']);
            }

            /*
             * Get the profile image of the requested user
             */
            $user_img = Common::getUserThumb($requestedUserId, $requested_user['User']['type'], 'medium', 'profile_brdr_5');
            $user_details['img'] = $user_img;
            $user_details['profile_url'] = Common::getUserProfileLink( $requested_user['User']['username'], true);
        }

        $response = $View->element('User.hovercard', compact('isAjax', 'is_logged_in', 'user_details', 'friend_status', 'is_same', 'current_user'));
        echo $response;
        exit;
    }

    public function inviteFriendsByEmail() {
        $this->autoRender = false;
        $email_list = $this->request->query ['email_list'];
        $email_list_array = explode(",", $email_list);
        $user_id = $this->Auth->user('id');

        $response = $this->EmailInvite->inviteFriendsByEmail($email_list_array, $user_id);
        echo json_encode($response);
    }

    public function getUserHealthStatus() {
        $timezone = $this->Auth->user('timezone');

        if ($this->request->is('ajax')) {

            $latest_feeling = $this->HealthReading->getLatestHealthStatus($this->Auth->user('id'));
        } else {
            $latest_feeling = $this->HealthReading->getLatestHealthStatus($this->_requestedUser['id']);
        }

        $feeling = HealthStatus::getFeelingSmileyClass($latest_feeling['health_status']);

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->layout = "ajax";
        }
        return array($feeling, $latest_feeling);
    }

    function getPeopleYouMayKnowList() { //done
        $user_id = $this->Auth->user('id');
        $recommendedUsers = NULL;
        $recommendedUsers = $this->RecommendedFriend->getRecommendedUsers($user_id, 50);
        if (isset($recommendedUsers) && $recommendedUsers != NULL) {
			$recommendedUsersDetails = array();

			foreach ($recommendedUsers as $recommendedUser) {
				$privacy = new UserPrivacySettings($recommendedUser['User']['id']);
				$diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');


				if (
						$diseaseViewPermittedTo === $privacy::PRIVACY_PUBLIC || $recommendedUser['User']['id'] == $user_id
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
					} else {
						$recommendedUsersDetails[] = array(
							'user' => $recommendedUser['User'],
							'diseases' => NULL
						);
					}
				}
//                if ($recommendedUser['User']['is_admin'] != 1) {
			}
		} else {
			$recommendedUsersDetails = NULL;
		}
        return $recommendedUsersDetails;
//        $this->set(compact('recommendedUsersDetails'));
    }

    function getOnlinePtientsWithDisease($diseaseId) {
//        $user_id = $this->Auth->user('id');
        $usersDetails = NULL;
        $usersIds = $this->PatientDisease->findUsersWithDisease($diseaseId);
        if (isset($usersIds) && $usersIds != NULL) {
            $users = $this->User->getUsersData($usersIds);
            if (isset($users) && $users != NULL) {
                $UsersDetails = array();
                foreach ($users as $user) {
//                if ($user['User']['is_admin'] != 1) {
                    $usersDetails[] = array(
                        'user' => $user['User'],
                        'diseases' => $this->User->getUserDiseases($user['User']['id'])
                    );
//                }
                }
            } else {
                $usersDetails = NULL;
            }
        } else {
            $usersDetails = NULL;
        }
        return $usersDetails;
//        $this->set(compact('recommendedUsersDetails'));
    }

    function getOnlineFriends() {
        $user_id = $this->Auth->user('id');

        $myFrinedsUserIdList = $this->MyFriends->getUserConfirmedFriendsIdList($user_id);
        $onlineFriends = $this->User->checkOnlineUsers($myFrinedsUserIdList, $user_id);
        
        return $onlineFriends;
    }
    
    /**
     * Function to get online friends html in ajax call
     */
    public function getOnlineFriendsHTML() {
    
    	$this->autoRender = false;
    
    	$user_id = $this->Auth->user('id');
        $onlineFriends = NULL;

        $myFrinedsUserIdList = $this->MyFriends->getUserConfirmedFriendsIdList($user_id);
        $onlineFriends = $this->User->checkOnlineUsers($myFrinedsUserIdList, $user_id);
    
    	$this->set(compact('onlineFriends'));
    	$view = new View($this, false);
    	$HTML = $view->element('User.Profile/online_friends');
    	echo ($HTML);
    }
    
    /**
     * Function to get online friends html in ajax call
     */
    public function getOnlineVideoFriendsHTML() {
    
    	$this->autoRender = false;
    
    	$user_id = $this->Auth->user('id');
        $onlineFriends = NULL;

        $myFrinedsUserIdList = $this->MyFriends->getUserConfirmedFriendsIdList($user_id);
        $onlineFriends = $this->User->checkOnlineUsers($myFrinedsUserIdList, $user_id);
    
    	$this->set(compact('onlineFriends'));
    	$view = new View($this, false);
    	$HTML = $view->element('User.Profile/online_video_friends');
    	echo ($HTML);
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
     * Function to get online friends count 
     */
    public function getOnlineUserCountAjax(){
    	
    	$this->autoRender = false;
    	
    	$user_id = $this->Auth->user('id');
    	$myFrinedsUserIdList = $this->MyFriends->getUserConfirmedFriendsIdList($user_id);
    	echo $this->User->getOnlineUserCount($myFrinedsUserIdList);
    }

    /**
     * Function to get team details of the logged in user
     * @return null
     */
    public function setMyTeamDetails(){
        
        $teamDetails = $this->TeamMember->getUserApprovedTeams($this->_currentUser['id']);

        $this->set(compact('teamDetails'));        
    }

	/**
	 * Function to set cover photo
	 * 
	 * @param int $profileId
	 * @param int $slideShowStatus
	 */
	private function __setCoverPhotoData($profileId, $slideShowStatus) {
		$coverType = "profile";
		$coverPhotos = $this->Photo->getProfileCoverPhotos($profileId);
		$photos = array();
		$defaultPhotoId = 0;
		if (!empty($coverPhotos)) {
			$photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/user_profile/';
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
			$defaultPhoto = '/theme/App/img/cover_bg.png';
		}

		// set data on view
		if ($slideShowStatus == User::COVER_SLIDESHOW_ENABLED) {
			$isSlideShowEnabled = User::COVER_SLIDESHOW_ENABLED;
		} else {
			$isSlideShowEnabled = User::COVER_SLIDESHOW_DISABLED;
		}
		$this->request->data['User']['is_cover_slideshow_enabled'] = $isSlideShowEnabled;
		$this->request->data['User']['default_photo_id'] = $defaultPhotoId;
		$this->request->data['User']['default_photo'] = $defaultPhoto;
		$this->set(compact('coverType', 'photos', 'defaultPhoto', 'defaultPhotoId', 'isSlideShowEnabled'));
	}
    
    private function __setProfileBg($userId){
        
            $defaultPhoto = '/theme/App/img/profile_tile_bg.png';
            $photoPath = Configure::read('App.UPLOAD_PATH_URL') . '/user_profile/';
            
            $profileTileBg = $this->Photo->getUserProfileBg($userId);
            
            
            if ( !empty( $profileTileBg )) {
                $defaultPhoto = $photoPath . $profileTileBg['Photo']['file_name'];
            }
            $this->set('defaultProfileTileBg', $defaultPhoto);
    }
    /**
     * Function to check if currently logged in user can view the disease of
     * the specified user
     * 
     * @param int $userId
     * @return boolean
     */
    private function __canViewConditionOfUser($userId) {
            $viewDisease = false;
            $userId = (int) $userId;
            $currentUserId = (int) $this->_currentUser['id'];
            if ($userId === $currentUserId) {
                $viewDisease = true;
            } else {
                $viewDisease = false;
                $privacy = new UserPrivacySettings($userId);
                $diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');
                if ($diseaseViewPermittedTo === $privacy::PRIVACY_PUBLIC) {
                    $viewDisease = true;
                } elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
                    $friendStatus = (int) $this->MyFriends->getFriendStatus($currentUserId, $userId);
                    if (($friendStatus === MyFriends::STATUS_CONFIRMED)) {
                            $viewDisease = true;
                    }
                }
            }
            return $viewDisease;
    }
}
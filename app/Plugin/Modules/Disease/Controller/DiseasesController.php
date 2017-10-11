<?php

/**
 * DiseaseController class file.
 *
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('DiseaseAppController', 'Disease.Controller');
App::uses('FollowingPage', 'Model');

/**
 * DiseaseController for frontend communities.
 *
 * DiseaseController is used for listing communities.
 *
 * @package 	Disease
 * @category	Controllers
 */
class DiseasesController extends DiseaseAppController {

    public $uses = array(
        'Analytics',
        'Disease',
        'CommunityDisease',
        'EventDisease',
        'PatientDisease',
        'Event',
        'EventMember',
        'User',
        'MyFriends',
        'Post',
        'Answer',
		'FollowingPage'
    );
    public $components = array(
        'Analytics',
        'Paginator',
        'session',
        'RecommendedFriend',
        'Posting'
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
                'index', 'getDiseaseCommunityList', 'getDiseaseEventList'
        );
    }

    /*
     * Function to display disease details.
     *
     * @param int disease_id
     */

    public function index() {
        $coverType = "disease";
        //Enabling sharing option
        $is_sharing_enabled = $this->getSharingOptions();

        /*
         * If the request is for a particular disease, show the disease detail page,
         * else show the disease listing page.
         */
        if (isset($this->request->params['diseaseId'])) {

            $diseaseId = $this->request->params['diseaseId'];
			$defaultPhoto = '/theme/App/img/disease_tile_bg.png';

            /*
             * Check if the requested disease is a valid one.
             */
            if (!$this->Disease->exists($diseaseId)) {
                $this->Session->setFlash(__($this->invalidMessage), 'error');
                $invalid_disease_url = '/';
                if ($this->Auth->loggedIn()) {
                    $invalid_disease_url = '/dashboard';
                }
                $this->redirect($invalid_disease_url);
            }

            if ($this->Auth->loggedIn()) {

                if ($this->Auth->loggedIn()) {
                    $user = $this->Auth->user();
                    // user timezone
                    $timezone = $this->Auth->user('timezone');
                    //set RHS list.
                    $recommendedUsersDetails = $this->getPeopleYouMayKnowList();

                    $onlineFriends = $this->getOnlineFriends();
                    $login_user_id = $user['id'];
                }
				
                $disease = $this->Disease->get_disease_details_by_id($diseaseId);
                $this->set('title_for_layout', h($disease['Disease']['name']) . ' symptoms');

                $usersIds = $this->getUsersFollowingDisease($diseaseId);
                $usersWithDisease = $this->getOnlineUsersFollowingDisease($usersIds);
                $onlineMemberCount = $this->User->getOnlineUserCount($usersIds);
                
				//get if login user is following this disease or not.
				$followStatus = $this->FollowingPage->getFollowStatus(
						$login_user_id, FollowingPage::DISEASE_TYPE, $disease['Disease']['id']
				);
				
                /*
                 * Get the Community detail page url
                 */
                $disease_detail_url = Configure::read('Url.condition') .
                        //Inflector::singularize($this->request->params['controller']) . "/" .
                        $this->request->params['action'] . "/" .
                        $diseaseId;


                $section_list = array(
                    "forum" => "Forum",
                    "pharma" => "Pharma Catalog",
                    "trials" => "Clinical Trials",
                    "track" => "Track Condition",
                    "communities" => "Communities",
                    "events" => "Events",
//                    "#medications" => "Medications",
//                    "#treatments" => "Treatments",
//                    "#nutrition" => "Nutrition",
                    //            "#testmonials" => "Patient Testmonials",
//                    "library" => "Library"
                );

                (isset($this->request->params['pass'][0])) ?
                                $section = $this->request->params['pass'][0] :
                                $section = 'forum';

                /*
                 * Set to the default one if an invalid request
                 */
                if (!key_exists($section, $section_list)) {
                    $section = "communities";
                }

                switch ($section) {
                    case 'events':
                        $paginate = $this->findEventsForDisease();
                        break;

                    case 'communities':
                        $paginate = $this->findCommunitiesForDisease();
                        break;
                    case 'members':
                        break;

                    case 'forum':
                        $this->set('hasPostPermission', true);
                        $this->set('hasFilterPermission', true);
                        $this->Posting->hasLikePermission = true;
                        $this->Posting->hasCommentPermission = true;
                        if ($this->request->is('ajax')) {
                            $this->layout = 'ajax';
                            $this->view = 'forum_ajax_index';
                            $this->__loadForumPosts($diseaseId);
                            return;
                        }
                        $this->__setForumTabData($diseaseId);
                        break;
                    case 'library':
                        $library = array();
                        if (is_array($disease['library']) && !empty($disease['library']['url'])) {
                            foreach ($disease['library']['url'] as $video_key => $video_lib) {
                                if (!empty($video_lib)) {
                                    $video_details = json_decode($video_lib, TRUE);
                                    $video_src = $video_details['src'];
                                    if($video_details['src'] != '') {
                                        $library[] = array(
                                            'src' => $video_src,
                                            'image' => $video_details['image']
                                        );
                                    }
                                }
                            }
                        }
                        $this->set('library', $library);
                        break;
                }

                $users_count = $this->Analytics->getDiseaseUserCount($diseaseId);
                $gender_analytics = $this->Analytics->getDiseaseUserGenderCount($diseaseId);
                $treatment_analytics = $this->Analytics->getDiseaseTreatmentUsersAgeCount($diseaseId);
                
                /* meta tags */
                $meta_og_title = h($disease['Disease']['name']);
                $meta_og_disc = __(htmlspecialchars_decode($disease['Disease']['description']));
                if (trim($disease['profile_video']) != "") {
                    $video_details = Common::getYoutubeDetails($disease['profile_video']);
                    $this->set('meta_og_image', $video_details['image']);
                }
                
                if (trim($disease['advertisement_video']) != "") {
                    $video_details = Common::getYoutubeDetails($disease['advertisement_video']);
                    $this->set('meta_og_image', $video_details['image']);
                }
                
                $disease_image =  Common::getDiseaseThumb($diseaseId);
				$isSlideShowEnabled = TRUE;
                $this->set(compact('disease_image' ,'disease', 'login_user_id',
						'user', 'section_list', 'section', 'timezone',
						'disease_detail_url', 'diseaseId', 'recommendedUsersDetails',
						'usersWithDisease', 'onlineFriends', 'is_sharing_enabled',
						'gender_analytics', 'users_count', 'treatment_analytics',
						'meta_og_title', 'meta_og_disc','defaultPhoto', 'isSlideShowEnabled',
						'followStatus', 'onlineMemberCount', 'coverType'
                ));
            } else {

                $new_members_list = $this->__getLatestMembers();
                $map_details = $this->Analytics->getDiseaseUserLocations($diseaseId);
                $disease_details = $this->Disease->get_disease_details_by_id($diseaseId);
                $disease_image = Common::getDiseaseThumb($diseaseId);
                $title_for_layout = h($disease_details['Disease']['name']);
                $this->set(compact('new_members_list', 'map_details', 'disease_details', 'disease_image', 'title_for_layout', 'is_sharing_enabled'));
                $this->render('view');
            }
        } else {
            /*
             * Otherwise show the disease listing page
             */
            $diseases_list = $this->Disease->getConditionsList();
            $this->set(compact('diseases_list', 'is_sharing_enabled'));
            $this->render('list');
        }
    }

    /**
     * Function to set data for the forum tab
     *
     * @param int $diseaseId
     */
    private function __setForumTabData($diseaseId) {
        $options = array('disease_id' => $diseaseId);
        $this->Posting->setFormData($options);
        $filterOptions = $this->Posting->getFilterOptions();

        if (!$this->Auth->loggedIn()) {
            unset($filterOptions[2]); // remove my activity filter if not login
        }

        $this->set('filterOptions', $filterOptions);
        $this->__loadForumPosts($diseaseId);
    }

    /**
     * Loads the posts for a disease forum and sets them on view
     *
     * @param int $diseaseId
     */
    private function __loadForumPosts($diseaseId) {
        $this->Paginator->settings = $this->Posting->getDiseaseNewsFeedQuerySettings($diseaseId);
        $posts = $this->Paginator->paginate('Post');
        $postsData = array();
        if (!empty($posts)) {
			$this->Posting->layout = '2_column';
			$displayPage = Post::POSTED_IN_TYPE_DISEASES;
			foreach ($posts as $post) {
				$postsData[] = $this->Posting->getPostDisplayData($post, $displayPage);
			}
		}
        $this->set('posts', $postsData);
    }

    /*
     * Function to get the list of events in a disease
     *
     * @param int $community_id community id
     * @param int $page count of page
     */

    public function getDiseaseEventList() {

        $this->autoRender = FALSE;
        $disease_id = $this->request->params['diseaseId'];

        (isset($this->request->params['named']['page'])) ?
                        $page = $this->request->params['named']['page'] :
                        $page = 1;

        $limit = 9; //Pagination limit

        if ($this->Auth->loggedIn()) {
            $user = $this->Auth->user();
            $timezone = $this->Auth->user('timezone');
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
        }

        $paginate = $this->findEventsForDisease();

        $this->layout = 'ajax';
        $View = new View($this, FALSE);
        $response['htm_content'] = $View->element('Disease.events_row');
        $response['paginator'] = $paginate;
        echo json_encode($response);
    }
    
    /**
     * Function returns paginated results for events for a disease
     */
    private function findEventsForDisease() {
        $disease_id = $this->request->params['diseaseId'];

        $limit = 9; //Pagination limit
        $this->Paginator->settings = array(
            'limit' => $limit,
            'conditions' => array(
                'EventDisease.disease_id' => $disease_id
            ),
            'fields' => array('Event.*', 'Disease.*', 'EventDisease.*'),
            'order' => array('Event.start_date' => 'desc'),
            'group' => array('Event.id')
        );

        $events = $this->paginate('EventDisease');
        $paginate = $this->params['paging']['EventDisease'];

        $now = date("Y-m-d H:i:s");

        $this->set(compact('events', 'disease_id', 'timezone', 'now', 'goingEventIds', 'notAttendingEventIds', 'maybeEventIds', 'user'));

       return $paginate;
    }

    /*
     * Function to get the list of communities in a disease
     *
     * @param int $community_id community id
     * @param int $page count of page
     */

    public function getDiseaseCommunityList() {
        $this->autoRender = FALSE;
        $this->layout = "ajax";
        $paginate = $this->findCommunitiesForDisease();
        $View = new View($this, false);
        $response['htm_content'] = $View->element('Disease.community_row');
        $response['paginator'] = $paginate;
        echo json_encode($response);
        exit;
    }
    
    private function findCommunitiesForDisease() {
        $disease_id = $this->request->params['diseaseId'];

        (isset($this->request->params['named']['page'])) ?
                        $page = $this->request->params['named']['page'] :
                        $page = 1;

        $limit = 9; //Pagination limit
        $user = $this->Auth->user();

        $this->Paginator->settings = array(
            'limit' => $limit,
            'conditions' => array(
                'CommunityDisease.disease_id' => $disease_id
            ),
            'fields' => array('Community.*', 'Disease.*', 'CommunityDisease.*'),
            'order' => array('Community.name' => 'asc')
        );

        $communities = $this->paginate('CommunityDisease');

        $paginate = $this->params['paging']['CommunityDisease'];

        $this->set(compact('users', 'communities', 'disease_id'));
        return $paginate;
    }

    public function getPeopleYouMayKnowList() { //done
        $user_id = $this->Auth->user('id');
        $recommendedUsers = NULL;
        $recommendedUsers = $this->RecommendedFriend->getRecommendedUsers($user_id, 50);
        if (isset($recommendedUsers) && $recommendedUsers != NULL) {
            $recommendedUsersDetails = array();
            foreach ($recommendedUsers as $recommendedUser) {
//                if ($recommendedUser['User']['is_admin'] != 1) {
				$disease = '';
				$privacy = new UserPrivacySettings($recommendedUser['User']['id']);
				$diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');				
				
				if (
						$diseaseViewPermittedTo === $privacy::PRIVACY_PUBLIC 
						|| $user_id == $recommendedUser['User']['id']
				) {
					$disease = $this->User->getUserDiseases($recommendedUser['User']['id']);
				} elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
					$friendStatus = (int) $this->MyFriends->getFriendStatus($user_id, $recommendedUser['User']['id']);
					if (($friendStatus === MyFriends::STATUS_CONFIRMED)) {
						$disease = $this->User->getUserDiseases($recommendedUser['User']['id']);
					}
				}
				
                $recommendedUsersDetails[] = array(
                    'user' => $recommendedUser['User'],
                    'diseases' => $disease
                );
//                }
            }
        } else {
            $recommendedUsersDetails = NULL;
        }
        return $recommendedUsersDetails;
//        $this->set(compact('recommendedUsersDetails'));
    }
/**
     * Function to get user ids of a specific disease followers
     * 
     * @param int $diseaseId
     * @return array
     */
    public function getUsersFollowingDisease($diseaseId) {

        $usersIds = $this->FollowingPage->getPageFollowingUsers($diseaseId, FollowingPage::DISEASE_TYPE);
        return $usersIds;

    }
    
    /**
     * Function to get disease following online user list with online status 
     * 
     * @param array $usersIds
     * @return array 
     */
    public function getOnlineUsersFollowingDisease($usersIds) {
		$user_id = $this->Auth->user('id');
        
        $usersDetails = $this->User->checkOnlineUsers($usersIds, $user_id);        
        return $usersDetails;
    }
    
    /**
     * Function to get patients id of a specific disease
     * 
     * @param int $diseaseId
     * @return array
     */
    public function getPatientsWithDisease($diseaseId) {

        $usersIds = $this->PatientDisease->findUsersWithDisease($diseaseId);
        return $usersIds;

    }
    
    /**
     * Function to get line friends list with online status
     * 
     * @param array $usersIds
     * @return array 
     */
    public function getOnlinePatientsWithDisease($usersIds) {
        $user_id = $this->Auth->user('id');
        $usersDetails = $this->User->checkOnlineUsers($usersIds, $user_id);        
        return $usersDetails;
    }

    function getOnlineFriends() {
        $user_id = $this->Auth->user('id');        

        $myFrinedsUserIdList = $this->MyFriends->getUserConfirmedFriendsIdList($user_id);
        $onlineFriends = $this->User->checkOnlineUsers($myFrinedsUserIdList, $user_id);
        
        return $onlineFriends;
    }

    function __getLatestMembers() {

        $static_user_array = array(
            array(
                "User" => array
                    (
                    "id" => "border_family user_medium_thumb ",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "AlisonRenee",
                    "type" => "border_family user_medium_thumb ",
                    "profile_picture" => "member_1.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "California"
                ),
                "City" => array
                    (
                    "description" => " Palo Alto"
                ),
                "Disease" => "Crohn's"
            ),
            array(
                "User" => array
                    (
                    "id" => "border_patient user_medium_thumb ",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Alexander",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_2.png",
                ),
                "Country" => array
                    (
                    "short_name" => "India"
                ),
                "State" => array
                    (
                    "description" => "Kerala"
                ),
                "City" => array
                    (
                    "description" => " Ernakulum, Kochi,"
                ),
                "Disease" => "Rheumatoid Arthritis"
            ),
            array(
                "User" => array
                    (
                    "id" => "border_other user_medium_thumb ",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "MarissaL",
                    "type" => "border_patient user_medium_thumb ",
                    "profile_picture" => "member_3.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Texas"
                ),
                "City" => array
                    (
                    "description" => "Austin"
                ),
                "Disease" => "Colitis"
            ),
            array(
                "User" => array
                    (
                    "id" => "border_caregiver user_medium_thumb",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Jasim",
                    "type" => "border_caregiver user_medium_thumb",
                    "profile_picture" => "member_4.png",
                ),
                "Country" => array
                    (
                    "short_name" => "Bangladesh"
                ),
                "State" => array
                    (
                    "description" => "Sylhet"
                ),
                "City" => array
                    (
                    "description" => ""
                ),
                "Disease" => "Lupus"
            ),
            array(
                "User" => array
                    (
                    "id" => "5",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "NinaA-Canada",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_5.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "California"
                ),
                "City" => array
                    (
                    "description" => "Los Angeles"
                ),
                "Disease" => "Rheumatoid arthritis"
            ),
            array(
                "User" => array
                    (
                    "id" => "6",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Troy-Landreau",
                    "type" => "border_family user_medium_thumb ",
                    "profile_picture" => "member_6.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Louisiana"
                ),
                "City" => array
                    (
                    "description" => "Baton Rouge"
                ),
                "Disease" => "Scleraderma"
            ),
            array(
                "User" => array
                    (
                    "id" => "7",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Johnson",
                    "type" => "border_caregiver user_medium_thumb",
                    "profile_picture" => "member_7.png",
                ),
                "Country" => array
                    (
                    "short_name" => "Canada"
                ),
                "State" => array
                    (
                    "description" => "Quebec"
                ),
                "City" => array
                    (
                    "description" => "Montreal"
                ),
                "Disease" => "Lupus"
            ),
            array(
                "User" => array
                    (
                    "id" => "8",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Will-Wadsworth",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_8.png",
                ),
                "Country" => array
                    (
                    "short_name" => "Canada"
                ),
                "State" => array
                    (
                    "description" => "British Columbia"
                ),
                "City" => array
                    (
                    "description" => "Vancouver"
                ),
                "Disease" => "Rheumatoid arthritis"
            ),
            array(
                "User" => array
                    (
                    "id" => "9",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "AmandaHintz",
                    "type" => "border_family user_medium_thumb ",
                    "profile_picture" => "member_9.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Connecticut"
                ),
                "City" => array
                    (
                    "description" => "Bridgeport"
                ),
                "Disease" => "Scleraderma"
            ),
            array(
                "User" => array
                    (
                    "id" => "10",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Tamasin",
                    "type" => "border_caregiver user_medium_thumb",
                    "profile_picture" => "member_10.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Nevada"
                ),
                "City" => array
                    (
                    "description" => "Las Vegas"
                ),
                "Disease" => "Crohn's"
            ),
            array(
                "User" => array
                    (
                    "id" => "11",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Zykima",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_11.png",
                ),
                "Country" => array
                    (
                    "short_name" => "UK"
                ),
                "State" => array
                    (
                    "description" => "England"
                ),
                "City" => array
                    (
                    "description" => "London WS1"
                ),
                "Disease" => "Scleroderma"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Peter Smith",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_12.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Texas"
                ),
                "City" => array
                    (
                    "description" => "Austin"
                ),
                "Disease" => "Crohn's"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Patrica",
                    "type" => "border_caregiver user_medium_thumb ",
                    "profile_picture" => "member_13.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Austin"
                ),
                "City" => array
                    (
                    "description" => "Texas"
                ),
                "Disease" => "RSD"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Jennifer",
                    "type" => "border_patient user_medium_thumb ",
                    "profile_picture" => "member_14.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Washington"
                ),
                "City" => array
                    (
                    "description" => "Seattle"
                ),
                "Disease" => "Crohn's"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Karen",
                    "type" => "border_family user_medium_thumb ",
                    "profile_picture" => "member_15.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Oklahoma"
                ),
                "City" => array
                    (
                    "description" => "Boston"
                ),
                "Disease" => "Lupus"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Christine",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_16.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Arizona"
                ),
                "City" => array
                    (
                    "description" => "Boston"
                ),
                "Disease" => "Lupus"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Charly.Shaun",
                    "type" => "border_family user_medium_thumb ",
                    "profile_picture" => "member_17.png",
                ),
                "Country" => array
                    (
                    "short_name" => "Australia"
                ),
                "State" => array
                    (
                    "description" => "Adelaide"
                ),
                "City" => array
                    (
                    "description" => "Adelaide"
                ),
                "Disease" => " Colitis"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Nicole",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_18.png",
                ),
                "Country" => array
                    (
                    "short_name" => "UK"
                ),
                "State" => array
                    (
                    "description" => "London"
                ),
                "City" => array
                    (
                    "description" => "Croydon"
                ),
                "Disease" => "Lyme Disease"
            ),
        );
        return $static_user_array;
        //return $this->User->getLatestMembers();  
    }

    public function getEmbedPlayer() {

        $this->autoRender = false;
        App::uses('Crawler', 'Utility');
        $url = $this->request->data['link'];
        $Crawler = new Crawler();
        $videoEmbedCode = $Crawler->getEmbedPlayer($url);
        echo json_encode($this->Posting->getWmodeVideoEmbedCode($videoEmbedCode));
    }
    
    public function getDiseases() {
        $this->Disease->getDiseaseAdVideo();
    }
    
        
    /**
     * Function to get diease member list with online status
     */
    public function getDiseaseMembersHTML(){
        $this->autoRender = false;
        if ( $this->request->is('ajax') ) {
            $diseaseId = $this->request->data('diseaseId');
            
            if ( !empty( $diseaseId )) {
                $usersIds = $this->getUsersFollowingDisease($diseaseId);
                $usersWithDisease = $this->getOnlineUsersFollowingDisease($usersIds);
                $loggedIn = $this->Auth->loggedIn();
                $this->set(compact('usersWithDisease', 'loggedIn'));
                $view = new View($this, false);
                $HTML = $view->element('Disease.users_with_same_disease');
                echo ($HTML);
            }
        }
    }
    
    /**
     * Function to get online friends count 
     */
    public function getDiseaseMembersCountAjax(){
    	
    	$this->autoRender = false;
    	if ( $this->request->is('ajax') ) {
            $diseaseId = $this->request->data('diseaseId');
            
            if ( !empty( $diseaseId )) {
                $usersIds = $this->getUsersFollowingDisease($diseaseId);
                echo $this->User->getOnlineUserCount($usersIds);
            }
        }
    }

	/**
	 * Loads the questions for a disease forum and sets them on view
	 */
	public function listForumQuestions() {
		$diseaseId = $this->params['diseaseId'];
		$limit = PostingComponent::POSTS_PER_PAGE;
		$querySettings = $this->Posting->getDiseaseQuestionsQuerySettings($diseaseId);
		$totalCount = $this->Post->find('count', $querySettings);
		$offset = isset($this->params['named']['offset']) ? $this->params['named']['offset'] : 0;
		$hasCurrentPage = ($totalCount > $offset);
		if ($hasCurrentPage) {
			$querySettings['limit'] = $limit;
			$querySettings['offset'] = $offset;
			$posts = $this->Post->find('all', $querySettings);
			$postsData = array();
			if (!empty($posts)) {
				foreach ($posts as $post) {
					$postsData[] = $this->Posting->getPostDisplayData($post);
				}
			}
			$this->set('questions', $postsData);
			$nextPageOffset = $offset + $limit;
			$hasNextPage = ($totalCount > $nextPageOffset);
			if ($hasNextPage) {
				$this->set(compact('nextPageOffset'));
			}
		}
		$this->view = 'questions_index';
		if ($this->request->is('ajax')) {
			$this->layout = 'ajax';
		}
	}

	/**
	 * Loads the answers for a question and sets them on view
	 */
	public function listQuestionAnswers() {
		$this->autoRender = false;
		$this->layout = 'ajax';
		$postId = $this->request->data['postId'];
		if ($postId > 0) {
			$answers = $this->Posting->getPostAnswersData($postId);
			$view = new View($this, false);
			$view->set(compact('answers'));
			echo $view->element('Post.answers_list');
		}
	}
}
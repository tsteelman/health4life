<?php

/**
 * SearchController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('SearchAppController', 'Search.Controller');
App::uses('Date', 'Utility');
App::uses('UserPrivacySettings', 'Lib');

/**
 * SearchController for searching Communitys.
 * 
 * SearchController is used for searching Communitys.
 *
 * @author      Ajay Arjunan
 * @package 	Communitys
 * @category	Controllers 
 */
class SearchController extends SearchAppController {
	public $uses = array(
		'Community',
		'User',
		'MyFriends',
		'Disease',
		'Hashtag',
		'City'
	);
	public $components = array(
            'Paginator',
            'RecommendedFriend'
            );
        
    public function index() {
		$MAX_RESULT_COUNT = 12;
		$searchStrName = NULL;
		$searchStrGender = NULL;
		$searchStrAge = NULL;
		$searchStrDisease = NULL;
		$searchStrLocation = NULL;
		$searchStrSymptoms = NULL;
		$searchStrTreatment = NULL;
        if (isset($this->request->query['name']) || isset($this->request->query['gender']) || isset($this->request->query['age']) 
		|| isset($this->request->query['disease']) || isset($this->request->query['location']) || isset($this->request->query['symptoms'])
		|| isset($this->request->query['treatment'])) {
            $searchStr = NULL;
            if (isset($this->request->query['name']))
                $searchStrName = $this->request->query['name'];
            if (isset($this->request->query['gender']))
                $searchStrGender = $this->request->query['gender'];
            if (isset($this->request->query['age']))
                $searchStrAge = $this->request->query['age'];
            if (isset($this->request->query['disease']))
                $searchStrDisease = $this->request->query['disease'];
            else
                $searchStrDisease = "";
            if (isset($this->request->query['location']))
                $searchStrLocation = $this->request->query['location'];
            else
                $searchStrLocation = "";
            if (isset($this->request->query['symptoms']))
                $searchStrSymptoms = $this->request->query['symptoms'];
            else
                $searchStrSymptoms = "";
            if (isset($this->request->query['treatment']))
                $searchStrTreatment = $this->request->query['treatment'];
            else
                $searchStrTreatment = "";
        }
        else {
            if (isset($this->request->query['keyword'])) {
                $searchStr = $this->request->query['keyword'];
            } else if (isset($this->request->query['ajax'])) {
                $searchStr = $this->request->query['ajax'];
            } else {
                $searchStr = NULL;
            }
        }

        // Check the search type
        if (isset($this->request->query['type'])) {
            $type = strtolower($this->request->query['type']);
        } else {
            $type = 'all';
        }

        switch ($type) {
            case 'all' :
                $this->searchAll($searchStr, $MAX_RESULT_COUNT);
//                $type = 'community'; // For temporary purpose
                $layout_file = $type . "_search";
                $this->set(compact('layout_file'));
                break;
            case 'people' :
                if ($searchStrName !== NULL || $searchStrGender !== NULL ||
                        $searchStrAge !== NULL || $searchStrDisease !== NULL ||
                        $searchStrLocation !== NULL || $searchStrSymptoms !== NULL || $searchStrTreatment !== NULL) {
                    $this->searchPeopleAdvanced($searchStrName, $searchStrAge, $searchStrGender, $searchStrDisease, $searchStrLocation, $searchStrSymptoms, $searchStrTreatment, $MAX_RESULT_COUNT);
                    $layout_file = $type . "_search";
                        $this->set(compact('layout_file')); 
                } else if ($searchStr === NULL) {
                    $this->pendingInvites();
                    $layout_file = "people_pending";
                    $this->set(compact('layout_file'));
                } else {
                    $this->searchPeople($searchStr, $MAX_RESULT_COUNT);
                    $layout_file = $type . "_search";
                    $this->set(compact('layout_file'));
                }
                break;     
            case 'community' :
                $this->searchCommunities($searchStr, $MAX_RESULT_COUNT);
                $layout_file = $type . "_search";
                $this->set(compact('layout_file'));
                break;
            case 'disease' :
                $this->searchDiseases($searchStr, $MAX_RESULT_COUNT);
                $layout_file = $type . "_search";
                $this->set(compact('layout_file'));
                break;
            case 'hashtag' :
                $this->searchHashtags($searchStr, $MAX_RESULT_COUNT);
                $layout_file = "hash_search";
                $this->set(compact('layout_file'));
                break;
            default :
                $this->searchCommunities($searchStr, $MAX_RESULT_COUNT);
//                $type = 'community'; // For temporary purpose
                $layout_file = $type . "_search";
                $this->set(compact('layout_file'));
        }

        $layout_file = $type . "_search";
        $this->set(compact('layout_file'));
    }
    /**
     * Function for header search
     */
    public function getHeaderSearch(){
    
    	$this->layout = "ajax";
    
    	$searchStr = $this->request->query['term'];
    	$category = isset($this->request->query['category'])? $this->request->query['category']: 1;
		
        $searchResult = array();
    	switch ($category) {
            case 1:
                $searchResult = array_merge(
					$this->searchDiseases($searchStr),
					$this->searchPeople($searchStr),
					$this->searchCommunities($searchStr),
                                        $this->searchHashtags($searchStr)
				);
                break;
            case 2: 
				$searchResult = $this->searchPeople($searchStr);
                break;
            case 3: break;
            case 4: 
				$searchResult = $this->searchCommunities($searchStr);
                break;  
            case 5: 
				$searchResult = $this->searchDiseases($searchStr);
                break;
            case 6: 
				$searchResult = $this->searchHashtags($searchStr);
                break;
        }

		if (empty($searchResult)) {
			$searchResult [] = array(
				'Name' => 'empty'
			);
		}

		echo json_encode($searchResult);
        exit();
    }    
    
    /**
     * Function get pending invitation
     */
    public function pendingInvites(){
    
    	$logged_in_user = $this->Auth->user();
    	$pending_invites = $this->MyFriends->getPendingFriendsList($logged_in_user['id']);
    
    	$users = $this->__paginatePendingFriends($pending_invites);
    	$users = $this->__getUsersSearchData($users, true);
    
    	if(isset($this->request->query['ajax'])){
    
    		$this->set(compact('users', 'logged_in_user'));
    		$View = new View($this, false);
    		$this->layout = "ajax";
    		$response['htm_content'] = $View->element('users_row');
    		$response['paginator'] = $this->params['paging']['User'];
    		echo json_encode($response);
    		exit;
    	}else{
    		$this->__loadPendingInvitesDefaultPage($users, $logged_in_user);
    	}
    
    }
    
    /**
     * Function to paginate pending invitation
     * @param int $pending_invites
     * @param int $MAX_RESULT_COUNT
     * @return multitype:
     */
    private function __paginatePendingFriends($pending_invites, $MAX_RESULT_COUNT = 10){
    
        $this->Paginator->settings = array(
            'joins' => array(
                array('table' => 'countries',
                    'alias' => 'Country',
                    'type' => 'INNER',
                    'conditions' => 'Country.id = User.country'
                ),
                array(
                    'table' => 'states',
                    'alias' => 'State',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.state = State.id'
                    )
                ),
                array(
                    'table' => 'cities',
                    'alias' => 'City',
                    'type' => 'INNER',
                    'conditions' => 'City.id = User.city'
                )
            ),
            'conditions' => array(
                'User.is_admin != 1',
                'User.id' => $pending_invites
            ),
            'fields' => array(
                'User.username',
                'User.id',
                'User.type',
                'User.privacy_settings',
                'Country.short_name',
                'State.description',
                'City.description'
            ),
            'limit' => $MAX_RESULT_COUNT
        );
        $users = $this->paginate('User');
        return $users;
    }


     /**
     * Function to implement recommended friends 
     * 
     */
    public function recommendedFriends() {         

        $logged_in_user = $this->Auth->user();        
        $recommended_users = $this->RecommendedFriend->paginateRecommendedFriends($logged_in_user["id"]);

        $recommended_users = $this->__getUsersSearchData($recommended_users);
        $paginate = $this->params ['paging'] ['User'];          	
        
        $view = new View($this, false);
        echo $view->element('recommended_friends',compact('recommended_users', 'logged_in_user', 'paginate'));

        
        $this->autoRender = false;
        
    }

    /**
     * Function to load default page for pending invitation
     * @param array $users
     * @param array $logged_in_user
     */
    private function __loadPendingInvitesDefaultPage($users, $logged_in_user) {
    	$pending_invitations = true;
    
    	$this->set ( compact ( 'users', 'logged_in_user', 'pending_invitations' ) );
    	$View = new View ( $this, false );
    	$results = $View->element ( 'users_row_pending' );
    	$this->set ( compact ( 'results' ) );
    	$paginate = $this->params ['paging'] ['User'];
    
    	// setting result count in header
    	if ($paginate ['count'] > 1) {
    		$header = "Respond to Your " . $paginate ['count'] . " Friend Requests";
    	} else if ($paginate ['count'] == 1) {
    		$header = "Respond to Your " . $paginate ['count'] . " Friend Request";
    	}
    	// adding more button to the view
    	if ($paginate ['nextPage'] == true) {
    		$moreButton = '<div id="more_button2" class="block"><a href="javascript:load_more_pending_friends(2)" id="load-more" class="btn btn_more pull-right more-arrow ladda-button" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a></div>';
    	} else {
    		$moreButton = "";
    	}
    
    	// setting class name for selected category
    	$searchClass = null;
    	$type = "people";
    	$this->set ( compact ( 'results', 'moreButton', 'header', 'type', 'searchClass', 'paginate' ) );
    }
    
    /**
     * Function to search communities
     * @param String $searchStr
     */
    public function searchCommunities($searchStr = NULL, $MAX_RESULT_COUNT = 5){
        $searchStr = addslashes($searchStr);
    	if($searchStr!== NULL && $searchStr != '') {
    		$communities = $this->__paginateCommunity($searchStr, $MAX_RESULT_COUNT);
    		$paginate = $this->params['paging']['Community'];
    	} else {
    		$communities = null;
    		$paginate = array (
    				'nextPage' => false,
    				'count' => 0
    		);
    	}
    
    	$community_type = 0;
    	$this->set ( compact ( 'communities', 'community_type' ) );
    	$View = new View ( $this, false );
    
    	if (isset ( $this->request->query ['term'] )) {
    		// get community serarch results in header
    		return $this->__getHeaderCommunitySearch ( $searchStr, $communities, $paginate );
    	} else if (isset ( $this->request->query ['ajax'] )) {
    			
    		$this->layout = "ajax";
    		$response ['htm_content'] = $View->element ( 'Community.community_row' );
    		$response ['paginator'] = $paginate;
    		echo json_encode ( $response );
    		exit ();
    	} else {
    		// load default page for searchCommunity
    		$this->__loadSearchCommunityDefaultPage ( $searchStr, $paginate );
    	}
    }
    
    /**
     * Function to search all using the keyword
     * @param String $searchStr
     * @param Int    $MAX_RESULT_COUNT
     */
    public function searchAll($searchStr = NULL, $MAX_RESULT_COUNT = 5) {
        $logged_in_user = $this->Auth->user();
        $searchStr = addslashes($searchStr);
        if ($searchStr !== NULL && $searchStr != '') {


            $paginator = $this->__paginateAll($searchStr, $logged_in_user, $MAX_RESULT_COUNT);
            $users = $this->paginate('User');
            $communities = $this->paginate('Community');
            $diseases = $this->paginate('Disease');
            $hashtags = $this->paginate('Hashtag');
            // add Disease names to each user
            $users = $this->__getUsersSearchData($users);
        } else {
            $communities = array();
            $users = array();
            $diseases = array();
            $hashtags = array();
            $paginate = array(
                'nextPage' => false,
                'count' => 0
            );
        }
        
        $community_type = 0;
        $search_people = 0;
        $disease_type = 0;
        $hashtag_type = 0;
        $type = "all";
        $searchClass = 'all';
        $this->set(compact('communities','search_people' , 'community_type', 'users', 'searchStr', 'searchClass', 'type', 'diseases', 'disease_type', 'hashtags', 'hashtag_type'));
    }
    /**
     * Function for paginate Community in searchCommunity
     *
     * @param string $searchStr
     * @param int $MAX_RESULT_COUNT
     * @return multitype:communities
     */
    private function __paginateCommunity($searchStr = NULL, $MAX_RESULT_COUNT) {
        $this->Community->unbindModel(array(
            'hasMany' => array(
                'CommunityDisease',
                'CommunityMember',
                'Event'
            )
        ));
        $this->Community->bindModel(array(
            'hasOne' => array(
                'City' => array(
                    'className' => 'City',
                    'foreignKey' => false,
                    'type' => 'INNER',
                    'conditions' => array(
                        'Community.city = City.id'
                    )
                ),
                'State' => array(
                    'className' => 'State',
                    'foreignKey' => false,
                    'type' => 'INNER',
                    'conditions' => array(
                        'Community.state = State.id'
                    )
                )
            )
                ), false);

        $this->Paginator->settings = array(
            'joins' => array(
                array(
                    'table' => 'community_diseases',
                    'alias' => 'CommunityDisease',
                    'type' => 'LEFT',
                    'conditions' => 'Community.id = CommunityDisease.Community_id'
                ),
                array(
                    'table' => 'diseases',
                    'alias' => 'Disease',
                    'type' => 'LEFT',
                    'conditions' => 'Disease.id = CommunityDisease.disease_id'
                )
            ),
            'conditions' => array(
                'OR' => array(
                    'Community.name LIKE' => "%{$searchStr}%",
                    'FIND_IN_SET(\'' . $searchStr . '\',tags)',
                    'Community.description LIKE' => "%{$searchStr}%",
                    'Disease.name LIKE' => "%{$searchStr}%",
                    'State.description LIKE' => "%{$searchStr}%",
                    'City.description LIKE' => "%{$searchStr}%",
                    'Community.zip LIKE' => "%{$searchStr}%"
                )
            ),
            'fields' => array(
                'Community.id',
                'Community.name',
                'Community.description',
                'Community.member_count',
                'Community.discussion_count'
            ),
            'group' => array(
                'Community.id'
            ),
            'order' => array(
                'Community.name' => 'ASC',
                'Community.tags' => 'ASC',
                'Community.description' => 'ASC',
                'Disease.name' => 'ASC'
            ),
            'limit' => $MAX_RESULT_COUNT
        );
        return $communities = $this->paginate('Community');
    }

    /**
     *Function to search Community search on header
     * @param unknown $searchStr
     * @param unknown $communities
     * @param unknown $paginate
     */
    private function __getHeaderCommunitySearch($searchStr, $communities, $paginate) {
    	$items = array ();
    	if (! empty ( $communities )) {
    			
    		$paginate = $this->params ['paging'] ['Community'];
    			
    		if ($paginate ['nextPage'] == true) {
    			$items [] = array (
    					'LogoUrl' => '',
    					'Name' => 'More',
    					'Url' => '/search?type=community&keyword=' . $searchStr,
    					'Disc' => 'Community',
    					'Style' => 'media-object'
    			);
    		} else {
    			$items [] = array (
    					'LogoUrl' => '',
    					'Name' => 'More',
    					'Url' => '',
    					'Disc' => 'Community',
    					'Style' => 'media-object'
    			);
    		}
    		foreach ( $communities as $community ) {
    			$items [] = array (
    					'LogoUrl' => Common::getCommunityThumb ( $community ['Community'] ['id'] ),
    					'Name' => $community ['Community'] ['name'],
    					'Url' => '/community/details/index/' . $community ['Community'] ['id'],
    					'Disc' => $community ['Community'] ['description'],
    					'Style' => 'media-object',
                                        'Type' => 'community'
    			);
    		}
    	} 
            
        return $items;
    }    
     
    /**
     * Function to load the default page for serachCommunity
     *
     * @param string $searchStr
     * @param Array $paginate
     */
    private function __loadSearchCommunityDefaultPage($searchStr = NULL, $paginate) {
    	$View = new View ( $this, false );
    	$results = $View->element ( 'Community.community_row');
    	$this->set ( compact ( 'results' ) );
    
    	// setting result count in header
    	if ($paginate ['count'] > 1) {
    		$header = $paginate ['count'] . " results for <strong style='font-family: OpenSans-bold;'> " . stripslashes(htmlspecialchars($searchStr)) . " </strong>";
    	} else {
    		$header = $paginate ['count'] . " result for <strong style='font-family: OpenSans-bold;'> " . stripslashes(htmlspecialchars($searchStr)) . " </strong>";
    	}
    
    	// adding more button to the view
    	if ($paginate ['nextPage'] == true) {
    		$moreButton = '<div id="more_button2" class="block"><a href="javascript:load_more_items(2)" id="load-more" class="btn btn_more pull-right more-arrow ladda-button" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a></div>';
    	} else {
    		$moreButton = "";
    	}
    
    	// setting class name for selected category
    	$searchClass = 'community_search';
    	$type = "community";
    	$this->set ( compact ( 'results', 'moreButton', 'searchStr', 'header', 'type', 'searchClass' ) );
    }
    
    /**
     * Function to search Members
     *
     * @param String $searchStr
     */
    public function searchPeople($searchStr = NULL, $MAX_RESULT_COUNT = 5) {
    	$logged_in_user = $this->Auth->user();
        $searchStr = addslashes($searchStr);
    	if($searchStr!== NULL && $searchStr != '') {
    		
    		$users = $this->__paginateUser($searchStr, $MAX_RESULT_COUNT);
    		$paginate = $this->params ['paging'] ['User'];
    			
    		// add Disease names to each user
    		$users = $this->__getUsersSearchData($users);
    	} else if ($searchStr == '') {
    		$users = null;
    		$paginate = array (
    				'nextPage' => false,
    				'count' => 0
    		);
    	}
    
    	if (isset ( $this->request->query ['term'] )) {
    		// get people serarch results in header
    		return $this->__getHeaderPeopleSearch ( $searchStr, $users, $paginate );
    	} else if (isset ( $this->request->query ['ajax'] )) {
    			
    		$this->set ( compact ( 'users' ) );
    		$View = new View ( $this, false );
    			
    		$this->layout = "ajax";
    		$response ['htm_content'] = $View->element ( 'users_row' );
    		$response ['paginator'] = $paginate;
    		echo json_encode ( $response );
    		exit ();
    	} else {
    		// load the default page
    		$this->__loadSearchPeopleDefaultPage ( $searchStr, $users, $paginate, $logged_in_user );
    	}
    }
    
      /**
     * Function for paginate user in searchPeople
     *
     * @param string $searchStr
     * @param array $logged_in_user
     * @return multitype:pagitnate
     */
    private function __paginateAll($searchStr = NULL, $logged_in_user, $MAX_RESULT_COUNT) {
        $logged_in_user = $this->Auth->user();

        $this->Community->unbindModel(array(
            'hasMany' => array(
                'CommunityDisease',
                'CommunityMember',
                'Event'
            )
        ));
        $this->Community->bindModel(array(
            'hasOne' => array(
                'City' => array(
                    'className' => 'City',
                    'foreignKey' => false,
                    'type' => 'INNER',
                    'conditions' => array(
                        'Community.city = City.id'
                    )
                ),
                'State' => array(
                    'className' => 'State',
                    'foreignKey' => false,
                    'type' => 'INNER',
                    'conditions' => array(
                        'Community.state = State.id'
                    )
                )
            )
                ), false);
		/**
		 * commented condition as said by client ( removed from query )
		 *                         'CONCAT_WS(" ", User.first_name,User.last_name) LIKE' => "%{$searchStr}%",
		 *                       'User.first_name LIKE' => "%{$searchStr}%",
		 *                        'User.last_name LIKE' => "%{$searchStr}%"
		 */
        $this->Paginator->settings = array(
            'User' => array(
                'joins' => array(
                    array(
                        'table' => 'countries',
                        'alias' => 'Country',
                        'type' => 'INNER',
                        'conditions' => 'Country.id = User.country'
                    ),
                    array(
                        'table' => 'states',
                        'alias' => 'State',
                        'type' => 'INNER',
                        'conditions' => array('User.state = State.id')
                    ),
                    array('table' => 'cities',
                        'alias' => 'City',
                        'type' => 'INNER',
                        'conditions' => 'City.id = User.city'
                    )
                ),
                'conditions' => array(
					'User.id !=' => $this->Auth->user('id'),
					'User.is_admin != 1',
					'User.username LIKE' => "%{$searchStr}%",
					'OR' => $this->__getUserSearchPrivacyCondition(),
				),
                'fields' => array('User.username', 'User.id', 'User.type', 'User.privacy_settings',
                    'Country.short_name', 'State.description', 'City.description'),
                'limit' => $MAX_RESULT_COUNT,
                'order' => array('User.username' => 'asc'),
            ),
            'Community' => array(
                'joins' => array(
                    array(
                        'table' => 'community_diseases',
                        'alias' => 'CommunityDisease',
                        'type' => 'LEFT',
                        'conditions' => 'Community.id = CommunityDisease.Community_id'
                    ),
                    array(
                        'table' => 'diseases',
                        'alias' => 'Disease',
                        'type' => 'LEFT',
                        'conditions' => 'Disease.id = CommunityDisease.disease_id'
                    )
                ),
                'conditions' => array(
                    'OR' => array(
                        'Community.name LIKE' => "%{$searchStr}%",
                        'FIND_IN_SET(\'' . $searchStr . '\',tags)',
                        'Community.description LIKE' => "%{$searchStr}%",
                        'Disease.name LIKE' => "%{$searchStr}%",
                        'State.description LIKE' => "%{$searchStr}%",
                        'City.description LIKE' => "%{$searchStr}%",
                        'Community.zip LIKE' => "%{$searchStr}%"
                    )
                ),
                'fields' => array(
                    'Community.id',
                    'Community.name',
                    'Community.description',
                    'Community.member_count',
                    'Community.discussion_count'
                ),
                'group' => array(
                    'Community.id'
                ),
                'order' => array(
                    'Community.name' => 'ASC',
                    'Community.tags' => 'ASC',
                    'Community.description' => 'ASC',
                    'Disease.name' => 'ASC'
                ),
                'limit' => $MAX_RESULT_COUNT
        ),
         'Disease' => array(
             'joins' => array(
                array(
                    'table' => 'community_diseases',
                    'alias' => 'CommunityDisease',
                    'type' => 'LEFT',
                    'conditions' => 'Disease.id = CommunityDisease.disease_id'
                ),
                 array(
                    'table' => 'communities',
                    'alias' => 'Community',
                    'type' => 'LEFT',
                    'conditions' => 'Community.id = CommunityDisease.Community_id'
                )
            ),
            'conditions' => array(
                'OR' => array(
                    'Disease.name LIKE' => "%{$searchStr}%",
                    'FIND_IN_SET(\'' . $searchStr . '\',tags)',
                    'Disease.description LIKE' => "%{$searchStr}%",
                    'Disease.library LIKE' => "%{$searchStr}%"
                )
            ),
            'fields' => array(
                'Disease.id',
                'Disease.name',
                'Disease.parent_id',
                'Disease.description',
                'Disease.library'
            ),
            'group' => array(
                'Disease.id'
            ),
            'order' => array(
                'Disease.name' => 'ASC',
                'Disease.description' => 'ASC'
            ),
            'limit' => $MAX_RESULT_COUNT
        ),
         'Hashtag' => array(
              'conditions' => array(
                'OR' => array(
                    'Hashtag.tag_name LIKE' => "%{$searchStr}%",
//                    'FIND_IN_SET(\'' . $searchStr . '\',tags)',
//                    'Disease.description LIKE' => "%{$searchStr}%",
//                    'Disease.library LIKE' => "%{$searchStr}%"
                   // 'Community.name LIKE' => "%{$searchStr}%"
                )
            ),
            'fields' => array(
                'Hashtag.*'
            ),
            'group' => array(
                'Hashtag.id'
            ),
            'order' => array(
                'Hashtag.tag_name' => 'ASC'
            ),
            'limit' => $MAX_RESULT_COUNT
            )
        );

        return $this->Paginator;
    }
    
    /**
     * Function for paginate user in searchPeople
     *
     * @param string $searchStr
     * @param array $logged_in_user
     * @return multitype:pagitnate
     */
    private function __paginateUser($searchStr, $MAX_RESULT_COUNT) {
    	$this->Paginator->settings = array (
    			'joins' => array (
    					array (
    							'table' => 'countries',
    							'alias' => 'Country',
    							'type' => 'INNER',
    							'conditions' => 'Country.id = User.country'
    					),
    					array(
    							'table' => 'states',
    							'alias' => 'State',
    							'type'=>'INNER',
    							'conditions' => array('User.state = State.id')
    					),
    					array( 'table' => 'cities',
    							'alias' => 'City',
    							'type' => 'INNER',
    							'conditions' => 'City.id = User.city'
    					)
    						
    			),
				'conditions' => array(
					'User.id !=' => $this->Auth->user('id'),
					'User.is_admin != 1',
					'User.username LIKE' => "%{$searchStr}%",
					'OR' => $this->__getUserSearchPrivacyCondition()
				),
    			'fields' => array('User.username', 'User.id', 'User.type', 'User.privacy_settings',
    					'Country.short_name', 'State.description', 'City.description'),
    					'limit' =>$MAX_RESULT_COUNT,
    					'order' => array('User.username' => 'asc'),
    	);
    		
    	$users = $this->paginate('User');
    
    	return $users;
    }

	/**
	 * Function to get the condition to check based on search privacy of user
	 * 
	 * @return array
	 */
	private function __getUserSearchPrivacyCondition() {
		$currentUserId = $this->Auth->user('id');
		$friendIds = $this->MyFriends->getUserConfirmedFriendsIdList($currentUserId);
		$condition = array(
			array(
				'User.searchable_by' => UserPrivacySettings::PRIVACY_FRIENDS,
				'User.id' => $friendIds
			),
			array(
				'User.searchable_by' => UserPrivacySettings::PRIVACY_PUBLIC
			)
		);
		return $condition;
	}

	/**
	 * Function to get the data of the users for search results
	 * 
	 * @param array $users
	 * @param bool $isInvite
	 * @return array
	 */
	private function __getUsersSearchData($users = NULL, $isInvite = false) {
		$usersData = array();
		$currentUserId = (int) $this->Auth->user('id');
		foreach ($users as $user) {
			$userId = (int) $user['User']['id'];
			if ($isInvite === true) {
				$user['status'] = MyFriends::STATUS_REQUEST_RECIEVED;
			}

			if ($userId !== $currentUserId) {
				$viewDisease = false;
				$privacySettings = unserialize($user['User']['privacy_settings']);
				unset($user['User']['privacy_settings']);
				if (!empty($privacySettings['view_your_disease'])) {
					$viewDiseasePermittedTo = (int) $privacySettings['view_your_disease'];
				} else {
					$viewDiseasePermittedTo = UserPrivacySettings::PRIVACY_FRIENDS;
				}

				if ($viewDiseasePermittedTo === UserPrivacySettings::PRIVACY_PUBLIC) {
					$viewDisease = true;
				} elseif ($viewDiseasePermittedTo === UserPrivacySettings::PRIVACY_FRIENDS) {
					if (!isset($user['status'])) {
						$user['status'] = (int) $this->MyFriends->getFriendStatus($currentUserId, $userId);
					}
					if ($user['status'] === MyFriends::STATUS_CONFIRMED) {
						$viewDisease = true;
					}
				}
			} else {
				$viewDisease = true;
			}

			if ($viewDisease === true) {
				$user['disease'] = $this->User->getUserDiseases($user['User']['id']);
			} else {
				$user['disease'] = null;
			}

			if (!isset($user['status'])) {
				$user['status'] = (int) $this->MyFriends->getFriendStatus($currentUserId, $userId);
			}

			$usersData[] = $user;
		}

		return $usersData;
	}
    
    /**
     * Function to get people serarch result in header
     *
     * @param String $searchStr
     * @param Array $users
     * @param Array $paginate
     */
    private function __getHeaderPeopleSearch($searchStr, $users, $paginate) {
    	$items = array ();
    	if (! empty ( $users )) {
    			
    		$paginate = $this->params ['paging'] ['User'];
    			
    		if ($paginate ['nextPage'] == true) {
    			$items [] = array (
    					'LogoUrl' => '',
    					'Name' => 'More',
    					'Url' => '/search?type=people&keyword=' . $searchStr,
    					'Disc' => 'People',
    					'Style' => 'border_patient'
    			);
    		} else {
    			$items [] = array (
    					'LogoUrl' => '',
    					'Name' => 'More',
    					'Url' => '',
    					'Disc' => 'People',
    					'Style' => 'border_patient'
    			);
    		}
    		foreach ( $users as $user ) {
    			switch ($user ['User'] ['type']) {
    				case '1' :
    					$border = 'border_patient ';
    					break;
    				case '2' :
    					$border = 'border_family ';
    					break;
    				case '3' :
    					$border = 'border_caregiver ';
    					break;
    				case '4' :
    					$border = 'border_other ';
    					break;
    			}
    
    			$items [] = array(
    					'LogoUrl' => Common::getUserThumb($user['User']['id'],$user['User']['type'],'x_small', '', 'src'),
    					'Name' => $user['User']['username'],
    					'Url' => Common::getUserProfileLink(  $user['User']['username'], true),
    					'Disc' => h($user['disease']),
    					'Style' => 'media-object ' . $border,
                                        'Type' => 'member'
    			);
    		}
    	} 

        return $items;
    }
     
    /**
     * Function to load the default page for serachPeople
     * @param String $searchStr
     * @param Array $users
     * @param Array $paginate
     */
    private function __loadSearchPeopleDefaultPage($searchStr, $users, $paginate, $logged_in_user){
    
    	$search_people = true;
    	$this->set(compact('users', 'logged_in_user', 'search_people'));
    	$View = new View($this, false);
    	$results = $View->element('users_row');
    	$this->set(compact('results'));
    
    	//setting result count in header
    	if($paginate['count'] > 1) {
    		$header = $paginate['count'] . " results for <strong> ".stripslashes(htmlspecialchars($searchStr))." </strong>";
    	}else{
    		$header = $paginate['count'] . " result for <strong> ".stripslashes(htmlspecialchars($searchStr))." </strong>";
    	}
    		
    	// adding more button to the view
    	if($paginate['nextPage'] == true) {
    		$moreButton = '<div id="more_button2" class="block"><a href="javascript:load_more_items(2)" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a></div>';
    	}else {
    		$moreButton ="";
    	}
    	//setting class name for selected category
    	$searchClass = 'people_search';
    	$type = "people";
    	$this->set(compact('results', 'moreButton', 'searchStr', 'header', 'type', 'searchClass', 'paginate'));
    	
    }
    
    /**
     * Function to search Members
     * @param String $searchStr
     */
    public function searchPeopleAdvanced($searchStrName = NULL, $searchStrAge = NULL, $searchStrGender = NULL, $searchStrDisease = NULL, $searchStrLocation = NULL, $searchStrSymptoms = NULL, $searchStrTreatment = NULL, $MAX_RESULT_COUNT = 5) {
        $logged_in_user = $this->Auth->user();
        $searchStrName = addslashes($searchStrName);
        if (($searchStrName !== NULL && $searchStrName != '') || ($searchStrAge !== NULL && $searchStrAge != '') ||
                ($searchStrGender !== NULL && $searchStrGender != '') || ($searchStrDisease !== NULL && $searchStrDisease !== '') || ($searchStrLocation !== NULL && $searchStrLocation !== '')
			|| ($searchStrSymptoms !== NULL && $searchStrSymptoms !== '') || ($searchStrTreatment !== NULL && $searchStrTreatment !== '')) {

            $users = $this->__paginateUserAdvanced($searchStrName, $searchStrAge, $searchStrGender, $searchStrDisease, $searchStrLocation, $searchStrSymptoms, $searchStrTreatment, $logged_in_user, $MAX_RESULT_COUNT);
            $paginate = $this->params['paging']['User'];
				
				// add Disease names to each user
			$users = $this->__getUsersSearchData($users);
		} else if ($searchStrName == '' && $searchStrGender == '' && $searchStrAge == '' && $searchStrDisease == '' && $searchStrLocation == '' && $searchStrSymptoms == '' && $searchStrTreatment == '') {
			$users = null;
			$paginate = array (
					'nextPage' => false,
					'count' => 0 
			);
		}
		if (isset ( $this->request->query ['term'] )) {
			// get people serarch results in header
			return $this->__getHeaderPeopleSearch ( $searchStrName, $users, $paginate );
		} else if (isset ( $this->request->query ['ajax'] )) {
			
			$this->set ( compact ( 'users' ) );
			$View = new View ( $this, false );
			
			$this->layout = "ajax";
			$response ['htm_content'] = $View->element ( 'users_row' );
			$response ['paginator'] = $paginate;
			echo json_encode ( $response );
			exit ();
		} else {
			// load the default page
			$this->__loadSearchPeopleAdvancedDefaultPage ( $searchStrName, $searchStrAge, $searchStrGender, $searchStrDisease, $searchStrLocation, $searchStrSymptoms, $searchStrTreatment, $users, $paginate, $logged_in_user );
		}
    }

    /**
     * Function for paginate user in searchPeople
     * @param string $searchStrName, $searchStrAge, $searchStrGender
     * @param array $logged_in_user
     * @param array $searchStrDisease, $searchStrLocation, $searchStrSymptoms, $searchStrTreatment
     * @return multitype:pagitnate
     */
    private function __paginateUserAdvanced($searchStrName = NULL, $searchStrAge = NULL, $searchStrGender = NULL, $searchStrDisease = NULL, $searchStrLocation = NULL, $searchStrSymptoms, $searchStrTreatment, $logged_in_user, $MAX_RESULT_COUNT) {
		$logged_in_user = $this->Auth->user ();
		$conditions = array(
			'User.id !=' => $logged_in_user['id'],
			'User.is_admin != 1',
			'User.username LIKE' => "%{$searchStrName}%",
			'OR' => $this->__getUserSearchPrivacyCondition()
		);
		
		if (!empty($searchStrGender)) {
			if ($searchStrGender === "B") {
				$genderQuery = '(User.gender = "M" OR User.gender = "F")';
			} else {
				$genderQuery = array(
					'User.gender = ' => "{$searchStrGender}"
				);
			}
			$conditions[] = $genderQuery;
		}
		
		if (!empty($searchStrAge)) {
			(int) $searchStrAge++;
			$ageFrom = (int) $searchStrAge * 10;
			$ageTo = ((int) ($searchStrAge) + 1) * 10;
			$ageQuery = "YEAR( CURDATE( ) ) - YEAR( `User.date_of_birth` ) 
			BETWEEN '{$ageFrom}' and '{$ageTo}'";
			$agesQuery = array(
				$ageQuery
			);
			$conditions[] = $agesQuery;
		}
			
		if (!empty($searchStrDisease)) {
			$conditions['Disease.disease_id'] = $searchStrDisease;
		}
		if (!empty($searchStrLocation)) {
			$conditions['searchedCity.id'] = $searchStrLocation;
		}
		if (!empty($searchStrSymptoms)) {
			$conditions['Symptoms.symptom_id'] = $searchStrSymptoms;
		}
		if (!empty($searchStrTreatment)) {
			$conditions['UserTreatment.treatment_id'] = $searchStrTreatment;
		}
	
		$this->Paginator->settings = array (
				'joins' => array (
						array (
								'table' => 'countries',
								'alias' => 'Country',
								'type' => 'INNER',
								'conditions' => 'Country.id = User.country' 
						),
						array (
								'table' => 'states',
								'alias' => 'State',
								'type' => 'INNER',
								'conditions' => array (
										'User.state = State.id' 
								) 
						),
						array (
								'table' => 'cities',
								'alias' => 'City',
								'type' => 'INNER',
								'conditions' => 'City.id = User.city' 
						),
						array (
								'table' => 'patient_diseases',
								'alias' => 'Disease',
								'type' => 'LEFT',
								'conditions' => 'User.id = Disease.patient_id' 
						),
						array (
								'table' => 'cities',
								'alias' => 'searchedCity',
								'type' => 'LEFT',
								'conditions' => 'User.city = searchedCity.id' 
						),
						array (
								'table' => 'user_symptoms',
								'alias' => 'Symptoms',
								'type' => 'LEFT',
								'conditions' => 'User.id = Symptoms.user_id' 
						),
						array (
								'table' => 'user_treatments',
								'alias' => 'UserTreatment',
								'type' => 'LEFT',
								'conditions' => 'User.id = UserTreatment.user_id' 
						)                                     
				),
				'conditions' => $conditions,
				'fields' => array (
						'User.username',
						'User.id',
						'User.type',
						'User.privacy_settings',
						'User.city',
						'User.date_of_birth',
						'Country.short_name',
						'State.description',
						'City.description',
						'User.gender',
						'searchedCity.id',
						'City.id',
						'Disease.disease_id',
						'Disease.patient_id',
						'Symptoms.user_id',
						'Symptoms.symptom_id',
						'UserTreatment.treatment_id' 
				),
				'group' => 'User.id',
				'limit' => $MAX_RESULT_COUNT,
				'order' => array (
						'User.username' => 'asc' 
				) 
		);
		
		$users = $this->paginate ( 'User' );
		
		return $users;
	}
	
	/**
	 * Function to get the diagnosis form for advanced search
	 */
	public function getDiagnosisForm() {
		$index = $this->request->data ['index'];
		$options = array (
				'label' => false,
				'div' => false 
		);
		$view = new View ( $this, false );
		echo $view->element ( 'diagnosis_form', compact ( 'index', 'options' ) );
		$this->autoRender = false;
	}
	
	/**
	 * Function to get the location form for advanced search
	 */
	public function getLocationForm() {
		$index = $this->request->data ['index'];
		$options = array (
				'label' => false,
				'div' => false 
		);
		$view = new View ( $this, false );
		echo $view->element ( 'location_form', compact ( 'index', 'options' ) );
		$this->autoRender = false;
	}
	
	/**
	 * Function to get the symptoms form for advanced search
	 */
	public function getSymptomsForm() {
		$index = $this->request->data ['index'];
		$options = array (
				'label' => false,
				'div' => false 
		);
		$view = new View ( $this, false );
		echo $view->element ( 'symptoms_form', compact ( 'index', 'options' ) );
		$this->autoRender = false;
	}

    /**
     * Function to get the treatment form for advanced search
     */
    public function getTreatmentForm() {
        $index = $this->request->data['index'];
        $options = array(
            'label' => false,
            'div' => false,
        );
        $view = new View($this, false);
        echo $view->element('treatment_form', compact('index', 'options'));
        $this->autoRender = false;
    }

    /**
     * Function to load the default page for serachPeople
     * @param String $searchStr
     * @param Array $users
     * @param Array $paginate
     */
    private function __loadSearchPeopleAdvancedDefaultPage($searchStrName, $searchStrAge, $searchStrGender, $searchStrDisease, $searchStrLocation, $searchStrSymptoms, $searchStrTreatment, $users, $paginate, $logged_in_user) {
	
        $search_people_advanced = true;
        $this->set(compact('users', 'logged_in_user', 'search_people_advanced'));
        $View = new View($this, false);
        $results = $View->element('users_row');
        $this->set(compact('results'));
//		$searchString = $searchStrName . " " . $searchStrAge . " " . $searchStrGender . " " . implode(',',$searchStrDisease) . " " . implode(',',$searchStrLocation[0]) . " " . implode(',',$searchStrSymptoms);
        //setting result count in header
        if ($paginate['count'] > 1) {
            $header = $paginate['count'] . " results "; //for <strong> ".h($searchString)." </strong>";
        } else {
            $header = $paginate['count'] . " result "; //for <strong> ". h($searchString)." </strong>";
        }

        // adding more button to the view
        if ($paginate['nextPage'] == true) {
            $moreButton = '<div id="more_button2" class="block"><a href="javascript:load_more_items_advanced(2)" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a></div>';
        } else {
            $moreButton = "";
        }
        //setting class name for selected category
        $searchClass = 'people_search';
        $type = "people";
        $this->set(compact('results', 'moreButton', 'searchStrName', 'searchStrAge', 'searchStrGender', 'searchStrDisease', 'searchStrLocation', 'searchStrSymptoms', 'searchStrTreatments', 'header', 'type', 'searchClass', 'paginate'));
    }
    
    /**
     * Function to search diseases
     * 
     * @param String $searchStr
     */
    public function searchDiseases($searchStr = NULL, $MAX_RESULT_COUNT = 5){
        $searchStr = addslashes($searchStr);
    	if($searchStr!== NULL && $searchStr != '') {
    		$diseases = $this->__paginateDisease($searchStr, $MAX_RESULT_COUNT);
    		$paginate = $this->params['paging']['Disease'];
    	} else {
    		$diseases = null;
    		$paginate = array (
    				'nextPage' => false,
    				'count' => 0
    		);
    	}
    
    	$this->set ( compact ( 'diseases' ) );
    	$View = new View ( $this, false );
    
    	if (isset ( $this->request->query ['term'] )) {
    		// get disease serarch results in header
    		return $this->__getHeaderDiseaseSearch ( $searchStr, $diseases, $paginate );
    	} else if (isset ( $this->request->query ['ajax'] )) {
    			
    		$this->layout = "ajax";
    		$response ['htm_content'] = $View->element ( 'Disease.disease_row' );
    		$response ['paginator'] = $paginate;
    		echo json_encode ( $response );
    		exit ();
    	} else {
    		// load default page for searchDisease
    		$this->__loadSearchDiseaseDefaultPage ( $searchStr, $paginate );
    	}
    }
    
    /**
     * Function for paginate disease in searchDisease
     *
     * @param string $searchStr
     * @param int $MAX_RESULT_COUNT
     */
    private function __paginateDisease($searchStr = NULL, $MAX_RESULT_COUNT) {
        $this->Paginator->settings = array(
            'joins' => array(
                array(
                    'table' => 'community_diseases',
                    'alias' => 'CommunityDisease',
                    'type' => 'LEFT',
                    'conditions' => 'Disease.id = CommunityDisease.disease_id'
                ),
                 array(
                    'table' => 'communities',
                    'alias' => 'Community',
                    'type' => 'LEFT',
                    'conditions' => 'Community.id = CommunityDisease.Community_id'
                )
            ),
            'conditions' => array(
                'OR' => array(
                    'Disease.name LIKE' => "%{$searchStr}%",
                    'FIND_IN_SET(\'' . $searchStr . '\',tags)',
                    'Disease.description LIKE' => "%{$searchStr}%",
                    'Disease.library LIKE' => "%{$searchStr}%"
                   // 'Community.name LIKE' => "%{$searchStr}%"
                )
            ),
            'fields' => array(
                'Disease.id',
                'Disease.name',
                'Disease.parent_id',
                'Disease.description',
                'Disease.library'
            ),
            'group' => array(
                'Disease.id'
            ),
            'order' => array(
                'Disease.name' => 'ASC',
                'Disease.description' => 'ASC'
            ),
            'limit' => $MAX_RESULT_COUNT
        );
        return $diseases = $this->paginate('Disease');
    }
    
    /**
     *Function to perform Disease search on header
     * 
     * @param unknown $searchStr
     * @param unknown $diseases
     * @param unknown $paginate
     */
    private function __getHeaderDiseaseSearch($searchStr, $diseases, $paginate) {
    	$items = array ();
    	if (! empty ( $diseases )) {
    			
    		$paginate = $this->params ['paging'] ['Disease'];
    			
    		if ($paginate ['nextPage'] == true) {
    			$items [] = array (
    					'LogoUrl' => '',
    					'Name' => 'More',
    					'Url' => '/search?type=disease&keyword=' . $searchStr,
    					'Disc' => 'Conditions',
    					'Style' => 'media-object'
    			);
    		} else {
    			$items [] = array (
    					'LogoUrl' => '',
    					'Name' => 'More',
    					'Url' => '',
    					'Disc' => 'Conditions',
    					'Style' => 'media-object'
    			);
    		}
    		foreach ( $diseases as $disease ) {
    			$items [] = array (
    					//'LogoUrl' => Common::getCommunityThumb ( $disease ['Disease'] ['id'] ),
    					'Name' => h($disease ['Disease'] ['name']),
    					'Url' => Configure::read('Url.condition').'index/' . $disease ['Disease'] ['id'],
    					'Disc' => strip_tags(htmlspecialchars_decode($disease ['Disease'] ['description'])),
    					'Style' => 'media-object',
                                        'Type' => 'condition'
    			);
    		}
    	}
           
        return $items;
    }    

    /**
     * Function to load the default page for serachDisease
     *
     * @param string $searchStr
     * @param Array $paginate
     */
    private function __loadSearchDiseaseDefaultPage($searchStr = NULL, $paginate) {
    	$View = new View ( $this, false );
    	$results = $View->element ( 'Disease.disease_row');
    	$this->set ( compact ( 'results' ) );
    
    	// setting result count in header
    	if ($paginate ['count'] > 1) {
    		$header = $paginate ['count'] . " results for <strong style='font-family: OpenSans-bold;'> " . stripslashes(htmlspecialchars($searchStr)) . " </strong>";
    	} else {
    		$header = $paginate ['count'] . " result for <strong style='font-family: OpenSans-bold;'> " . stripslashes(htmlspecialchars($searchStr)) . " </strong>";
    	}
    
    	// adding more button to the view
    	if ($paginate ['nextPage'] == true) {
    		$moreButton = '<div id="more_button2" class="block"><a href="javascript:load_more_items(2)" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a></div>';
    	} else {
    		$moreButton = "";
    	}
    
    	// setting class name for selected category
    	$searchClass = 'disease_search';
    	$type = "disease";
    	$this->set ( compact ( 'results', 'moreButton', 'searchStr', 'header', 'type', 'searchClass' ) );
    }

	/**
	 * Function to search the city, state, and country of all the locations 
	 */
	public function searchLocations() {
		$this->autoRender = false;
		if (isset($this->request->data['term'])) {
			$searchTerm = $this->request->data['term'];
			$locations = $this->City->searchLocations($searchTerm);
			$result = array();
			if (!empty($locations)) {
				foreach ($locations as $cityId => $locationName) {
					$result[] = array(
						'label' => $locationName,
						'value' => $locationName,
						'id' => $cityId
					);
				}
			}
			echo json_encode($result);
		}
	}
        
        
         
    /**
     * Function to search Hashtags
     * 
     * @param String $searchStr
     */
    public function searchHashtags($searchStr = NULL, $MAX_RESULT_COUNT = 5){
        $searchStr = addslashes($searchStr);
    	if($searchStr!== NULL && $searchStr != '') {
    		$hashtags = $this->__paginateHashtag($searchStr, $MAX_RESULT_COUNT);
    		$paginate = $this->params['paging']['Hashtag'];
    	} else {
    		$hashtags = null;
    		$paginate = array (
    				'nextPage' => false,
    				'count' => 0
    		);
    	}
    
    	$this->set ( compact ( 'hashtags' ) );
    	$View = new View ( $this, false );
    
    	if (isset ( $this->request->query ['term'] )) {
    		// get disease serarch results in header
    		return $this->__getHeaderHashtagSearch ( $searchStr, $hashtags, $paginate );
    	} else if (isset ( $this->request->query ['ajax'] )) {
    			
    		$this->layout = "ajax";
    		$response ['htm_content'] = $View->element ( 'Hashtag.hashtags_row' );
    		$response ['paginator'] = $paginate;
    		echo json_encode ( $response );
    		exit ();
    	} else {
    		// load default page for searchDisease
    		$this->__loadSearchHashtagDefaultPage ( $searchStr, $paginate );
    	}
    }
    
     
    /**
     * Function for paginate disease in searchDisease
     *
     * @param string $searchStr
     * @param int $MAX_RESULT_COUNT
     */
    private function __paginateHashtag($searchStr = NULL, $MAX_RESULT_COUNT) {
        $this->Paginator->settings = array(
            'conditions' => array(
                'OR' => array(
                    'Hashtag.tag_name LIKE' => "%{$searchStr}%",
//                    'FIND_IN_SET(\'' . $searchStr . '\',tags)',
//                    'Disease.description LIKE' => "%{$searchStr}%",
//                    'Disease.library LIKE' => "%{$searchStr}%"
                   // 'Community.name LIKE' => "%{$searchStr}%"
                )
            ),
            'fields' => array(
                'Hashtag.*'
            ),
            'group' => array(
                'Hashtag.id'
            ),
            'order' => array(
                'Hashtag.tag_name' => 'ASC'
            ),
            'limit' => $MAX_RESULT_COUNT
        );
        return $diseases = $this->paginate('Hashtag');
    }
    
    /**
     *Function to perform Hashtag search on header
     * 
     * @param unknown $searchStr
     * @param unknown $diseases
     * @param unknown $paginate
     */
    private function __getHeaderHashtagSearch($searchStr, $hashtags, $paginate) {
    	$items = array ();
    	if (! empty ( $hashtags )) {
    			
    		$paginate = $this->params ['paging'] ['Hashtag'];
    			
    		if ($paginate ['nextPage'] == true) {
    			$items [] = array (
    					'LogoUrl' => '',
    					'Name' => 'More',
    					'Url' => '/search?type=hashtag&keyword=' . $searchStr,
    					'Disc' => 'Hashtags',
    					'Style' => 'media-object'
    			);
    		} else {
    			$items [] = array (
    					'LogoUrl' => '',
    					'Name' => 'More',
    					'Url' => '',
    					'Disc' => 'Hashtags',
    					'Style' => 'media-object'
    			);
    		}
    		foreach ( $hashtags as $hashtag ) {
    			$items [] = array (
    					//'LogoUrl' => Common::getCommunityThumb ( $disease ['Disease'] ['id'] ),
    					'Name' => h('#'.$hashtag ['Hashtag'] ['tag_name']),
    					'Url' => '/hashtag?tag=' . $hashtag ['Hashtag'] ['tag_name'],
    					'Disc' => strip_tags(htmlspecialchars_decode($hashtag ['Hashtag'] ['total_posts'].' posts')),
    					'Style' => 'media-object',
                                        'Type' => 'hashtag'
    			);
    		}
    	}
           
        return $items;
    }
    
    /**
     * Function to load the default page for serachDisease
     *
     * @param string $searchStr
     * @param Array $paginate
     */
    private function __loadSearchHashtagDefaultPage($searchStr = NULL, $paginate) {
    	$View = new View ( $this, false );
    	$results = $View->element ( 'Hashtag.hashtags_row');
    	$this->set ( compact ( 'results' ) );
    
    	// setting result count in header
    	if ($paginate ['count'] > 1) {
    		$header = $paginate ['count'] . " results for <strong style='font-family: OpenSans-bold;'> " . stripslashes(htmlspecialchars($searchStr)) . " </strong>";
    	} else {
    		$header = $paginate ['count'] . " result for <strong style='font-family: OpenSans-bold;'> " . stripslashes(htmlspecialchars($searchStr)) . " </strong>";
    	}
    
    	// adding more button to the view
    	if ($paginate ['nextPage'] == true) {
    		$moreButton = '<div id="more_button2" class="block"><a href="javascript:load_more_items(2)" id="load-more" class="btn btn_more pull-right ladda-button more-arrow" data-style="expand-right" data-size="l" data-spinner-color="#3581ED"><span class="ladda-label">More</span></a></div>';
    	} else {
    		$moreButton = "";
    	}
    
    	// setting class name for selected category
    	$searchClass = 'hash_search';
    	$type = "hashtag";
    	$this->set ( compact ( 'results', 'moreButton', 'searchStr', 'header', 'type', 'searchClass' ) );
    }
	
	/**
     * Function to get all Hash tags in dashboard
     */
	public function getAllHashtags() {
		$trendingTags = $this->Hashtag->getTrendingHashTags(12);
		echo json_encode($trendingTags);
        exit();
	}
}
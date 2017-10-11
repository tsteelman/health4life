<?php

App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
App::import('Model', 'MyFriends');
App::import('Model', 'ArrowchatStatus');
App::uses('UserPrivacySettings', 'Lib');

/**
 * User Model
 *
 */
class User extends AppModel {

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->virtualFields = array(
			'age' => "DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT({$this->alias}.date_of_birth, '%Y')
            - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT({$this->alias}.date_of_birth, '00-%m-%d'))",
			'username_email' => sprintf('CONCAT(%s.username, " (", %s.email, ")")', $this->alias, $this->alias)
		);
	}
	
	/**
	 * User roles
	 */
	const ROLE_PATIENT = 1;
	const ROLE_FAMILY = 2;
	const ROLE_CAREGIVER = 3;
	const ROLE_OTHER = 4;
	const ROLE_ADMIN = 5;
	const ROLE_SUPER_ADMIN = 6;

	/**
	 * Admin user
	 */
	const ADMIN_USER = 1;

	/**
	 * User status
	 */
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;
	const STATUS_BLOCKED = 2;

	/**
	 * Dashboard slideshow enabled/disabled status
	 */
	const DASHBOARD_SLIDESHOW_ENABLED = 1;
	const DASHBOARD_SLIDESHOW_DISABLED = 0;
        
        /**
         * Cover slideshow enabled/disabled status
         */
        const COVER_SLIDESHOW_ENABLED = 1;
        const COVER_SLIDESHOW_DISABLED = 0;

	public $components = array('Email', 'EmailTemplate');
	public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Username is required'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Password is required'
            )
        ),
    );
    public $displayField = 'username';

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }

    /*
     * Function to get password.
     */

    public function getpassword($userId = NULL) {
        $user = $this->find('first', array(
            'conditions' => array('id' => $userId)
        ));
        return $user['User']['password'];
    }

    /*
     * Function to create a user based on the details passed.
     */

    public function createUser($data) {
        // new user
        $this->create();

        // save data
        $this->save($data);

        return true;
    }

    /**
     * Records the current user's activity on every request
     * @return void 
     */
    public function recordActivity() {
        $this->id = AuthComponent::user('id');
        $this->set(array(
            'last_activity' => date('Y-m-d H:i:s'),
            'modified' => $this->field('modified')
        ));

        $this->save(null, array('callbacks' => false, 'validate' => false));
    }

    /*
     * Function to record logged out state.
     */

    public function logout() {
        $this->id = AuthComponent::user('id');
        $this->set(array(
            'last_activity' => NULL,
            'modified' => $this->field('modified')
        ));

        $this->save(null, array('callbacks' => false, 'validate' => false));
    }

    /*
     * Function to check if an user is online
     * 
     * @param array userIds,
     * @return array
     */

    public function checkOnlineUsers($userIds = array(), $loginUserId) {

        $status = array();
        $statusAway = array();
        $statusOnline = array();
        $userStatus = array();		
        
		$this->MyFriends = ClassRegistry::init('MyFriends');
		
        foreach ($userIds as $key => $userId) {
			$diseases = NULL;
//            if ($this->isUserOnline($userId)) {
            $onlineStatusResult = $this->getUserOnlineSatus($userId);
            if (isset($onlineStatusResult) && $onlineStatusResult != NULL && $onlineStatusResult != FALSE) {
                $userStatus = $this->findById($userId, array(
                    'User.id',
                    'User.username',
                    'User.last_activity',
                    'User.type')
                );
				
				$privacy = new UserPrivacySettings($userId);				
				$diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');
				
				if (
						$diseaseViewPermittedTo === $privacy::PRIVACY_PUBLIC || $userId == $loginUserId
				) {
					$diseases = $this->getUserDiseases($userStatus['User']['id']);
				} elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
					$friendStatus = (int) $this->MyFriends->getFriendStatus($loginUserId, $userId);
					if (($friendStatus === MyFriends::STATUS_CONFIRMED)) {
						$diseases = $this->getUserDiseases($userStatus['User']['id']);
					}
				} 
                if($onlineStatusResult == 'available') {
                    $statusOnline[] = array(
                        'friend_id' => $userStatus['User']['id'],
                        'friend_name' => $userStatus['User']['username'],
                        'friend_type' => $userStatus['User']['type'],
                        'user_pic_small' => Common::getUserThumb($userStatus['User']['id'], $userStatus['User']['type'], 'small', '', 'url'),
                        'user_pic_medium' => Common::getUserThumb($userStatus['User']['id'], $userStatus['User']['type'], 'medium', '', 'url'),
                        'diseases' => $diseases,
                        'is_online' => true,
                        'online_status' => $onlineStatusResult//true
                    );
                    unset($userIds[$key]);  
                } elseif($onlineStatusResult == 'away'){
                    $statusAway[] = array(
                        'friend_id' => $userStatus['User']['id'],
                        'friend_name' => $userStatus['User']['username'],
                        'friend_type' => $userStatus['User']['type'],
                        'user_pic_small' => Common::getUserThumb($userStatus['User']['id'], $userStatus['User']['type'], 'small', '', 'url'),
                        'user_pic_medium' => Common::getUserThumb($userStatus['User']['id'], $userStatus['User']['type'], 'medium', '', 'url'),
                        'diseases' => $diseases,
                        'is_online' => true,
                        'online_status' => $onlineStatusResult//true
                    );
                    unset($userIds[$key]);  
                }               
            }
        }
        $status = array_merge($statusOnline, $statusAway);
        $status = $this->addOfflineUsers($userIds, $status);
        return $status;
    }

    /**
     * Function to check if a user is online or not
     * 
     * @param int $userId
     * @return boolean true if online, else false
     */
    public function isUserOnline($userId) {
        $online_timeout = Configure::read('App.onlineTimeout');
        if ($userId > 0) {
            $arrowchat = new ArrowchatStatus();
            $query = array(
                'conditions' => array(
                    'ArrowchatStatus.userid' => $userId,
                    'CAST(' . time() . ' AS SIGNED) - CAST(ArrowchatStatus.session_time AS SIGNED) - 60 < ' . $online_timeout
                )
            );
            $status = $arrowchat->find('count', $query);

            if ($status > 0) {
                return true;
            }
        }

        return false;
    }
    /**
     * Function to check if a user is online or not and get the status
     * 
     * @param int $userId
     * @return status is online else return false
     */
    public function getUserOnlineSatus($userId) {
        $online_timeout = Configure::read('App.onlineTimeout');
        if ($userId > 0) {
            $arrowchat = new ArrowchatStatus();
            $query = array(
                'conditions' => array(
                    'ArrowchatStatus.userid' => $userId,
                    'CAST(' . time() . ' AS SIGNED) - CAST(ArrowchatStatus.session_time AS SIGNED) - 60 < ' . $online_timeout
                )
            );
            $status = $arrowchat->find('first', $query);

            if (!empty($status) && isset($status['ArrowchatStatus']['status'])) {
                return $status['ArrowchatStatus']['status'];
            }
        }

        return false;
    }

    public function addOfflineUsers($userIds = array(), $friends = array()) {

        $count = count($friends);
        $friends_temp = array();

        foreach ($userIds as $userId) {
            if($userId != "") {
                $userStatus = $this->findById($userId, array('User.id', 'User.username', 'User.type'));
                if(is_array($userStatus) && !empty($userStatus)) {
                    $friends_temp[] = array(
                        'friend_id' => $userStatus['User']['id'],
                        'friend_name' => $userStatus['User']['username'],
                        'friend_type' => $userStatus['User']['type'],
                        'user_pic_small' => Common::getUserThumb($userStatus['User']['id'], $userStatus['User']['type'], 'small', '', 'url'),
                        'user_pic_medium' => Common::getUserThumb($userStatus['User']['id'], $userStatus['User']['type'], 'medium', '', 'url'),
                        'diseases' => $this->getUserDiseases($userStatus['User']['id']),
                        'is_online' => false,
                        'online_status' => 'offline'
                    );
                }
            }
        }

        if (!empty($friends_temp)) {
            $friends_temp = Set::sort($friends_temp, '{n}.friend_name', 'asc');
            $friends = array_merge($friends, $friends_temp);
        }
        return $friends;
    }

    /**
     * Function to get count of online users from array of userIds
     * @param array $userIds
     * @return number
     */
    public function getOnlineUserCount($userIds = array()) {
        $count = 0;
        foreach ($userIds as $key => $userId) {
            if ($this->isUserOnline($userId)) {
                $count++;
            }
        }

        return $count;
    }

    /*
     * Function to get user details
     * 
     * @param $userId Integer
     * 
     * @return array
     */

    public function getUserDetails($userId) {
        $user = $this->find('first', array(
            'conditions' => array('User.id' => $userId)
        ));
        if (isset($user) && $user != NULL) {
            $userDetails = array(
                'first_name' => $user['User']['first_name'],
                'last_name' => $user['User']['last_name'],
                'profile_picture' => $user['User']['profile_picture'],
                'user_id' => $user['User']['id'],
                'type' => $user['User']['type'],
                'email' => $user['User']['email'],
                'user_name' => $user['User']['username'],
                'gender' => $user['User']['gender'],
                'last_activity' => $user['User']['last_activity']
            );
        } else {
            $userDetails = Null;
        }
        return $userDetails;
    }

    /**
     * Function to get the data of a list of users
     * 
     * @param array $userIdArray array of user ids
     * @return array
     */
    public function getUsersData($userIdArray) {
        $this->bindModel(array('belongsTo' => array(
                'Country' => array(
                    'className' => 'Country',
                    'foreignKey' => 'country',
                ),
                'State' => array(
                    'className' => 'State',
                    'foreignKey' => 'state',
                ),
                'City' => array(
                    'className' => 'City',
                    'foreignKey' => 'city',
                )
            )), false
        );
        $usersData = $this->find('all', array(
            'conditions' => array(
                'User.id' => $userIdArray
            ),
            'fields' => array(
                'User.id',
                'User.first_name',
                'User.last_name',
                'User.username',
                'User.type',
                'User.about_me',
                'User.is_admin',
                'User.profile_picture',
                'Country.short_name',
                'State.description',
                'City.description',
            )
        ));

        return $usersData;
    }

    /*
     * Function to get all the details of a user @param array $find_by_value @return array
     */

    function getFullUserDetails($find_by_value, $find_by_field = "id") {
        $MyFriends = new MyFriends ();

        $data = $this->find("all", array(
            'joins' => array(
                array(
                    'table' => 'patient_diseases',
                    'alias' => 'PatientDisease',
                    'type' => 'LEFT',
                    'conditions' => 'User.id = PatientDisease.patient_id'
                ),
                array(
                    'table' => 'diseases',
                    'alias' => 'Disease',
                    'type' => 'LEFT',
                    'conditions' => 'Disease.id = PatientDisease.disease_id'
                ),
                array(
                    'table' => 'states',
                    'alias' => 'State',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'User.state = State.id'
                    )
                ),
                array(
                    'table' => 'cities',
                    'alias' => 'City',
                    'type' => 'LEFT',
                    'conditions' => 'City.id = User.city'
                ),
                array(
                    'table' => 'countries',
                    'alias' => 'Country',
                    'type' => 'LEFT',
                    'conditions' => 'Country.id = User.country'
                )
            ),
            'conditions' => array(
                'User.' . $find_by_field => $find_by_value,
            ),
            'fields' => array('User.*',
                "age",
                'group_concat(DISTINCT Disease.id ORDER BY PatientDisease.created ASC, Disease.name ASC SEPARATOR ", ")as diseases_id',
                'group_concat(DISTINCT Disease.name ORDER BY PatientDisease.created ASC, Disease.name ASC SEPARATOR ", ")as diseases',
                'CONCAT_WS(", ", City.description, State.description, Country.short_name) as location',
                ),
            'order' => array('User.username' => 'asc'),
            'group' => array('User.id')
                )
        );
        
        $i = 0;
        if ($find_by_field == "id") {
            foreach ($data as $user) {
                $mutualFriends = $MyFriends->getMutualFriends($find_by_value, $user['User']['id']);
				$data[$i]['mutual_friends_count'] = $mutualFriends;
                $i++;
            }
        }
        return($data);
    }

    /**
     * Function to get the diseases that a user has/associated with
     *
     * @param int $userID
     * @return string
     */
    public function getUserDiseases($userID, $array = FALSE) {

        $diseases = $this->find("all", array(
            'joins' => array(
                array(
                    'table' => 'patient_diseases',
                    'alias' => 'PatientDisease',
                    'type' => 'LEFT',
                    'conditions' => 'User.id = PatientDisease.patient_id'
                ),
                array(
                    'table' => 'diseases',
                    'alias' => 'Disease',
                    'type' => 'LEFT',
                    'conditions' => 'Disease.id = PatientDisease.disease_id'
                )
            ),
            'conditions' => array(
                'User.id = ' . $userID
            ),
            'fields' => array(
                'User.id',
                'PatientDisease.patient_id',
                'Disease.name'
            ),
            'order' => array(
                'PatientDisease.created' => 'asc',
                'User.username' => 'asc',
                'Disease.name' => 'asc'
            ),
            'group' => array(
                'Disease.name', 'Disease.id'
            )
        ));
        $disease_names = "";
        foreach ($diseases as $disease) {
            $disease_names .= ", " . $disease ['Disease'] ['name'];
        }
        if (strlen($disease_names) > 2) {
            $disease_names = substr($disease_names, 2);
        } else {
            $disease_names = "";
        }
        if($array) {
            $disease_array = array();
            foreach ($diseases as $disease) {
				if (!empty($disease['Disease']['name'])) {
					$disease_array[] = $disease['Disease']['name'];
				}
            }
            return $disease_array;
        } else {
            return $disease_names;
        }
    }

    /**
     * Function to get the details of the users by their email
     * 
     * @param array $emailArray
     * @return array
     */
    public function getUsersByEmail($emailArray) {
        return $this->getFullUserDetails($emailArray, 'email');
    }

    /*
     * Function to get recent users.
     * 
     * @return array
     */

    public function getLatestMembers() {
        $members = $this->find('list', array(
            'conditions' => array(
                'User.status' => 1
            ),
            'order' => array('User.created' => 'desc'),
            'limit' => 12,
            'fields' => array('User.id'),
            'group' => array('User.id')
                )
        );
        $member_details = $this->getUsersData($members);
        $i = 0;
        foreach ($member_details as $member) {
            $disease = $this->getUserDiseases($member['User']['id']);
            $member_details[$i]['Disease'] = $disease;
            $i++;
        }
        return $member_details;
    }

    /**
     * Function to get userLocation from userId
     * @param int $userId
     * @param boolean $country
     * @return string
     */
    public function getUserLocation($userId, $country = false) {
        $data = $this->find("first", array(
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
                'User.id' => $userId,
            ),
            'fields' => array(
                'Country.short_name', 'State.description', 'City.description'
            )
                )
        );

        $location = "";
		$locationArray = array();
		if (!empty($data['City']['description'])) {
			$locationArray[] = $data['City']['description'];
		}
		if (!empty($data['State']['description'])) {
			$locationArray[] = $data['State']['description'];
		}
		if ($country && !empty($data['Country']['short_name'])) {
			$locationArray[] = $data['Country']['short_name'];
		}
		if (!empty($locationArray)) {
			$location = join(', ', $locationArray);
		}

        return $location;
    }

    /**
     * Function to get the users who are in the spedified timezones
     * 
     * @param array $timezones
     * @return array
     */
    public function getUsersInTimezones($timezones) {
        $query = array(
            'fields' => array(
                "{$this->alias}.id",
                "{$this->alias}.username",
                "{$this->alias}.email",
                "{$this->alias}.type",
                "{$this->alias}.timezone",
                "{$this->alias}.last_activity"
            ),
            'conditions' => array(
                "{$this->alias}.status" => 1,
                "{$this->alias}.is_admin" => 0,
                "{$this->alias}.timezone" => $timezones,
            )
        );
        return $this->find('all', $query);
    }
	
	/**
     * Function to get the patient users who are in the specified timezones
     * 
     * @param array $timezones
     * @return array
     */
    public function getPatientUsersInTimezones($timezones) {
        $query = array(
            'fields' => array(
                "{$this->alias}.id",
                "{$this->alias}.username",
                "{$this->alias}.email",
                "{$this->alias}.type",
                "{$this->alias}.timezone",
                "{$this->alias}.last_activity"
            ),
            'conditions' => array(
                "{$this->alias}.status" => 1,
                "{$this->alias}.is_admin" => 0,
				"{$this->alias}.type" => self::ROLE_PATIENT,		
                "{$this->alias}.timezone" => $timezones,
            )
        );
        return $this->find('all', $query);
    }
	
	/**
     * Function to get the non patient users who are in the specified timezones
     * 
     * @param array $timezones
     * @return array
     */
    public function getNonPatientUsersInTimezones($timezones) {
        $query = array(
            'fields' => array(
                "{$this->alias}.id",
                "{$this->alias}.username",
                "{$this->alias}.email",
                "{$this->alias}.type",
                "{$this->alias}.timezone",
                "{$this->alias}.last_activity"
            ),
            'conditions' => array(
                "{$this->alias}.status" => 1,
                "{$this->alias}.is_admin" => 0,
				"{$this->alias}.type !=" => self::ROLE_PATIENT,		
                "{$this->alias}.timezone" => $timezones,
            )
        );
        return $this->find('all', $query);
    }

    public function getFavoritePostIds($userId) {
        $favorite_posts = array();
        $favorite_posts_ids = array();
        $favorite_posts_list = array();
        $data = $this->find('first', array(
            'conditions' => array('User.id' => $userId),
            'fields' => array('favorite_posts')
                )
        );
        if (!empty($data)) {

            $favorite_posts = json_decode($data['User']['favorite_posts'], TRUE);
            if (isset($favorite_posts) && $favorite_posts != NULL) {
                $favorite_posts_list = $favorite_posts;
                foreach ($favorite_posts_list as $post) {
                    array_push($favorite_posts_ids, $post ['post_id']);
                }
            }
        }

        return $favorite_posts_ids;
    }

    public function getFavoritePosts($userId) {
        $favorite_posts = array();
        $favorite_posts_list = array();
        $data = $this->find('first', array(
            'conditions' => array('User.id' => $userId),
            'fields' => array('favorite_posts')
                )
        );
        if (!empty($data)) {
            $favorite_posts = json_decode($data['User']['favorite_posts'], TRUE);
            $favorite_posts_list = $favorite_posts;
        }

        return $favorite_posts_list;
    }

    /**
     * Function to get username from userId
     * @param unknown $userId
     */
    public function getUsername($userId) {
        $user = $this->findById($userId);
        return $user['User']['username'];
    }

    public function getTimezone($userId) {
		$user = $this->findById($userId);
		return $user['User']['timezone'];
	}

	/**
	 * Function to save a user's dashboard slideshow enabled/disabled status
	 * 
	 * @param int $userId
	 * @param int $status 
	 * @return boolean
	 */
	public function saveUserDashboardSlideshowStatus($userId, $status) {
		$this->id = $userId;
		return $this->saveField('is_dashboard_slideshow_enabled', $status);
	}
	
	/**
	 * Functioon to unsubscribe news letter 
	 * @param int $id
	 * @param string $action
	 */
	public function unsubscribeNewsletter($id) { 
		$this->id = $id;
		return $this->saveField ( 'newsletter', false );
	}
	
	/**
	 * Functioon to subscribe news letter
	 * @param int $id
	 * @param string $action
	 */
	public function subscribeNewsletter($id) {
		$this->id = $id;
		return $this->saveField ( 'newsletter', true );
	}
	
	/**
	 * Function to check is newsletter subscribed for an email address
	 * @param string $email
	 * @return boolean
	 */
	public function isNewsletterSubscribed($email = NULL) {
		
		if ($email) {
			
			/*
			 * Find user with the email address
			 */
			$user = $this->find ( 'first', array (
					'conditions' => array (
							'email' => $email 
					),
					'fields' => array (
							'newsletter' 
					) 
			) ); 
			
			/*
			 * If the user subscribed newsletter
			 * return true
			 */
			if ($user ['User'] ['newsletter']) {
				
				return true;
			}
		}
		
		return false;
	}
	
	public function getNewsletterSetting($userId){
		
		/*
		 * Find user with user id
		 */
		$user = $this->find ( 'first', array (
				'conditions' => array (
						'id' => $userId 
				),
				'fields' => array (
						'newsletter' 
				) 
		) );
		/*
		 * If the user subscribed newsletter
		* return true
		*/
		if ($user ['User'] ['newsletter']) {
		
			return 1;
		}
		
		return 0;
	}
	
	public function getUserIdFromEmail($email){
		$user = $this->findByEmail($email);
		return ($user['User']['id']);
	}

	/**
	 * Function to block a user
	 * 
	 * @param int $userId
	 */
	public function blockUser($userId) {
		try {
			$this->id = $userId;
			$this->saveField('status', self::STATUS_BLOCKED);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Function to activate a user
	 * 
	 * @param int $userId
	 */
	public function activateUser($userId) {
		try {
			$this->id = $userId;
			$this->saveField('status', self::STATUS_ACTIVE);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Function to block the anonymous permission of a user
	 * 
	 * @param int $userId
	 * @return bool 
	 */
	public function blockAnonymousPermission($userId) {
		$this->id = $userId;
		return $this->saveField('has_anonymous_permission', false);
	}

	/**
	 * Function to enable the anonymous permission of a user
	 * 
	 * @param int $userId
	 * @return bool 
	 */
	public function enableAnonymousPermission($userId) {
		$this->id = $userId;
		return $this->saveField('has_anonymous_permission', true);
	}

	/**
	 * Function to get the inactive users who are in the specified timezones
	 * 
	 * @param array $timezones
	 * @return array
	 */
	public function getInactiveUsersInTimezones($timezones) {
		$query = array(
			'fields' => array(
				"{$this->alias}.id",
				"{$this->alias}.username",
				"{$this->alias}.email",
				"{$this->alias}.created"
			),
			'conditions' => array(
				'OR' => array(
					"{$this->alias}.status" => self::STATUS_INACTIVE,
					"{$this->alias}.status IS NULL"
				),
				"{$this->alias}.is_admin" => 0,
				"{$this->alias}.timezone" => $timezones
			)
		);
		return $this->find('all', $query);
	}

	/**
	 * Function to generate activation link for a user
	 * 
	 * @param array $user
	 * @return string 
	 */
	public function generateActivationLink($user) {
		$link = null;

		// generate activation token
		$now = microtime(true);
		$timelimit = $now + 24 * 3600; // the activation link is good for the next one day
		$tokenData = array(
			'email' => $user['email'],
			'time' => $timelimit
		);
		$token = base64_encode(json_encode($tokenData));


		// save activation token for the user
		$userId = $user['id'];
		$this->id = $userId;
		if ($this->saveField('activation_token', $token)) {
			$link = Router::Url('/', TRUE) . 'user/register/activate/' . $token;
		}

		return $link;
	}

	/**
	 * Function to get the permissions of a user in a profile
	 * 
	 * @param int $userId
	 * @param int $profileId
	 * @return bool
	 */
	public function getUserPermissionsInProfile($userId, $profileId) {
		$permissions = array(
			'anonymous_messaging' => true,
			'messaging' => true,
			'access' => true
		);

		if ((int) $userId !== (int) $profileId) {
			$profileUser = $this->findById($profileId);
			$blockedUsersJSON = $profileUser['User']['blocked_users'];
			if (!empty($blockedUsersJSON)) {
				$blockedUsers = json_decode($blockedUsersJSON, true);
				$permissionKeys = array_keys($permissions);
				foreach ($permissionKeys as $permissionKey) {
					if (!empty($blockedUsers[$permissionKey])) {
						if (in_array($userId, $blockedUsers[$permissionKey])) {
							$permissions[$permissionKey] = false;
						}
					}
				}
			}
		}

		return $permissions;
	}

	/**
	 * Function to get the anonymous permission of a user
	 * 
	 * @param int $userId
	 * @return bool
	 */
	public function getUserAnonymousPermission($userId) {
		$query = array(
			'fields' => array('has_anonymous_permission'),
			'conditions' => array('id' => $userId)
		);
		$user = $this->find('first', $query);
		if (!empty($user)) {
			return $user['User']['has_anonymous_permission'];
		} else {
			return false;
		}
	}

        
        /**
    	 * Function to save a User's cover slideshow enabled/disabled status
	 * 
	 * @param int $userId
	 * @param int $status 
	 * @return boolean
	 */
	public function saveUserCoverSlideshowStatus($userId, $status) {
		$this->id = $userId;
		return $this->saveField('is_cover_slideshow_enabled', $status);
	}
        
	/**
	 * Function to get all the active users with their notification settings
	 * 
	 * @return array
	 */
	public function getAllActiveUsersWithNotificationSettings() {
		$hasOneAssocitaions = array(
			'NotificationSetting' => array(
				'className' => 'NotificationSetting',
				'foreignKey' => 'user_id'
			)
		);
		$this->bindModel(array('hasOne' => $hasOneAssocitaions), false);
		$query = array(
			'fields' => array(
				'User.id',
				'User.username',
				'User.email',
				'User.type',
				'NotificationSetting.id',
				'NotificationSetting.email_settings',
				'NotificationSetting.recommended_users',
				'NotificationSetting.last_recommended_datetime',
				'NotificationSetting.recommend_friends_frequency',
				'NotificationSetting.frequency_changed_datetime'
			),
			'conditions' => array(
				"{$this->alias}.status" => self::STATUS_ACTIVE,
				"{$this->alias}.is_admin" => 0
			)
		);
		return $this->find('all', $query);
	}
}
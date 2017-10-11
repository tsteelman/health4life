<?php

/**
 * FrontAppController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppController', 'Controller');
App::uses('Common', 'Utility');
App::uses('CakeTime', 'Utility');
App::uses('Date', 'Utility');

/**
 * FrontAppController for frontend application.
 *
 * FrontAppController is used as the parent controller for all the controllers
 * in the frontend application.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	app.Controller
 * @category	Controllers
 */
class FrontAppController extends AppController {

    /**
     * Set the theme for the front end application
     *
     * @var string
     */
    public $theme = 'App';
    public $helpers = array('JQValidator.JQValidator', 'AssetCompress.AssetCompress');
    public $components = array(
        'Otp',
        'JQValidator.JQValidator' => array('closestSelector' => 'form-group'),
        'Cookie',
        'Ads',
        'DebugKit.Toolbar',
        'EmailQueue',
        'Session',
        'LogUtil',
        'ImportContacts',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'dashboard', 'action' => 'index', 'plugin' => 'User'),
            'logoutRedirect' => array('controller' => 'pages', 'action' => 'index', 'plugin' => ''),
            'loginAction' => array('controller' => 'users', 'action' => 'login', 'plugin' => 'User'),
            'authError' => 'You must be logged in to view this page.',
            'authenticate' => array(
                'User.User'
            ),
        )
    );

    /**
     * Indicates whether the user's activity was recorded for the current request
     * @var boolean
     */
    private static $activityRecorded = false;

    /**
     * This function is executed before every action in the controller.
     */
    public function beforeFilter() {
        // disable cache to avoid browser back button issues
        $this->disableCache();

        // set cookie options
        $this->Cookie->httpOnly = true;

        $rememberMeCookie = Configure::read('userRememberMeCookieName');
        if (!$this->Auth->loggedIn() && $this->Cookie->read($rememberMeCookie)) {

            $cookie = $this->Cookie->read($rememberMeCookie);

            $this->loadModel('User'); // If the User model is not loaded already
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username' => $cookie['username'],
                    'User.password' => $cookie['password']
                )
            ));

            if ($user && !$this->Auth->login($user['User'])) {
                $this->redirect('/logout'); // destroy session & cookie
            }
        }

        // if the user is not logged in, and auto login token is present
        if (!$this->Auth->loggedIn() && isset($_GET['auto_login_token']) && isset($_GET['email'])) {
            $this->__autoLoginUser();
        }

        // if it is not account activation page, save current page in session
        if (!(($this->request->plugin === 'user' ) &&
                ( $this->request->controller === 'register') &&
                ($this->request->action === 'activate'))) {
            // avoid AJAX pages
            if (!$this->request->is('ajax')) {
                $currentPage = $this->request->here;
                $this->Session->write('currentPage', $currentPage);
            }
        }
    }

    /**
     * Auto login user to the site using a token
     */
    private function __autoLoginUser() {
        $autoLoginToken = $_GET['auto_login_token'];
        $email = $_GET['email'];
        if ($autoLoginToken !== '' && $autoLoginToken !== null && $email !== '' && $email !== null) {
            $email = base64_decode($email);
            if ($this->Otp->authenticateOTP($autoLoginToken, array('email' => $email))) {
                $userData = $this->User->findByEmail($email);
                if (!empty($userData) && isset($userData['User'])) {
                    $user = $userData['User'];
                    $this->Auth->login($user);
					$data = array(
						'id' => $this->Auth->user('id'),
						'last_login_datetime' => date("Y-m-d H:i:s")
					);
					$this->User->save($data);
                    $this->__doAfterAutoLogin();
                }
            }
        }
    }

    /**
     * Function that does the functionalities after auto login
     */
    private function __doAfterAutoLogin() {
        // if promptHealthStatusUpdate is set, show the health status pop up
        if (isset($_GET['promptHealthStatusUpdate'])) {
            $this->Session->write('isFirstLoginToday', true);
        }
    }

    public function afterFilter() {
        $this->recordUserActivity();
        $this->recordUserActivityLog();
    }

    /**
     * Records the current user's activity on every request
     * @return void
     */
    private function recordUserActivity() {
        if (!AuthComponent::user() || self::$activityRecorded || $this->viewClass !== 'View') { // only record for views, not media
            return;
        }
        ClassRegistry::init('User')->recordActivity();

        self::$activityRecorded = true;
    }

    public function isAuthorized($user) {
        return true;
    }

    /**
     * This function is executed before the view is rendered
     */
    public function beforeRender() {
        parent::beforeRender();
        $loggedIn = $this->Auth->loggedIn();
        $header_login = false;
        $is_dashboard_page = false;
        $timezoneOffset = '';
		$notificationMusicSetting = '';
        if ($loggedIn) {
            $username = $this->Auth->user('username');
            $firstName = $this->Auth->user('first_name');
            $loggedin_userid = $this->Auth->user('id');
            $loggedin_user_type = (int) $this->Auth->user('type');
            $loggedin_user_email = $this->Auth->user('email');
            $googleAccessToken = $this->Session->read('google_access_token');
            $fbAccessToken = $this->Session->read('fb_access_token');
            $googleContactLink = Common::getImportContactLink('google', $googleAccessToken);

            $fbContactLink = Common::getImportContactLink('facebook', $fbAccessToken);
            $this->loadModel('MyFriends');
            $pending_friend_requests_count = $this->MyFriends->getFriendsStatusCount($loggedin_userid, MyFriends::STATUS_REQUEST_RECIEVED);
            
            $timezone = $this->Auth->user('timezone');
            $timezoneOffset = Date::getCurrentUserTimezoneOffset($timezone);

            $dashboard_details = $this->setDashboardDetails();
            $this->_promptHealthStatusUpdate();
			
			$notificationMusicSetting = $this->isNotificationMusicEnabled($loggedin_userid);
        } else {
            $show_header_login = $this->showHeaderLoginForm();
            $loggedin_userid = null;
            $loggedin_user_email = null;
        }

        if ($this->request->here == "/dashboard")
            $is_dashboard_page = true;
        // set logged in status and username on view variable
        //To set ads
        $count = $this->getAdsCount();
        $ads = $this->Ads->getAds($count);
        $this->set(compact('ads'));

        $this->set(compact('loggedIn', 'username', 'loggedin_userid', 'loggedin_user_type', 'dashboard_details', 'show_header_login', 'googleContactLink', 'fbContactLink', 'is_dashboard_page', 'firstName', 'ads', 'timezoneOffset', 'loggedin_user_email', 'notificationMusicSetting'
        ));
    }

    /**
     * Function to prompt the user to set the health status if logging in for the
     * first time that day
     */
    protected function _promptHealthStatusUpdate() {
        if ($this->Session->check('isFirstLoginToday')) {
            $promptHealthStatusUpdate = true;
            App::uses('HealthStatus', 'Utility');
            $healthStatusList = HealthStatus::getHealthStatusList();
            $this->Session->delete('isFirstLoginToday');
			$userType = (int) $this->Auth->user('type');
            $this->set(compact('healthStatusList', 'promptHealthStatusUpdate', 'userType'));
        }
    }

    /**
     * Function to Set the Dasboard items list
     *
     * @param null
     * @return array
     */
    public function getDashboardItems() {
        return array(
            "home" => array(
                "url" => "/dashboard",
                "name" => "Dashboard",
                "small_icon" => "dashboard_s_home",
                "large_icon" => "dashboard_l_home",
                "class" => "noicon"
            ),
            "event" => array(
                "url" => "/event",
                "name" => "Events",
                "small_icon" => "dashboard_s_event",
                "large_icon" => "dashboard_l_event",
                "class" => "noicon"
            ),
            "community" => array(
                "url" => "/community",
                "name" => "Community",
                "small_icon" => "dashboard_s_group",
                "large_icon" => "dashboard_l_group",
                "class" => "community"
            ),
             "my_pmr" => array(
                "url" => "http://pmr.qburst.com/",
                "name" => "My PMR",
                "small_icon" => "dashboard_s_mypmr",
                "large_icon" => "dashboard_l_mypmr",
                "class" => "noicon",
                "new_window" => true
            ),    
            "profile" => array(
                "url" => "/profile",
                "name" => "My Profile",
                "small_icon" => "dashboard_s_profile",
                "large_icon" => "dashboard_l_profile",
                "class" => "myprofile"
            ),
            "message" => array(
                "url" => "/message",
                "name" => "Inbox",
                "small_icon" => "dashboard_s_message",
                "large_icon" => "dashboard_l_message",
                "class" => "inbox"
            ),
            "my_team" => array(
                "url" => "/myteam",
                "name" => "My Team",
                "small_icon" => "dashboard_s_myteam",
                "large_icon" => "dashboard_l_myteam",
                "class" => "noicon"
            ),
            "my_health" => array(
                "url" => "/profile/myhealth",
                "name" => "My Health",
                "small_icon" => "dashboard_s_myhealth",
                "large_icon" => "dashboard_l_myhealth",
                "class" => "noicon"
            ),
            "my_nutrition" => array(
                "url" => "/profile/mynutrition",
                "name" => "My Nutrition",
                "small_icon" => "dashboard_s_nutrition",
                "large_icon" => "dashboard_l_nutrition",
                "class" => "noicon",
				"disabled" => false
            ),
            "calendar" => array(
                "url" => "/calendar",
                "name" => "Calendar",
                "small_icon" => "dashboard_s_calendar",
                "large_icon" => "dashboard_l_calendar",
                "class" => "calendar"
            ),
            "settings" => array(
                "url" => "/user/edit",
                "name" => "Settings",
                "small_icon" => "dashboard_s_settings",
                "large_icon" => "dashboard_l_settings",
                "class" => "settings"
            ),      
            "logout" => array(
                "url" => "/logout",
                "name" => "Logout",
                "small_icon" => "dashboard_s_logout",
                "large_icon" => "dashboard_l_logout",
                "class" => "noicon"
            ),
        );
    }

    /**
     * Function to Set the Dasboard items list
     *
     * @param null
     * @return array
     */
    public function setDashboardDetails() {
        $dashboard_details = array();
        /*
         * Get all dashboard items
         */
        $all_dashboard_items = $this->getDashboardItems();
        $dashboard_details['items'] = $all_dashboard_items;

        /*
         * Get current dashboard item
         */
        $current_item = $this->getCurrentDashbaordItem();
        if (!key_exists($current_item, $all_dashboard_items))
            $current_item = "home";
        $dashboard_details['current_item'] = $dashboard_details['items'][$current_item];

        return $dashboard_details;
    }

    /**
     * Function to get the current items details
     *
     * @param null
     * @return String
     */
    protected function getCurrentDashbaordItem() {
        return "home";
    }

    /**
     * Function to get the current items details
     *
     * @param null
     * @return String
     */
    protected function showHeaderLoginForm() {
        $loggedIn = $this->Auth->loggedIn();
        $discardPages = array("login", "register");
        if (!($loggedIn) &&
                in_array(trim($this->request->here, "/"), $discardPages)) {
            return false;
        }

        return true;
    }

    /**
     * Search Treatment by name
     */
    public function searchUsername() {
        $searchStr = $this->request->query['q'];
        $loginUserId = $this->Auth->user('id');
        $this->loadModel('User'); // If the User model is not loaded already
        $data = $this->User->find('list', array(
            'conditions' => array(
                'User.username LIKE' => "{$searchStr}%",
                'NOT' => array(
                    'User.id' => $loginUserId,
                    'User.is_admin' => User::ADMIN_USER
                )
            ))
        );

        if (!empty($data)) {
            foreach ($data as $id => $name) {
                echo strip_tags("$name|$id\n");
            }
        }
        exit();
    }

    public function getAdsCount() {
        return 2;
    }

    /**
     * Records the user's activity log on every request
     * @return void
     */
    public function recordUserActivityLog() {
        $this->LogUtil->userLog();
    }

    /**
     * Whether to show or disable sharing options
     *
     * @return boolean
     */
    public function getSharingOptions() {
        return TRUE;
    }
	
	public function isNotificationMusicEnabled($userid) {
		$this->loadModel('NotificationSetting');
		$status = $this->NotificationSetting->getNotificationMusicSetting($userid);
		return $status;
	}

}

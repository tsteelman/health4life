<?php

/**
 * AdminAppController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
App::uses('Date', 'Utility');
App::uses('Common', 'Utility');

/**
 * AdminAppController for the admin
 * 
 * AdminAppController is used as the parent for all the admin controllers
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Admin
 * @category	Controllers 
 */
class AdminAppController extends AppController {

    public $theme = 'Admin';
    public $helpers = array('JQValidator.JQValidator', 'AssetCompress.AssetCompress');
    public $components = array(
        'ForgotPassword',
        'Cookie',
        'Session',
        'Auth' => array(
            'loginRedirect' => array('controller' => 'dashboard', 'action' => 'index', 'plugin' => 'admin'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login', 'plugin' => 'admin'),
            'loginAction' => array('controller' => 'users', 'action' => 'login', 'plugin' => 'admin'),
			'authenticate' => array(
                'Admin.User'
            ),
            'authError' => 'You are not authorized to access that location. Please login to continue.',
            'authorize' => 'Controller'
        ),
        'JQValidator.JQValidator' => array('closestSelector' => 'control-group')
    );

    /**
     * Check if the provided user is authorized for the request.
     *
     * This method return a boolean to indicate whether or not the user is authorized.
     *
     * @param array $user The user to check the authorization of. If empty the user in the session will be used.
     * @return boolean True if $user is authorized, otherwise false
     */
    public function isAuthorized($user = null) {
        // Only admins can access admin functions
        return (bool) (intval($user['is_admin']) === 1);
    }

    /**
     * This function is executed before every action in the controller.
     */
    public function beforeFilter() {
        // disable cache to avoid browser back button issues
        $this->disableCache();

        // set cookie options
        $this->Cookie->httpOnly = true;
        $this->Cookie->path = '/admin/';

        $rememberMeCookie = Configure::read('adminRememberMeCookieName');
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
                $this->redirect('/admin/users/logout'); // destroy session & cookie
            }
        }
    }

    /**
     * This function is executed before the view is rendered
     */
    public function beforeRender() {
        parent::beforeRender();
        $loggedIn = $this->Auth->loggedIn();
        if ($loggedIn) {
			$username = $this->Auth->user('username');
			$isSuperAdmin = $this->_isSuperAdmin();
			$timezone = $this->Auth->user('timezone');
			$userId = $this->Auth->user('id');
			$userType = $this->Auth->user('type');
			$dashboardUrl = '/admin/dashboard';
		} else {
			$userId = null;
		}

		// set logged in status and username on view variable
		$this->set(compact('loggedIn', 'username', 'isSuperAdmin', 'timezone', 'userId', 'userType', 'dashboardUrl'));
	}

	/**
	 * Function to check if logged in user is a super admin
	 * 
	 * @return boolean
	 */
	protected function _isSuperAdmin() {
		$userType = intval($this->Auth->user('type'));
		$isAdmin = intval($this->Auth->user('is_admin'));
		$isSuperAdmin = ($isAdmin === User::ADMIN_USER && $userType === User::ROLE_SUPER_ADMIN );
		return $isSuperAdmin;
	}
}
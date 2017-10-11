<?php

/**
 * UsersController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');

/**
 * UsersController for the frontend
 * 
 * UsersController is used for front end user related functionalities
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class UsersController extends UserAppController {

    public $uses = array('User');
    public $components = array('Email', 'EmailTemplate');

    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('login');
    }

    /**
     * Logs in the user
     */
    public function login() {

        $formId = 'UserLoginForm';
        $this->JQValidator->addValidation('User', $this->User->validate, $formId);
        $this->set(compact('formId'));

        // if user already logged in, redirect
        if ($this->Auth->loggedIn()) {
            if ($this->Auth->user()) {
                $data = array(
                    'id' => $this->Auth->user('id'),
                    'last_login_datetime' => date("Y-m-d H:i:s")
                );
                $this->User->save($data);
                $this->redirect($this->referer());
            } else {
                $this->redirect($this->Auth->loginRedirect);
            }
        }

        // if we get the post information, try to authenticate
        if ($this->request->is('post')) {
            $isAjax = ($this->request->is('ajax')) ? true : false;
            if ($isAjax) {
                $this->disableCache();
                $this->autoRender = false;
            }
            if ($this->Auth->login()) {
				// show a message if the user has logged in without activating the account
				$status = (int) $this->Auth->user('status');
				if ($status === User::STATUS_INACTIVE) {
					$message = __('Your account is not yet verified. Please verify it by using the activation link sent to your inbox.');
					$this->Session->setFlash($message, 'warning');
				}                
				switch ($this->Auth->user('type')) {
				case User::ROLE_PATIENT:
					$this->_setIfFirstLoginToday();
					break;
				default :
					$this->_setShowFeelingsPopup();
				}
			
                $data = array(
                    'id' => $this->Auth->user('id'),
                    'last_login_datetime' => date("Y-m-d H:i:s")
                );
                $this->User->save($data);
                if ($this->request->data['User']['rememberMe'] == 1) {
                    // After what time frame should the cookie expire
                    $cookieTime = Configure::read('rememberMeCookieTime');
                    // remove "remember me checkbox"
                    unset($this->request->data['User']['rememberMe']);

                    // hash the user's password
                    $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);

                    // write the cookie
                    $rememberMeCookie = Configure::read('userRememberMeCookieName');
                    $this->Cookie->write($rememberMeCookie, $this->request->data['User'], true, $cookieTime);
                }
                $redirectUrl = $this->Auth->redirectUrl();
                if ($isAjax) {
                    $result = array(
                        'success' => true,
                        'redirectUrl' => $redirectUrl,
                    );
                    echo json_encode($result);
                } else {
                    return $this->redirect($redirectUrl);
                }
            } else {
                if ($isAjax) {
                    if ($this->Session->check('Message.flash')) {
                        $message = $this->Session->read('Message.flash.message');
                        $this->Session->delete('Message.flash');
                        $result = array(
                            'error' => true,
                            'message' => $message,
                        );
                        echo json_encode($result);
                    }
                } else {
                    if ($this->Session->check('Message.flash')) {
                        $message = $this->Session->read('Message.flash.message');
                        $this->Session->delete('Message.flash');
                        $this->Session->setFlash(__($message), 'error');
                        unset($this->request->data['User']['password']);                      
                    }
                }
            }
        }
    }

    /**
     * Sets in session if the user is logged in for the first time today
     */
    protected function _setIfFirstLoginToday() {
        $timezone = $this->Auth->user('timezone');
        $today = Date::getCurrentDate($timezone);
        $lastLoginDateTime = $this->Auth->user('last_login_datetime');
        $lastLoginDate = CakeTime::format('Y-m-d', $lastLoginDateTime, false, $timezone);
        if ($lastLoginDate !== $today) {
            $this->Session->write('isFirstLoginToday', true);
        }
    }
	/**
	 * Function to implement weekly feeling popup for users other than patient
	 */
	protected function _setShowFeelingsPopup() {
		$lastPopupShownTime = $this->Auth->user('feeling_popup_datetime');
		if (is_null($lastPopupShownTime)) {
			$this->Session->write('isFirstLoginToday', true);
			$data = array(
				'id' => $this->Auth->user('id'),
				'feeling_popup_datetime' => date("Y-m-d H:i:s")
			);
			$this->User->save($data);
		} else {
			$interval = $this->_getFeelingPopupLastShownInterval();
			if ($interval->days >= 7) {
				$this->Session->write('isFirstLoginToday', true);
				$data = array(
					'id' => $this->Auth->user('id'),
					'feeling_popup_datetime' => date("Y-m-d H:i:s")
				);
				$this->User->save($data);
			}
		}
	}

	protected function _getFeelingPopupLastShownInterval() {
		$timezone = $this->Auth->user('timezone');
		$today = new DateTime(Date::getCurrentDate($timezone));
		$lastPopupShownTime = $this->Auth->user('feeling_popup_datetime');
		$lastPopupShownDate = new DateTime(CakeTime::format('Y-m-d', $lastPopupShownTime, false, $timezone));
		$interval = date_diff($lastPopupShownDate, $today);
		return $interval;
	}

	/**
     * Logs out the user
     */
    public function logout() {        
        $userId = $this->Auth->user('id');
        $this->loadModel('ArrowchatStatus');
        $this->ArrowchatStatus->delete($userId); //remove online status record from arrowchat
        
        $this->User->logout();
        $this->Session->destroy();     
        $rememberMeCookie = Configure::read('userRememberMeCookieName');
		$this->Cookie->delete($rememberMeCookie);
		
		if (isset($this->request->query['blocked'])) {
			$this->Auth->logoutRedirect = '/pages/accountBlocked';
		}
		
        return $this->redirect($this->Auth->logout());
    }
		
}
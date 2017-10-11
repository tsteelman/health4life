<?php

/**
 * UserAuthenticate class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('BaseAuthenticate', 'Controller/Component/Auth');

/**
 * An authentication adapter for AuthComponent. Provides the ability to authenticate using POST
 * data. Can be used by configuring AuthComponent to use it via the AuthComponent::$authenticate setting.
 * 
 * This class is used to authenticate the front end user.
 *
 * {{{
 * 	$this->Auth->authenticate = array(
 * 		'User.User'
 * 	)
 * }}}
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User.Controller.Component.Auth
 * @category	Component 
 * @see         AuthComponent::$authenticate
 */
class UserAuthenticate extends BaseAuthenticate {

	/**
	 * Model name to check the user identity
	 * 
	 * @var string
	 */
	public $model = 'User';

	/**
	 * Authenticates the identity contained in a request. Will return false if
	 * there is no user matching the POST data.
	 *
	 * @param CakeRequest $request The request that contains login information.
	 * @param CakeResponse $response Unused response object.
	 * @return mixed False on login failure. An array of User data on success.
	 */
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		return $this->_findUser(
						$request->data[$this->model]['username'], $request->data[$this->model]['password']
		);
	}

	/**
	 * Finds the user by username/email and password.
	 * Also checks if user is active.
	 * 
	 * @param type $username
	 * @param type $password
	 * @return boolean
	 */
	protected function _findUser($username, $password = NULL) {
		$model = $this->model;
		$this->Model = ClassRegistry::init($model);
		$result = $this->Model->find('first', array(
			'conditions' => array(
				'username' => $username,
				'is_admin' => User::ADMIN_USER
		)));
		if (empty($result[$model])) {
			// no admin user with the username or email
			SessionComponent::setFlash(__('Invalid username or password'));
			$this->passwordHasher()->hash($password);
			return false;
		}

		$user = $result[$model];
		if ($password) {
			if (!$this->passwordHasher()->check($password, $user['password'])) {
				// password does not match
				SessionComponent::setFlash(__('Invalid username or password'));
				return false;
			} else {
				// admin user exists with the username/email and password
				// check if the user is active
				$status = intval($user['status']);
				if ($status !== User::STATUS_ACTIVE) {
					if ($status === User::STATUS_BLOCKED) {
						$message = __('It looks like your account is blocked.');
					} else {
						$message = __('It looks like your account is not active.');
					}
					SessionComponent::setFlash($message);
					return false;
				}
			}
			unset($user['password']);
		} else {
			SessionComponent::setFlash(__('Invalid username or password'));
			return false;
		}

		unset($result[$model]);
		return array_merge($user, $result);
	}
}
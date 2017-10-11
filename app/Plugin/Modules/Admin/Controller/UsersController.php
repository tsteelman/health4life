<?php

/**
 * UsersController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AdminAppController', 'Admin.Controller');
App::uses('CakeTime', 'Utility');
App::uses('User', 'Model');
App::uses('Common', 'Utility');
App::uses('Date', 'Utility');
App::import('Controller', 'Api');
App::uses('File', 'Utility');
App::import('Vendor', 'ImageTool');

/**
 * UsersController for the admin
 * 
 * UsersController is used for admin user related functionalities
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Admin
 * @category	Controllers 
 */
class UsersController extends AdminAppController {

	public $uses = array('User', 'ResetPasswordForm', 'ForgotPasswordForm',
		'AdminProfileForm', 'AdminUserForm', 'AdminChangePasswordForm', 'Timezone',
		'UserSymptom', 'UserTreatment', 'MyFriends', 'Analytics');
	public $components = array('Email', 'EmailTemplate', 'EmailQueue', 'Uploader');
	public $helper = array('TimeHelper');

	/**
	 * Variable to store the minimum profile image size
	 */
	public $minimumImageSize = array('200', '200');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow('forgotpassword', 'resetpassword', 'login', 'accountBlocked');
	}

	const PAGE_LIMIT = 10;

	/**
	 * Logs in the admin user
	 */
	public function login() {
		// redirect if already logged in
		if ($this->Auth->loggedIn()) {
			$this->redirect($this->Auth->loginRedirect);
		}

		$login_box_visiblity = 'visible';
		$forgot_box_visiblity = '';

		$this->set(compact('login_box_visiblity', 'forgot_box_visiblity'));

		$this->JQValidator->addValidation('User', $this->User->validate, 'UserLoginForm');
		$this->JQValidator->addValidation('ForgotPasswordForm', $this->ForgotPasswordForm->validate, 'ForgotPasswordForm');
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				if ($this->request->data['User']['rememberMe'] == 1) {
					// After what time frame should the cookie expire
					$cookieTime = Configure::read('rememberMeCookieTime');
					// remove "remember me checkbox"
					unset($this->request->data['User']['rememberMe']);

					// hash the user's password
					$this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);

					// write the cookie
					$rememberMeCookie = Configure::read('adminRememberMeCookieName');
					$this->Cookie->write($rememberMeCookie, $this->request->data['User'], true, $cookieTime);
				}
				return $this->redirect($this->Auth->redirect());
			} else {
				unset($this->request->data['User']['password']);
			}
		}
	}

	/**
	 * Logs out the admin user
	 */
	public function logout() {

		if (isset($this->request->query['blocked'])) {
			$this->Auth->logoutRedirect = '/admin/users/accountBlocked';
		} else {
			$this->Session->setFlash(__("You've been logged out"));
		}

		$rememberMeCookie = Configure::read('adminRememberMeCookieName');
		$this->Cookie->delete($rememberMeCookie);
		return $this->redirect($this->Auth->logout());
	}

	/**
	 * Account blocked page
	 */
	public function accountBlocked() {
		// redirect if logged in
		if ($this->Auth->loggedIn()) {
			$this->redirect($this->Auth->loginRedirect);
		}
	}

	/**
	 * User profile
	 */
	public function profile() {
		$profileUser = $this->Auth->user();

		$profileUser['name'] = $profileUser['first_name'] . ' ' . $profileUser['last_name'];

		$dob = $profileUser['date_of_birth'];
		if (!is_null($dob) && ($dob !== '')) {
			list($dobYear, $dobMonth, $dobDay) = explode('-', $dob);
			$formattedDob = sprintf('%s-%s-%s', $dobMonth, $dobDay, $dobYear);
			$profileUser['date_of_birth'] = $formattedDob;
		}

		$gender = $profileUser['gender'];
		if (!is_null($gender) && ($gender !== '')) {
			$profileUser['gender'] = ($gender === 'M') ? 'Male' : 'Female';
		}

		$profileImg = Common::getUserThumb($profileUser['id'], $profileUser['type'], 'medium');

		$superAdminStatus = __('No');
		if (intval($profileUser['type']) === User::ROLE_SUPER_ADMIN) {
			$superAdminStatus = __('Yes');
		}

		$title_for_layout = 'Profile';

		$this->set(compact('title_for_layout', 'profileImg', 'profileUser', 'superAdminStatus'));
	}

	/**
	 * User edit profile
	 */
	public function editProfile() {
		if ($this->request->is('put')) {
			$formId = $this->request->data['User']['form_id'];
			if ($formId === 'basic_info') {
				$this->__saveBasicInfo();
			} elseif ($formId === 'change_password') {
				$this->__changePassword();
			}
		} else {
			$this->__setProfileFormData();
		}
	}

	/**
	 * Function to set profile form data
	 */
	private function __setProfileFormData() {
		$adminUser = $this->Auth->user();

		$this->request->data['User'] = $adminUser;
		$this->request->data['User']['old_email'] = $adminUser['email'];

		$dob = $adminUser['date_of_birth'];
		if (!is_null($dob) && ($dob !== '')) {
			list($dobYear, $dobMonth, $dobDay) = explode('-', $dob);
			$formattedDob = sprintf('%s-%s-%s', $dobMonth, $dobDay, $dobYear);
			$this->request->data['User']['date_of_birth'] = $formattedDob;
		}

		$gender = $adminUser['gender'];

		$profileImg = Common::getUserThumb($adminUser['id'], $adminUser['type'], 'medium', 'user_pic');

		$superAdminStatus = __('No');
		if (intval($adminUser['type']) === User::ROLE_SUPER_ADMIN) {
			$superAdminStatus = __('Yes');
		}

		$timezoneList = $this->Timezone->get_timezone_list();

		// form details
		$formId = 'AdminProfileEditForm';
		$changePasswordFormId = 'AdminChangePasswordForm';
		$changePasswordFields = $this->__listAdminUserChangePasswordFormFields();
		$inputDefaults = array(
			'label' => false,
			'div' => false
		);

		// validation
		$modelName = 'User';
		$this->JQValidator->addValidation($modelName, $this->AdminChangePasswordForm->validate, $changePasswordFormId);
		$this->JQValidator->addValidation($modelName, $this->AdminProfileForm->validate, $formId);

		$title_for_layout = 'Edit Profile';

		$this->set(compact('title_for_layout', 'modelName', 'changePasswordFields', 'formId', 'changePasswordFormId', 'timezoneList', 'inputDefaults', 'gender', 'profileImg', 'superAdminStatus'));
	}

	/**
	 * Function to save basic profile info
	 */
	private function __saveBasicInfo() {
		$postData = $this->request->data['User'];

		$fields = array('username', 'first_name', 'last_name', 'email', 'gender', 'timezone');
		$data = array();
		foreach ($fields as $field) {
			$data[$field] = $postData[$field];
		}

		// convert dob
		$dob = $postData['date_of_birth'];
		list($dobMonth, $dobDay, $dobYear) = explode('-', $dob);
		$formattedDob = sprintf('%s-%s-%s', $dobYear, $dobMonth, $dobDay);
		$data['date_of_birth'] = $formattedDob;

		$data['id'] = $userId = $this->Auth->user('id');
		if ($this->User->save($data)) {
			// save user photo
			$this->__saveUserPhoto($userId, $this->request->data['cropfileName']);

			// send email change notification if email is changed
			$sendEmailChangeNotification = ($postData['email'] !== $postData['old_email']) ? true : false;
			if ($sendEmailChangeNotification === true) {
				$this->__sendEmailChangeNotification($postData);
			}

			// set new data on session
			$this->Session->write('Auth', $this->User->read(null, $userId));

			// redirect with success message
			$this->Session->setFlash(__('Successfully updated the details.'), 'success');
			$this->redirect($this->referer());
		} else {
			// redirect with error message
			$this->Session->setFlash(__('Failed to update the details.'), 'error');
		}
	}

	/**
	 * Function to save the photo of a user
	 * 
	 * @param int $userId
	 * @param string $fileName
	 * @return array
	 * @throws Exception
	 */
	private function __saveUserPhoto($userId, $fileName) {
		$result['success'] = false;

		try {
			if (isset($fileName) && !empty($fileName)) {
				$uploadPath = Configure::read("App.UPLOAD_PATH");
				$thumbnailPath = Configure::read("App.PROFILE_IMG_PATH");
				$croppedImage = $uploadPath . DIRECTORY_SEPARATOR . $fileName;

				if (file_exists($croppedImage)) {
					$croppedFile = new File($croppedImage);

					if (!file_exists($thumbnailPath)) {
						mkdir($thumbnailPath, 0777);
					}

					// resize and copy the image to new folders
					$imageSizes = Common::getUserThumbSize();
					foreach ($imageSizes as $suffix => $size) {
						$targetImage = $thumbnailPath . DIRECTORY_SEPARATOR . md5($userId) . "_" . $suffix . ".jpg";
						ImageTool::resize(array(
							'input' => $croppedImage,
							'output' => $targetImage,
							'width' => $size['w'],
							'height' => $size['h']
						));
					}

					// move the original image also
					$originalThumb = $thumbnailPath . DIRECTORY_SEPARATOR . md5($userId) . "_original.jpg";
					$croppedFile->copy($originalThumb, true);

					// remove the initial cropped image
					$croppedFile->delete();

					$result['success'] = true;
				} else {
					throw new Exception("Uploaded file does not exist");
				}
			}
		} catch (Exception $e) {
			$result['success'] = false;
			$result['msg'] = $e->getMessage();
		}

		return $result;
	}

	/**
	 * Function to change profile password
	 */
	private function __changePassword() {
		$data = $this->request->data['User'];
		$this->User->id = $this->Auth->user('id');
		if ($this->User->saveField('password', $data['new_password'])) {
			$this->Session->setFlash(__('Your password changed successfully.'), 'success');
			$this->redirect($this->referer());
		} else {
			$this->Session->setFlash(__('Could not change your password due to a server problem, try again later.'), 'error');
		}
	}

	/**
	 * Function to send reset password mail
	 */
	public function forgotpassword() {

		$login_box_visiblity = '';
		$forgot_box_visiblity = 'visible';

		$this->JQValidator->addValidation('User', $this->User->validate, 'UserLoginForm');
		$this->JQValidator->addValidation('ForgotPasswordForm', $this->ForgotPasswordForm->validate, 'ForgotPasswordForm');

		$this->set(compact('login_box_visiblity', 'forgot_box_visiblity'));

		if (array_key_exists('ForgotPasswordForm', $this->data)) {
			$flag = $this->ForgotPassword->sendMail($this->data['ForgotPasswordForm'], 'admin');

			if ($flag) {
				$this->redirect('login');
			}
		} else {
			$this->login();
		}
	}

	/**
	 * Function to reset password
	 */
	public function resetpassword($timelimit, $forgot_password_code) {

		$this->JQValidator->addValidation('ResetPasswordForm', $this->ResetPasswordForm->validate, 'ResetPasswordFormResetpasswordForm');

		$flag = $this->ForgotPassword->resetPassword($this->data, $timelimit, $forgot_password_code);

		if ($flag) {
			$this->redirect('/admin/users/login');
		}
	}

	/**
	 * Function to list all the users
	 */
	public function index() {

		$conditions = array(
			'User.type !=' => NULL,
			'User.is_admin' => 0,
			'DATE(User.created) !=' => '0000-00-00'
		);

		$this->paginate = array(
			'limit' => UsersController::PAGE_LIMIT,
			'conditions' => $conditions,
			'fields' => array('User.id','User.username', 'User.first_name', 'User.last_name',
				'User.email', 'User.status', 'User.created', 'User.type'),
			'order' => array('User.username' => 'asc', 'User.first_name' => 'asc',
				'User.last_name' => 'asc')
		);
		$user_list = $this->paginate('User');
		$title_for_layout = 'Manage Users';
                
		$this->set(compact('user_list', 'title_for_layout'));
	}

	public function view($userId = NULL) {
                $userDeatils = $this->User->getUserDetails($userId);
                $username = $userDeatils['user_name'];
		if (isset($username)) {
			$user = $this->User->findByUsername($username);
			if (isset($user) && !empty($user)) {
				$online_status = $this->User->isUserOnline($user['User']['id']);
				$location = $this->User->getUserLocation($user['User']['id'], TRUE);
				$user_diseases = $this->User->getUserDiseases($user['User']['id'], TRUE);
				$user_symptoms = $this->UserSymptom->getUserSymptomNames($user['User']['id']);
				$user_treatments = $this->UserTreatment->getUserTreatmentNames($user['User']['id']);
				$friends_list = $this->MyFriends->getFriendsList($user['User']['id']);
				$this->set(compact('user', 'online_status', 'location', 'user_diseases', 'user_symptoms', 'user_treatments', 'friends_list'));
			} else {
				$this->Session->setFlash('No User found.', 'warning');
				$this->redirect('/admin/Users/index');
			}
		} else {
			$this->Session->setFlash('No User found.', 'warning');
			$this->redirect('/admin/Users/index');
		}
	}

	public function search() {

		$conditions = array('User.is_admin' => 0);
		if ($this->request->query('username')) {
			$keyword = $this->request->query('username');
			$this->paginate = array(
				'conditions' => array(
					'OR' => array('User.username LIKE' => '%' . $keyword . '%',
						'User.email LIKE' => '%' . $keyword . '%'),
					$conditions),
				'fields' => array('User.id','User.username', 'User.first_name', 'User.last_name',
					'User.email', 'User.status', 'User.created', 'User.type'),
				'order' => array('User.username' => 'asc', 'User.first_name' => 'asc',
					'User.last_name' => 'asc'),
				'limit' => 10
			);
		} else {
			$this->paginate = array(
				'limit' => 10,
				'conditions' => $conditions
			);
			$user_list = $this->paginate('User');
		}
		$user_list = $this->paginate('User');
		if (sizeof($user_list) == 0) {
			$this->Session->setFlash('No User found.', 'warning');
		} else {
			$this->set(compact('user_list', 'keyword', 'filter'));
		}
		$this->render('index');
	}

	/**
	 * Access denied page
	 */
	public function accessDenied() {
		$this->Session->setFlash('You are not allowed to access this page.', 'error');
	}

	/**
	 * List admins
	 */
	public function admins() {
		$this->__allowSuperAdminOnly();
		$this->__setAdminPageHeader();
		$conditions = array(
			'is_admin' => 1
		);
		$this->paginate = array(
			'limit' => 10,
			'conditions' => $conditions,
		);
		$adminUsersList = $this->paginate('User');
		$authUser = $this->Auth->user();
		$timezone = new DateTimeZone($authUser['timezone']);
		$loggedInAdminId = $authUser['id'];
		foreach ($adminUsersList as $key => $adminUser) {
			$adminUsersList[$key]['User']['created'] = Date::getUSFormatDateTime($adminUsersList[$key]['User']['created'], $timezone);
			$adminUsersList[$key]['User']['show_status_change_icon'] = ($adminUsersList[$key]['User']['id'] !== $loggedInAdminId) ? true : false;
			$adminUsersList[$key]['User']['status'] = intval($adminUsersList[$key]['User']['status']);
		}
		$this->set(compact('title_for_layout', 'adminUsersList'));
	}

	/**
	 * This function checks if the logged in user is super admin and redirects
	 * to access denied page if not
	 */
	private function __allowSuperAdminOnly() {
		$isSuperAdmin = $this->_isSuperAdmin();
		if ($isSuperAdmin === false) {
			$this->redirect('/admin/users/accessDenied');
		}
	}

	private function __setAdminPageHeader() {
		$title_for_layout = __('Admins');
		$this->set(compact('title_for_layout'));
	}

	/**
	 * Add admin user
	 */
	public function addAdmin() {
		$this->__allowSuperAdminOnly();
		$this->__setAdminPageHeader();
		if ($this->request->is('post')) {
			$this->__saveAdmin();
		}
		$this->__showAdminUserForm();
	}

	/**
	 * Edit admin user
	 * 
	 * @param int $id
	 */
	public function editAdmin($id) {
		$this->__allowSuperAdminOnly();
		$this->__setAdminPageHeader();
		if ($this->request->is('put')) {
			$this->__saveAdmin($id);
		}
		$this->__showAdminUserForm($id);
	}

	/**
	 * Function to show admin user add/edit form
	 */
	private function __showAdminUserForm($id = null) {
		$fields = $this->__listAdminUserFormFields();
		$modelName = 'User';
		if ($id > 0) {
			// no need of password fields in edit form
			unset($fields[2]);
			unset($fields[3]);

			// logged in user should not be able to change him to normal admin
			if ($id === $this->Auth->user('id')) {
				unset($fields[7]);
			}

			$changePasswordFormId = 'AdminChangePasswordForm';
			$changePasswordFields = $this->__listAdminUserChangePasswordFormFields();

			$pageTitle = __('Edit Admin');
			$formId = 'UserEditAdminForm';
			$this->User->recursive = -1;

			$adminUser = $this->User->findById($id);
			if (intval($adminUser['User']['type']) === User::ROLE_SUPER_ADMIN) {
				$adminUser['User']['is_super_admin'] = true;
			}
			$this->request->data = $adminUser;
			$this->request->data['User']['old_email'] = $adminUser['User']['email'];

			$this->JQValidator->addValidation($modelName, $this->AdminChangePasswordForm->validate, $changePasswordFormId);
		} else {
			$pageTitle = __('Add Admin');
			$formId = 'UserAddAdminForm';
		}

		$this->JQValidator->addValidation($modelName, $this->AdminUserForm->validate, $formId);
		$this->set(compact('fields', 'pageTitle', 'modelName', 'changePasswordFields', 'changePasswordFormId'));
	}

	/**
	 * List of fields in the admin user add/edit form
	 * 
	 * @return array
	 */
	private function __listAdminUserFormFields() {
		$fields = array(
			array(
				'name' => 'username',
				'label' => __('Username'),
				'required' => true
			),
			array(
				'name' => 'email',
				'label' => __('Email'),
				'required' => true
			),
			array(
				'name' => 'password',
				'label' => __('Password'),
				'type' => 'password',
				'required' => true
			),
			array(
				'name' => 'confirm_password',
				'label' => __('Confirm Password'),
				'type' => 'password',
				'required' => true
			),
			array(
				'name' => 'first_name',
				'label' => __('First Name'),
				'required' => true
			),
			array(
				'name' => 'last_name',
				'label' => __('Last Name'),
				'required' => true
			),
			array(
				'name' => 'timezone',
				'label' => __('Timezone'),
				'type' => 'select',
				'options' => $this->Timezone->get_timezone_list(),
				'required' => true
			),
			array(
				'name' => 'is_super_admin',
				'label' => __('Is Super Admin'),
				'type' => 'checkbox'
			)
		);
		return $fields;
	}

	/**
	 * List of fields in the admin user edit-change password form
	 * 
	 * @return array
	 */
	private function __listAdminUserChangePasswordFormFields() {
		$fields = array(
			array(
				'name' => 'current_password',
				'label' => __('Current Password'),
				'type' => 'password'
			),
			array(
				'name' => 'new_password',
				'label' => __('New Password'),
				'type' => 'password'
			),
			array(
				'name' => 'confirm_password',
				'label' => __('Confirm Password'),
				'type' => 'password'
			),
		);
		return $fields;
	}

	/**
	 * Function to save admin user
	 * 
	 * @param int $id for edit
	 */
	private function __saveAdmin($id = null) {
		$data = $this->request->data['User'];
		if (!empty($data)) {
			if (isset($data['is_super_admin'])) {
				if (intval($data['is_super_admin']) === 1) {
					$data['type'] = User::ROLE_SUPER_ADMIN;
				} else {
					$data['type'] = User::ROLE_ADMIN;
				}
				unset($data['is_super_admin']);
			}

			if ($id > 0) {
				$data['id'] = $id;
				$sendMail = false;
				$sendEmailChangeNotification = ($data['email'] !== $data['old_email']) ? true : false;
			} else {
				$data['is_admin'] = User::ADMIN_USER;
				$data['status'] = User::STATUS_ACTIVE;
				$sendMail = true;
				$sendEmailChangeNotification = false;
			}
			$success = $this->User->save($data, false);
			if ($success === false) {
				$this->Session->setFlash(__('Failed to save the admin user.'), 'error');
			} else {
				if ($sendMail === true) {
					$this->__sendNewAdminEmail($data);
				} elseif ($sendEmailChangeNotification === true) {
					$this->__sendEmailChangeNotification($data);
				}

				$username = $data['username'];
				if ($id > 0) {

					// if editing current user details, set new data on session
					if ($id === $this->Auth->User('id')) {
						$this->Session->write('Auth', $this->User->read(null, $id));
					}

					$message = __('Successfully updated the details of the admin user "%s".', $username);
				} else {
					$message = __('Successfully added the user "%s" as admin.', $username);
				}
				$this->Session->setFlash($message, 'success');
				$this->redirect('admins');
			}
		} else {
			$this->Session->setFlash(__('No data entered.'), 'error');
		}
	}

	/**
	 * Function to send email notification when email is changed.
	 * 
	 * @param array $data
	 */
	private function __sendEmailChangeNotification($data) {
		$email = $data['email'];
		$templateData = array(
			'email' => $email,
			'oldEmail' => $data['old_email'],
			'username' => $data['username']
		);

		$Api = new ApiController();
		$Api->constructClasses();
		$templateId = EmailTemplateComponent::CHANGE_EMAIL_NOTIFICATION;
		$Api->sendHTMLMail($templateId, $templateData, $email);
	}

	/**
	 * Function to send email to admin user when super admin adds a new admin
	 * 
	 * @param array $data
	 */
	private function __sendNewAdminEmail($data) {
		$link = Router::Url('/', TRUE) . 'admin';
		$emailData = array(
			'username' => $data['username'],
			'email' => $data['email'],
			'password' => $data['password'],
			'link' => $link
		);
		$templateId = EmailTemplateComponent::NEW_ADMIN_EMAIL_TEMPLATE;
		$emailManagement = $this->EmailTemplate->getEmailTemplate($templateId, $emailData);

		$mailData = array(
			'subject' => $emailManagement['EmailTemplate']['template_subject'],
			'to_name' => $data['username'],
			'to_email' => $data['email'],
			'content' => json_encode($emailData),
			'module_info' => 'admin',
			'email_template_id' => $templateId
		);

		$this->EmailQueue->createEmailQueue($mailData);
	}

	/**
	 * Function to change the password of an admin user
	 */
	public function changeAdminUserPassword() {
		$data = $this->request->data['User'];

		$this->User->id = $userId = $data['id'];
		if ($this->User->saveField('password', $data['new_password'])) {
			$isPasswordChanged = ($data['current_password'] !== $data['new_password']) ? true : false;
			if ($isPasswordChanged && ($userId !== $this->Auth->User('id'))) {
				$this->__sendPasswordChangedEmail($data);
			}
			$this->Session->setFlash(__('Password changed successfully.'), 'success');
		} else {
			$this->Session->setFlash(__('Could not change the password due a server problem, try again later.'), 'error');
		}

		$this->redirect('/admin/users/editAdmin/' . $userId);
	}

	/**
	 * Function to send password changed email to admin
	 * 
	 * @param array $data
	 */
	private function __sendPasswordChangedEmail($data) {
		$emailData = array(
			'username' => $data['username'],
			'password' => $data['new_password'],
		);
		$templateId = EmailTemplateComponent::ADMIN_PASSWORD_CHANGED_EMAIL_TEMPLATE;
		$emailManagement = $this->EmailTemplate->getEmailTemplate($templateId, $emailData);

		$mailData = array(
			'subject' => $emailManagement['EmailTemplate']['template_subject'],
			'to_name' => $data['username'],
			'to_email' => $data['email'],
			'content' => json_encode($emailData),
			'module_info' => 'admin',
			'email_template_id' => $templateId
		);

		$this->EmailQueue->createEmailQueue($mailData);
	}

	/**
	 * Function to deactivate admin user
	 */
	public function deactivateAdmin($id) {
		$this->__allowSuperAdminOnly();
		$adminUserData = $this->User->findById($id);
		if (!empty($adminUserData)) {
			$success = $this->User->blockUser($id);
			if ($success === true) {
				$this->__sendAdminDeactivatedEmail($adminUserData);
				$adminUsername = $adminUserData['User']['username'];
				$message = __('Successfully deactivated the user "%s".', $adminUsername);
				$this->Session->setFlash($message, 'success');
			} else {
				$this->Session->setFlash(__('Failed to deactivate the admin user.'), 'error');
			}
		} else {
			$this->Session->setFlash(__('No admin with id: %d.', $id), 'error');
		}
		$this->redirect('admins');
	}

	/**
	 * Function to send email to admin user on account deactivation
	 * 
	 * @param array $adminUserData
	 */
	private function __sendAdminDeactivatedEmail($adminUserData) {
		$adminUser = $adminUserData['User'];
		$email = $adminUser['email'];
		$templateData = array(
			'username' => $adminUser['username']
		);

		$Api = new ApiController();
		$Api->constructClasses();
		$templateId = EmailTemplateComponent::ADMIN_DEACTIVATED_EMAIL_TEMPLATE;
		$Api->sendHTMLMail($templateId, $templateData, $email);
	}

	/**
	 * Function to activate admin user
	 */
	public function activateAdmin($id) {
		$this->__allowSuperAdminOnly();
		$adminUserData = $this->User->findById($id);
		if (!empty($adminUserData)) {
			$success = $this->User->activateUser($id);
			if ($success === true) {
				$this->__sendAdminActivatedEmail($adminUserData);
				$adminUsername = $adminUserData['User']['username'];
				$message = __('Successfully activated the user "%s".', $adminUsername);
				$this->Session->setFlash($message, 'success');
			} else {
				$this->Session->setFlash(__('Failed to activate the admin user.'), 'error');
			}
		} else {
			$this->Session->setFlash(__('No admin with id: %d.', $id), 'error');
		}
		$this->redirect('admins');
	}

	/**
	 * Function to send email to admin user on account activation
	 * 
	 * @param array $adminUserData
	 */
	private function __sendAdminActivatedEmail($adminUserData) {
		$adminUser = $adminUserData['User'];
		$email = $adminUser['email'];
		$templateData = array(
			'username' => $adminUser['username']
		);

		$Api = new ApiController();
		$Api->constructClasses();
		$templateId = EmailTemplateComponent::ADMIN_ACTIVATED_EMAIL_TEMPLATE;
		$Api->sendHTMLMail($templateId, $templateData, $email);
	}

	/**
	 * Function to upload user profile photo and give response in 
	 * Ajax request
	 */
	public function photo() {
		$this->layout = null;
		$uploadPath = Configure::read("App.UPLOAD_PATH");
		$thumbnailPath = Configure::read("App.PROFILE_IMG_PATH");
		$uploadUrl = Configure::read("App.UPLOAD_PATH_URL");
		$webFolder = $thumbnailPath;
		$tempUrl = FULL_BASE_URL . "/uploads/tmp/";
		$webUrl = FULL_BASE_URL . "/uploads/user_profile";
		/*
		 * Do the image Cropping
		 */
		if (isset($this->request->data['crop_image'])) {


			$uploadedImage = $this->request->data['cropfileName'];

			try {

				$options = array('thumbnail' => array(
						"max_width" => $this->minimumImageSize[0],
						"max_height" => $this->minimumImageSize[1],
						"path" => $thumbnailPath
					),
					'max_width' => 700
				);
				$x1 = $_POST["x1"];
				$y1 = $_POST["y1"];
				$width = $_POST["w"];
				$height = $_POST["h"];
				$fileName = $_POST['cropfileName'];
				$imageUrl = $tempUrl . $_POST['cropfileName'];

				$photoPath = $uploadPath . DIRECTORY_SEPARATOR . $uploadedImage;

				if ($width <= 0) {
					$width = $this->minimumImageSize[0];
					$x1 = 0;
				}

				if ($height <= 0) {
					$height = $this->minimumImageSize[1];
					$y1 = 0;
				}

				if ($width > 0 && $height > 0) {
					$status = ImageTool::crop(array(
								'input' => $photoPath,
								'output' => $photoPath,
								'width' => $width,
								'height' => $height,
								'output_width' => $this->minimumImageSize[0],
								'output_height' => $this->minimumImageSize[1],
								'top' => $y1,
								'left' => $x1,
					));

					$result['success'] = true;
					$result['fileUrl'] = $tempUrl . "" . $uploadedImage;
					$result['fileName'] = $uploadedImage;
				} else {
					throw new Exception("Image Not cropped");
				}
			} catch (Exception $e) {
				$result['success'] = false;
				$result['message'] = $e->getMessage();
			}

			$result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
			echo $result;
		} else {
			/*
			 * Functionality to upload the image to a temporary folder
			 */
			$uploader = new $this->Uploader();
			//$webFolder

			$uploader->allowedExtensions = array("jpg", "jpeg", "png", "gif"); // all files types allowed by default
			// Specify max file size in bytes.
			$uploader->sizeLimit = 5 * 1024 * 1024; // default is 5 MiB

			$uploader->minImageSize = array('200', '200');

			// Specify the input name set in the javascript.
			$uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default
			// If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
			$uploader->chunksFolder = "chunks";

			$method = $_SERVER["REQUEST_METHOD"];
			if ($method == "POST") {
				header("Content-Type: text/plain");

				// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
				$result = $uploader->handleUpload($uploadPath);

				if (isset($result['success'])) {
					$result['file_name'] = $uploader->getUploadName();

					$photoPath = $uploadPath . DIRECTORY_SEPARATOR . $result['file_name'];
					$status = ImageTool::resize(array(
								'quality' => 90,
								'enlarge' => false,
								'keepRatio' => true,
								'paddings' => false,
								'crop' => false,
								'input' => $photoPath,
								'output' => $photoPath,
								'width' => '520',
								'height' => '220'
					));

					// image dimension
					list($imageWidth, $imageHeight) = getimagesize($photoPath);

					$result['imageWidth'] = $imageWidth;
					$result['imageHeight'] = $imageHeight;
					$result['fileName'] = $result['file_name'];
					$result['fileurl'] = $tempUrl . DIRECTORY_SEPARATOR . $result['file_name'];
				}

				echo json_encode($result);
			} else {
				header("HTTP/1.0 405 Method Not Allowed");
			}
		}

		exit;
	}
}
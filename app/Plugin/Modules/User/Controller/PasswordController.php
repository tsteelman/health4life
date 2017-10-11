<?php

/**
 * PasswordController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('AuthComponent', 'Controller/Component');
App::import('Controller', 'Api');

/**
 * PasswordController for the frontend
 * 
 * PasswordController is used for edit user password
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class PasswordController extends UserAppController {

    /**
     * Models used by this controller
     *
     * @var array
     */
    public $uses = array('User', 'ChangePasswordForm');

    /**
     * Override parent function to get the current dasboard item
     *
     * @param null
     * @return String
     */
    protected function getCurrentDashbaordItem() {
        return "settings";
    }

    public function index() {
		$this->set('title_for_layout',"Change Password");
        $changePasswordFormId = 'changePasswordForm';
        $inputDefaults = array(
            'label' => false,
            'div' => false,
            'class' => 'form-control'
        );
        $model = 'User';
        $changePasswordModelForm = 'ChangePasswordForm';

        $userId = $this->Auth->user('id');
        $userData = $this->User->findById($userId);
        $type = $userData['User']['type'];
        $userImage = Common::getUserThumb($userId, $type, 'medium', 'img-responsive pull-left img-thumbnail', 'url');
        $profilePhotoClass = Common::getUserThumbClass($type);

        // validation for password change form
        $validations = $this->$changePasswordModelForm->validate;
        $this->JQValidator->addValidation($model, $validations, $changePasswordFormId);

        // set view variables
        $this->set(compact('changePasswordFormId', 'model', 'inputDefaults', 'userId', 'userImage', 'profilePhotoClass'));
    }

    public function changePassword() {
        $userId = $this->Auth->user('id');
        $userEmail = $this->Auth->user('email');
        $userPassword = $this->User->getpassword($userId);
        $current_password = AuthComponent::password($this->request->data ['User'] ['current_password']);
        $new_password = $this->request->data ['User'] ['new_password'];
        $confirm_password = $this->request->data ['User'] ['confirm_password'];

        if ($current_password === $userPassword) {
            if ($new_password === $confirm_password) {
                $this->User->id = $userId;
                if ($this->User->saveField('password', $new_password)) {

                    $Api = new ApiController ();
                    $Api->constructClasses();
                    $templateId = 17;
                    $emailData = array(
                        'username' => $this->Auth->user('username')
                    );
                    $toEmail = $userEmail;
                    $Api->sendHTMLMail($templateId, $emailData, $toEmail);

                    $this->Session->setFlash(__('Your password has been changed
                        and a confirmation has been emailed to ' . $userEmail . '.'), 'success');
                } else {
                    $this->Session->setFlash(__('Could not change your password
                        due a server problem, try again later.'), 'error');
                }
            }
        } else {
            $this->Session->setFlash(__('Current password entered is wrong.'), 'error');
        }
        $this->redirect('/user/password');
    }

}
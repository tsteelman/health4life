<?php
App::uses('UserAppController', 'User.Controller');
class ForgotPasswordController extends UserAppController {

    public $uses = array('user', 'ResetPasswordForm', 'ForgotPasswordForm');
    public $components = array('ForgotPassword');
    
    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('index', 'resetpassword');
    }

    public function index() {

        // if user already logged in, redirect
        if ($this->Auth->loggedIn()) {
            $this->redirect($this->Auth->loginRedirect);
        }
        
        $formId = 'ForgotpasswordForm';
        $isForgotpasswordForm = true;
        $this->set(compact('formId', 'isForgotpasswordForm'));
        $this->JQValidator->addValidation('ForgotPasswordForm', $this->ResetPasswordForm->validate, $formId);

        if($this->data){
            $flag = $this->ForgotPassword->sendMail($this->data['ForgotPasswordForm'], 'user');
            
            if ($flag) {
                $this->redirect('/user/forgotPassword');
            }
        }
    }

    public function resetpassword($timelimit, $forgot_password_code) {

        // if user already logged in, redirect
        if ($this->Auth->loggedIn()) {
            $this->redirect($this->Auth->loginRedirect);
        }
        
        $isResetpasswordForm = true;
        $this->set(compact('isResetpasswordForm'));
        
        $this->JQValidator->addValidation('ResetPasswordForm', $this->ResetPasswordForm->validate, 'ResetPasswordFormResetpasswordForm');

        $flag = $this->ForgotPassword->resetPassword($this->data, $timelimit, $forgot_password_code);

        if ($flag) {
            $this->redirect('/login');
        }
    }

}
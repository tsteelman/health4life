<?php

/**
 * InviteController class file.
 * 
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('AuthComponent', 'Controller/Component');
App::uses('UserPrivacySettings', 'Lib');

class InviteController extends UserAppController {

    /**
     * Models used by this controller
     *
     * @var array
     */
    public $uses = array('User');
    
    /**
     * Override parent function to get the current dasboard item
     *
     * @param null
     * @return String
     */
    protected function getCurrentDashbaordItem() {
        return "settings";
    }

    /**
     * Edit User Account Settings
     */
    public function index() {
        $inputDefaults = array(
            'label' => false,
            'div' => false,
            'class' => 'form-control'
        );

        $model = 'User';

        $userId = $this->Auth->user('id');
        $userData = $this->User->findById($userId);
        $type = $userData['User']['type'];
        $userImage = Common::getUserThumb($userId, $type, 'medium', 'img-responsive pull-left img-thumbnail', 'url');
        $profilePhotoClass = Common::getUserThumbClass($type);

        $this->set(compact('model', 'inputDefaults', 'userId', 'userImage', 'profilePhotoClass'));
    }

}
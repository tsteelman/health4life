<?php

/**
 * PrivacyController class file.
 *
 * @author    Amith Hariharan <amit@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('AuthComponent', 'Controller/Component');
App::uses('UserPrivacySettings', 'Lib');

class PrivacyController extends UserAppController {

    /**
     * Models used by this controller
     *
     * @var array
     */
    public $uses = array('User');

    /**
     * Edit User Account Settings
     */
    public function index() {
		$this->set('title_for_layout',"Privacy Settings");
        $inputDefaults = array(
            'label' => false,
            'div' => false,
            'class' => 'form-control'
        );
        $model = 'User';

		$options = array('1'=>"Only me", '2'=>"Friends", '3'=>"Anyone");

        $userId = $this->Auth->user('id');
        $userData = $this->User->findById($userId);
        $type = $userData['User']['type'];
        $userImage = Common::getUserThumb($userId, $type, 'medium', 'img-responsive pull-left img-thumbnail', 'url');
        $profilePhotoClass = Common::getUserThumbClass($type);

		// get privacy settings
		$privacy = new UserPrivacySettings($userId);
		$privacyfields = $privacy->getPrivacySettings();

        if (empty($this->request->data)) {
            $userId = $this->Auth->user('id');
            $this->request->data = $this->User->findById($userId);
        }
		else {
			if ($privacy->setPrivacyValue($userId, $this->request->data)) {
				$this->Session->setFlash(__('Privacy settings updated successfully.'), 'success');
				$privacyfields = $this->request->data['User'];
			 }
			 else {
				 $this->Session->setFlash(__('Privacy settings updation failed.'), 'error');
			 }
		}
		// set view variables
        $this->set(compact('model', 'inputDefaults', 'userId', 'userImage', 'profilePhotoClass', 'privacyfields', 'options'));
    }

}
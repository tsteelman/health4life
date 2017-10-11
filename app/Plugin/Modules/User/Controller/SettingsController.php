<?php

/**
 * SettingsController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('AuthComponent', 'Controller/Component');
App::import('Controller', 'Api');

/**
 * SettingsController for the frontend
 * 
 * SettingsController is used for front end user settings
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class SettingsController extends UserAppController {

    /**
     * Models used by this controller
     * 
     * @var array
     */
    public $uses = array('User', 'UserSettingsForm', 'ChangePasswordForm', 'Timezone', 'Languages', 'NotificationSetting');

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
		$this->set('title_for_layout',"Account Settings");
        $changeTimezoneFormId = 'userSettingsForm';
        $inputDefaults = array(
            'label' => false,
            'div' => false,
            'class' => 'form-control'
        );
        $timeZones = $this->Timezone->get_timezone_list();
        $languages = $this->Languages->getLanguagesList();
        $changeTimezoneModelForm = 'UserSettingsForm';
        $model = 'User';
        $unitSettingsModel = 'NotificationSettingForm';
        // validation for timezone form
        $validations = $this->$changeTimezoneModelForm->validate;
        $this->JQValidator->addValidation($model, $validations, $changeTimezoneFormId);

        $userId = $this->Auth->user('id');
        $userData = $this->User->findById($userId);
        $type = $userData['User']['type'];
        $userImage = Common::getUserThumb($userId, $type, 'medium', 
        		'img-responsive pull-left img-thumbnail', 'url');
        $profilePhotoClass = Common::getUserThumbClass($type);
        
        $unit_settings = $this->NotificationSetting->getUnitSettings($userId);
        
        // set view variables
        $this->set(compact('changeTimezoneFormId', 'model', 'unitSettingsModel', 
        		'inputDefaults', 'timeZones', 'languages', 'userId', 'userImage', 
        		'profilePhotoClass', 'unit_settings'));

        if (empty($this->request->data)) {
            // edit
            $userId = $this->Auth->user('id');
            $this->request->data = $this->User->findById($userId);

        } else {        	
            // save
            $this->__saveUserSettings();
            $this->redirect(array ("action" => "index"));
        }
    }

    /**
     * Saves User Account Settings
     */
    private function __saveUserSettings() {
        $userId = $this->Auth->user('id');
        $this->User->id = $userId;
        $userSettings['User'] = $this->request->data['User'];
        $notificationSetting = $this->request->data['NotificationSetting'];

        if ($this->User->save($userSettings) && $this->NotificationSetting->changeUnitSetting($notificationSetting, $userId)) {
            $this->Session->write('Auth', $this->User->read(null, $userId));
            $this->Session->setFlash(__('Account settings saved successfully.'), 'success');
        } else {
            $this->Session->setFlash(__('Failed to save account settings.'), 'error');
        }
    }
    
    /*
     * Function to change the units of measurement.
     */
    public function changeUnits() {
        
        $data = $this->request->query('value');
        $this->autoRender = false;
        $userId = $this->Auth->user('id');
        $result = $this->NotificationSetting->changeUnitSetting($data, $userId);
        return $result;
    }

}
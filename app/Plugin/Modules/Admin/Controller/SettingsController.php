<?php

/**
 * SettingsManagementController class file.
 *
 * @author    Varun Ashok <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
/**
 * Settings Management for the admin
 *
 * Settings Management Controller is used for admin to control app settings
 *
 * @author 	Varun Ashok
 * @package 	Admin
 * @category	Controllers
 */
App::uses('Common', 'Utility');
App::uses('CakeTime', 'Utility');

class SettingsController extends AdminAppController {

    public $uses = array(
        'User',
        'Configuration'
    );
    public $components = array('Session');
    
    /**
     * Settings Management home
     */
    function index() {
          $configurations = $this->Configuration->find('all', array(
                ));
          $adminList = $this->User->find('list', array(
              'conditions' => array('is_admin' => 1, 'status' => 1),
              'fields' => array('User.username_email')
           ));
          $isSuperAdmin = $this->_isSuperAdmin();
          if ($isSuperAdmin === false) {
			$this->redirect('/admin/users/accessDenied');
          }
           
          $this->set(compact('adminList', 'configurations'));
    }
    
    /**
     * Function to edit settings
     */
    public function editSettings() {
          if (!empty($this->request->data)) {
              foreach ($this->request->data as $data) {
                  foreach ($data as $key => $value) {
                      if(empty($value)) {
                          $this->Session->setFlash(__('Please choose a valid option', true));
                          $this->redirect(array('action' => 'index'));
                      }
                      $status = $this->Configuration->updateConfig($key, $value);
                      if(!$status) {
                          break;
                      }
                  }
              }
              if($status) {
                  $this->Session->setFlash(__('Settings updated', true));
              } else {
                  $this->Session->setFlash(__("Settings not updated, try again later", true));
              }
              $this->redirect(array('action' => 'index'));
          }
    }
}

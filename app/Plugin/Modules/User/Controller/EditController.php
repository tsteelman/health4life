<?php

/**
 * EditController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('RegisterController', 'User.Controller');
App::import('Vendor', 'ImageTool');
App::uses('Validation', 'Utility');
App::import('Controller', 'Api');

/**
 * EditController for the frontend
 *
 * EditController is used for edit user profile details
 *
 * @author 		Ajay Arjunan
 * @package 	User
 * @category	Controllers
 */
class EditController extends UserAppController {

    public $uses = array('User', 'UserEditForm', 'Country', 'State');
    
    public $components = array('EmailTemplate');

    /**
     * Override parent function to get the current dasboard item
     *
     * @param null
     * @return String
     */
    protected function getCurrentDashbaordItem()
    {
            return "settings";
    }

    function index() {
		$this->set('title_for_layout',"Edit Profile");
        $formId = 'userEditForm';
        $inputDefaults = array(
            'label' => false,
            'div' => false,
            'class' => 'form-control'
        );
        $modelForm = 'UserEditForm';
        $model = 'User';

        $countryList = $this->Country->getAllCountries();



        //validation
        $validations = $this->$modelForm->validate;
        $this->JQValidator->addValidation($model, $validations, $formId);

        if (empty($this->request->data)) {
            // edit
            $userId = $this->Auth->user('id');
            $userData = $this->User->findById($userId);

            $stateList = $this->Country->getCountryStates($userData['User']['country']);
            $cityList = $this->State->getStateCities($userData['User']['state']);
			$zipMandatoryClass = $this->Country->getZipMandatoryClass($userData['User']['country']);
            $username = $userData['User']['username'];
            $email = $userData['User']['email'];
            $userDob = $userData['User']['date_of_birth'];
            $userData['User']['aboutMe'] = html_entity_decode($userData['User']['about_me']);
			if (!is_null($userDob) && ($userDob !== '')) {
				$userDobArray = explode("-", $userDob);
			} else {
				$userDobArray = array();
			}
            $type = $userData['User']['type'];
            $userImage = Common::getUserThumb($userId, $type, 'medium',
                    'img-responsive pull-left img-thumbnail', 'url');
            $dob = array();
            $dob['year'] = Date::getBirthYears();
            $dob['month'] = Date::getMonths();
            $dob['day'] = Date::getDays();

            if (!empty($userDobArray)) {
                $userData ['User'] ['dob-year'] = $userDobArray [0];
                $userData ['User'] ['dob-month'] = $userDobArray [1];
                $userData ['User'] ['dob-day'] = $userDobArray [2];
                $dob['day'] = Date::getDaysOfMonth($userData ['User'] ['dob-month'], $userData ['User'] ['dob-year']);
            }

            $this->request->data = $userData;

			$profilePhotoClass = Common::getUserThumbClass($type);
        } else {
            // save


            $this->__saveUserProfileDetails();
            $this->redirect('index');
        }

        // set view variables
        $this->set(compact('formId', 'model', 'inputDefaults', 'type', 'dob',
                'countryList', 'stateList', 'cityList', 'username', 'email',
                'userId', 'userImage', 'profilePhotoClass', 'zipMandatoryClass'));
    }

    /**
     * Saves User profiledetails
     */
    private function __saveUserProfileDetails() {
        $userId = $this->Auth->user('id');
        $this->User->id = $userId;
        // formatting dob
        $userData = $this->request->data['User'];
		/* if email edited properly save it else update other details */
		if ($userData['verified'] != 1) {
			unset($userData['email']);
		}
		else {
			// check for duplicates
			 if (Validation::email($userData['email'], true)) {
				 $email_exist = $this->User->find('all',
					array('conditions' => array('User.email' => $userData['email'],
						'User.id !=' => $userId),
					'limit' => 1));
				if (!empty($email_exist)) {
					$this->Session->setFlash(__('Email already registered for different user.'), 'error');
					return;
				}
                                
                                $this->__sendEmailChangeNotification($userData['email'], $userData['oldEmail']);
                                
			 }
			 else {
				 $this->Session->setFlash(__('Please enter a valid email address.'), 'error');
				return;
			 }

		}
		
		if ((!empty($userData['dob-year'])) && (!empty($userData['dob-month'])) && (!empty($userData['dob-day']))) {
			$dob = join('-', array(
				$userData['dob-year'],
				$userData['dob-month'],
				$userData['dob-day'],
			));
		} else {
			$dob = null;
		}
        $this->request->data['User']['date_of_birth'] = $dob;
        $this->request->data['User']['about_me'] = htmlentities($userData['aboutMe']);
        if ($this->User->save($this->request->data)) {
            $this->Session->write('Auth', $this->User->read(null, $userId));
            $this->Session->setFlash(__('Account settings saved successfully.'), 'success');
        } else {
            $this->Session->setFlash(__('Failed to save account settings.'), 'error');
        }
    }

    public function changeProfilePic() {
        $image_name = $this->request->data['cropfileName'];
        $userId = $this->request->data['id'];
        $registerController = new RegisterController();
        $result = $registerController->save_user_image($userId, $image_name);
        if ($result['success']) {
            $this->Session->setFlash(__('Image changed successfully.'), 'success');
        } else {
            $this->Session->setFlash($result['msg'], 'error');
        }
        $this->User->id = $this->Auth->user('id');
        $this->User->save(array());
        $this->redirect('/user/edit');
    }


	/**
	 * Function to validate user password
	 *
	 * @return JSON $response
	 */
	public function verify_user()	{
		$userId = $this->Auth->user('id');
		$this->User->id = $userId;
		$this->autoRender = false;
		$userPassword = $this->User->getpassword($userId);
		$pwd = AuthComponent::password($this->request->data['password']);
		if ($userPassword == $pwd) {
			$response = json_encode(array('status' => 'success'));
		}
		else {
			$response = json_encode(array('status' => 'failed',
				'message' => 'Invalid Password'));
		}
		echo $response;
	}
        
        private function __sendEmailChangeNotification($email, $oldEmail) {

            $templateData = array(
                    'email' => $email,
                    'oldEmail' => $oldEmail,
                    'username' => $this->Auth->user('username')
                );
            
            $Api = new ApiController();
            $Api->constructClasses();
            $Api->sendHTMLMail(EmailTemplateComponent::CHANGE_EMAIL_NOTIFICATION, $templateData, $email);
            
//            $emailTemplateData = $this->EmailTemplate->getEmailTemplate($templateId, $templateData);
//        $emailTemplate = $emailTemplateData['EmailTemplate'];
//
//        // email queue to be saved
//        $mailData = array(
//            'subject' => $emailTemplate['template_subject'],
//            'to_name' => $templateData['username'],
//            'to_email' => $toEmail,
//            'content' => json_encode($templateData),
//            'email_template_id' => $templateId,
//            'module_info' => 'API Email',
//            'priority' => Email::DEFAULT_SEND_PRIORITY
//        );
//
//        $this->EmailQueue->createEmailQueue($mailData);
            
            
//            public function sendHTMLMail($templateId, $templateData, $toEmail) {
            
        }
}
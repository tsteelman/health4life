<?php

/**
 * RegisterController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('File', 'Utility');
App::import('Controller', 'Api');
App::uses('FollowingPage', 'Model');

/**
 * RegisterController for the frontend application.
 * 
 * RegisterController is used for frontend app registration.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class RegisterController extends UserAppController {

    /**
     * Variable to store the minimum profile image size
     */
    public $minimumImageSize = array('200', '200');

    /**
     * Models to be used in the controller
     */
    public $uses = array(
        'User',
        'Country',
        'UserRegistrationForm',
        'PatientDisease',
        'CareGiverPatient',
        'ActionToken',
        'Disease',
        'Configuration',
        'Volunteer',
		'Notification',
		'NotificationSetting',
		'FollowingPage'
    );

    /**
     * Components to be used in the controller
     */
    public $components = array('Email', 'EmailTemplate', 'EmailQueue', 'Uploader', 'Otp');

    /**
     * Initialize componenets
     *
     * @param null
     */
    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('crop', 'index', 'resendActivationMail',
				'activate', 'getDiagnosisForm', 'photo',
				'getSupportDiagnosisForm'
				);

        App::uses('User', 'Model');
        App::uses('Date', 'Utility');
        App::import('Vendor', 'ImageTool');
    }

    /**
     * Initial page to be shown while taking registration page
     *
     * @param null
     */
    public function index() {
        // if there is an action after registration
        if (isset($this->request->query['token'])) {
            $action_token = $this->request->query['token'];
            // check whether the action token exists
            if ($this->ActionToken->isExistsToken($action_token)) {
                $this->Session->write('action_token', $action_token);
                if (isset($this->request->query['profile'])) {
                    $this->Session->write('profile', true);
                }
                if (isset($this->request->query['rej'])) {
                    $this->Session->write('reject', true);
                }
            } else {
                if (isset($this->request->query['e'])) {
                    $email = $this->request->query['e'];
                    $user = $this->User->findByEmail($email);
                    if (!empty($user['User'])) {
                        //already responded user           			
                        $this->Session->setFlash(__('You have already responded to this email.'), 'warning');
                        return $this->redirect($this->Auth->redirectUrl($this->Auth->loginRedirect));
                    }
                }
                $this->Session->setFlash(__('This link is not valid anymore.'), 'error');
                return $this->redirect($this->Auth->redirectUrl($this->Auth->loginRedirect));
            }
        }

        // if user is logged in, redirect
        if ($this->Auth->loggedIn()) {
            $this->redirect($this->Auth->loginRedirect);
        }

        /*
         * Create the user and send avtivation mail on 
         * successful signup
         */
        if ($this->request->isPost()) {
            $this->_createUser();
        } else {
            $formId = 'User';
            $validationOptions = $this->UserRegistrationForm->validate;
            $this->JQValidator->addValidation('User', $validationOptions, $formId);

            $countryList = $this->Country->getAllCountries();
            $dob = array();
            $dob['year'] = Date::getBirthYears();
            $dob['month'] = Date::getMonths();
            $dob['day'] = Date::getDays();
            $dob['diagnosisYear'] = Date::getYears();
            $defaultDOBStartingYear = Date::getDOB_DefaultStartingYear();
			$userTypesData = $this->__getUserTypesData();
			$defaultUserType = User::ROLE_PATIENT;
			$videoUrl = Configure::read('App.signUpVideo');
			$this->set(compact('countryList', 'formId', 'dob', 'defaultDOBStartingYear', 'defaultUserType', 'userTypesData', 'videoUrl'));
		}
	}
	
	/**
	 * Function to get the data of the user types
	 * 
	 * @return array
	 */
	private function __getUserTypesData() {
		$userTypesData = array();
		$userTypes = array(
			User::ROLE_PATIENT,
			User::ROLE_FAMILY,
			User::ROLE_CAREGIVER,
			User::ROLE_OTHER
		);
		foreach ($userTypes as $userType) {
			switch ($userType) {
				case User::ROLE_PATIENT:
					$roleName = __('Patient');
					$roleDescription = __('A patient is any recipient of health care services. The patient is most often ill or injured and in need of treatment by a physician or looked after by a Caregiver.');
					$roleClass = 'role_patient active';
					$roleImg = 'patient_role.png';
					break;
				case User::ROLE_FAMILY:
					$roleName = __('Family');
					$roleDescription = __('A a family is a group of people affiliated by consanguinity (by recognized birth), affinity (by marriage), or co-residence/shared consumption.');
					$roleClass = 'role_family';
					$roleImg = 'family_role.png';
					break;
				case User::ROLE_CAREGIVER:
					$roleName = __('Caregiver');
					$roleDescription = __('A caregiver is someone who is responsible for the care of someone who has poor mental health, physically disabled or whose health is impaired by sickness or old age.');
					$roleClass = 'role_caregiver';
					$roleImg = 'caregiver_role.png';
					break;
				case User::ROLE_OTHER:
					$roleName = __('Friends');
					$roleDescription = __('Friendship is a relationship of mutual affection between two or more people. Friendship is a stronger form of interpersonal bond than an association.');
					$roleClass = 'role_friend';
					$roleImg = 'other_role.png';
					break;
			}
			$imgPath = '/theme/App/img/';
			$roleImgPath = $imgPath . $roleImg;
			$userTypesData[] = compact('userType', 'roleName', 'roleDescription', 'roleClass', 'roleImgPath');
		}

		return $userTypesData;
	}

	/**
     * Function to create the user on successfull registration
     *
     * @param null
     */
    public function _createUser() {
        $postData = $this->request->data;
        $userData = $postData['User'];
        $userDetails['type'] = $userData['type'];
        $userDetails['username'] = $userData['username'];
        $userDetails['email'] = $userData['email'];
        $userDetails['password'] = $userData['password'];
        $userDetails['first_name'] = $userData['firstname'];
        $userDetails['last_name'] = $userData['lastname'];
		if ((!empty($userData['dob-year'])) && (!empty($userData['dob-month'])) && (!empty($userData['dob-day']))) {
			$dob = join('-', array(
				$userData['dob-year'],
				$userData['dob-month'],
				$userData['dob-day'],
			));
		} else {
			$dob = null;
		}
        $userDetails['date_of_birth'] = $dob;
        $userDetails['gender'] = $userData['gender'];
        $userDetails['country'] = $userData['country'];
        $userDetails['state'] = $userData['state'];
        $userDetails['city'] = $userData['city'];
        $userDetails['zip'] = $userData['zip'];
        $userDetails['timezone'] = $userData['timezone'];
        if ($userDetails['timezone'] == '') {
            // set default timezone US/Central
            $userDetails['timezone'] = 'US/Central';
        }
        $userDetails['last_login_datetime'] = date("Y-m-d H:i:s");
		$userDetails['feeling_popup_datetime'] = date("Y-m-d H:i:s");
        $userDetails['created'] = date("Y-m-d H:i:s");
        $userDetails['is_admin'] = 0;
        if (isset($userData['newsletter'])) {
            $userDetails ['newsletter'] = $userData ['newsletter'];
        }

        // after successful registration, send activation mail,
        // make the user auto login, and congratulate the user for sign up.
        if ($this->User->createUser($userDetails)) {
            $id = $this->User->id;

            $this->_addPatientDiseases($id);

            /*
             * Save the userthumbnail
             */
            $this->save_user_image($this->User->id, $postData['cropfileName']);

			$userDetails['id'] = $id;
            $this->_sendActivationMail($userDetails);
            unset($userDetails['password']);
            $this->Auth->login($userDetails);

            // set the status that first login today, to show "how are you feeling" pop up.
            $this->Session->write('isFirstLoginToday', true);

            //add invited users to pending frineds
            $this->__addPendingFriendRequest($id, $userDetails['email']);
			
			// add default unit settings (Fahrenheit and imperial)
			$unitSettings = array('height' => '1','weight' => '1','temp' => '2');
			$this->NotificationSetting->changeUnitSetting($unitSettings, $id);

            // default friendId for new users
            $friendId = $this->Configuration->getValue('new_users_friend_id');
            if(empty($friendId)) {
                $friendId = 0;
            }
           
            // add Default friend
            $this->__addDefaultFriend($id, $friendId);
            
            // add user as a volunteer
            if(isset($userData['volunteer'])) {
                $this->__addVolunteer($id);
            }
			
            //check for action after registration
            if ($this->Session->check('action_token')) {

                $this->loadModel('ActionToken');

                $action_token = $this->Session->read('action_token');
                $this->Session->delete('action_token');
                $action = $this->ActionToken->find('first', array(
                    'conditions' => array('ActionToken.token' => $action_token),
                    'fields' => array('ActionToken.id', 'ActionToken.action')
                ));

                if (!empty($action)) {
                    $actionData = json_decode($action['ActionToken']['action'], true);

                    if ($actionData['action'] == 'addFriend') {
                        // there is a redirection in this function !!   			
                        $this->__addFriend($action_token, $action['ActionToken']['id'], $actionData, $id);
                    }
                }
            }

            // redirect to loginRedirect after deleting the action tokens.
            $this->registerRediredt();
        }
    }

    private function __addFriend($action_token, $action_id, $actionData, $user_id) {

        $this->loadModel('MyFriends');
        if ($this->Otp->authenticateOTP($action_token, array('friend_email' => $actionData['friend_email'], 'friendId' => $actionData['user_id']))) {

            $this->ActionToken->delete($action_id);
            $friend_id = $actionData ['user_id'];
            if ($this->Session->read('profile')) {
                $this->Session->delete('profile');

                $friend_user_data = $this->User->getUserDetails($friend_id);
                $friend_username = $friend_user_data ['user_name'];
                //redirect to friend profile after deleting the action tokens
                $this->registerRediredt($friend_username);
            } else if ($this->Session->read('reject')) {
                $this->MyFriends->rejectFriend($user_id, $friend_id);
            } else {
                $this->MyFriends->approveFriend($user_id, $friend_id);
            }
        }
    }

    /**
     * Function to add invited useres to pending friends.
     * @param int $user_id
     * @param string $email
     */
    private function __addPendingFriendRequest($user_id, $email) {

        $this->loadModel('InvitedUser');
        $this->loadModel('MyFriends');
        $invited_user_list = $this->InvitedUser->getAllInvitedUsers($email);
        foreach ($invited_user_list as $friend_details) {
            $this->MyFriends->addFriend($friend_details->user_id, $user_id);
        }
        $this->InvitedUser->deleteAllInvitedUsers($email);
    }

    /**
     * Adds patient diseases
     * 
     * @param int $patientId
     */
    public function _addPatientDiseases($patientId) {
		if (isset($this->request->data['PatientDisease'])) {
			$patientDiseases = $this->request->data['PatientDisease'];
			foreach ($patientDiseases as $patientDisease) {
				if (($patientDisease ['disease_id'] > 0) || (!empty($patientDisease ['disease_name']))) {
					// diagnosis date
					if (!empty($patientDisease['diagnosis_date_year'])) {
						$diagnosisDate = join('-', array(
							$patientDisease['diagnosis_date_year'],
							'01',
							'01'
						));
						$diagnosisDate.=' 00:00:00';
					} else {
						$diagnosisDate = '0000-00-00 00:00:00';
					}

					// for new user created disease
					if ($patientDisease ['disease_id'] == 0) {
						$this->Disease->create();
						$data_disease['Disease']['id'] = '';
						$data_disease['Disease']['name'] = $patientDisease ['disease_name'];
						$data_disease['Disease']['user_id'] = $patientId;
						$data_disease['Disease']['status'] = Disease::AWAITING_USER_CREATED_DISEASE; // user requested disease
						$flag = $this->Disease->save($data_disease, array(
							'validate' => false
						));
						$patientDisease['disease_id'] = $this->Disease->id;
					}


					// data to be saved
					$data = array(
						'disease_id' => $patientDisease['disease_id'],
						'patient_id' => $patientId,
						'diagnosis_date' => $diagnosisDate
					);

					$this->PatientDisease->create();

					//Disease follow data
					$diseaseData = array(
						'type' => FollowingPage::DISEASE_TYPE,
						'page_id' => $patientDisease['disease_id'],
						'user_id' => $patientId,
						'notification' => FollowingPage::NOTIFICATION_ON
					);
					$this->FollowingPage->followPage($diseaseData);
					$patientDiseaseData = $this->PatientDisease->save($data, array('validate' => false));
					$patientDiseaseId = $patientDiseaseData['PatientDisease']['id'];

					// add user treatments
					if (!empty($patientDisease['treatment_id'])) {
						$this->loadModel('UserTreatment');
						$treatments = explode(',', trim($patientDisease['treatment_id'], ','));
						foreach ($treatments as $treatmentId) {
							$this->UserTreatment->addPatientTreatment($patientId, $treatmentId, $patientDiseaseId);
						}
					}
				}
			}
		}
	}

	/**
     * Resends activation email to user
     *
     * @param null
     */
    public function resendActivationMail() {
        if (!$this->request->is('ajax')) {
            return false;
        } else {
            $this->autoRender = false;
            $this->disableCache();
            $username = $this->request->data['username'];
            $user = $this->User->findByEmailOrUsername($username, $username);
            if (!empty($user['User'])) {
                $user = $user['User'];
                $status = $this->_sendActivationMail($user);
                if ($status === true) {
                    $email = $user['email'];
                    $result = array(
                        'success' => true,
                        'message' => __("Activation mail sent to {$email}. Please check your inbox. The activation link will expire in 24 hours.")
                    );
                } else {
                    $result = array(
                        'error' => true,
                        'message' => __('Failed to resend activation email.')
                    );
                }
            } else {
                $result = array(
                    'error' => true,
                    'message' => __('No such user.'),
                );
            }
            echo json_encode($result);
        }
    }

    /**
     * Sends activation email
     *
     * @param array $user
     * @return boolean
     */
    private function _sendActivationMail($user) {
		$link = $this->User->generateActivationLink($user);
        $email = $user['email'];
        $username = Common::getUsername($user['username'], $user['first_name'], $user['last_name']);
        $emailData = array(
            'username' => $username,
            'link' => $link
        );

        $emailManagement = $this->EmailTemplate->getEmailTemplate(EmailTemplateComponent::ACCOUNT_ACTIVATION_TEMPLATE, $emailData);

        try {
            // email queue to be saved
            $mailData = array(
                'subject' => $emailManagement['EmailTemplate']['template_subject'],
                'to_name' => $emailData['username'],
                'to_email' => $email,
                'content' => json_encode($emailData),
                'email_template_id' => EmailTemplateComponent::ACCOUNT_ACTIVATION_TEMPLATE,
                'module_info' => 'Activation Email',
                'priority' => '3'
            );

            $this->EmailQueue->createEmailQueue($mailData);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Activates a user
     * 
     * @param string $key
     */
    public function activate($key) {

        $activateSuccess = false;
        $user = array();
        $data = json_decode(base64_decode($key));
        
        if ($data != null) {
            $user = $this->User->findByEmail($data->email);
        }

        $now = microtime(true);

        if (!empty($user['User'])) {
			if ((int) $user['User']['status'] === User::STATUS_ACTIVE) {
				$activateMessge = __('It seems like you have already activated the account.');
			} else {
				$username = $user['User']['username'];
				if ($this->Auth->loggedIn() && ($this->Auth->user('username') !== $username)) {
					$activateMessge = __('You are not allowed to access this link.');
				} else {
					if ($user['User']['activation_token'] == $key) {
						if ($data->time >= $now) {
							$save_data = array('id' => $user['User']['id'], 'activation_token' => NULL, 'status' => 1);
							$this->User->save($save_data);
							$this->_sendWelcomeMail($user['User']);
							$activateMessge = __('Your account is activated successfully.');
							$activateSuccess = true;
						} else {
							$activateMessge = __('Sorry, the link has expired. Please click <a id="resend_activation_mail_link" data-username = ' . $username . '>here</a> to resend activation mail to the registered email address.');
							$activateSuccess = true;
						}
					} else {
						$activateMessge = __('This seems to be an invalid link. Please click <a id="resend_activation_mail_link" data-username = ' . $username . '>here</a> to resend activation mail to the registered email address.');
					}
				}
			}
		} else {
			$activateMessge = __('Sorry, we are not able to identify the user.');
		}

         
        $this->set(compact('activateMessge', 'activateSuccess'));
        
        // if a user is logged in and they authenticate, the success message 
        // should appear on the page they were on
		if ($this->Auth->loggedIn()) {
			$currentPage = $this->Session->read('currentPage');
			$element = ($activateSuccess === true) ? 'success' : 'error';
			$this->Session->setFlash($activateMessge, $element);
			$this->redirect($currentPage);
		}
    }

    /**
     * Sends welcome email to the activated user
     * 
     * @param array $user
     * @return boolean
     */
    public function _sendWelcomeMail($user) {
        $name = Common::getUsername($user['username'], $user['first_name'], $user['last_name']);
        $email = $user['email'];
        $emailData = array(
            'name' => $name,
            'username' => $name,
            'email' => $email
        );

        $emailManagement = $this->EmailTemplate->getEmailTemplate(EmailTemplateComponent::WELCOME_MAIL_TEMPLATE, $emailData);

        try {
            // email queue to be saved
            $mailData = array(
                'subject' => $emailManagement['EmailTemplate']['template_subject'],
                'to_name' => $emailData['username'],
                'to_email' => $email,
                'content' => json_encode($emailData),
                'email_template_id' => EmailTemplateComponent::WELCOME_MAIL_TEMPLATE,
                'module_info' => 'Welcome Email',
                'priority' => Email::DEFAULT_SEND_PRIORITY
            );

            $this->EmailQueue->createEmailQueue($mailData);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Function to get the diagnosis form
     * in the Ajax request
     */
    public function getDiagnosisForm() {
        $dob = array();
        $dob['month'] = Date::getMonths();
        $dob['diagnosisYear'] = Date::getYears();
        $dob['day'] = Date::getDays();


        $index = $this->request->data['index'];
        $options = array(
            'label' => false,
            'div' => false,
        );
        $view = new View($this, false);
        echo $view->element('User.diagnosis_form', compact('index', 'dob', 'options'));
        $this->autoRender = false;
    }
	
	/**
     * Function to get the diagnosis which friends and family support form
     * in the Ajax request
     */
    public function getSupportDiagnosisForm() {
        
        $index = $this->request->data['index'];
        $options = array(
            'label' => false,
            'div' => false,
        );
        $view = new View($this, false);
        echo $view->element('User.diagnosis_addsupport_form', compact('index', 'options'));
        $this->autoRender = false;
    }

    public function crop() {
        $this->layout = null;
        $upload_path = Configure::read("App.UPLOAD_PATH");
        $thumbnail_path = Configure::read("App.PROFILE_IMG_PATH");
        $upload_url = Configure::read("App.UPLOAD_PATH_URL");
        $uploaded_image = "mac.jpg";
        $x1 = "120";
        $y1 = "10";
        $width = "200";
        $height = "200";


        $webFolder = $thumbnail_path;
        $tempUrl = FULL_BASE_URL . "/uploads/tmp/";
        $webUrl = FULL_BASE_URL . "/uploads/user_profile";

        $fileName = $uploaded_image;
        $imageUrl = $tempUrl . $uploaded_image;

        $newphotoPath = $upload_path . DIRECTORY_SEPARATOR . $uploaded_image;
        $photoPath = $upload_path . DIRECTORY_SEPARATOR . "149_new.jpg";

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
                        'input' => $newphotoPath,
                        'output' => $photoPath,
                        'width' => $width,
                        'height' => $height,
                        'output_width' => $this->minimumImageSize[0],
                        'output_height' => $this->minimumImageSize[1],
                        'top' => $y1,
                        'left' => $x1,
            ));

            $result['success'] = true;
            $result['fileUrl'] = $tempUrl . "" . $uploaded_image;
            $result['fileName'] = $uploaded_image;
        } else {
            throw new Exception("Image Not cropped");
        }
    }

    /**
     * Function to upload user profile photo and give response in 
     * Ajax request
     */
    public function photo() {

        $this->layout = null;
        $upload_path = Configure::read("App.UPLOAD_PATH");
        $thumbnail_path = Configure::read("App.PROFILE_IMG_PATH");
        $upload_url = Configure::read("App.UPLOAD_PATH_URL");
        $webFolder = $thumbnail_path;
        $tempUrl = FULL_BASE_URL . "/uploads/tmp/";
        $webUrl = FULL_BASE_URL . "/uploads/user_profile";
        /*
         * Do the image Cropping
         */
        if (isset($this->request->data['crop_image'])) {


            $uploaded_image = $this->request->data['cropfileName'];

            try {

                $options = array('thumbnail' => array("max_width" => $this->minimumImageSize[0],
                        "max_height" => $this->minimumImageSize[1],
                        "path" => $thumbnail_path),
                    'max_width' => 700);
                $x1 = $_POST["x1"];
                $y1 = $_POST["y1"];
                $width = $_POST["w"];
                $height = $_POST["h"];
                $fileName = $_POST['cropfileName'];
                $imageUrl = $tempUrl . $_POST['cropfileName'];

                $photoPath = $upload_path . DIRECTORY_SEPARATOR . $uploaded_image;

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
                    $result['fileUrl'] = $tempUrl . "" . $uploaded_image;
                    $result['fileName'] = $uploaded_image;
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
                $result = $uploader->handleUpload($upload_path);

                if (isset($result['success'])) {
                    $result['file_name'] = $uploader->getUploadName();

                    $photoPath = $upload_path . DIRECTORY_SEPARATOR . $result['file_name'];
                    $status = ImageTool::resize(array(
                                'quality' => 90,
                                'enlarge' => false,
                                'keepRatio' => true,
                                'paddings' => false,
                                'crop' => false,
                                'input' => $photoPath,
                                'output' => $photoPath,
                                'width' => '570',
                                'height' => '270'
                    ));

					$result['fileName'] = $result['file_name'];
					$result['fileurl'] = $tempUrl . DIRECTORY_SEPARATOR . $result['file_name'];

					// image dimension
					list($imageWidth, $imageHeight) = getimagesize($photoPath);
					$result['imageWidth'] = $imageWidth;
					$result['imageHeight'] = $imageHeight;
				}


                echo json_encode($result);
            } else {
                header("HTTP/1.0 405 Method Not Allowed");
            }
        }

        exit;
    }

    /**
     * Function to save the cropped image of the user 
     * into different dimensions and save them to a new folder
     *
     * @param int $user_id
     * @param string $image_name
     */
    public function save_user_image($user_id, $image_name) {
        $result['success'] = false;

        try {
            if (isset($image_name) && !empty($image_name)) {

                $upload_path = Configure::read("App.UPLOAD_PATH");
                $thumbnail_path = Configure::read("App.PROFILE_IMG_PATH");
                $cropped_image = $upload_path . DIRECTORY_SEPARATOR . $image_name;

                if (file_exists($cropped_image)) {
                    $cropped_file = new File($cropped_image);


                    if (!file_exists($thumbnail_path)) {
                        mkdir($thumbnail_path, 0777);
                    }


                    /*
                     * Resize and copy the image to new folders
                     */
                    $image_sizes = Common::getUserThumbSize();

                    foreach ($image_sizes as $suffix => $images) {
                        $target_image = $thumbnail_path . DIRECTORY_SEPARATOR . md5($user_id) . "_" . $suffix . ".jpg";
                        $status = ImageTool::resize(array(
                                    'input' => $cropped_image,
                                    'output' => $target_image,
                                    'width' => $images['w'],
                                    'height' => $images['h']
                        ));
                    }

                    /*
                     * Move the original image also
                     */
                    $original_thumb = $thumbnail_path . DIRECTORY_SEPARATOR . md5($user_id) . "_original.jpg";
                    $cropped_file->copy($original_thumb, true);

                    /*
                     * remove the initial cropped image
                     */
                    $cropped_file->delete();

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

    public function registerRediredt($username = null) {
        $this->ActionToken->deleteWhereEmailPresentInAddFriend($actionData ['friend_email']);
		$this->Notification->addRegisterNotification($this->Auth->user('id'));
        $this->Session->setFlash(__('Congrats for signing up to '. Configure::read ( 'App.name' ) .'. Please check your inbox for the activation link which will expire in 24 hours.'), 'success');
        if ($username != null) {
            return $this->redirect($this->Auth->redirectUrl(Common::getUserProfileLink( $friend_username, true) ));
        } else {
            return $this->redirect($this->Auth->redirectUrl($this->Auth->loginRedirect));
        }
    }
    
    /**
     * Function to add defalt friend for registering user
     * @param unknown $userId
     */
    private function __addDefaultFriend($userId, $friendId) {

        $this->loadModel('MyFriends');
        $this->MyFriends->addFriend($userId, $friendId);
        $this->MyFriends->approveFriend($friendId, $userId);
    }
    
    /**
     * Function to add user as a volunteer
     * 
     * @param int $userId
     */
    public function __addVolunteer($userId) {
        $data['user_id'] = $userId;
        $data['type'] = 0;
        $data['created'] = date("Y-m-d H:i:s");
        $this->Volunteer->createVolunteer($data);
    }

}
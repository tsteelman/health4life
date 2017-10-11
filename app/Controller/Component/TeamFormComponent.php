<?php

/**
 * TeamFormComponent class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('Team', 'Model');
App::import('Vendor', 'ImageTool');

/**
 * TeamFormComponent for adding teams.
 * 
 * This class is used for team add/edit form display and save.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Controller.Component
 * @category	Component 
 */
class TeamFormComponent extends Component {

	/**
	 * Constructor
	 * 
	 * Initialises the models
	 */
	public function __construct() {
		$this->Team = ClassRegistry::init('Team');
	}

	/**
	 * Initialises the component
	 * 
	 * @param Controller $controller
	 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->user = $controller->Auth->user();
	}

	/**
	 * Sets the team form data on the controller
	 */
	public function setFormData($teamId = null) {
		$formId = 'teamForm';
		$inputDefaults = array(
			'label' => false,
			'div' => false,
			'class' => 'form-control'
		);

		$title = __('Create Team');
		$submitBtnTxt = __('Create');
		if (!is_null($teamId) && $teamId > 0) {
			$title = __('Edit Team');
			$submitBtnTxt = __('Update');
		}

		// set data on controller
		$this->controller->set(compact('formId', 'inputDefaults', 'title', 'submitBtnTxt'));

		// validation
		$model = 'Team';
		$validations = $this->$model->validate;
		$this->controller->JQValidator->addValidation($model, $validations, $formId);
	}

	/**
	 * Saves a team
	 */
	public function saveTeam() {
		$userId = $this->user['id'];
		$postData = $this->controller->request->data;
		$teamPostData = $postData['Team'];
		$teamData = array();
               
		// check if new record
		$this->isNewRecord = true;
		if (isset($teamPostData['id']) && $teamPostData['id'] > 0) {
			$teamData['id'] = $teamPostData['id'];
			$this->isNewRecord = false;
		}

		if ($this->isNewRecord === true) {
			$teamData['created_by'] = $userId;
		}

		// other details
		$teamData['name'] = $teamPostData['name'];
		$teamData['about'] = $teamPostData['about'];
                $teamData['privacy'] = $teamPostData['privacy'];
                
                // privacy changed form private to open
                if ( isset( $this->teamOldData )) {
                    
                        /*
                         * If the privacy changed to private to open and
                         * the user is not a patient
                         */
                        if ( $this->teamOldData['privacy'] != $teamPostData['privacy'] &&
                                $teamPostData['privacy'] == Team::TEAM_PUBLIC &&
                                $this->memberRole != TeamMember::TEAM_ROLE_PATIENT &&
                                $this->memberRole != TeamMember::TEAM_ROLE_PATIENT_ORGANIZER ) {
                            
                                // patient should verify
                                $teamData['privacy'] = Team::TEAM_PRIVATE_TO_PUBLIC;
                                $teamData['privacy_requester_id'] = $userId;
                        }
                }
                
		// save data
		if ($this->Team->save($teamData, array('validate' => false))) {
			$teamId = $this->Team->id;
			$this->__saveTeamImage($teamId, $teamPostData['image']);
			if ($this->isNewRecord) {
				$message = __('The team has been created successfully.');
			} else {
                                /*
                                 * If team privacy changed and it is not a pending request
                                 */                            
                                if ( $this->teamOldData['privacy'] != $teamPostData['privacy'] && 
                                        $teamData['privacy'] != Team::TEAM_PRIVATE_TO_PUBLIC ) {
                                       /*
                                        * team notification
                                        */
                                       $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                                       $this->Team = ClassRegistry::init('Team');
                                       $data = array(
                                               'activity_type' => Notification::ACTIVITY_TEAM_PRIVACY_CHANGE,
                                               'team_id' => $teamId,
                                               'sender_id' => $userId,
                                               'old_privacy' => $this->teamOldData['privacy'],
                                               'new_privacy' =>  $teamPostData['privacy']
                                       );
                                        $this->QueuedTask->createJob('TeamNotification', $data);
                                        
                                        /*
                                         * Privacy change Post
                                         */                                        
                                        $this->Post = ClassRegistry::init('Post');
                                        $clientIp = $this->controller->request->clientIp();
                                        $this->Post->addNewTeamPost($teamData, $userId, $clientIp);
                                        
                                } else  if ( $this->teamOldData['privacy'] != $teamPostData['privacy'] &&                                     
                                            $teamData['privacy'] == Team::TEAM_PRIVATE_TO_PUBLIC ){
                                    
                                        /*
                                         * Patient notification
                                         */
                                        $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                                        $this->Team = ClassRegistry::init('Team');
                                        $data = array(
                                                'activity_type' => Notification::ACTIVITY_TEAM_PRIVACY_CHANGE_REQUEST,
                                                'team_id' => $teamId,
                                                'sender_id' => $userId,
                                                'old_privacy' => Team::PRIVACY_PRIVATE,
                                                'new_privacy' => Team::PRIVACY_PUBLIC
                                        );
                                        $this->QueuedTask->createJob('TeamNotification', $data);
                                }
                                
				$message = __('The team details has been updated successfully.');
			}
			$this->controller->Session->setFlash($message, 'success');
			return $this->controller->redirect("/myteam/{$teamId}");
		}
	}

	/**
	 * Function to resize the team image to required dimension
	 * and save it to permanent folders
	 * 
	 * @param int $teamId
	 * @param string $imageName
	 * @return array
	 * @throws Exception
	 */
	private function __saveTeamImage($teamId, $imageName) {
		$result['success'] = false;

		try {
			if (isset($imageName) && !empty($imageName)) {
				$uploadPath = Configure::read('App.UPLOAD_PATH');
				$thumbnailPath = Configure::read('App.TEAM_IMG_PATH');
				$croppedImage = $uploadPath . DIRECTORY_SEPARATOR . $imageName;

				if (file_exists($croppedImage)) {
					$croppedFile = new File($croppedImage);

					if (!file_exists($thumbnailPath)) {
						mkdir($thumbnailPath, 0777);
					}

					// Resize and copy the image to new folders
					$imageSizes = Common::getTeamThumbDimensions();
					foreach ($imageSizes as $suffix => $images) {
						$targetImage = $thumbnailPath . DIRECTORY_SEPARATOR . md5($teamId) . "_" . $suffix . ".jpg";
						ImageTool::resize(array(
							'input' => $croppedImage,
							'output' => $targetImage,
							'width' => $images['w'],
							'height' => $images['h']
						));
					}

					// Move the original image also
					$originalThumb = $thumbnailPath . DIRECTORY_SEPARATOR . md5($teamId) . "_original.jpg";
					$croppedFile->copy($originalThumb, true);

					// Remove the initial cropped image
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
}
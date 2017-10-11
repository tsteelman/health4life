<?php

/**
 * ApiController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MyTeamAppController', 'MyTeam.Controller');
App::uses('File', 'Utility');
App::import('Vendor', 'ImageTool');
App::uses('Notification', 'Model');
App::uses('UserPrivacySettings', 'Lib');

/**
 * ApiController for myteam.
 * 
 * ApiController is used for handling ajax functionalities for team.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	MyTeam
 * @category	Controllers 
 */
class ApiController extends MyTeamAppController {

    /**
     * Variable to store the minimum team image size
     */
    public $minimumImageSize = array('240', '106');

    /**
     * Components to be used in the controller
     */
    public $components = array('Uploader', 'EmailTemplate', 'EmailQueue');

    /**
     * Models used by this controller
     */
    public $uses = array('Email', 'Volunteer', 'CareCalendarEvent', 'Notification');

    /**
     * Array to store API output data
     * 
     * @var array 
     */
    public $data = array();

    /**
     * Disable auto render
     */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->autoRender = false;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST') {
            header('Content-Type: text/plain');
        } else {
            header('HTTP/1.0 405 Method Not Allowed');
        }
    }

    /**
     * Output JSON data
     */
    public function afterFilter() {
        parent::afterFilter();
        $jsonData = htmlspecialchars(json_encode($this->data), ENT_NOQUOTES);
        echo $jsonData;
        exit;
    }

    /**
     * Function to crop uploaded team photo
     * 
     * @throws Exception
     */
    public function cropPhoto() {
        $uploadPath = Configure::read("App.UPLOAD_PATH");
        $tempUrl = FULL_BASE_URL . "/uploads/tmp/";

        $uploadedImage = $this->request->data['cropfileName'];

        try {
            $x1 = $_POST["x1"];
            $y1 = $_POST["y1"];
            $width = $_POST["w"];
            $height = $_POST["h"];

            $photoPath = $uploadPath . DS . $uploadedImage;

            if ($width <= 0) {
                $width = $this->minimumImageSize[0];
                $x1 = 0;
            }

            if ($height <= 0) {
                $height = $this->minimumImageSize[1];
                $y1 = 0;
            }

            if ($width > 0 && $height > 0) {
                ImageTool::crop(array(
                    'input' => $photoPath,
                    'output' => $photoPath,
                    'width' => $width,
                    'height' => $height,
                    'output_width' => $this->minimumImageSize[0],
                    'output_height' => $this->minimumImageSize[1],
                    'top' => $y1,
                    'left' => $x1,
                ));

                $this->data['success'] = true;
                $this->data['fileUrl'] = $tempUrl . "" . $uploadedImage;
                $this->data['fileName'] = $uploadedImage;
            } else {
                throw new Exception("Image Not cropped");
            }
        } catch (Exception $e) {
            $this->data['success'] = false;
            $this->data['message'] = $e->getMessage();
        }
    }

    /**
     * Function to upload team photo to temporary location
     */
    public function uploadPhoto() {
        $uploadPath = Configure::read("App.UPLOAD_PATH");
        $tempUrl = FULL_BASE_URL . "/uploads/tmp/";

        $uploader = new $this->Uploader();
        $uploader->allowedExtensions = Configure::read('App.imageExtensions');
        $uploader->sizeLimit = 5 * 1024 * 1024;
        $uploader->minImageSize = $this->minimumImageSize;
        $uploader->inputName = "qqfile";
        $uploader->chunksFolder = "chunks";
        $this->data = $uploader->handleUpload($uploadPath);

        if (isset($this->data['success'])) {
            $fileName = $uploader->getUploadName();
            $photoPath = $uploadPath . DS . $fileName;
            ImageTool::resize(array(
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

            $this->data['fileName'] = $fileName;
            $this->data['fileurl'] = $tempUrl . DS . $fileName;

            // image dimension
            list($imageWidth, $imageHeight) = getimagesize($photoPath);
            $this->data['imageWidth'] = $imageWidth;
            $this->data['imageHeight'] = $imageHeight;
        }
    }

    /**
     * Function to approve a team
     */
    public function approveTeam() {
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            if ($this->Team->exists($teamId)) {
                if ($this->Team->approve($teamId)) {
                    $this->__doAfterApproveTeam($teamId);
                } else {
                    $this->data = array(
                        'error' => true,
                        'message' => 'Failed to approve team.'
                    );
                }
            } else {
                $this->data = array(
                    'error' => true,
                    'errorType' => 'fatal',
                    'message' => 'Sorry, seems like the team got deleted.'
                );
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
        }
    }
	
	 /**
     * Function to approve a role request
     * 
     * @return Boolean
     */
    public function approveRole() {
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            $userId = $this->_currentUserId;
            if ($this->Team->exists($teamId)) {
                $teamMemberData = $this->TeamMember->getTeamMemberData($teamId, $userId);
                if ($this->TeamMember->approveRole($teamId, $userId)) {
//                    allow new organizer to view medical data if setting != only me.
//                    $teamData = $this->Team->getTeam($teamId);
//                    $privacy = new UserPrivacySettings($teamData['patient_id']);
//                    $myHealthViewStatus = $privacy->__get('view_your_health');
//                    $this->TeamMember->updateAdminMedicalDataPermission($teamData, $myHealthViewStatus, $userId);

                    $this->TeamMember->resetPatientOrganizerRole($teamId); //reset patient-organizer to patient when organizer comes.
                    $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                    $this->QueuedTask->createJob('TeamNotification', array(
                        'activity_type' => Notification::ACTIVITY_TEAM_ROLE_APPROVED,
                        'teamMemberData' => $teamMemberData
                    ));
                    $this->data = array(
                        'success' => true,
                        'members_container' => $this->__updateTeamMemberData($teamId)
                    );
                    $message = __('Team Lead role has been approved successfully.');
                    $this->Session->setFlash($message, 'success');
                } else {
                    $this->data = array(
                        'error' => true,
                        'message' => 'Failed to approve role'
                    );
                }
            } else {
                $this->data = array(
                    'error' => true,
                    'errorType' => 'fatal',
                    'message' => 'Sorry, seems like the team got deleted.'
                );
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
        }
    }
	
	/**
	 * Function for public join team request
	 */
	public function joinTeam() {
		if (isset($this->request->data['team_id'])) {
			$teamId = $this->request->data['team_id'];
			$userId = $this->_currentUserId;
			$team = $this->Team->findById($teamId);
			if ($this->Team->exists($teamId) && ($team['Team']['privacy'] == Team::PRIVACY_PUBLIC)) {
				if ($this->TeamMember->joinRequest($teamId, $userId)) {
					$this->__addTeamJoinRequestNotification($teamId, $userId);
					$this->data = array(
						'success' => true
					);
				} else {
					$this->data = array(
						'error' => true,
						'message' => 'Failed to send team join request.'
					);
				}
			} else {
				$this->data = array(
					'error' => true,
					'errorType' => 'fatal',
					'message' => 'Sorry, You don\'t have permission to join this team'
				);
			}
		}
	}

	/**
	 * Function to add team join request notification task to queue
	 * 
	 * @param type $teamId
	 * @param type $userId
	 */
	private function __addTeamJoinRequestNotification($teamId, $userId) {
		$this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
		$this->Team = ClassRegistry::init('Team');
		$data = array(
			'activity_type' => Notification::ACTIVITY_TEAM_JOIN_REQUEST,
			'team_id' => $teamId,
			'sender_id' => $userId
		);
		$this->QueuedTask->createJob('TeamNotification', $data);
	}

	/**
	 * Function to accept a team join invitation
	 */
	public function approveTeamJoinRequest() {
		if (isset($this->request->data['team_member_id'])) {
			$teamMemberId = $this->request->data['team_member_id'];
			$teamMemberData = $this->TeamMember->findById($teamMemberId);
			$teamId = $teamMemberData['TeamMember']['team_id'];
			$requestUserId = $teamMemberData['TeamMember']['user_id'];
			// checking the logged in user is an organizer of the team
			$isOrganizer = $this->TeamMember->isOrganizer($teamId, $this->_currentUserId);
			if ($isOrganizer) {
				// ckecking user has already accepted the request or not
				if ($teamMemberData['TeamMember']['status'] == TeamMember::STATUS_NOT_APPROVED) {
					if ($this->TeamMember->approve($teamId, $requestUserId)) {
						$this->__addTeamJoinRequestAcceptedNotification($teamId, $requestUserId);
						$this->data = array(
							'success' => true
						);
						$message = __('Team join request has been approved successfully.');
						$this->Session->setFlash($message, 'success');
					} else {
						$this->data = array(
							'error' => true,
							'errorType' => 'fatal',
							'message' => 'Unable to approve member'
						);
					}
				} else {
					$this->data = array(
						'error' => true,
						'errorType' => 'fatal',
						'message' => 'Unable to approve member'
					);
				}
			}
		}
	}

	/**
	 * Function to add team join request accepted notification task to queue
	 * 
	 * @param int $teamId
	 * @param int $userId
	 */
	private function __addTeamJoinRequestAcceptedNotification($teamId, $userId) {
		$this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
		$data = array(
			'activity_type' => Notification::ACTIVITY_ACCEPT_TEAM_JOIN_REQUEST,
			'team_id' => $teamId,
			'sender_id' => $this->_currentUserId,
			'recipient_id' => $userId
		);
		$this->QueuedTask->createJob('TeamNotification', $data);
	}

	public function __updateTeamMemberData($teamId) {
        $this->autoRender = false;
        $data = array();
        $teamMembers = $this->TeamMember->getApprovedTeamMembers($teamId);
        foreach ($teamMembers as $teamMember) {
            $data[] = $this->__getTeamMemberData($teamMember);
        }
        $this->set('members', $data);
        $View = new View($this, FALSE);

        return $View->element('MyTeam.Home/members');
    }

    /**
     * Function to get the data of a team member
     * 
     * @param array $teamMember
     * @return array
     */
    private function __getTeamMemberData($teamMemberData) {
        $teamMember = $teamMemberData['TeamMember'];
        $member = $teamMemberData['User'];
        $photo = Common::getUserThumb($teamMember['user_id'], $member['type'], 'x_small');
        $username = $member['username'];
        $profileUrl = Common::getUserProfileLink($member['username'], true);
        $roleName = $this->TeamMember->getMemberRoleName($teamMember['role']);
        $roleClass = $this->TeamMember->getMemberRoleClass($teamMember['role']);
        return compact('photo', 'username', 'profileUrl', 'roleName', 'roleClass');
    }

    /**
     * Does the functionalities after a team is approved
     */
    private function __doAfterApproveTeam($teamId) {
        $teamObj = $this->Team->findById($teamId);
        if ($this->TeamMember->addTeamAdmins($teamObj['Team'])) {
            /*functionality to approve team creator to view medical data w.r.t. patient's settings.*//*disabled*/
//            $patient = (int) $teamObj['Team']['patient_id'];
//            $organizerId = (int) $teamObj['Team']['created_by'];
//            $privacy = new UserPrivacySettings($patient);
//            $myHealthViewStatus = $privacy->__get('view_your_health');
//            $this->TeamMember->updateAdminMedicalDataPermission($teamObj['Team'], $myHealthViewStatus, $organizerId);
            /*functionality to approve team creator to view medical data w.r.t. patient's settings.*//*disabled*/
            $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
            $this->QueuedTask->createJob('TeamNotification', array(
                'activity_type' => Notification::ACTIVITY_TEAM_APPROVED,
                'teamObj' => $teamObj
            ));
            $this->data = array(
                'success' => true
            );
            $message = __('Your team has been approved successfully.');
            $this->Session->setFlash($message, 'success');
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'Failed to add team admin members.'
            );
        }
    }

    /**
     * Function to send HTML mail using templates stored in database.
     *
     * @param int $templateId template id
     * @param array $templateData template data
     * @param string $toEmail to email
     * @return bool
     */
    private function __sendHTMLMail($templateId, $templateData, $toEmail) {
        // getting email template from database
        $emailTemplateData = $this->EmailTemplate->getEmailTemplate($templateId, $templateData);
        $emailTemplate = $emailTemplateData['EmailTemplate'];

        // email queue to be saved
        $mailData = array(
            'subject' => $emailTemplate['template_subject'],
            'to_name' => $templateData['username'],
            'to_email' => $toEmail,
            'content' => json_encode($templateData),
            'email_template_id' => $templateId,
            'module_info' => 'MyTeam',
            'priority' => Email::DEFAULT_SEND_PRIORITY
        );

        return $this->EmailQueue->createEmailQueue($mailData);
    }

    /**
     * Function to send email to all members if a team is deleted.
     * 
     * @param array $teamData
     */
    private function __sendTeamDeleteNotificationEmail($teamId) {
        $teamMembers = $this->TeamMember->getApprovedTeamMembers($teamId);
        foreach ($teamMembers as $key => $member) {

            $data = array(
                'username' => $member['User']['username'],
                'team_name' => $member['Team']['name'],
                'link' => Router::Url('/', TRUE) . "myteam/{$member['Team']['id']}"
            );
            $toEmail = $member['User']['email'];
            $templateId = EmailTemplateComponent::TEAM_DELETE_NOTIFICATION_EMAIL_TEMPLATE;
            $this->__sendHTMLMail($templateId, $data, $toEmail);
        }
    }

    /**
     * Function to decline a team
     */
    public function declineTeam() {
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            if ($this->Team->exists($teamId)) {
                $teamData = $this->Team->findById($teamId);
                if ($this->Team->delete($teamId)) {
                    $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                    $this->QueuedTask->createJob('TeamNotification', array(
                        'activity_type' => Notification::ACTIVITY_TEAM_DECLINED,
                        'teamData' => $teamData,
                    ));
                    $this->data = array(
                        'success' => true
                    );
                    $message = __('The team has been declined successfully.');
                    $this->Session->setFlash($message, 'success');
                } else {
                    $this->data = array(
                        'error' => true,
                        'message' => 'Failed to decline team.'
                    );
                }
            } else {
                $this->data = array(
                    'error' => true,
                    'errorType' => 'fatal',
                    'message' => 'Sorry, seems like the team got deleted.'
                );
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
        }
    }

    /**
     * Function to decline new role request
     * 
     * @return Boolean
     */
    public function declineRole() {
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            if ($this->Team->exists($teamId)) {
                $userId = $this->_currentUserId;
                $teamMemberData = $this->TeamMember->getTeamMemberData($teamId, $userId);
                if ($this->TeamMember->declineRole($teamId, $userId)) {
                    $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                    $this->QueuedTask->createJob('TeamNotification', array(
                        'activity_type' => Notification::ACTIVITY_TEAM_ROLE_DECLINED,
                        'teamMemberData' => $teamMemberData
                    ));
                    $this->data = array(
                        'success' => true
                    );
                    $message = __('Team Lead role has been declined successfully.');
                    $this->Session->setFlash($message, 'success');
                } else {
                    $this->data = array(
                        'error' => true,
                        'message' => 'Failed to decline role'
                    );
                }
            } else {
                $this->data = array(
                    'error' => true,
                    'errorType' => 'fatal',
                    'message' => 'Sorry, seems like the team got deleted.'
                );
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
        }
    }

    /**
     * Function to accept a team join invitation
     */
    public function acceptTeamInvitation() {
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            if ($this->Team->exists($teamId)) {
                if ($this->TeamMember->approve($teamId, $this->_currentUserId)) {
                    $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                    $this->QueuedTask->createJob('TeamNotification', array(
                        'activity_type' => Notification::ACTIVITY_ACCEPT_TEAM_JOIN_INVITATION,
                        'team_id' => $teamId,
                        'user_id' => $this->_currentUserId
                    ));
                    $this->data = array(
                        'success' => true
                    );
                    $message = __('Congrats, You are now a member of this team.');
                    $this->Session->setFlash($message, 'success');
                } else {
                    $this->data = array(
                        'error' => true,
                        'message' => 'Failed to approve team invitation.'
                    );
                }
            } else {
                $this->data = array(
                    'error' => true,
                    'errorType' => 'fatal',
                    'message' => 'Sorry, seems like the team got deleted.'
                );
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
        }
    }

    /**
     * Function to decline a team join invitation
     */
    public function declineTeamInvitation() {
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            if ($this->Team->exists($teamId)) {
                $userId = $this->_currentUserId;
                $teamMemberData = $this->TeamMember->getTeamMemberData($teamId, $userId);
                $teamMemberId = $teamMemberData['TeamMember']['id'];
                if ($this->TeamMember->delete($teamMemberId)) {
                    $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                    $this->QueuedTask->createJob('TeamNotification', array(
                        'activity_type' => Notification::ACTIVITY_DECLINE_TEAM_JOIN_INVITATION,
                        'team_member_data' => $teamMemberData,
                    ));
                    $this->data = array(
                        'success' => true
                    );
                    $message = __('The team invitation has been declined successfully.');
                    $this->Session->setFlash($message, 'success');
                } else {
                    $this->data = array(
                        'error' => true,
                        'message' => 'Failed to decline team invitation.'
                    );
                }
            } else {
                $this->data = array(
                    'error' => true,
                    'errorType' => 'fatal',
                    'message' => 'Sorry, seems like the team got deleted.'
                );
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
        }
    }

    /**
     * Function to invite friends to team
     */
    public function inviteFriend() {
        $data = $this->request->data;
        if (isset($data['teamId'])) {
            $teamId = $data['teamId'];
            $users = $data['friends_list'];
            if (!empty($users)) {
                $recipients = array();
                foreach ($users as $userId) {
                    if ($this->TeamMember->inviteUser($teamId, $userId, $this->_currentUserId)) {
                        $recipients[] = $userId;
                        $this->data = array(
                            'success' => true
                        );
                        $message = __('Team invitation has been sent successfully.');
                        $this->Session->setFlash($message, 'success');
                    } else {
                        $this->data = array(
                            'error' => true,
                            'message' => 'Failed to invite.'
                        );
                    }
                }

                // add team join invitation notification task to queue
                if (!empty($recipients)) {
                    $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                    $this->QueuedTask->createJob('TeamNotification', array(
                        'activity_type' => Notification::ACTIVITY_TEAM_JOIN_INVITATION,
                        'team_id' => $teamId,
                        'recipients' => $recipients,
                        'sender_id' => $this->_currentUserId
                    ));
                }
            } else {
                $this->data = array(
                    'error' => true,
                    'message' => 'No users selected.'
                );
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
        }
    }

    /**
     * Function to gives the user type, total_members & organizer count in 
     * a team
     * 
     * @return array data
     */
    public function getUserTeamStatus() {
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            $userId = $this->_currentUserId;
            if ($this->Team->exists($teamId)) {
                $teamMemberData = $this->TeamMember->getTeamMemberData($teamId, $userId);
                $teamOrganizerCount = $this->TeamMember->getOrganizerCount($teamId);

                $this->data = array(
                    'success' => TRUE,
                    'user_id' => $userId,
                    'team_id' => $teamId,
                    'role' => $teamMemberData['TeamMember']['role'],
                    'total_members' => $teamMemberData['Team']['member_count'],
                    'organizer_count' => $teamOrganizerCount
                );
            } else {
                $this->data = array(
                    'error' => true,
                    'errorType' => 'fatal',
                    'message' => 'Sorry, seems like the team got deleted.'
                );
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
        }
    }

    /**
     * Function to implement leave a team.
     * 
     * @return array data
     */
    public function leaveTeam() {
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            $userId = $this->_currentUserId;

            if ($this->Team->exists($teamId)) {
                $teamMemberData = $this->TeamMember->getTeamMemberData($teamId, $userId);
                $this->__resetAllTasks();
                switch ($teamMemberData['TeamMember']['role']) {
                    case TeamMember::TEAM_ROLE_MEMBER:
                        $this->leaveTeamMember($teamMemberData);
                        $message = __('You have successfully left the team');
                        break;
                    case TeamMember::TEAM_ROLE_PATIENT:
                    case TeamMember::TEAM_ROLE_PATIENT_ORGANIZER:
                        $this->leaveTeamPatient($teamMemberData);
                        $message = __('Team has been removed successfully');
                        break;
                    case TeamMember::TEAM_ROLE_ORGANIZER:
                        $this->leaveTeamOrganizer($teamMemberData);
                        $message = __('You have successfully left the team');
                        break;
                }

                $this->Session->setFlash($message, 'success');
            } else {
                $this->data = array(
                    'error' => true,
                    'errorType' => 'fatal',
                    'message' => 'Sorry, seems like the team got deleted.'
                );
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
        }
    }

    /**
     * Function to implement member leave a team
     * 
     * @return boolean
     */
    public function leaveTeamMember($teamMemberData) {
        $this->TeamMember->delete($teamMemberData['TeamMember']['id']);
        $this->data = array(
            'success' => true,
            'type' => 'member',
            'username' => $teamMemberData['User']['username']
        );

        return TRUE;
    }

    /**
     * Function to implement patient leave a team
     * 
     * @return boolean
     */
    public function leaveTeamPatient($teamMemberData) {
        //send email to all members notifying team delete
        $teamId = $teamMemberData['Team']['id'];
        $this->__sendTeamDeleteNotificationEmail($teamId);
        $this->Team->delete($teamId, TRUE);
        $this->data = array(
            'success' => true,
            'type' => 'patient',
            'username' => $teamMemberData['User']['username']
        );

        return TRUE;
    }

    /**
     * Function to implement organizer leave a team
     * ( Many organizers or 2 members ( 1 organizer, 1 patient case only
     * considered here )
     * 
     * @return array data
     */
    public function leaveTeamOrganizer($teamMemberData) {
        $teamId = $teamMemberData['Team']['id'];
        $userId = $teamMemberData['TeamMember']['user_id'];
        $teamOrganizerCount = $this->TeamMember->getOrganizerCount($teamId, $userId);

        //if member count 2 means only patient & organizer
        if ($teamMemberData['Team']['member_count'] == 2 || $teamOrganizerCount == 1) {
            $this->__upgradeTeamUser($teamId, $userId);
            $this->TeamMember->delete($teamMemberData['TeamMember']['id']);
        } else { //many organizers
            $this->TeamMember->delete($teamMemberData['TeamMember']['id']);
        }
        $this->data = array(
            'success' => true,
            'type' => 'organizer',
            'username' => $teamMemberData['User']['username']
        );
    }

    /**
     * Function to implement ajax request to upgrade member to organizer
     * 
     * @return array data
     */
    public function upgradeToOrganizer() {
        if (isset($this->request->data['team_member_id'])) {
            $teamMemberId = $this->request->data['team_member_id'];
            $teamMemberData = $this->TeamMember->findById($teamMemberId);

            /**
             * From Member manage only member can be upgraded to organizer, no patient
             * so we check person is role member.
             */
            if ($teamMemberData['TeamMember']['role'] == TeamMember::TEAM_ROLE_MEMBER && $teamMemberData['TeamMember']['status'] == TeamMember::STATUS_APPROVED) {
                $this->__upgradeTeamUser($teamMemberData['TeamMember']['team_id'], $teamMemberData['TeamMember']['user_id']);
                $this->data = array(
                    'success' => true,
                    'type' => 'member',
                    'username' => $teamMemberData['User']['username']
                );
            } else {
                $this->data = array(
                    'error' => true,
                    'message' => 'You are not permitted to upgrade this user'
                );
            }
        }
    }

    /**
     * Function to implement ajax request to demote  organizer to member
     * 
     * @return array data
     */
    public function demoteOrganizer() {
        if (isset($this->request->data['team_member_id'])) {
            $teamMemberId = $this->request->data['team_member_id'];
            $teamMemberData = $this->TeamMember->findById($teamMemberId);

            /**
             * From Member manage only organizer can be demoted to member, no patient
             * so we check person is role member.
             */
            if ($teamMemberData['TeamMember']['role'] == TeamMember::TEAM_ROLE_ORGANIZER && $teamMemberData['TeamMember']['status'] == TeamMember::STATUS_APPROVED) {
                $this->__demoteOrganizerUser($teamMemberData['TeamMember']['team_id'], $teamMemberData['TeamMember']['user_id']);
                $this->data = array(
                    'success' => true,
                    'type' => 'member',
                    'username' => $teamMemberData['User']['username']
                );
            } else {
                $this->data = array(
                    'error' => true,
                    'message' => 'You are not permitted to demote this user'
                );
            }
        }
    }

    /**
     * Function to implement ajax request to upgrade member to organizer
     * ( used when leave from team after assigning role to another )
     * 
     * @return array data
     */
    public function assignOrganizer() {
        if (isset($this->request->data['team_member_id'])) {
            $teamMemberId = $this->request->data['team_member_id'];
            $teamMemberData = $this->TeamMember->findById($teamMemberId);
            $teamId = $teamMemberData['TeamMember']['team_id'];
            // checking the logged in user is an organizer of the team
            $isOrganizer = $this->TeamMember->isOrganizer($teamId, $this->_currentUserId);

            if ($isOrganizer) { //assign role to others can be done by organizers only
                $this->__upgradeTeamUser($teamMemberData['TeamMember']['team_id'], $teamMemberData['TeamMember']['user_id']);
                if ($teamMemberData['TeamMember']['role'] == TeamMember::TEAM_ROLE_MEMBER) {
                    $this->data = array(
                        'success' => true,
                        'type' => 'member',
                        'username' => $teamMemberData['User']['username']
                    );
                    $this->Session->setFlash(__($teamMemberData['User']['username'] . " is promoted as the Team Lead. Waiting for approval."), 'success');
                } else if (($teamMemberData['TeamMember']['role'] == TeamMember::TEAM_ROLE_PATIENT) || ($teamMemberData['TeamMember']['role'] == TeamMember::TEAM_ROLE_PATIENT_ORGANIZER)) {
                    $loginMemberData = $this->TeamMember->getTeamMemberData($teamId, $this->_currentUserId);
                    // since assigned to patient organizer can leave immediately.
                    if ($this->TeamMember->delete($loginMemberData['TeamMember']['id'])) {
                        $this->data = array(
                            'success' => true,
                            'type' => 'patient',
                            'username' => $teamMemberData['User']['username']
                        );
                        $this->Session->setFlash(__($teamMemberData['User']['username'] . " is promoted as the Team Lead and is notified."), 'success');
                    } else {
                        $this->data = array(
                            'error' => true,
                            'errorType' => 'fatal',
                            'message' => 'Unable to delete member'
                        );
                    }
                }
            } else {
                $this->data = array(
                    'error' => true,
                    'message' => 'You are not permitted to upgrade this user'
                );
            }
        }
    }

    /**
     * Function to handle remove a user
     * 
     * @return array data
     */
    public function removeTeamUser() {
        if (isset($this->request->data['team_member_id'])) {
            $teamMemberId = $this->request->data['team_member_id'];
            $reasonForRemoval = $this->request->data['reason'];
            $teamMemberData = $this->TeamMember->findById($teamMemberId);
            // checking the logged in user is an organizer of the team
            $isOrganizer = $this->TeamMember->isOrganizer($teamMemberData['TeamMember']['team_id'], $this->_currentUserId);
            if ($isOrganizer) {
                if ($this->TeamMember->delete($teamMemberId)) {
                    $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                    $this->QueuedTask->createJob('TeamNotification', array(
                        'activity_type' => Notification::ACTIVITY_REMOVED_FROM_TEAM,
                        'team_id' => $teamMemberData['Team']['id'],
                        'recipient_id' => $teamMemberData['TeamMember']['user_id'],
                        'sender_id' => $this->_currentUserId,
                        'reason' => $reasonForRemoval
                    ));
                    $this->data = array(
                        'success' => true
                    );
                } else {
                    $this->data = array(
                        'error' => true,
                        'errorType' => 'fatal',
                        'message' => 'Unable to delete member'
                    );
                }
            }
        }
    }

    /**
     * Function to upgrade a user
     */
    private function __upgradeTeamUser($teamId, $userId) {
        $teamMemberData = $this->TeamMember->getTeamMemberData($teamId, $userId);
        if ($teamMemberData['TeamMember']['role'] == TeamMember::TEAM_ROLE_MEMBER) {
            $this->TeamMember->save(array(
                'id' => $teamMemberData['TeamMember']['id'],
                'new_role' => TeamMember::TEAM_ROLE_ORGANIZER,
                'role_invited_by' => $this->_currentUserId
            ));
            $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
            $this->QueuedTask->createJob('TeamNotification', array(
                'activity_type' => Notification::ACTIVITY_TEAM_ROLE_INVITATION,
                'teamMemberId' => $teamMemberData['TeamMember']['id']
            ));
        } else if ($teamMemberData['TeamMember']['role'] == TeamMember::TEAM_ROLE_PATIENT) {
            $this->TeamMember->save(array(
                'id' => $teamMemberData['TeamMember']['id'],
                'role' => TeamMember::TEAM_ROLE_PATIENT_ORGANIZER,
                'new_role' => NULL,
                'role_invited_by' => NULL
            ));
        }
        return TRUE;
    }

    /**
     * Function to demote an organizer
     */
    private function __demoteOrganizerUser($teamId, $userId) {
        $teamMemberData = $this->TeamMember->getTeamMemberData($teamId, $userId);
        if ($teamMemberData['TeamMember']['role'] == TeamMember::TEAM_ROLE_ORGANIZER) {
            $this->TeamMember->save(array(
                'id' => $teamMemberData['TeamMember']['id'],
                'role' => TeamMember::TEAM_ROLE_MEMBER,
                'new_role' => NULL,
                'role_invited_by' => NULL
            ));
            $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
            $this->QueuedTask->createJob('TeamNotification', array(
                'activity_type' => Notification::ACTIVITY_DEMOTE_ORGANIZER,
                'teamMemberData' => $teamMemberData,
                'demote_by' => $this->_currentUserId
            ));
            return TRUE;
        }
    }

    /**
     * Temp function to test notification adding functionality.
     */
    public function test() {
        $teamId = 1;
        $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
        $this->Team = ClassRegistry::init('Team');

        // removed from team
//		$data = array(
//			'activity_type' => Notification::ACTIVITY_REMOVED_FROM_TEAM,
//			'team_id' => $teamId,
//			'sender_id' => 2,
//			'recipient_id' => 4
//		);
        // removed from team with reason
//        $data = array(
//            'activity_type' => Notification::ACTIVITY_REMOVED_FROM_TEAM,
//            'team_id' => $teamId,
//            'sender_id' => 2,
//            'recipient_id' => 4,
//            'reason' => 'this user is not doing anything for the patient'
//        );

        // request for care
//		$data = array(
//			'activity_type' => Notification::ACTIVITY_TEAM_CARE_REQUEST,
//			'team_id' => $teamId,
//			'sender_id' => $this->_currentUserId,
//			'care_type' => 'food'
//			'task_id' => 2
//		);
        // care request change
//		$data = array(
//			'activity_type' => Notification::ACTIVITY_TEAM_CARE_REQUEST_CHANGE,
//			'team_id' => $teamId,
//			'sender_id' => $this->_currentUserId,
//			'care_type' => 'food'
//			'task_id' => 2
//		);
        // health state change
//		App::uses('HealthStatus', 'Utility');
//		$data = array(
//			'activity_type' => Notification::ACTIVITY_HEALTH_STATUS_CHANGE,
//			'team_id' => $teamId,
//			'patient_id' => $this->_currentUserId,
//			'health_status' => HealthStatus::STATUS_GOOD,
//			'new_health_status' => HealthStatus::STATUS_BAD
//		);
        // create team
//		$data = array(
//			'activity_type' => Notification::ACTIVITY_CREATE_TEAM,
//			'team_id' => $teamId,
//			'created_by' => 3,
//			'patient_id' => 4
//		);
		// team privacy change from public to private
		$data = array(
			'activity_type' => Notification::ACTIVITY_TEAM_PRIVACY_CHANGE,
			'team_id' => $teamId,
			'sender_id' => 2,
			'old_privacy' => Team::PRIVACY_PUBLIC,
			'new_privacy' =>  Team::PRIVACY_PRIVATE
		);

		// team privacy change from private to public
		$data = array(
			'activity_type' => Notification::ACTIVITY_TEAM_PRIVACY_CHANGE,
			'team_id' => $teamId,
			'sender_id' => 2,
			'old_privacy' => Team::PRIVACY_PRIVATE,
			'new_privacy' =>  Team::PRIVACY_PUBLIC
		);
		
		// team privacy change request from private to public
		$data = array(
			'activity_type' => Notification::ACTIVITY_TEAM_PRIVACY_CHANGE_REQUEST,
			'team_id' => $teamId,
			'sender_id' => 2,
			'old_privacy' => Team::PRIVACY_PRIVATE,
			'new_privacy' => Team::PRIVACY_PUBLIC
		);
		// team privacy change request from public to private
		$data = array(
			'activity_type' => Notification::ACTIVITY_TEAM_PRIVACY_CHANGE_REQUEST,
			'team_id' => $teamId,
			'sender_id' => 2,
			'old_privacy' => Team::PRIVACY_PUBLIC,
			'new_privacy' =>  Team::PRIVACY_PRIVATE
		);
		// team privacy change request rejected
		$data = array(
			'activity_type' => Notification::ACTIVITY_TEAM_PRIVACY_CHANGE_REQUEST_REJECTED,
			'team_id' => $teamId,
			'organizer_id' => 2,
			'old_privacy' => Team::PRIVACY_PUBLIC,
			'new_privacy' => Team::PRIVACY_PRIVATE
		);
        $this->QueuedTask->createJob('TeamNotification', $data);
    }

    /**
     * Function to delete a volunteer from volunteers list
     * 
     */
    public function deleteVolunteer() {
        $id = $this->Auth->user('id');
        if ($this->Volunteer->deleteAll(array('Volunteer.user_id' => $id))) {
            $this->Session->setFlash(__("You have been removed from volunteer's list."), 'success');
            die(json_encode(array('success' => true)));
        } else {
            $this->Session->setFlash(__("Cannot delete user from volunteer's list, try again later."), 'error');
            die(json_encode(array('success' => false)));
        }
    }

    /**
     * Function to create a volunteer
     * 
     */
    public function createVolunteer() {
        $data['user_id'] = $this->Auth->user('id');
        $data['type'] = 0;
        $data['created'] = date("Y-m-d H:i:s");
        if ($this->Volunteer->createVolunteer($data)) {
            $this->Session->setFlash(__("You have been added as a volunteer."), 'success');
            die(json_encode(array('success' => true)));
        } else {
            $this->Session->setFlash(__("Cannot add user as a volunteer, try again later."), 'error');
            die(json_encode(array('success' => false)));
        }
    }

    /**
     * Function to cancel team join request
     * 
     * @return array data
     */
    public function cancelTeamJoinRequest() {
        if (isset($this->request->data['team_member_id'])) {
            $teamMemberId = $this->request->data['team_member_id'];
            $teamMemberData = $this->TeamMember->findById($teamMemberId);
            // checking the logged in user is an organizer of the team
            $isOrganizer = $this->TeamMember->isOrganizer($teamMemberData['TeamMember']['team_id'], $this->_currentUserId);
            if ($isOrganizer) {
                // ckecking user has already accepted the request or not
                if ($teamMemberData['TeamMember']['status'] == TeamMember::STATUS_NOT_APPROVED) {
                    if ($this->TeamMember->delete($teamMemberId)) {
						$teamMember = $teamMemberData['TeamMember'];
						if ($teamMemberData['TeamMember']['invited_by'] > 0) {
							$this->Notification->deleteTeamJoinInvitationNotification($teamMember['team_id'], $teamMember['user_id']);
						} else {
							$this->__addTeamJoinRequestDeclinedNotification($teamMember['team_id'], $teamMember['user_id']);
						}
						$this->data = array(
                            'success' => true
                        );
                        $message = __('Team join request has been cancelled.');
                        $this->Session->setFlash($message, 'success');
                    } else {
                        $this->data = array(
                            'error' => true,
                            'errorType' => 'fatal',
                            'message' => 'Unable to delete member'
                        );
                    }
                } else {
                    $this->data = array(
                        'success' => true
                    );
                    $message = __('Cannot cancel the request. User has already accepted the invitation');
                    $this->Session->setFlash($message, 'error');
                }
            }
        }
    }

	/**
	 * Function to add team join request declined notification task to queue
	 * 
	 * @param int $teamId
	 * @param int $userId
	 */
	private function __addTeamJoinRequestDeclinedNotification($teamId, $userId) {
		$this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
		$data = array(
			'activity_type' => Notification::ACTIVITY_DECLINE_TEAM_JOIN_REQUEST,
			'team_id' => $teamId,
			'sender_id' => $this->_currentUserId,
			'recipient_id' => $userId
		);
		$this->QueuedTask->createJob('TeamNotification', $data);
	}

	private function __resetAllTasks() {
		return $this->CareCalendarEvent->resetAllTasksOfUser($this->_currentUserId);
    }

    public function getAllTasksFromToday() {

        /*
         * TO DO
         * Check permission
         */
        $teamId = $this->request->data['teamId'];
        $offset = $this->request->data['offset'];
        $todayOffset = $this->request->data['todayOffset'];
        $limit = 5;
        $taskCount = $this->CareCalendarEvent->getTaskCount($teamId);
        $nextOffset = $this->CareCalendarEvent->getNextOffset($taskCount, $offset, $limit);
        $prevOffset = $this->CareCalendarEvent->getPreviousOffset($taskCount, $offset, $limit);
        $moreTasks = $this->CareCalendarEvent->getTeamTasks($teamId, $limit, $offset);
        $isToday = $todayOffset == $offset;

        $taskDetailsBaseUrl = '/myteam/' . $teamId . '/task/';

        $this->set('todayOffset', $todayOffset);
        $this->set('nextOffset', $nextOffset);
        $this->set('prevOffset', $prevOffset);
        $this->set('todayOffset', $todayOffset);
        $this->set('isToday', $isToday);
        $this->set('tasks', $moreTasks);
        $this->set('taskDetailsBaseUrl', $taskDetailsBaseUrl);
        $this->set('timezone', $this->Auth->user('timezone'));

        $View = new View($this, FALSE);

        echo $View->element('MyTeam.Home/task_list');
        exit();
    }

    public function requestMedicalDataAccess() {
        $userId = $this->Auth->user('id');
        $result = Array();
        $response = false;
        $result['success'] = false;
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            $result = $this->TeamMember->saveRequestForViewMedicalResords($userId, $teamId);
//            if ($response['success'] == TRUE) {
//                $result['success'] = TRUE;
//                $result['error'] = FALSE;
//            }
        }
        die(json_encode($result));
    }

    public function managePermissionRequest() {
        $result = Array();
        $result['success'] = false;
        $response = false;
        $patientId = $this->Auth->user('id');
        if (!empty($this->request->data['user_id']) && !empty($this->request->data['team_id']) && isset($this->request->data['action'])) {
            $teamId = $this->request->data['team_id'];
            $userId = $this->request->data['user_id'];
            $action = $this->request->data['action'];
            if ($this->TeamMember->isPatientOrOrganizerPatientOfTeam($teamId, $patientId)) {
                $response = $this->TeamMember->manageRequestForViewMedicalResords($userId, $teamId, $action);
                if ($response == TRUE) {
                    $result['success'] = TRUE;
                    $result['error'] = FALSE;
                }
            } else {
                $result['success'] = FALSE;
                $result['error'] = true;
            }
        } else {
            $result['success'] = FALSE;
            $result['error'] = true;
        }
        die(json_encode($result));
    }

    public function manageMultiplePermissionRequest() {
        $result['success'] = false;
        $patientId = $this->Auth->user('id');
        if (!empty($this->request->data['userArray']) && !empty($this->request->data['team_id']) && isset($this->request->data['action'])) {
            $teamId = $this->request->data['team_id'];
            $userIdArray = $this->request->data['userArray'];
            $action = $this->request->data['action'];
            if ($this->TeamMember->isPatientOrOrganizerOfTeam($teamId, $patientId)) {
                foreach ($userIdArray as $userId) {
                    $result['success'] = $this->TeamMember->manageRequestForViewMedicalResords($userId, $teamId, $action);
                }
            } else {
                $result['success'] = FALSE;
                $result['error'] = true;
            }
        } else {
            $result['success'] = FALSE;
            $result['error'] = true;
        }
        die(json_encode($result));
    }
    
   /**
    * Function to approve a team privacy as public
    */
    public function changeTeamPrivacy() { 
        /*
         * Loged in userId
         */
        $userId = $this->Auth->user('id');
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            
            /*
             * If the team exists
             */
            if ($this->Team->exists($teamId)) {
                
                /*
                 * If the user has permission to accept/decline
                 * ie, user is a patient or patientOrganizer
                 */
                if( $this->TeamMember->isPatientOrOrganizerPatientOfTeam($teamId, $userId)) {
                    
                        if ( isset( $this->request->data['action'])) {
                            $action = $this->request->data['action'];
                        } else {
                            $action = 'noAction';
                        }
                        // get team data
                        $team = $this->Team->getTeam($teamId);
                        
                        switch ($action) {
                            case 'approve'  :   if ( $this->__makeTeamPublic($teamId) ) {                                                    
                                                    //Team notification                                                     
                                                    $this->__doAfterMakeTeamPublic($teamId, $team['privacy_requester_id']);
                                                }
                                                break;
                            case 'decline'  :   if ( $this->__makeTeamPrivate($teamId) ) {
                                                    //Team notification                                                     
                                                    $this->__doAfterMakeTeamPrivate($teamId, $team['privacy_requester_id']);
                                                }   
                                                break;
                            default         :   //set error message
                                                $this->data = array(
                                                    'error' => true,
                                                    'message' => 'Invalid action.'
                                                );
                        }
                        
                } else {
                    $this->data = array(
                                'error' => true,
                                'message' => 'Sorry, seems like you don\'t have permission for this.'
                            );
                }
            } else {
                $this->data = array(
                    'error' => true,
                    'errorType' => 'fatal',
                    'message' => 'Sorry, seems like the team got deleted.'
                );
                
                return true;
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
            
            return false;
        }
    }  
    
   

    /*
     * Make team Public
     */
    private function  __makeTeamPublic($teamId) {
        if ($this->Team->makeTeamPublic($teamId)) {
                    $this->data = array(
                        'success' => true,
                        'message' => 'Your team privacy has been changed to public successfully.'
                    );                   
                    return true;
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'Failed to approve.'
            );
            
            return false;
        }
    }
    
    /**
     * Make team Private ( used in decline request for public privacy change )
     * @param int $teamId
     */
    private function __makeTeamPrivate($teamId) {
        if ($this->Team->makeTeamPrivate($teamId)) {
                    $this->data = array(
                        'success' => true,
                        'message' => 'Privacy change request has been declined successfully.'
                    );  
                    return true;
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'Failed to deline.'
            );
            return false;
        }
    }
    
    /**
     * Function to do after make team public
     * @param int $teamId
     */
    private function __doAfterMakeTeamPublic($teamId, $privacy_requester_id){
        /*
         * team notification
         */        
        $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
        $this->Team = ClassRegistry::init('Team');
        $data = array(
                'activity_type' => Notification::ACTIVITY_TEAM_PRIVACY_CHANGE,
                'team_id' => $teamId,
                'sender_id' => $privacy_requester_id,
                'old_privacy' => Team::TEAM_PRIVATE,
                'new_privacy' => Team::TEAM_PUBLIC
        );
        $this->QueuedTask->createJob('TeamNotification', $data);
        
        /*
        * Privacy change Post
        */    
        $teamData = $this->Team->getTeam($teamId);
        $this->Post = ClassRegistry::init('Post');
        $clientIp = $this->request->clientIp();
        $this->Post->addNewTeamPost($teamData, $privacy_requester_id, $clientIp);
      
    }
    
    
    /**
     * Function to do after make team private
     * @param int $teamId
     */
    private function __doAfterMakeTeamPrivate($teamId, $privacy_requester_id){
        /*        
         * team notification
         */       
        $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
        $this->Team = ClassRegistry::init('Team');
        // team privacy change request rejected
        $data = array(
                'activity_type' => Notification::ACTIVITY_TEAM_PRIVACY_CHANGE_REQUEST_REJECTED,
                'team_id' => $teamId,
                'organizer_id' => $privacy_requester_id,
                'old_privacy' => Team::PRIVACY_PRIVATE,
                'new_privacy' => Team::PRIVACY_PUBLIC
        );
        $this->QueuedTask->createJob('TeamNotification', $data);
    }
	
	/**
     * Function to cancel a team request to a patient
     */
    public function cancelTeamRequest() {
        if (isset($this->request->data['team_id'])) {
            $teamId = $this->request->data['team_id'];
            if ($this->Team->exists($teamId)) {
				$teamUserId = $this->request->data['team_userid'];
				$teamData = $this->Team->findById($teamId);
                if ($this->Team->delete($teamId)) {
                    $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                    $this->QueuedTask->createJob('TeamNotification', array(
                        'activity_type' => Notification::ACTIVITY_TEAM_REQUEST_CANCEL,
						'teamData' => $teamData,
                        'created_by' => $this->_currentUserId,
                        'patient_id' => $teamUserId
                    ));
                    $this->data = array(
                        'success' => true
                    );
                    $message = __('The team request has been cancelled successfully.');
                    $this->Session->setFlash($message, 'success');
                } else {
                    $this->data = array(
                        'error' => true,
                        'message' => 'Failed to cancel request.'
                    );
                }
            } else {
                $this->data = array(
                    'error' => true,
                    'errorType' => 'fatal',
                    'message' => 'Sorry, seems like the team got deleted.'
                );
            }
        } else {
            $this->data = array(
                'error' => true,
                'message' => 'No team selected.'
            );
        }
    }
    
}

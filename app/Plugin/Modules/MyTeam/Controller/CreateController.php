<?php

/**
 * CreateController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MyTeamAppController', 'MyTeam.Controller');
App::uses('Team', 'Model');
App::uses('TeamMember', 'Model');
App::uses('Notification', 'Model');

App::import('Vendor', 'ImageTool');

class CreateController extends MyTeamAppController {

    public $uses = array(
        'Volunteer',
        'MyFriends',
        'PatientDisease',
        'User'
    );
    /**
     * Function to create a team
     */
    public function index() {

        $isPatient = FALSE;
        $userDetails = $this->Auth->user();
        $userId = $this->Auth->user('id');
        $userRole = $this->Auth->user('type');
        
        $teamImage = "/theme/app/img/team_default.png";

        /*
         * Check if the request is coming for creating a team
         */
        if (isset($this->request->params['pass'][0]) && 
            $this->request->params['pass'][0] == "finish" && 
            $this->request->is('post')) {

            /*
             * Create the team only if the team name is not empty
             * and the team is for a valid user
             */
            $teamName = $this->request->data['team_name'];
            $teamUserId = $this->request->data['team_userid'];
            
            $teamPatient = FALSE;
            if($teamUserId > 0) {
                $teamPatient = $this->User->getUserDetails($teamUserId);
            }
            
            if(trim($teamName) != "" && $teamUserId > 0 &&  $teamPatient) {             
               
                
                $teamForMyself = ($teamUserId == $userId) ? TRUE : FALSE;

                $teamData['privacy'] = $this->request->data['privacy'];
                $teamData['name'] = $teamName;            
                $teamData['about'] = $this->request->data['team_about'];                
                $teamData['patient_id'] = $teamUserId;
                $teamData['created_by'] = $userId;
                $teamCustomPhoto =$this->request->data['team_photo'];
//                $teamPhoto = Common::userHasThumb($teamUserId, 'medium', 'path');
                $teamPhoto = $teamImage;
                $teamData['status'] = $teamForMyself ? 
                        Team::STATUS_APPROVED : Team::STATUS_NOT_APPROVED;
                
                         
                /*
                 * Save the team details
                 */
                if ($this->Team->save($teamData, array('validate' => false))) {
                        $teamData['team_id'] = $teamId = $this->Team->id;
                        $this->setTeamID($teamId);
                        $teamData['team_url'] = $this->replaceUrl('home');
                        
                        $teamNewPhoto = "";
                        if(trim($teamCustomPhoto) != "") {
                            $teamNewPhoto = Configure::read("App.UPLOAD_PATH_URL") . "/tmp/" .  $teamCustomPhoto;
                        } else if($teamPhoto != "") {
                            $teamNewPhoto = $teamPhoto;
                        }
                        
                        if($teamNewPhoto != "") {
                            $thumbnailPath = Configure::read('App.TEAM_IMG_PATH');
                            if (!file_exists($thumbnailPath)) {
                                    mkdir($thumbnailPath, 0777);
                            }                    
                            // Resize and copy the image to new folders
                            $imageSizes = Common::getTeamThumbDimensions();
                            foreach ($imageSizes as $suffix => $images) {
                                    $targetImage = $thumbnailPath . DIRECTORY_SEPARATOR . md5($teamId) . "_" . $suffix . ".jpg";
                                    ImageTool::resize(array(
                                            'input' => $teamNewPhoto,
                                            'output' => $targetImage,
                                            'width' => $images['w'],
                                            'height' => $images['h']
                                    ));
                            }                    
                        }
                
                        /*
                         * If the creating user and the patient is same,
                         * create the team and show the option to invite the friends
                         * 
                         * Otherwise, redirect the user to the team detail page
                         * and show the information about waiting for approval from
                         * the friend.
                         */
                        if($teamForMyself) {
                            $teamMember['user_id'] = $userId;
                            $teamMember['team_id'] = $teamId;
                            $teamMember['status'] = TeamMember::STATUS_APPROVED;
                            $teamMember['role'] = TeamMember::TEAM_ROLE_PATIENT_ORGANIZER;
                            $this->TeamMember->save($teamMember, array('validate' => false));
                            
                            $message = __('The team has been created successfully.');
                            $this->Session->setFlash($message, 'success');
                            $response['redirect_url'] = 'invite';
                            $response['team'] = $teamData;
                           
                        } else {
                            /*
                             * Notify the patient about the team creation
                             */
                            $this->QueuedTask = ClassRegistry::init('Queue.QueuedTask');
                            $data = array(
                                   'activity_type' => Notification::ACTIVITY_CREATE_TEAM,
                                   'team_id' => $teamId,
                                   'created_by' => $userId,
                                   'patient_id' => $teamUserId
                            );
                            $this->QueuedTask->createJob('TeamNotification', $data);

                            $message = __('Your friend is notified about the team. Awaiting reponse from the user');
                            $response['redirect_url'] = '/myteam/'.$teamId;
                        }
                        
                        $response['success'] = true;
                        $response['message'] = $message;
                        
                }

            } else {
                $response['success'] = false;
                $response['message'] = __('Enter mandatory details');
            }

            $jsonData = htmlspecialchars(json_encode($response), ENT_NOQUOTES);
            echo $jsonData;
            exit;

        } else {

            if($userRole == User::ROLE_PATIENT) {
                $isPatient = TRUE;
            }
            
            $teamPrivacyHintList =  array('Public - This will make the team visible to all the users.',
                                                'Private - This will make the team visible only for invited users and members');

            
            $isVolunteer = ($this->Volunteer->hasAny(array('user_id' => $userId))) ? true : false;
            $myFriends = $this->getMyFriends();
            //$patientFriends = $this->getPatientFriends();
            // for implementing search in invite Friends List on creating the team.
            $myFriendsListJson = $this->getMyFriendsJson();
            //$patientFriendsListJson = $this->getPatientFriendsJson();
            $element = 'create_team';              
            $this->set(compact('isPatient', 'userDetails', 'element', 
                'isVolunteer', 'myFriends', 'myFriendsListJson', 'teamImage', 'teamPrivacyHintList'));
        }        

    }
    
    /**
     * Function to get all friends
     */
    function getMyFriends() {
		$user_id = $this->Auth->user('id');
		$myFriends = NULL;
		$myFriends = $this->MyFriends->getFriendsList($user_id);
		$friendToInvite = array();
		foreach ($myFriends as $friend) {
			if ($friend['friend_type'] != 5 && $friend['friend_type'] != 6) { // Avoiding admin friends
				$friendList['User'] = $friend;
				// show disease privacy checking.
				$privacy = new UserPrivacySettings($friend['friend_id']);
				$diseaseViewPermittedTo = (int) $privacy->__get('view_your_disease');
				$viewDisease = FALSE;
				if (
						$diseaseViewPermittedTo === $privacy::PRIVACY_PUBLIC || $friend['friend_id'] == $user_id
				) {
					$viewDisease = true;
				} elseif ($diseaseViewPermittedTo === $privacy::PRIVACY_FRIENDS) {
					$friendStatus = (int) $this->MyFriends->getFriendStatus($friend['friend_id'], $user_id);
					if (($friendStatus === MyFriends::STATUS_CONFIRMED)) {
						$viewDisease = true;
					}
				}
				if ($viewDisease) {
					$diseaseDetails = $this->PatientDisease->getUserDisease($friend['friend_id']);
					$friendList['Disease'] = $diseaseDetails;
				}
				$friendToInvite[] = $friendList;
			}
		}
        return $friendToInvite;
    }
    
    /**
     * Function to get my patient friends
     */
//    function getPatientFriends() {
//        $patientFriends = array();
//        $user_id = $this->Auth->user('id');
//        $myFriends = NULL;
//        $myFriends = $this->MyFriends->getPatientFriendsList($user_id);
//        foreach ($myFriends as $friend) {
//                $friendList['User'] = $friend;
//                $diseaseDetails = $this->PatientDisease->getUserDisease($friend['friend_id']);
//                $friendList['Disease'] = $diseaseDetails;
//                $patientFriends[] = $friendList;
//        }
//        return $patientFriends;
//    }
    
    /**
     *  For implementing search in patient selection on creating the team.
     */
//    function getPatientFriendsJson() {
//        $user_id = $this->Auth->user('id');
//        $myFriends = NULL;
//        $myFriends = $this->MyFriends->getPatientFriendsList($user_id);
//        $patientFriendsListJson = json_encode(array('friends' => array('friend' => $myFriends)));
//        return $patientFriendsListJson;
//    }
    
    /**
     *  For implementing search in invite friend pop up on team creation.
     */
    function getMyFriendsJson() {
        $user_id = $this->Auth->user('id');
        $myFriends = NULL;
        $myFriends = $this->MyFriends->getFriendsList($user_id);
        $myFriendsListJson = json_encode(array('friends' => array('friend' => $myFriends)));
        return $myFriendsListJson;
    }

}
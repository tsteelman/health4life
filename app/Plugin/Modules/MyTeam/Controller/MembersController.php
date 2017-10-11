<?php

/**
 * MembersController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MyTeamAppController', 'MyTeam.Controller');


class MembersController extends MyTeamAppController {

    public $uses = array(
        'TeamMember',
        'MyFriends',
        'PatientDisease'
    );
    /**
	 * View Team Details
	 */
	public function index() {

		if (isset($this->request->params['teamId'])) {
			$showRoleRequest = FALSE;
			$showJoinRequest = FALSE;	
			$joinRequests = array();
			$teamId = $this->request->params['teamId'];
			$loginUserId = $this->_currentUserId;
			//get all members detail ( Members, Invited users )
			$memberDetails = $this->TeamMember->getTeamMemberDetails($teamId); 

			$teamMemberData = $this->TeamMember->getTeamMemberData($this->_teamId, $this->_currentUserId);
			
			//Check if there is any new role to approve.
			if (!is_null($teamMemberData['TeamMember']['new_role'])) {
				$showRoleRequest = TRUE;
			}
			// checking the logged in user is an organizer of the team
			$isOrganizer = $this->TeamMember->isOrganizer($teamId, $this->_currentUserId);
                        // checking the logged in user is the patient of the team
			$isPatient = $this->TeamMember->isPatientOfTeam($teamId, $this->_currentUserId);
			// list of friends to invite to team
			$myFriends = $this->getMyFriends($teamId, $this->_currentUserId);			
			
			$team = $this->_teamObj['Team'];
			if ($isOrganizer) { 				
				$joinRequests = $this->TeamMember->getAllTeamJoinRequests($this->_teamId);
				/**
				 * Show team join request if DB have request entry, irrespect to private/public
				 * To handle privacy switching
				 */
				if (!empty($joinRequests)) {
					$showJoinRequest = TRUE;
				}
			}
 
             // for implementing search in invite friends to team.
			$myFriendsListJson = $this->getmyFriendsJson($teamId, $this->_currentUserId);

			$this->set(compact(
							'memberDetails',
					'isOrganizer',
                                    'isPatient',
					'myFriends',
					'teamId',
					'loginUserId',
					'showRoleRequest',
					'showJoinRequest',
					'joinRequests',
                                        'myFriendsListJson'));
		}
	}
    
    /*
     * Function to get friends who is not part of the team.
     * 
     * @param int $teamId
     * @param int $userId
     * return array
     */
    public function getMyFriends($teamId, $userId) {
        $myFriends = NULL;
        $myFriends = $this->MyFriends->getFriendsList($userId);
        $friendToInvite = array();
        foreach ($myFriends as $friend) {
            $status = $this->TeamMember->isTeamMember($friend['friend_id'], $teamId);
            if($status == false && $friend['friend_type']!= 5 && $friend['friend_type']!= 6) {
                $friendList['User'] = $friend;
                $diseaseDetails = $this->PatientDisease->getUserDisease($friend['friend_id']);
                $friendList['Disease'] = $diseaseDetails;
                $friendToInvite[] = $friendList;
            }
        }
        return $friendToInvite;
    }
    
    /*
     * For implementing search in team member invite.
     * 
     * @param int $teamId
     * @param int $userId
     * return array
     */
     public function getmyFriendsJson($teamId, $userId) {
         $myFriends = NULL;
         $myFriends = $this->MyFriends->getFriendsList($userId);
         $friendToInvite = array();
         foreach ($myFriends as $friend) {
             $status = $this->TeamMember->isTeamMember($friend['friend_id'], $teamId);
             // Avoiding admin friends
             if($status == false && $friend['friend_type']!= 5 && $friend['friend_type']!= 6) {
                 $friendToInvite[] = $friend;
             }
         }
         $friendsListJson = json_encode(array('friends' => array('friend' => $friendToInvite)));
         return $friendsListJson;
     }


}
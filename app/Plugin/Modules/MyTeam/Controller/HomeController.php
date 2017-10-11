<?php

/**
 * HomeController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MyTeamAppController', 'MyTeam.Controller');
App::uses('HealthStatus', 'Utility');

/**
 * HomeController for teams.
 * 
 * HomeController is used to display the details of a team
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @author 	  Greeshma Radhakrishnan
 * @package   MyTeam
 * @category  Controllers 
 */
class HomeController extends MyTeamAppController {

    public $uses = array(
        'User',
        'UserSymptom',
        'UserTreatment',
        'HealthReading',
        'TeamMember',
        'CareCalendarEvent'
    );

    /**
     * View Team Details
     */
    public function index() {
        $teamObj = $this->getTeam();
        $team = $teamObj['Team'];
        $isApproved = $this->Team->isApproved($team['status']);
        $data = array();
        $data['organizerName'] = $teamObj['Organizer']['username'];

        if ($isApproved) { // team is approved
            $teamMemberData = $this->TeamMember->getTeamMemberData($this->_teamId, $this->_currentUserId);

            // if team is public, non members also can view 
            // patient and members tiles
            if ((int) $team['privacy'] === Team::PRIVACY_PUBLIC) {
                $this->_permissions['view_patient_tile'] = true;
                $this->_permissions['view_members_tile'] = true;
                $this->_permissions['view_medical_info'] = NULL;

                if (isset($this->_memberStatus)) { // record already present for approval
                    $data['showTeamJoinButtons'] = FALSE;
                } else {
                    $data['showTeamJoinButtons'] = TRUE;
                }

                if ((int) $this->_memberStatus == TeamMember::STATUS_NOT_APPROVED && !empty($teamMemberData['TeamMember']) && is_null($teamMemberData['TeamMember']['invited_by'])) {
                    $data['showTeamJoinApprovalWaiting'] = TRUE;
                } else {
                    $data['showTeamJoinApprovalWaiting'] = FALSE;
                }
            }
            if (!is_null($this->_memberStatus)) {

                //Check if there is any new role to approve.
                if (!is_null($teamMemberData['TeamMember']['new_role'])) {
                    $data['showNewRoleApproveDeclineButtons'] = true;
                }


                if ($this->TeamMember->isPatientOrOrganizerPatientOfTeam($this->_teamId, $this->_currentUserId)) {
                    $this->_permissions['view_medical_info'] = TeamMember::VIEW_MEDICAL_DATA_PERMISSION_APPROVED;
                } elseif (!$this->_isApprovedMember()) {
                    $this->_permissions['view_medical_info'] = NULL;
                } else {
                    $this->_permissions['view_medical_info'] = $teamMemberData['TeamMember']['can_view_medical_data'];
                }

                $this->_permissions['view_patient_tile'] = true;
                $this->_permissions['view_members_tile'] = true;
                if ($this->_isApprovedMember()) {
                    $this->_permissions['view_tasks_tile'] = true;
                } else if((!$this->_isApprovedMember()) 
						&& (!is_null($teamMemberData['TeamMember']['invited_by']))){
                    $data['showTeamMemberApproveDeclineButtons'] = true;
                    $data['invitedDate'] = $this->__getInvitedDate();
                    $data['notMember'] = true;
                }
            }
            $this->__setHomeTilesData();
        } else {
            $teamPatientId = (int) $team['patient_id'];
            if ($teamPatientId === $this->_currentUserId) {
                $data['showTeamApproveDeclineButtons'] = true;
            } else {
                $data['showTeamAwaitingApproval'] = true;
            }
        }

        $this->set($data);
    }

    /**
     * Function to get the date when the current user was invited to the team
     * 
     * @return string
     */
    private function __getInvitedDate() {
        // find current user from the list of team members
        $teamMembers = $this->_teamObj['TeamMember'];
        foreach ($teamMembers as $teamMember) {
            $teamMemberUserId = (int) $teamMember['user_id'];
            if ($teamMemberUserId === $this->_currentUserId) {
                $currentMember = $teamMember;
                break;
            }
        }

        // get the invited date
        $invitedDateStr = $currentMember['created'];
        $timezone = $this->Auth->user('timezone');
        $invitedDate = CakeTime::format('dS M', $invitedDateStr, false, $timezone);

        return $invitedDate;
    }

    /**
     * Function to set the data for the tiles in the home page of the team
     */
    private function __setHomeTilesData() {
        if (isset($this->_permissions['view_patient_tile'])) {
            $this->__setPatientTileData();
        }
        if (isset($this->_permissions['view_members_tile'])) {
            $this->__setMembersTileData();
        }
        if (isset($this->_permissions['view_tasks_tile'])) {
            $this->__setTasksTileData();
        }
        if ($this->TeamMember->isPatientOrOrganizerPatientOfTeam($this->_teamId, $this->_currentUserId)) {
            $this->__setpermissionRequestsTileData();
        }
    }

    /**
     * Function to set the data about the patient 
     */
    private function __setPatientTileData() {
        $patientId = $this->_teamObj['Team']['patient_id'];
        $patientFullData = $this->User->getFullUserDetails($patientId);
        $patientData = $patientFullData[0];
        $patientUser = $patientData['User'];
        $photo = Common::getUserThumb($patientId, $patientUser['type'], 'x_small');
        $profileUrl = Common::getUserProfileLink($patientUser['username'], true);

        // symptoms
        $symptoms = '';
        $symptomsList = $this->UserSymptom->getUserSymptomsList($patientId);
        if (!empty($symptomsList)) {
            $symptoms = join(', ', $symptomsList);
        }

        // treatments
        $treatments = '';
        $treatmentsList = $this->UserTreatment->getUserTreatmentNames($patientId);
        if (!empty($treatmentsList)) {
            $treatments = join(', ', $treatmentsList);
        }

        $data = array(
            'name' => $patientUser['username'],
            'profileUrl' => $profileUrl,
            'photo' => $photo,
            'healthStatus' => $this->__getHealthStatusData($patientId),
            'age' => $patientUser['age'],
            'location' => $patientData[0]['location'],
            'diseases' => $patientData[0]['diseases'],
            'medications' => $treatments,
            'symptoms' => $symptoms,
        );
        $this->set('patient', $data);
        $this->set('medDataViewPermission', $this->_permissions['view_medical_info']);
        $this->set('teamId', $this->_teamId);
    }

    /**
     * Function to get the latest health status data of patient
     * 
     * @param int $patientId
     * @return array
     */
    private function __getHealthStatusData($patientId) {
        $healthStatusData = array();
        $healthStatus = $this->HealthReading->getLatestHealthStatus($patientId);
        if (!empty($healthStatus)) {
            $smileyClass = HealthStatus::getFeelingSmileyClass($healthStatus['health_status']);
            $statusText = HealthStatus::getHealthStatusText($healthStatus['health_status']);
            $timezone = $this->Auth->user('timezone');

            if (CakeTime::isToday($healthStatus['created'], $timezone)) {
                $dateStr = __('today');
            } else {
                $date = CakeTime::format('dS M', $healthStatus['created'], false, $timezone);
                $dateStr = __('on %s', $date);
            }

            $healthStatusData = array(
                'smileyClass' => $smileyClass,
                'statusText' => ucwords($statusText),
                'date' => $dateStr
            );
        } else {
            $healthStatusData = array(
                'smileyClass' => 'feeling_very_good',
                'statusText' => __('Very Good'),
                'date' => ''
            );
        }
        return $healthStatusData;
    }

    /**
     * Function to set the members tile data
     */
    private function __setMembersTileData() {
        $data = array();
        $teamMembers = $this->TeamMember->getApprovedTeamMembers($this->_teamId);
        foreach ($teamMembers as $teamMember) {
            $data[] = $this->__getTeamMemberData($teamMember);
        }
        $this->set('members', $data);
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
     * Function to set the tasks tile data
     */
    private function __setTasksTileData() {

        $limit = 5; // get first five tasks
        $timezone = $this->Auth->user('timezone');
        $currentOffset = $this->CareCalendarEvent->getCurrentTaskOffset($this->_teamId, $timezone);
        $taskCount = $this->CareCalendarEvent->getTaskCount($this->_teamId);
        $nextOffset = $this->CareCalendarEvent->getNextOffset($taskCount, $currentOffset, $limit);
        $prevOffset = $this->CareCalendarEvent->getPreviousOffset($taskCount, $currentOffset, $limit);

        $data = $this->CareCalendarEvent->getTeamTasks($this->_teamId, $limit, $currentOffset);

        $this->set('tasks', $data);
        $moreTaskUrl = '/myteam/' . $this->_teamId . '/calendar';
        $taskDetailsBaseUrl = '/myteam/' . $this->_teamId . '/task/';
        $this->set('nextOffset', $nextOffset);
        $this->set('prevOffset', $prevOffset);
        $this->set('todayOffset', $currentOffset);
        $this->set('isToday', true);
        $this->set('teamId', $this->_teamId);
        $this->set('moreTaskUrl', $moreTaskUrl);
        $this->set('taskDetailsBaseUrl', $taskDetailsBaseUrl);
        $this->set('timezone', $this->Auth->user('timezone'));
    }

    /**
     * Function to set the tasks tile data
     */
    private function __setpermissionRequestsTileData() {

//        $requestedUsersData = $this->TeamMember->getAllPermissionRequests($this->_teamId);
        $requestedUsersData = array();
        $requestedUsersData[0] = array();
        $requestedUsersData[1] = array();
        $requestedUsersData[2] = array();
        $allTeamMembers = $this->TeamMember->getApprovedTeamMembers($this->_teamId);
        if (!empty($allTeamMembers)) {
            foreach ($allTeamMembers as $member) {

                $value = intval($member['TeamMember']['can_view_medical_data']);
                if ($member['TeamMember']['role'] != TeamMember::TEAM_ROLE_PATIENT && $member['TeamMember']['role'] != TeamMember::TEAM_ROLE_PATIENT_ORGANIZER) {
                    switch ($value) {
                        case 0:
                            $requestedUsersData[1][] = $member; //rejected users
                            break;
                        case 1:
                            $requestedUsersData[0][] = $member; //requested users
                            break;
                        case 2:
                            $requestedUsersData[2][] = $member; //approved users
                            break;
                    }
                }
            }
        }
        $this->set('requestedUsersData', $requestedUsersData);
    }

}

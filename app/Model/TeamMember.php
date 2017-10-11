<?php

App::uses('AppModel', 'Model');
App::uses('TeamModel', 'Model');
App::uses('PatientDisease', 'Model');

/**
 * TeamMember Model
 */
class TeamMember extends AppModel {

    /**
     * Team Member Status constants
     */
    const STATUS_NOT_APPROVED = 0;
    const STATUS_APPROVED = 1;

    /**
     * Team Member role constants
     */
    const TEAM_ROLE_MEMBER = 0;
    const TEAM_ROLE_PATIENT = 1;
    const TEAM_ROLE_ORGANIZER = 2;
    const TEAM_ROLE_PATIENT_ORGANIZER = 3;

    /**
     * Team Member permission to view patient medical data
     */
    const VIEW_MEDICAL_DATA_PERMISSION_REJECTED = 0;
    const VIEW_MEDICAL_DATA_PERMISSION_REQUESTED = 1;
    const VIEW_MEDICAL_DATA_PERMISSION_APPROVED = 2;

    /**
     * Notification setting constants
     */
    const NOTIFICATION_ON = 1;
    const NOTIFICATION_OFF = 0;

    public $belongsTo = array(
        'Team' => array(
            'className' => 'Team',
            'foreignKey' => 'team_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => array(
                'member_count' => array(
                    'TeamMember.status' => self::STATUS_APPROVED
                )
            ),
            'counterScope' => array('TeamMember.status' => self::STATUS_APPROVED)
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => array('username', 'type', 'email'),
            'order' => ''
        ),
        'InvitedBy' => array(
            'className' => 'User',
            'foreignKey' => 'invited_by',
            'conditions' => '',
            'fields' => array('username', 'type', 'email'),
            'order' => ''
        ),
        'RoleInvitedBy' => array(
            'className' => 'User',
            'foreignKey' => 'role_invited_by',
            'conditions' => '',
            'fields' => array('username', 'type', 'email'),
            'order' => ''
        )
    );

    /**
     * Function to get a team member
     * 
     * @param int $userId
     * @param int $teamId
     * @return array|null
     */
    public function getTeamMember($userId, $teamId) {
        $teamMember = null;
        $query = array(
            'conditions' => array(
                'user_id' => $userId,
                'team_id' => $teamId,
            )
        );
        $teamMemberObj = $this->find('first', $query);
        if (!empty($teamMemberObj['TeamMember'])) {
            $teamMember = $teamMemberObj['TeamMember'];
        }
        return $teamMember;
    }

    /**
     * Function to save notificaton settings for a user in a team
     * 
     * @param array $data
     * @return bool 
     */
    public function saveNotificationSettings($data) {
        $belongsTo = array('Team', 'User', 'InvitedBy', 'RoleInvitedBy');
        $this->unbindModel(array('belongsTo' => $belongsTo));

        $fields = array(
            "{$this->alias}.email_notification" => $data['email_notification'],
            "{$this->alias}.site_notification" => $data['site_notification'],
        );
        $conditions = array(
            "{$this->alias}.team_id" => $data['team_id'],
            "{$this->alias}.user_id" => $data['user_id']
        );

        return $this->updateAll($fields, $conditions);
    }

    /**
     * Function to get the list of ids of the approved members of a team
     * 
     * @param int $teamId
     * @return array
     */
    public function getApprovedTeamMemberIds($teamId) {
        $teamMemberIdList = $this->find('list', array(
            'conditions' => array(
                'team_id' => $teamId,
                'status' => self::STATUS_APPROVED,
            ),
            'fields' => array('user_id', 'user_id')
        ));

        return $teamMemberIdList;
    }

    /**
     * Function to check if user is approved member of a team
     * 
     * @param int $userId
     * @param int $teamId
     * @return bool
     */
    public function isUserApprovedTeamMember($userId, $teamId) {
        $count = $this->find('count', array(
            'conditions' => array(
                "{$this->alias}.team_id" => $teamId,
                "{$this->alias}.user_id" => $userId,
                "{$this->alias}.status" => self::STATUS_APPROVED
            )
        ));
        $isUserApprovedTeamMember = ($count > 0) ? true : false;
        return $isUserApprovedTeamMember;
    }

    /**
     * Function to get the list of approved members of a team
     * 
     * @param int $teamId
     * @return array
     */
    public function getApprovedTeamMembers($teamId) {
        $this->unbindModel(array('belongsTo' => array('InvitedBy')));
        $teamMembers = $this->find('all', array(
            'conditions' => array(
                "{$this->alias}.team_id" => $teamId,
                "{$this->alias}.status" => self::STATUS_APPROVED
            ),
            'order' => array('FIELD(TeamMember.role, 3, 1, 2, 0)')
        ));


        return $teamMembers;
    }

    public function getApprovedTeamMembersByList($teamId) {
        $teamMembersArray = array();
        $this->unbindModel(array('belongsTo' => array('Team', 'InvitedBy')));
        $teamMembers = $this->find('all', array(
            'conditions' => array(
                "{$this->alias}.team_id" => $teamId,
                "{$this->alias}.status" => self::STATUS_APPROVED
            )
        ));

        foreach ($teamMembers as $teamMember) {
            $teamMembersArray[$teamMember ['TeamMember']['user_id']] = $teamMember ['User']['username'];
        }
        return $teamMembersArray;
    }

    /**
     * Function to get the name of a role
     * 
     * @param int $role
     * @return string
     */
    public function getMemberRoleName($role) {
        switch ($role) {
            case self::TEAM_ROLE_MEMBER:
                $roleName = 'Member';
                break;
            case self::TEAM_ROLE_PATIENT:
            case self::TEAM_ROLE_PATIENT_ORGANIZER:
                $roleName = 'Patient';
                break;
            case self::TEAM_ROLE_ORGANIZER:
                $roleName = 'Team Lead';
                break;
        }
        return $roleName;
    }

    /**
     * Function to get the CSS class for a role
     * 
     * @param int $role
     * @return string
     */
    public function getMemberRoleClass($role) {
        switch ($role) {
            case self::TEAM_ROLE_MEMBER:
                $roleClass = 'memebr_member';
                break;
            case self::TEAM_ROLE_PATIENT:
            case self::TEAM_ROLE_PATIENT_ORGANIZER:
                $roleClass = 'memebr_patient';
                break;
            case self::TEAM_ROLE_ORGANIZER:
                $roleClass = 'memebr_organizer';
                break;
        }
        return $roleClass;
    }

    /**
     * Functionality to approve team creator to view medical data w.r.t. patient's settings.
     * 
     * @param array $team
     */
    public function updateAdminMedicalDataPermission($team, $myHealthViewStatus, $organizerId) {
        $result = FALSE;
//        $patientId = (int) $team['patient_id'];
//        $organizerId = (int) $team['created_by'];
//        $teamOrganizerData = $this->TeamMember->find($organizerId);
        $teamOrganizerData = $this->find('first', array(
            'conditions' => array(
                "{$this->alias}.user_id" => $organizerId,
                "{$this->alias}.team_id" => $team['id']
            )
        ));
        if (isset($teamOrganizerData['TeamMember']['id']) && $teamOrganizerData['TeamMember']['id'] > 0) {
            if ($myHealthViewStatus != UserPrivacySettings::PRIVACY_PRIVATE) {
                $this->id = $teamOrganizerData['TeamMember']['id'];
                $result = $this->saveField('can_view_medical_data', self::VIEW_MEDICAL_DATA_PERMISSION_APPROVED);
            }
        }
        return $result;
    }

    public function addTeamAdmins($team) {
        $now = date('Y-m-d H:i:s');
        $timestamp = "'" . $now . "'";
        $teamId = $team['id'];
        $patientId = (int) $team['patient_id'];
        $organizerId = (int) $team['created_by'];
        $patientMember = array(
            'user_id' => $patientId,
            'team_id' => $teamId,
            'status' => self::STATUS_APPROVED,
            'can_view_medical_data' => self::VIEW_MEDICAL_DATA_PERMISSION_APPROVED,
            'role' => self::TEAM_ROLE_PATIENT,
            'joined_on' => $timestamp
        );
        $organizerMember = array(
            'user_id' => $organizerId,
            'team_id' => $teamId,
            'status' => self::STATUS_APPROVED,
            'can_view_medical_data' => self::VIEW_MEDICAL_DATA_PERMISSION_REJECTED,
            'role' => self::TEAM_ROLE_ORGANIZER,
            'joined_on' => $timestamp
        );
        $data = array($patientMember, $organizerMember);
        return $this->saveAll($data);
    }

    /**
     * Function to get the list of approved and invited members of a team
     * 
     * @param int $teamId
     * @return array
     */
    public function getTeamMemberDetails($teamId) {
        $approvedMembers = $this->find('all', array(
            'conditions' => array(
                "{$this->alias}.team_id" => $teamId,
                "{$this->alias}.status" => self::STATUS_APPROVED,
            ),
            'fields' => array('TeamMember.id', 'TeamMember.user_id', 'TeamMember.status', 'TeamMember.role', 'TeamMember.new_role', 'User.username', 'User.type'),
            'order' => array('FIELD(TeamMember.role, 3, 1, 2, 0)')
        ));
        $invitedMembers = $this->find('all', array(
            'conditions' => array(
                "{$this->alias}.team_id" => $teamId,
                "{$this->alias}.status" => self::STATUS_NOT_APPROVED,
				"{$this->alias}.invited_by IS NOT NULL"	
            ),
            'fields' => array('TeamMember.id', 'TeamMember.user_id', 'TeamMember.status', 'TeamMember.role', 'User.username', 'User.type'),
            'order' => array('FIELD(TeamMember.role, 3, 1, 2, 0)')
        ));

        $teamMembers['approved'] = $approvedMembers;
        $teamMembers['invited'] = $invitedMembers;
        return $teamMembers;
    }

    /**
     * Function to check whether the logged in user is an organizer of the team
     * 
     * @param int $teamId
     * @param int $userId
     * @return boolean
     */
    public function isOrganizer($teamId, $userId) {
        $role = $this->find('list', array(
            'conditions' => array(
                "{$this->alias}.team_id" => $teamId,
                "{$this->alias}.user_id" => $userId,
            ),
            'fields' => array('TeamMember.user_id', 'TeamMember.role')
        ));
        if ($role[$userId] == self::TEAM_ROLE_ORGANIZER || $role[$userId] == self::TEAM_ROLE_PATIENT_ORGANIZER) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function to approve a team member
     * 
     * @param int $teamId
     * @param int $userId
     */
    public function approve($teamId, $userId) {
        $fields = array(
            "{$this->alias}.status" => self::STATUS_APPROVED,
            "{$this->alias}.new_role" => NULL,
            "{$this->alias}.joined_on" => "'" . date('Y-m-d H:i:s') . "'"
        );
        $conditions = array(
            "{$this->alias}.team_id" => $teamId,
            "{$this->alias}.user_id" => $userId
        );

        $updateStatus = $this->updateAll($fields, $conditions);
        //done to update member count since cakephp cachecounter is not working during update. :(
        $this->Team->updateMemberCount($teamId);

        return $updateStatus;
    }

    /**
     * Function to approve a role
     * 
     * @param int $teamId
     * @param int $userId
     * 
     * @return bool
     */
    public function approveRole($teamId, $userId) {
        $teamMemberData = $this->getTeamMemberData($teamId, $userId);
        $fields = array(
            "{$this->alias}.role" => $teamMemberData['TeamMember']['new_role'],
            "{$this->alias}.new_role" => NULL,
            "{$this->alias}.role_invited_by" => NULL,
            "{$this->alias}.joined_on" => "'" . date('Y-m-d H:i:s') . "'"
        );
        $conditions = array(
            "{$this->alias}.team_id" => $teamId,
            "{$this->alias}.user_id" => $userId
        );
        return $this->updateAll($fields, $conditions);
    }

    /**
     * Function to reset Patient-Organizer to patient
     * 
     * @param int $teamId	
     * 
     * @return bool
     */
    public function resetPatientOrganizerRole($teamId) {

        $fields = array(
            "{$this->alias}.role" => TeamMember::TEAM_ROLE_PATIENT,
            "{$this->alias}.new_role" => NULL,
            "{$this->alias}.role_invited_by" => NULL
        );
        $conditions = array(
            "{$this->alias}.team_id" => $teamId,
            "{$this->alias}.role" => TeamMember::TEAM_ROLE_PATIENT_ORGANIZER
        );
        return $this->updateAll($fields, $conditions);
    }

    /**
     * Function to decline new role request
     * 
     * @param int $teamId
     * @param int $userId
     * 
     * @return bool
     */
    public function declineRole($teamId, $userId) {
        $teamMemberData = $this->getTeamMemberData($teamId, $userId);
        $fields = array(
            "{$this->alias}.new_role" => NULL,
            "{$this->alias}.role_invited_by" => NULL
        );
        $conditions = array(
            "{$this->alias}.team_id" => $teamId,
            "{$this->alias}.user_id" => $userId
        );
        return $this->updateAll($fields, $conditions);
    }

    /**
     * Function to get the data of a team member
     * 
     * @param int $teamId
     * @param int $userId
     * @return array|null
     */
    public function getTeamMemberData($teamId, $userId) {
        $query = array(
            'conditions' => array(
                'user_id' => $userId,
                'team_id' => $teamId,
            ),
            'order' => array('FIELD(TeamMember.role, 3, 1, 2, 0)')
        );
        $teamMemberData = $this->find('first', $query);
        return $teamMemberData;
    }

    /**
     * Function to check if user is a member of a team, either approved or invited
     * 
     * @param int $userId
     * @param int $teamId
     * @return boolean
     */
    public function isTeamMember($userId, $teamId) {
        $count = $this->find('count', array(
            'conditions' => array(
                "{$this->alias}.team_id" => $teamId,
                "{$this->alias}.user_id" => $userId
            )
        ));
        $isTeamMember = ($count > 0) ? true : false;
        return $isTeamMember;
    }

    /**
     * Function to invite user to a team
     * 
     * @param int $teamId
     * @param int $userId
     * @param int $invitedBy
     * @return boolean
     */
    public function inviteUser($teamId, $userId, $invitedBy) {
        $data['user_id'] = $userId;
        $data['invited_by'] = $invitedBy;
        $data['team_id'] = $teamId;
        $data['status'] = self::STATUS_NOT_APPROVED;
        $data['role'] = self::TEAM_ROLE_MEMBER;
        return $this->saveAll($data);
    }
	
	/**
     * Function to give join request
     * 
     * @param int $teamId
     * @param int $userId     
     * @return boolean
     */
    public function joinRequest($teamId, $userId) {
        $data['user_id'] = $userId;        
        $data['team_id'] = $teamId;
        $data['status'] = self::STATUS_NOT_APPROVED;
        $data['role'] = self::TEAM_ROLE_MEMBER;
        return $this->saveAll($data);
    }

    /**
     * Function to get the count of appoved organizer ( Type 2 ) in a team.
     * 
     * @param int $teamId	 
     * @return int
     */
    public function getOrganizerCount($teamId) {
        $query = array(
            'conditions' => array(
                'team_id' => $teamId,
                'role' => self::TEAM_ROLE_ORGANIZER
            )
        );
        $organizerCount = $this->find('count', $query);
        return $organizerCount;
    }

    /**
     * Function to get the team members who have not approved team join
     * Updating Modified date in each week
     * 
     * @return array
     */
    public function getMembersForTeamJoinInvitationReminder() {
        $dayBeforeWeek = CakeTime::format('-1 weeks', '%Y-%m-%d');
        $teamMembers = $this->find('all', array(
			'conditions' => array(
				"{$this->alias}.status" => self::STATUS_NOT_APPROVED,
				"{$this->alias}.invited_by IS NOT NULL",
                "DATE({$this->alias}.modified)" => $dayBeforeWeek
			)
		));
        if (!empty($teamMembers)) {
            foreach ($teamMembers as $member) {
                $this->id = $member['TeamMember']['id'];
                $this->set('modified', date('Y-m-d H:i:s'));
                $this->save();
            }
        }
        return $teamMembers;
    }

    /**
     * Function to get the teams in which a user is an approved member
     * 
     * @param int $userId
     * @return array 
     */
    public function getUserApprovedTeams($userId) {
        $this->unbindModel(array('belongsTo' => array('User', 'InvitedBy')));
        $teamMembers = $this->find('all', array(
            'conditions' => array(
                "{$this->alias}.status" => self::STATUS_APPROVED,
                "{$this->alias}.user_id" => $userId,
            ),
            'order' => array('TeamMember.joined_on DESC'),
            'fields' => array('Team.id',
                'Team.name',
                'Team.created',
                'Team.member_count',
                'Team.patient_id',
                'TeamMember.role',
                'TeamMember.email_notification'
            )
        ));
        return $teamMembers;
    }

    /**
     * Function to get count of teams that supports user
     * 
     * @param int $userId
     * 
     * @return array
     */
    public function getUserTeamsCount($userId, $login_user_age) {

        $team = new Team();
        $user_supporting_count = $this->find('count', array(
            'conditions' => array(
                'TeamMember.user_id' => $userId,
                'TeamMember.role' => array(TeamMember::TEAM_ROLE_PATIENT,
                    TeamMember::TEAM_ROLE_PATIENT_ORGANIZER),
                'TeamMember.status' => TeamMember::STATUS_APPROVED
            )
        ));
        $data['user_supporting'] = $user_supporting_count;

        $supported_by_user_count = $this->find('count', array(
            'conditions' => array(
                'TeamMember.user_id' => $userId,
                'TeamMember.role' => array(TeamMember::TEAM_ROLE_MEMBER,
                    TeamMember::TEAM_ROLE_ORGANIZER),
                'TeamMember.status' => TeamMember::STATUS_APPROVED
            )
        ));
        $team_created_by_user = $team->find('count', array(
            'conditions' => array(
                'Team.created_by' => $userId,
                'Team.status' => Team::STATUS_NOT_APPROVED
            )
        ));
        $data['supported_by_user'] = $supported_by_user_count + $team_created_by_user;

        $invites_count = $this->find('count', array(
            'conditions' => array(
                'TeamMember.user_id' => $userId,
                'TeamMember.role' => TeamMember::TEAM_ROLE_MEMBER,
                'TeamMember.status' => TeamMember::STATUS_NOT_APPROVED,
				'TeamMember.invited_by !=' => NULL
            )
        ));

        $team_create_requests = $team->find('count', array(
            'conditions' => array(
                'Team.patient_id' => $userId,
                'Team.status' => Team::STATUS_NOT_APPROVED
            )
        ));

        $data['user_invited'] = $invites_count + $team_create_requests;
		
		$data['recommended_team'] = $this->getRecommendedTeamCount($userId, $login_user_age);

        $data['total'] = $data['user_invited'] + $data['supported_by_user'] + $data['user_supporting'];

        return $data;
    }
	/**
	 * Function to get count of recommended team
	 * 
	 * @param int $userId
	 * @return int
	 */
	public function getRecommendedTeamCount($userId, $login_user_age) {
		$myFriends = new MyFriends();
		$friends = array();
		$friends = $myFriends->getFriendsList($userId);
		$friendIds = array();
		foreach ($friends as $friend) {
			$friendIds[] = $friend['friend_id'];
		}

		$patientDisease = new PatientDisease();
		$userDiseaseIds = $patientDisease->getPatientDiseaseIds($userId);
		$myDiseaseIds = array();
		foreach ($userDiseaseIds as $myDisease) {
			$myDiseaseIds[] = $myDisease['PatientDisease']['disease_id'];
		}	
		
		//team ids of my friends or users having same disease.
		$userTeamsIds = $this->Team->getSimilarUserTeamIds($friendIds, $myDiseaseIds);
		$this->Team->virtualFields = array(
			'patient_age' => "DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(Patient.date_of_birth, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(Patient.date_of_birth, '00-%m-%d'))"
		);		

		$this->Team->unbindModel(
				array('hasMany' => array('TeamMember'))
		);
		$this->Team->bindModel(array(
			'hasOne' => array(
				'TeamMember' => array(
					'className' => 'TeamMember',
					'foreignKey' => 'team_id',
					'type' => 'LEFT',
					'conditions' => array(
						'TeamMember.user_id' => $userId
					),
				)
			)
				), false);
		$team_count = $this->Team->find('count', array(
			'conditions' => array(				
				'OR' => array(
					'AND' => array(
						'Team.status' => Team::STATUS_APPROVED,
						'Team.privacy' => Team::PRIVACY_PUBLIC,
						'Team.id' => $userTeamsIds,
						'TeamMember.invited_by IS NULL',
						'OR' => array(
							'TeamMember.status !=' => self::STATUS_APPROVED,
							'TeamMember.id IS NULL'
						)
					),
					array('AND' => array(
							'Team.patient_age BETWEEN ? AND ?' => array(
								$login_user_age - 5,
								$login_user_age + 5
							),
							'Team.status' => Team::STATUS_APPROVED,
							'Team.privacy' => Team::PRIVACY_PUBLIC,
							'TeamMember.invited_by IS NULL',
							'OR' => array(
								'TeamMember.status !=' => self::STATUS_APPROVED,
								'TeamMember.id IS NULL'
							)
						)
					))
			),
			
		));
		return $team_count;
	}

    /**
     * Function to check whether the logged in user is the patient of the team
     * 
     * @param int $teamId
     * @param int $userId
     * @return boolean
     */
    public function isPatientOfTeam($teamId, $userId) {
        $role = $this->find('list', array(
            'conditions' => array(
                "{$this->alias}.team_id" => $teamId,
                "{$this->alias}.user_id" => $userId,
            ),
            'fields' => array('TeamMember.user_id', 'TeamMember.role')
        ));
        if ($role[$userId] == self::TEAM_ROLE_PATIENT) {
            return true;
        } else {
            return false;
        }
    }

    public function isPatientOrOrganizerPatientOfTeam($teamId, $userId) {
        $role = $this->find('list', array(
            'conditions' => array(
                "{$this->alias}.team_id" => $teamId,
                "{$this->alias}.user_id" => $userId,
            ),
            'fields' => array('TeamMember.user_id', 'TeamMember.role')
        ));
        if (isset($role[$userId])) {
            if ($role[$userId] == self::TEAM_ROLE_PATIENT || $role[$userId] == self::TEAM_ROLE_PATIENT_ORGANIZER) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Function to get the members who have not approved the role promotion request
     * Updating Modified date in each week
     * 
     * @return array
     */
    public function getMembersForRolePromotionReminder() {
        $dayBeforeWeek = CakeTime::format('-1 weeks', '%Y-%m-%d');
        $teamMembers = $this->find('all', array(
            'conditions' => array(
                "{$this->alias}.new_role" => self::TEAM_ROLE_ORGANIZER,
                "DATE({$this->alias}.modified)" => $dayBeforeWeek
            )
        ));
        if (!empty($teamMembers)) {
            foreach ($teamMembers as $member) {
                $this->id = $member['TeamMember']['id'];
                $this->set('modified', date('Y-m-d H:i:s'));
                $this->save();
            }
        }
        return $teamMembers;
    }

    /**
     * Function to get the number of weeks between created week and current week
     * 
     * @return integer
     */
    public function getWeekDifference($id) {
        $created = $this->find('list', array(
            'conditions' => array(
                "{$this->alias}.id" => $id
            ),
            'fields' => array('TeamMember.created')
        ));
        $createdDate = strtotime($created[$id]);
        $currentDate = strtotime(date('Y-m-d H:i:s'));
        $difference = date("W", $currentDate) - date("W", $createdDate);
        return $difference;
    }

    //    public function approveAllRequestForViewMedicalResords($teamId) {
//        $result = false;
//            $data = $this->find('all', array(
//                'conditions' => array(
//                    "{$this->alias}.team_id" => $teamId,
//                    "{$this->alias}.can_view_medical_data !=" => 2,
//                )
//            ));
//            $this->id = $data['TeamMember']['id'];
//            if($this->saveAll('can_view_medical_data', 2)) {
//                $result = true;
//            }
//        return $result;
//    }

    public function getAllPermissionRequests($teamId) {
        $data = $this->find('all', array(
            'conditions' => array(
                "{$this->alias}.team_id" => $teamId,
                "{$this->alias}.status" => 1,
                "{$this->alias}.can_view_medical_data" => 1
            ),
            'fields' => array('TeamMember.id', 'TeamMember.user_id', 'TeamMember.team_id', 'TeamMember.status', 'TeamMember.role', 'User.username', 'User.type'),
        ));
        return $data;
    }
	
	/**
	 * Function to retrieve all team join request.
	 * @param int $teamId
	 * @return array 
	 */
	 public function getAllTeamJoinRequests($teamId) {
		$data = $this->find('all', array(
			'conditions' => array(
				"TeamMember.team_id" => $teamId,
				"TeamMember.status" => self::STATUS_NOT_APPROVED,
				"TeamMember.invited_by" => NULL
			)			
		));
		return $data;
	}

    public function manageRequestForViewMedicalResords($userId, $teamId, $action = 0) {
        if ($userId > 0 && $teamId > 0 && isset($action)) {
            $result = false;
            $isMember = $this->isTeamMember($userId, $teamId);
            if ($isMember) {
                $data = $this->find('first', array(
                    'conditions' => array(
                        "{$this->alias}.team_id" => $teamId,
                        "{$this->alias}.user_id" => $userId
                    )
                ));
                if (!empty($data)) {
                    $this->id = $data['TeamMember']['id'];
                    if ($action == 1) {//approve
                        $status = self::VIEW_MEDICAL_DATA_PERMISSION_APPROVED;
                    } else {
                        $status = self::VIEW_MEDICAL_DATA_PERMISSION_REJECTED;
                    }
                    $result = $this->saveField('can_view_medical_data', $status);
                    if (isset($result['TeamMember']['can_view_medical_data'])) {
                        $result = TRUE;
                    }
                }
            }
        }
        return $result;
    }

    public function saveRequestForViewMedicalResords($userId, $teamId) {
        $result['success'] = false;
        $result['message'] = '';
        $isMember = $this->isTeamMember($userId, $teamId);
        if ($isMember) {
            $data = $this->find('first', array(
                'conditions' => array(
                    "{$this->alias}.team_id" => $teamId,
                    "{$this->alias}.user_id" => $userId
                )
            ));
            if ($data['TeamMember']['can_view_medical_data'] != self::VIEW_MEDICAL_DATA_PERMISSION_APPROVED) {
                $this->id = $data['TeamMember']['id'];
                $resultData = $this->saveField('can_view_medical_data', self::VIEW_MEDICAL_DATA_PERMISSION_REQUESTED);
                if (isset($resultData['TeamMember']['can_view_medical_data'])) {
                    $result['success'] = TRUE;
                } else {
                    $result['success'] = FALSE;
                    $result['message'] = 'Cannot sent request. Please try again later.';
                }
            } else {
                $result['success'] = FALSE;
                $result['message'] = 'Already Approved to view the content';
            }
        }
        return $result;
    }

	/**
	 * Function to get the organizers of a team
	 * 
	 * @param int $teamId
	 * @return array
	 */
	public function getTeamOrganizers($teamId) {
		$organizerRoles = array(
			self::TEAM_ROLE_ORGANIZER,
			self::TEAM_ROLE_PATIENT_ORGANIZER
		);
		$teamOrganizers = $this->find('all', array(
			'conditions' => array(
				"{$this->alias}.team_id" => $teamId,
				"{$this->alias}.status" => self::STATUS_APPROVED,
				"{$this->alias}.role" => $organizerRoles
			)
		));
		return $teamOrganizers;
	}
	
	/**
	 * Function to get my teams and its members
	 * 
	 * @param int $userId
	 * @return array
	 */
	public function getMyTeamsAndMembers($userId) {
		$teams = $this->getUserApprovedTeams($userId);
		foreach ($teams as $team) {
			$teamId = $team['Team']['id'];
			$temp['teamName'] = $team['Team']['name'];
			$temp['role'] = $this->getMemberRoleName($team['TeamMember']['role']);
			$memberDetails = $this->getTeamMemberDetails($teamId);
			$names = array();
			foreach ($memberDetails['approved'] as $member) {
				$id = $member['TeamMember']['user_id'];
				if($id != $userId) {
					$names[] = $member['User']['username'];
				}
			}
			$memberList = implode(', ', $names);
			$temp['members'] = $memberList;
			if(empty($memberList)) {
				$temp['members'] = 'No other members';
			}
			$myTeams[] = $temp;
		}
		return $myTeams;
	}
}
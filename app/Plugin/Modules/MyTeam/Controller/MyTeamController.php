<?php

/**
 * MyTeamController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MyTeamAppController', 'MyTeam.Controller');
App::uses('Team', 'Model');
App::uses('TeamMember', 'Model');

/**
 * MyTeamController for frontend my team.
 * 
 * MyTeamController is used for managing my team functionality.
 *
 * @author      Ajay Arjunan
 * @package 	Calendar
 * @category	Controllers 
 */
class MyTeamController extends MyTeamAppController {

	public $uses = array(
		'Volunteer',
		'PatientDisease'
	);
	public $components = array('Paginator');

	const TEAM_LIMIT = 3;

	/**
	 * Main function in the My team controller
	 *
	 * @param null
	 * @return String
	 */
	public function index() {
		
		$this->set('title_for_layout',"My Team");
		$userType = $this->Auth->user('type');
		$isVolunteer = ($this->Volunteer->hasAny(array('user_id' => $this->_currentUserId))) ? true : false;
		$hasTeamCreatePermission = $this->_hasTeamCreatePermission();
		$login_user_age = $this->Auth->user('age');
		$teams_count = $this->TeamMember->getUserTeamsCount($this->_currentUserId, $login_user_age);
                
		$this->set(compact('isVolunteer', 'hasTeamCreatePermission', 'teams_count', 'userType'));
	}

	/**
	 * Load team supporting the user. ( Me )
	 */
	public function loadTeamSupportingMe() {
		
		/**
		 * Unbind and bind done to apply the filter condition to 2nd join table
		 * for paginator. 
		 */
		$this->Team->unbindModel(
				array('hasMany' => array('TeamMember'))
		);
		$this->Team->bindModel(array(
			'hasOne' => array(
				'TeamMember' => array(
					'className' => 'TeamMember',
					'foreignKey' => 'team_id',
					'conditions' => array(
						'TeamMember.status' => TeamMember::STATUS_APPROVED,
						'TeamMember.user_id' => $this->_currentUserId,
					),
				)
			)), false);

		$this->Paginator->settings = array(
			'conditions' => array(				
				'Team.status' => Team::STATUS_APPROVED,
				'Team.patient_id' => $this->_currentUserId
			),
                        'order' => array('TeamMember.joined_on' => 'DESC'),
			'limit' => self::TEAM_LIMIT
		);
		$teams = $this->Paginator->paginate('Team');
		$paginate = $this->params['paging']['Team'];
		$timezone = $this->Auth->user('timezone');
		$this->set(compact('teams', 'timezone'));

		$this->layout = "ajax";
		$View = new View($this, false);
		$response['htm_content'] = $View->element('MyTeam.myteam');
		$response['paginator'] = $paginate;
		echo json_encode($response);
		exit;
	}

	/**
	 * Load teams that login user support. 
	 * ( Team(s) that I give Support to )
	 * 
	 */
	public function loadTeamUserSupport() {
	
		$myTeamsIds = $this->Team->getMemberTeamIds($this->_currentUserId);
		/**
		 * Unbind and bind done to apply the filter condition to 2nd join table
		 * for paginator. 
		 */
		$this->Team->unbindModel(
				array('hasMany' => array('TeamMember'))
		);
		$this->Team->bindModel(array(
			'hasOne' => array(
				'TeamMember' => array(
					'className' => 'TeamMember',
					'foreignKey' => 'team_id',
					'conditions' => array(
						'TeamMember.status' => TeamMember::STATUS_APPROVED,
						'TeamMember.user_id' => $this->_currentUserId,
					),
				)
			)), false);
		
		$this->Paginator->settings = array(
			'conditions' => array(
				'AND' => array(
					'OR' => array(
						array(
							'Team.id' => $myTeamsIds,
							'Team.status' => Team::STATUS_APPROVED
						),
						array(
							'Team.created_by' => $this->_currentUserId,
							'Team.status' => Team::STATUS_NOT_APPROVED
						)
					),
					'Team.patient_id !=' => $this->_currentUserId
				)
			),
                        'order' => array('TeamMember.joined_on' => 'DESC'),
			'limit' => self::TEAM_LIMIT
		);

		$teams = $this->Paginator->paginate('Team');

		$paginate = $this->params['paging']['Team'];

		$timezone = $this->Auth->user('timezone');
		$this->set(compact('teams', 'timezone'));

		$this->layout = "ajax";
		$View = new View($this, false);
		$response['htm_content'] = $View->element('MyTeam.team_user_support');
		$response['paginator'] = $paginate;
		echo json_encode($response);
		exit;
	}

	/**
	 * Load team invitation.
	 */
	public function loadTeamInvitation() {
		$teamInvitation = $this->TeamMember->find('all', array(
			'conditions' => array(
				'TeamMember.status' => TeamMember::STATUS_NOT_APPROVED,
				'TeamMember.user_id' => $this->_currentUserId,
				'TeamMember.invited_by IS NOT NULL'
			),
			'fields' => array(
				'TeamMember.team_id'
			)
		));
		$myTeamsIds = array();
		
		foreach ($teamInvitation as $myTeam) {
			$myTeamsIds[] = $myTeam['TeamMember']['team_id'];			
		}
		/**
		 * Unbind and bind done to apply the filter condition to 2nd join table
		 * result set for paginator. 
		 */
		$this->Team->unbindModel(
				array('hasMany' => array('TeamMember'))
		);
		$this->Team->bindModel(array(
			'hasOne' => array(
				'TeamMember' => array(
					'className' => 'TeamMember',
					'foreignKey' => 'team_id',
					'conditions' => array(
						'TeamMember.status' => TeamMember::STATUS_NOT_APPROVED,						
						'TeamMember.user_id' => $this->_currentUserId,
						'TeamMember.invited_by IS NOT NULL'
					),
				)
			)), false);
		
		/**
		 * Retrieve not approved team ( another user created a team for u ) or 
		 * entry in team member which is not approved invited to you ( retrieve both ) 
		 */
		$this->Paginator->settings = array(
			'conditions' => array(
				'OR' => array(
					'AND' => array(
						'Team.status' => Team::STATUS_NOT_APPROVED,
						'Team.patient_id' => $this->_currentUserId,
					),
					'Team.id' => $myTeamsIds
				)
			),
			'order' => array('TeamMember.created' => 'DESC'),
			'recursive' => 2,
			'limit' => self::TEAM_LIMIT
		);

		$teams = $this->Paginator->paginate('Team');

		$paginate = $this->params['paging']['Team'];

		$timezone = $this->Auth->user('timezone');
		$authUser = $this->_currentUserId;
		$this->set(compact('teams', 'timezone', 'authUser'));

		$this->layout = "ajax";
		$View = new View($this, false);
		$response['htm_content'] = $View->element('MyTeam.team_invitation');
		$response['paginator'] = $paginate;
		echo json_encode($response);
		exit;
	}
	
	/**
	 * Load Teams I might be interested in.
	 * 
	 */
	public function loadRecommendedTeam() {
		$user_id = $this->_currentUserId;
		$login_user_age = $this->Auth->user('age');
		$myFriends = array();
		$myFriends = $this->MyFriends->getFriendsList($user_id);
		$myFriendIds = array();
		foreach ($myFriends as $myFriend) {
			$myFriendIds[] = $myFriend['friend_id'];
		}
		$userDiseaseIds = $this->PatientDisease->getPatientDiseaseIds($user_id);
		$myDiseaseIds = array();
		foreach ($userDiseaseIds as $myDisease) {
			$myDiseaseIds[] = $myDisease['PatientDisease']['disease_id'];
		}
		
		//team ids of my friends or users having same disease.
		$myFriendsTeamIds = $this->Team->getSimilarUserTeamIds($myFriendIds, $myDiseaseIds);
		/**
		 * Unbind and bind done to apply the filter condition to 2nd join table
		 * result set for paginator. 
		 */
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
						'TeamMember.user_id' => $user_id
					),
				)
			)
				), false);
		$this->Team->virtualFields = array(
			'patient_age' => "DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(Patient.date_of_birth, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(Patient.date_of_birth, '00-%m-%d'))"
		);
		$this->Paginator->settings = array(
			'conditions' => array(
				'OR' => array(
					'AND' => array(
						'Team.status' => Team::STATUS_APPROVED,
						'Team.privacy' => Team::PRIVACY_PUBLIC,
						'Team.id' => $myFriendsTeamIds,						
						'TeamMember.invited_by IS NULL',
						'OR' => array(
							'TeamMember.status !=' => TeamMember::STATUS_APPROVED,
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
								'TeamMember.status !=' => TeamMember::STATUS_APPROVED,
								'TeamMember.id IS NULL'
							)
						)
					))
			),
			'order' => array('Team.member_count' => 'asc'),
			'group' => array('Team.id'),			
			'limit' => self::TEAM_LIMIT
		);
		$teams = $this->Paginator->paginate('Team');		
		$paginate = $this->params['paging']['Team'];
		$timezone = $this->Auth->user('timezone');
		$this->set(compact('teams', 'timezone'));

		$this->layout = "ajax";
		$View = new View($this, false);
		$response['htm_content'] = $View->element('MyTeam.recommended_team');
		$response['paginator'] = $paginate;
		echo json_encode($response);
		exit;
	}
}
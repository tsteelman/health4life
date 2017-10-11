<?php

App::uses('AppModel', 'Model');

/**
 * Team Model
 */
class Team extends AppModel {

	/**
	 * Team Status constants
	 */
	const STATUS_NOT_APPROVED = 0;
	const STATUS_APPROVED = 1;

	/**
	 * Team privacy constans
	 */
	const TEAM_PUBLIC = 1;
	const TEAM_PRIVATE = 2;
	const TEAM_PRIVATE_TO_PUBLIC = 3;


	/**
	 * Team privacy constants
	 */
	const PRIVACY_PUBLIC = 1;
	const PRIVACY_PRIVATE = 2;
	const PRIVACY_CHANGE_PRI2PUB = 3;
	
	/**
	 * belongsTo associations
	 *
	 * @var array
	 */
	public $belongsTo = array(
		'Patient' => array(
			'className' => 'User',
			'foreignKey' => 'patient_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Organizer' => array(
			'className' => 'User',
			'foreignKey' => 'created_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	/**
	 * hasMany associations
	 *
	 * @var array
	 */
	public $hasMany = array(
		'TeamMember' => array(
			'className' => 'TeamMember',
			'foreignKey' => 'team_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter the team name.'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 50),
				'message' => 'Cannot be more than 50 characters long.'
			)
		),
		'about' => array(
			'maxLength' => array(
				'rule' => array('maxLength', 150),
				'message' => 'Cannot be more than 150 characters long.',
				'allowEmpty' => true
			)
		),
	);
	
	
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
	}
	
	/**
	 * Function to get the details of a team
	 * 
	 * @param int $teamId
	 * @return array
	 */
	public function getTeam($teamId) {
		$this->recursive = -1;
		$teamObj = $this->findById($teamId);
		return $teamObj['Team'];
	}

	/**
	 * Function to get the approved status of a team
	 * 
	 * @param int $teamStatus
	 * @return bool
	 */
	public function isApproved($teamStatus) {
		return ($teamStatus == self::STATUS_APPROVED) ? true : false;
	}

	/**
	 * Function to approve a team
	 * 
	 * @param int $teamId
	 * @return bool
	 */
	public function approve($teamId) {
		$this->id = $teamId;
		return $this->saveField('status', self::STATUS_APPROVED);
	}

	/**
	 * Function to get the ids of the approved teams of a patient
	 * 
	 * @param int $patientId
	 * @return array
	 */
	public function getPatientApprovedTeamIds($patientId) {
		$teamIdList = $this->find('list', array(
			'conditions' => array(
				'patient_id' => $patientId,
				'status' => self::STATUS_APPROVED
			),
			'fields' => array('id', 'id')
		));

		return $teamIdList;
	}
	
	/**
	 * Function to update member counter of a team
	 * 
	 * @param int $teamId
	 */
	public function updateMemberCount($teamId) {
		$this->id = $teamId;
		$count = $this->TeamMember->find('count',array(
			'conditions' => array(
				'TeamMember.status' => TeamMember::STATUS_APPROVED,
				'TeamMember.team_id' => $teamId
				)
		));
		$this->saveField('member_count', $count);
	}
	
	/**
	 * Function to get the patients who have not approved a team creation
	 * Updating Modified date in each week
	 * 
	 * @return array
	 */
	public function getPatientsForTeamApprovalReminder() {
		$dayBeforeWeek = CakeTime::format('-1 weeks', '%Y-%m-%d');
		$patient = $this->find('all', array(
			'conditions' => array(
				"{$this->alias}.status" => self::STATUS_NOT_APPROVED,
				"DATE({$this->alias}.modified)" => $dayBeforeWeek
			)
		));
		if(!empty($patient)) {
			foreach($patient as $team) {
				$this->id = $team['Team']['id'];
				$this->set('modified', date('Y-m-d H:i:s'));
				$this->save();
			}
		}
		return $patient;
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
            'fields' => array('Team.created')
        ));
	    $createdDate = strtotime($created[$id]);
		$currentDate = strtotime(date('Y-m-d H:i:s'));
		$difference = date("W", $currentDate) - date("W", $createdDate);
        return $difference;
	}
	
	/**
	 * Function to get team ids of a member ( approved member )
	 * 
	 * @return array teamids comma seperated ids
	 */
	
	public function getMemberTeamIds($userId){
		$teamInvitation = $this->TeamMember->find('all', array(
			'conditions' => array(
				'TeamMember.status' => TeamMember::STATUS_APPROVED,
				'TeamMember.user_id' => $userId,
			),
			'fields' => array(
				'TeamMember.team_id'
			)
		));
		$myTeamsIds = array();

		foreach ($teamInvitation as $myTeam) {
			$myTeamsIds[] = $myTeam['TeamMember']['team_id'];
		}
		
		return $myTeamsIds;
	}
	
	/**
	 * Function to get team ids of login user's friends.
	 * 
	 * @return array teamids comma seperated ids
	 */
	
	public function getFriendsTeamIds($friendsIds){
		$teamInvitation = $this->TeamMember->find('all', array(
			'conditions' => array(
				'TeamMember.status' => TeamMember::STATUS_APPROVED,
				'TeamMember.user_id' => $friendsIds,
			),
			'fields' => array(
				'TeamMember.team_id'
			),
			'group' => array('TeamMember.team_id')
		));
		$myTeamsIds = array();

		foreach ($teamInvitation as $myTeam) {
			$myTeamsIds[] = $myTeam['TeamMember']['team_id'];
		}
		
		return $myTeamsIds;
	}
	
	/**
	 * Function to get team ids of a member ( approved and non approved member )
	 * 
	 * @return array teamids comma seperated ids
	 */
	public function getUserAllTeamIds($userId){
		$teamInvitation = $this->TeamMember->find('all', array(
			'conditions' => array(				
				'TeamMember.user_id' => $userId,
			),
			'fields' => array(
				'TeamMember.team_id'
			)
		));
		$myTeamsIds = array();

		foreach ($teamInvitation as $myTeam) {
			$myTeamsIds[] = $myTeam['TeamMember']['team_id'];
		}
		
		return $myTeamsIds;
	} 

	/**
	 * Function to get recommended team ids based on our friends tam list and
	 * our diseses.
	 * 
	 * @param array $friendsIds 
	 * @param array $myDiseaseIds 
	 * @return type
	 */
	public function getSimilarUserTeamIds($friendsIds, $myDiseaseIds){
		
		$this->TeamMember->unbindModel(
				array('belongsTo' => array('User',
					'InvitedBy',
					'RoleInvitedBy'))
		); 
		$teamInvitation = $this->TeamMember->find('all', array(
			'joins' => array(
				   array(
                    'table' => 'patient_diseases',
                    'alias' => 'PatientDisease',
                    'type' => 'LEFT',
                    'conditions' => 'PatientDisease.patient_id  = TeamMember.user_id'
                ),
			),
			'conditions' => array(				
				'OR' => array(
					'AND' => array(
						'TeamMember.status' => TeamMember::STATUS_APPROVED,
						'TeamMember.user_id' => $friendsIds,
					),
					array('AND' => array(
							'TeamMember.status' => TeamMember::STATUS_APPROVED,
							'PatientDisease.disease_id' => $myDiseaseIds
						))
				)
			),
			'fields' => array(
				'TeamMember.team_id'
			),
			'group' => array('TeamMember.team_id')
		));
		$myTeamsIds = array();

		foreach ($teamInvitation as $myTeam) {
			$myTeamsIds[] = $myTeam['TeamMember']['team_id'];
		}
		
		return $myTeamsIds;
	}
        /**
         * Function to make a team public
         * 
         * @param int $id teamId
         * @return boolean 
         */
        public function makeTeamPublic( $id ) {
            
            // if team exists
            if ( $this->exists( $id )) {
                    $data['privacy'] = Team::TEAM_PUBLIC;
                    $data['privacy_requester_id'] = 0;
                    $this->id = $id;
                    return $this->save($data);
            }
            
            return false;
        }
        
         /**
         * Function to make a team private
         * 
         * @param int $id teamId
         * @return boolean 
         */
        public function makeTeamPrivate( $id ) {
            
            // if team exists
            if ( $this->exists( $id )) {
                    $data['privacy'] = Team::TEAM_PRIVATE;
                    $data['privacy_requester_id'] = 0;

                    $this->id = $id;
                    return $this->save($data);
            }
            
            return false;
        }
        
	/**
	 * Function to get the team privacy name from privacy value
	 * 
	 * @param int $privacy
	 * @return string
	 */
	public static function getTeamPrivacyName($privacy) {
		switch ($privacy) {
			case self::PRIVACY_PUBLIC:
				$privacyName = __('Public');
				break;
			case self::PRIVACY_PRIVATE:
				$privacyName = __('Private');
				break;
		}
		return $privacyName;
	}
}
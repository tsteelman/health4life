<?php

/**
 * MyTeamAppController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('FrontAppController', 'Controller');
App::uses('User', 'Model');

/**
 * MyTeamAppController for frontend myteam.
 * 
 * MyTeamAppController is the parent class file for MyTeam.
 *
 * @author      Ajay Arjunan
 * @package     MyTeam
 * @category    Controllers 
 */
class MyTeamAppController extends FrontAppController {

	public $uses = array(
		'Team',
		'TeamMember',
		'MyFriends',
		'Notification'
	);
	
	protected $_currentUserId;

	protected $_teamObj = null;
    
	protected $_teamId = null;
	
	protected $_memberStatus = null;
	
	protected $_memberRole = null;
	
	protected $_permissions = array();
	
	public $moduleUrl = '/myteam';
    
	public $url = array(
		'list' => array('link' => '/', 'title' => 'My Team'),
		'home' => array('link' => '/[team_id]', 'title' => '[team_name] Home'),
		'calendar' => array('link' => '/[team_id]/calendar', 'title' => '[team_name] Care Calendar'),
		'members' => array('link' => '/[team_id]/members', 'title' => '[team_name] Members'),
		'discussion' => array('link' => '/[team_id]/discussion', 'title' => '[team_name] Discussion'),
		'create' => array('link' => '/create', 'title' => 'Create Team'),
		'edit' => array('link' => '/[team_id]/edit', 'title' => '[team_name] Edit'),
		'task' => array('link' => '[team_id]/task', 'title' => '[team_name] Task'),
		'settings' => array('link' => '[team_id]/settings', 'title' => '[team_name] Settings')
	);

	/**
	 * Function to view the details of a team
	 *
	 * @param null
	 * @return String
	 */
	public function beforeFilter() {

		//call parent function
		parent::beforeFilter();

		if ($this->Auth->loggedIn() === true) {
			// set current user id
			$this->_currentUserId = (int) $this->Auth->user('id');

			$hasTeamCreatePermission = $this->_hasTeamCreatePermission();
			$this->set(compact('hasTeamCreatePermission'));

			/*
			 * If teamid is present, check if its an existing team
			 * and set the details to the class variable
			 */
			if (isset($this->params->teamId) && $this->params->teamId > 0) {
				$teamId = $this->params->teamId;
				$validTeam = $this->Team->exists($teamId);

				/*
				 * Do the following if a valid team
				 * otherwise, redirect to the My team page
				 */
				if ($validTeam) {
					// mark the team notifications as read by the logged in user
					$this->Notification->markTeamNotificationsReadByUser($teamId, $this->_currentUserId);

					// check permission and show team pages
					$this->setTeamID($this->params->teamId);
					$teamObj = $this->Team->findById($teamId);
					$this->setTeam($teamObj);
					$this->__setCurrentUserStatusAndRoleInTeam();
					$this->__setCurrentUserPermissionsOnTeam();					
					if (isset($this->_permissions['view_home']) && ($this->_permissions['view_home'])) {
						$isHomePage = ($this->params->controller === 'home');
						$isEditPage = ($this->params->controller === 'edit');
						$canViewOtherPages = isset($this->_permissions['view_all']);
						if (!$isHomePage && !$isEditPage && !$canViewOtherPages) {
							$this->redirect($this->replaceUrl('home'));
						} else {
							$this->__setViewData($teamId);
						}
					} else {
						$errorMsg = 'You are not allowed to access the requested team.';
						$this->Session->setFlash($errorMsg, 'error');
						$this->redirect($this->replaceUrl('list'));
					}
				} else {
					$this->Session->setFlash('The requested team does not exist', 'error');
					/*
					 * If Ajax request, give the response in a format
					 * @TODO
					 */
					if ($this->request->is('ajax')) {
						
					} else {
						$this->redirect($this->replaceUrl('list'));
					}
				}
			}
		}
	}

	/**
	 * Function to set the data for the view for the team
	 */
	private function __setViewData() {
		$team = $this->_teamObj['Team'];
		$page_title = str_replace('[team_name]', $team['name'], $this->url[$this->params->controller]['title']);
		$this->set('title_for_layout', $page_title);
		$menuItems = $this->__getLHSMenuItems();
		$teamImage = Common::getTeamThumb($this->_teamId, $team['patient_id'], 'medium');
		$patient = $this->_teamObj['Patient'];
		$patientName = $patient['username'];
		$patientId = $patient['id'];
		$timezone = $this->Auth->user('timezone');
		$createdDate = Date::getUSFormatDate($team['created'], $timezone);
                
                $organizerName = $this->_teamObj['Organizer']['username'];
		$viewData = compact('team', 'menuItems', 'teamImage', 
                        'patientName', 'createdDate', 'organizerName', 'patientId');
		$this->set($viewData);
                
                if ($this->TeamMember->isPatientOrOrganizerPatientOfTeam($this->_teamId, $this->_currentUserId)) {
                        if ( $this->_teamObj['Team']['privacy'] == Team::TEAM_PRIVATE_TO_PUBLIC ) {
                            $this->set('isPendingPrivacyApproval' , true);
                            
                            // If a requester Id is present
                            if ( !empty( $team['privacy_requester_id'] )) {
                                /*
                                 * Load modal User to get username form id
                                 */
                                $this->loadModel('User');
                                $privacyRequester = $this->User->getUsername($team['privacy_requester_id']);
                            } else {
                                $privacyRequester = "Organizer";
                            }
                            $this->set('privacyRequester' ,$privacyRequester );
                        }
                }
	}

	/**
	 * Function to set current user status and role in current team
	 */
	private function __setCurrentUserStatusAndRoleInTeam() {
		$team = $this->_teamObj['Team'];
		if ($this->Team->isApproved($team['status'])) {
			$teamMember = $this->TeamMember->getTeamMember($this->_currentUserId, $this->_teamId);
			if (!is_null($teamMember)) {
				$this->__setMemberStatus($teamMember['status']);
				$this->__setMemberRole($teamMember['role']);
			}
		}
	}

	/**
	 * Function to set the permissions of current user on current team
	 * 
	 * @return array
	 */
	private function __setCurrentUserPermissionsOnTeam() {
		$team = $this->_teamObj['Team'];		

		if ($this->Team->isApproved($team['status'])) {
			if($team['privacy'] == Team::PRIVACY_PUBLIC){ 
				$this->_permissions['view_home'] = true;
			} else {
				$this->_permissions['view_home'] = false;
			}
			if (!is_null($this->_memberStatus)) {
				if ($this->_isApprovedMember()) {
					$this->_permissions['view_home'] = true;
					$this->_permissions['view_all'] = true;

					$editPermittedRoles = array(
						TeamMember::TEAM_ROLE_PATIENT,
						TeamMember::TEAM_ROLE_ORGANIZER,
						TeamMember::TEAM_ROLE_PATIENT_ORGANIZER
					);
					if (in_array($this->_memberRole, $editPermittedRoles)) {
						$this->_permissions['edit'] = true;
					}
				} else { // to handle friend invitation, so not a approved member
					$this->_permissions['view_home'] = true;
				}
			}
		} else {
			$teamAdmins = array($team['created_by'], $team['patient_id']);
			if (in_array($this->_currentUserId, $teamAdmins)) {
				$this->_permissions['view_home'] = true;
				$this->_permissions['edit'] = true;
			}
		}
	}

	/**
	 * Function to get the list of LHS menu items for team
	 *
	 * @return array
	 */
	private function __getLHSMenuItems() {
		$teamUrl = $this->replaceUrl('home');
		$controller = $this->request->params['controller'];
		$homeMenuItem = array(
			'label' => __('Home'),
			'url' => "{$teamUrl}/home",
			'active' => ($controller === 'home'),
		);			
			
		$menuItems = array($homeMenuItem);

		if (isset($this->_permissions['edit'])) {
			$editMenuItem = array(
				'label' => __('Edit Details'),
				'url' => "{$teamUrl}/edit",
				'active' => ($controller === 'edit')
			);
			array_push($menuItems, $editMenuItem);
		}	
		
		if (isset($this->_permissions['view_all'])) {
			// checking the logged in user is an organizer of the team
			$isOrganizer = $this->TeamMember->isOrganizer($this->_teamId, $this->_currentUserId);
			$memberCount = $this->_teamObj['Team']['member_count'];
			if (isset($isOrganizer) && ($isOrganizer)) {
				$organizerMenuItem = array(
					'label' => __('Manage Members (%d)', $memberCount),
					'url' => "{$teamUrl}/members",
					'active' => ($controller === 'members'),
				);
				array_push($menuItems, $organizerMenuItem);
			} else {
				$nonOrganizerMenuItem = array(
					'label' => __('Members (%d)', $memberCount),
					'url' => "{$teamUrl}/members",
					'active' => ($controller === 'members'),		
				);
				array_push($menuItems, $nonOrganizerMenuItem);
			}
			$menuItems = array_merge($menuItems, array(
				array(
					'label' => __('Discussion'),
					'url' => "{$teamUrl}/discussion",
					'active' => ($controller === 'discussion'),
				),
				array(
					'label' => __('Care Calendar'),
					'url' => "{$teamUrl}/calendar",
					'active' => ($controller === 'calendar'),
				),				
				array(
					'label' => __('Settings'),
					'url' => "{$teamUrl}/settings",
					'active' => ($controller === 'settings'),
				),				
				array(
					'label' => __('Health Timeline'),
					'url' => "{$teamUrl}/#",
					'disabled' => true
				),
				array(
					'label' => __('Message'),
					'url' => "{$teamUrl}/#",
					'disabled' => true
				),
				array(
					'label' => __('Group Chat'),
					'url' => "{$teamUrl}/#",
					'disabled' => true
				)
			));
		}
		return $menuItems;
	}

	public function beforeRender() {
		parent::beforeRender();

		$this->set(array('module_url' => $this->moduleUrl,
			'module_sublinks' => $this->url));
	}

	/**
	 * Function to set the team object
	 *
	 * @param String
	 * @return null
	 */
	protected function setTeam($team) {
		$this->_teamObj = $team;
	}

	/**
	 * Function to get the team object
	 *
	 * @param null
	 * @return String
	 */
	protected function getTeam() {
		return $this->_teamObj;
	}

	/**
	 * Function to set the teamid
	 *
	 * @param String
	 * @return null
	 */
	protected function setTeamID($teamId) {
		$this->_teamId = $teamId;
	}

	/**
	 * Function to get the teamid
	 *
	 * @param null
	 * @return String
	 */
	protected function getTeamID() {
		return $this->_teamId;
	}
	
	/**
	 * Function to set the member status of current user in current team
	 *
	 * @param int
	 */
	private function __setMemberStatus($status) {
		$this->_memberStatus = (int) $status;
	}

	/**
	 * Function to get the member status of current user in current team
	 *
	 * @return int
	 */
	protected function _getMemberStatus() {
		return $this->_memberStatus;
	}

	/**
	 * Function to check if current user is approved member of current team
	 *
	 * @return boolean
	 */
	protected function _isApprovedMember() {
		return ($this->_memberStatus === TeamMember::STATUS_APPROVED);
	}

	/**
	 * Function to set the member role of current user in current team
	 *
	 * @param int
	 */
	private function __setMemberRole($role) {
		$this->_memberRole = (int) $role;
	}

	/**
	 * Function to get the member role of current user in current team
	 *
	 * @return int
	 */
	protected function _getMemberRole() {
		return $this->_memberRole;
	}
	
	/**
	 * Function to check if login member have privilage to create team
	 *
	 * @return boolean
	 */
	protected function _hasTeamCreatePermission() {		
//		$patientFriendCount = count($this->MyFriends->getPatientFriendsList($this->Auth->user('id'))); 

//		return (($patientFriendCount > 0) || ($this->Auth->user('type') == User::ROLE_PATIENT));
            return TRUE;
	}

	/**
	 * Function to return proper url by replacing the teamid
	 *
	 * @param null
	 * @return String
	 */
        protected function replaceUrl($url_key){
        return $this->moduleUrl.str_replace('[team_id]', 
                $this->getTeamID(), $this->url[$url_key]['link']);
	}
	
	

}
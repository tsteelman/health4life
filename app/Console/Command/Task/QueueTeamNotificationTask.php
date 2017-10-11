<?php

/**
 * The QueueTeamNotificationTask handles team notifications queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');
App::uses('EmailTemplateComponent', 'Controller/Component');
App::import('Controller', 'Api');

/**
 * Task class for adding team notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueueTeamNotificationTask extends AppShell {

	/**
	 * Models used by this command
	 * 
	 * @var array
	 */
	public $uses = array('Notification', 'Team', 'TeamMember', 'User', 'Email');

	/**
	 * Components used by this command
	 * 
	 * @var array
	 */
	public $components = array('EmailTemplate');

	/**
	 * @var bool
	 */
	public $autoUnserialize = true;

	/**
	 * @var array
	 */
	private $__data = array();

	/**
	 * QueueTeamNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return bool Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			$activityType = $data['activity_type'];
			$activityTypeCamelCase = $this->__underscoreToCamelCase($activityType);
			$method = sprintf('__add%sNotifications', $activityTypeCamelCase);
			if (method_exists($this, $method)) {
				$this->__data = $data;
				return $this->$method($data);
			} else {
				return false;
			}
		}
	}

	/**
	 * Function to convert an underscored string to camelcase format
	 * with first letter capital
	 * 
	 * @param string $string
	 * @return string
	 */
	private function __underscoreToCamelCase($string) {
		$str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
		return $str;
	}

	/**
	 * Function to add team join invitation accept email and site notifications
	 * 
	 * @return bool
	 */
	private function __addAcceptTeamJoinInvitationNotifications() {
		$teamId = $this->__data['team_id'];
		$userId = $this->__data['user_id'];
		$teamMemberData = $this->TeamMember->getTeamMemberData($teamId, $userId);
		$emailSuccess = $this->__sendTeamInvitationAcceptedMail($teamMemberData);
		$siteSuccess = $this->__addTeamInvitationAcceptSiteNotification($teamMemberData);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}
	
	/**
	 * Function to send email to the user who sent the invitation, to notify 
	 * that a member has accepted the team join invitation
	 * 
	 * @param array $teamMemberData
	 * @return bool
	 */
	private function __sendTeamInvitationAcceptedMail($teamMemberData) {
		$team = $teamMemberData['Team'];
		$invitedUser = $teamMemberData['InvitedBy'];
		$member = $teamMemberData['User'];
		$data = array(
			'username' => $invitedUser['username'],
			'name' => $member['username'],
			'team_name' => $team['name'],
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $invitedUser['email'];
		$templateId = EmailTemplateComponent::TEAM_INVITATION_APPROVED_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to add site notifications for the user who sent the invitation, 
	 * to notify that a member has accepted the team join invitation
	 * 
	 * @param array $teamMemberData
	 * @return bool
	 */
	private function __addTeamInvitationAcceptSiteNotification($teamMemberData) {
		$team = $teamMemberData['Team'];
		$teamMember = $teamMemberData['TeamMember'];
		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $teamMember['user_id'],
			'recipient_id' => $teamMember['invited_by']
		);
		return $this->Notification->addTeamNotification($team, $params);
	}

	/**
	 * Function to add team join invitation decline email and site notifications
	 * 
	 * @return bool
	 */
	private function __addDeclineTeamJoinInvitationNotifications() {
		$teamMemberData = $this->__data['team_member_data'];
		$emailSuccess = $this->__sendTeamInvitationDeclinedMail($teamMemberData);
		$siteSuccess = $this->__addTeamInvitationDeclinedSiteNotification($teamMemberData);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email to the user who sent the invitation, to notify 
	 * that a member has decined the team join invitation
	 * 
	 * @param array $teamMemberData
	 */
	private function __sendTeamInvitationDeclinedMail($teamMemberData) {
		$team = $teamMemberData['Team'];
		$invitedUser = $teamMemberData['InvitedBy'];
		$member = $teamMemberData['User'];
		$data = array(
			'username' => $invitedUser['username'],
			'name' => $member['username'],
			'team_name' => $team['name'],
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $invitedUser['email'];
		$templateId = EmailTemplateComponent::TEAM_INVITATION_DECLINED_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to add site notifications for the user who sent the invitation, 
	 * to notify that a member has declined the team join invitation
	 * 
	 * @param array $teamMemberData
	 * @return bool
	 */
	private function __addTeamInvitationDeclinedSiteNotification($teamMemberData) {
		$team = $teamMemberData['Team'];
		$teamMember = $teamMemberData['TeamMember'];
		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $teamMember['user_id'],
			'recipient_id' => $teamMember['invited_by']
		);
		return $this->Notification->addTeamNotification($team, $params);
	}

	/**
	 * Function to add team join invitation email and site notifications
	 * 
	 * @return bool
	 */
	private function __addTeamJoinInvitationNotifications() {
		$teamId = $this->__data['team_id'];
		$recipients = $this->__data['recipients'];

		// send email notifications
		foreach ($recipients as $userId) {
			$teamMemberData = $this->TeamMember->getTeamMemberData($teamId, $userId);
			$this->__sendTeamInvitationMail($teamMemberData);
		}

		// add site notifications
		$team = $teamMemberData['Team'];
		$this->__addTeamInvitationSiteNotifications($team);

		return true;
	}

	/**
	 * Function to send email to the users notifying invitation to join a team 
	 * 
	 * @param array $teamMemberData
	 */
	private function __sendTeamInvitationMail($teamMemberData) {
		$team = $teamMemberData['Team'];
		$member = $teamMemberData['User'];
		$data = array(
			'username' => $member['username'],
			'team_name' => $team['name'],
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $member['email'];
		$templateId = EmailTemplateComponent::TEAM_INVITATION_EMAIL_TEMPLATE;
		$this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to add site notifications to notify team join invitation
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __addTeamInvitationSiteNotifications($team) {
		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $this->__data['sender_id'],
			'recipients' => $this->__data['recipients']
		);
		return $this->Notification->addTeamNotification($team, $params);
	}

	/**
	 * Function to add email and site notifications to inform a user that he/she
	 * has been removed from a team
	 * 
	 * @return bool
	 */
	private function __addRemovedFromTeamNotifications() {
		$teamId = $this->__data['team_id'];
		$team = $this->Team->getTeam($teamId);
		$emailSuccess = $this->__sendRemovedFromTeamEmail($team);
		$siteSuccess = $this->__addRemovedFromTeamSiteNotification($team);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email notification to inform a user that he/she
	 * has been removed from a team
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __sendRemovedFromTeamEmail($team) {
		$recipient = $this->User->findById($this->__data['recipient_id']);
		$recipientUser = $recipient['User'];
		$link = Router::Url('/', TRUE) . "myteam/{$team['id']}";
		$data = array(
			'username' => $recipientUser['username'],
			'team_name' => $team['name'],
			'link' => $link
		);
		$toEmail = $recipientUser['email'];
		if (!empty($this->__data['reason'])) {
			$data['reason'] = $this->__data['reason'];
			$templateId = EmailTemplateComponent::REMOVED_FROM_TEAM_WITH_REASON_EMAIL_TEMPLATE;
		} else {
			$templateId = EmailTemplateComponent::REMOVED_FROM_TEAM_EMAIL_TEMPLATE;
		}
		return $this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to add site notification to inform a user that he/she
	 * has been removed from a team
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __addRemovedFromTeamSiteNotification($team) {
		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $this->__data['sender_id'],
			'recipient_id' => $this->__data['recipient_id']
		);
		if (!empty($this->__data['reason'])) {
			$params['additional_info'] = array('reason' => $this->__data['reason']);
		}
		return $this->Notification->addTeamNotification($team, $params);
	}

	/**
	 * Function to add site notifications to inform all users in a team about a 
	 * care request
	 * 
	 * @return bool
	 */
	private function __addCareRequestNotifications() {
		$this->__data['activity_id'] = $this->__data['task_id'];
		$additionalInfo = array('care_type' => $this->__data['care_type']);
		return $this->__sendNotificationsToAllMembers($additionalInfo);
	}

	/**
	 * Function to add site notifications to inform all users in a team about a 
	 * care request change
	 * 
	 * @return bool
	 */
	private function __addCareRequestChangeNotifications() {
		$this->__data['activity_id'] = $this->__data['task_id'];
		$additionalInfo = array('care_type' => $this->__data['care_type']);
		return $this->__sendNotificationsToAllMembers($additionalInfo);
	}

	/**
	 * Function to send notification to all members in a team about an activity
	 * 
	 * @param type $additionalInfo
	 * @return bool
	 */
	private function __sendNotificationsToAllMembers($additionalInfo = array()) {
		$teamId = $this->__data['team_id'];
		$teamMemberBelongsTo = array('Team', 'InvitedBy', 'RoleInvitedBy');
		$this->TeamMember->unbindModel(array('belongsTo' => $teamMemberBelongsTo));
		$members = $this->TeamMember->getApprovedTeamMembers($teamId);
		return $this->__sendNotificationToMembers($members, $additionalInfo);
	}

	/**
	 * Function to add team join request accepted email and site notifications
	 * 
	 * @return bool
	 */
	private function __addAcceptTeamJoinRequestNotifications() {
		$teamId = $this->__data['team_id'];
		$team = $this->__team = $this->Team->getTeam($teamId);
		$emailSuccess = $this->__sendTeamJoinRequestAcceptedMail($team);
		$siteSuccess = $this->__addTeamJoinRequestAcceptedSiteNotification($team);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email notification to the user who sent the request, 
	 * to notify that the organizer has accepted the team join request
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __sendTeamJoinRequestAcceptedMail($team) {
		$this->User->recursive = -1;
		$senderUser = $this->User->findById($this->__data['sender_id']);
		$sender = $senderUser['User'];
		$recipientUser = $this->User->findById($this->__data['recipient_id']);
		$recipient = $recipientUser['User'];
		$data = array(
			'username' => $recipient['username'],
			'name' => $sender['username'],
			'team_name' => $team['name'],
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $recipient['email'];
		$templateId = EmailTemplateComponent::TEAM_JOIN_REQUEST_ACCEPTED_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to send site notification to the user who sent the request, 
	 * to notify that the organizer has accepted the team join request
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __addTeamJoinRequestAcceptedSiteNotification($team) {
		return $this->Notification->addTeamNotification($team, $this->__data);
	}

	/**
	 * Function to add team join request declined email and site notifications
	 * 
	 * @return bool
	 */
	private function __addDeclineTeamJoinRequestNotifications() {
		$teamId = $this->__data['team_id'];
		$team = $this->__team = $this->Team->getTeam($teamId);
		$emailSuccess = $this->__sendTeamJoinRequestDeclinedMail($team);
		$siteSuccess = $this->__addTeamJoinRequestDeclinedSiteNotification($team);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email notification to the user who sent the request, 
	 * to notify that the organizer has declined the team join request
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __sendTeamJoinRequestDeclinedMail($team) {
		$this->User->recursive = -1;
		$senderUser = $this->User->findById($this->__data['sender_id']);
		$sender = $senderUser['User'];
		$recipientUser = $this->User->findById($this->__data['recipient_id']);
		$recipient = $recipientUser['User'];
		$data = array(
			'username' => $recipient['username'],
			'name' => $sender['username'],
			'team_name' => $team['name'],
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $recipient['email'];
		$templateId = EmailTemplateComponent::TEAM_JOIN_REQUEST_DECLINED_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to send site notification to the user who sent the request, 
	 * to notify that the organizer has declined the team join request
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __addTeamJoinRequestDeclinedSiteNotification($team) {
		return $this->Notification->addTeamNotification($team, $this->__data);
	}

	/**
	 * Function to send notification to selected members in a team about an activity
	 * 
	 * @param array $members
	 * @param array $additionalInfo
	 * @return bool
	 */
	private function __sendNotificationToMembers($members, $additionalInfo = array()) {
		$senderId = (int) $this->__data['sender_id'];
		foreach ($members as $member) {
			$teamMember = $member['TeamMember'];
			$memberId = (int) $teamMember['user_id'];
			if ($memberId !== $senderId) {
				if ($teamMember['email_notification'] === true) {
					$emailNotificationRecipients[] = $member['User'];
				}
				if ($teamMember['site_notification'] === true) {
					$siteNotificationRecipients[] = $memberId;
				}
			}
		}

		$teamId = $this->__data['team_id'];
		$team = $this->__team = $this->Team->getTeam($teamId);

		$siteSuccess = true;
		if (!empty($siteNotificationRecipients)) {
			$params = array(
				'team' => $team,
				'activity_type' => $this->__data['activity_type'],
				'sender_id' => $senderId,
				'recipients' => $siteNotificationRecipients,
				'additional_info' => $additionalInfo
			);

			if (isset($this->__data['activity_id'])) {
				$params['activity_id'] = $this->__data['activity_id'];
			}

			$siteSuccess = $this->Notification->addTeamNotification($team, $params);
		}

		$emailSuccess = true;
		if (!empty($emailNotificationRecipients)) {
			$emailSuccess = $this->__sendEmailNotifications($emailNotificationRecipients);
		}

		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email notifications to the recipients
	 * 
	 * @param array $recipients
	 * @return bool
	 */
	private function __sendEmailNotifications($recipients) {
		$activityType = $this->__data['activity_type'];
		$activityTypeCamelCase = $this->__underscoreToCamelCase($activityType);
		$method = sprintf('__add%sEmailNotification', $activityTypeCamelCase);
		if (method_exists($this, $method)) {
			$this->User->recursive = -1;
			$senderUser = $this->User->findById($this->__data['sender_id']);
			$sender = $senderUser['User'];
			foreach ($recipients as $recipient) {
				$this->$method($sender, $recipient);
			}
		}
		return true;
	}

	/**
	 * Function to send email notification to inform all users in a team about a 
	 * care request
	 * 
	 * @param array $sender
	 * @param array $recipient
	 * @return bool
	 */
	private function __addCareRequestEmailNotification($sender, $recipient) {
		$team = $this->__team;
		$taskId = $this->__data['task_id'];
		$taskName = $this->__data['task_name'];
		$link = Router::Url('/', TRUE) . "myteam/{$team['id']}/task/{$taskId}";
		$emailData = array(
			'username' => $recipient['username'],
			'name' => $sender['username'],
			'link' => $link,
			'care_type' => $this->__data['care_type'],
			'team_name' => $team['name'],
			'task_name' => $taskName
		);
		$toEmail = $recipient['email'];
		$templateId = EmailTemplateComponent::CARE_REQUEST_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $emailData, $toEmail);
	}

	/**
	 * Function to send email notification to inform all users in a team about a 
	 * change in care request
	 * 
	 * @param array $sender
	 * @param array $recipient
	 * @return bool
	 */
	private function __addCareRequestChangeEmailNotification($sender, $recipient) {
		$team = $this->__team;
		$taskId = $this->__data['task_id'];
		$taskName = $this->__data['task_name'];
		$link = Router::Url('/', TRUE) . "myteam/{$team['id']}/task/{$taskId}";
		$emailData = array(
			'username' => $recipient['username'],
			'name' => $sender['username'],
			'link' => $link,
			'care_type' => $this->__data['care_type'],
			'team_name' => $team['name'],
			'task_name' => $taskName
		);
		$toEmail = $recipient['email'];
		$templateId = EmailTemplateComponent::CARE_REQUEST_CHANGED_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $emailData, $toEmail);
	}

	/**
	 * Function to add team created email and site notifications
	 * 
	 * @return bool
	 */
	private function __addCreateTeamNotifications() {
		$teamId = $this->__data['team_id'];
		$teamData = $this->Team->findById($teamId);
		$team = $teamData['Team'];
		$emailSuccess = $this->__sendTeamCreatedMail($teamData);
		$siteSuccess = $this->__addTeamCreatedSiteNotification($team);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email to a patient to notify that a team has been 
	 * created to support him/her.
	 * 
	 * @param array $teamData
	 * @return bool
	 */
	private function __sendTeamCreatedMail($teamData) {
		$team = $teamData['Team'];
		$patient = $teamData['Patient'];
		$organizer = $teamData['Organizer'];
		$data = array(
			'username' => $patient['username'],
			'name' => $organizer['username'],
			'team_name' => $team['name'],
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $patient['email'];
		$templateId = EmailTemplateComponent::TEAM_CREATED_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to add site notification to a patient to notify that a team has 
	 * been created to support him/her.
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __addTeamCreatedSiteNotification($team) {
		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $this->__data['created_by'],
			'recipient_id' => $this->__data['patient_id']
		);
		return $this->Notification->addTeamNotification($team, $params);
	}

	/**
	 * Function to add team role approved email and site notifications
	 * 
	 * @return bool
	 */
	private function __addTeamRoleApprovedNotifications() {
		$teamMemberData = $this->__data['teamMemberData'];
		$emailSuccess = $this->__sendRoleApprovedMailToOrganizer($teamMemberData);
		$siteSuccess = $this->__addTeamRoleApprovedSiteNotification($teamMemberData);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email to the organizer about role approve by member
	 * 
	 * @param array $teamMemberData
	 * @return bool
	 */
	private function __sendRoleApprovedMailToOrganizer($teamMemberData) {
		$teamMember = $teamMemberData['TeamMember'];
		if ($teamMember['email_notification'] === true) {
			$link = Router::Url('/', TRUE) . "myteam/{$teamMemberData['Team']['id']}";
			$role = $this->TeamMember->getMemberRoleName(TeamMember::TEAM_ROLE_ORGANIZER);
			$data = array(
				'username' => $teamMemberData['RoleInvitedBy']['username'],
				'name' => $teamMemberData['User']['username'],
				'link' => $link,
				'team_name' => $teamMemberData['Team']['name'],
				'role' => $role
			);
			$toEmail = $teamMemberData['RoleInvitedBy']['email'];
			$templateId = EmailTemplateComponent::ROLE_APPROVED_NOTIFICATION_EMAIL_TEMPLATE;
			return $this->__sendHTMLMail($templateId, $data, $toEmail);
		} else {
			return true;
		}
	}

	/**
	 * Function to add site notifications for the user who sent the invitation, 
	 * to notify that a member has approved the role invitation
	 * 
	 * @param array $teamMemberData
	 * @return bool
	 */
	private function __addTeamRoleApprovedSiteNotification($teamMemberData) {
		$teamMember = $teamMemberData['TeamMember'];
		if ($teamMember['site_notification'] === true) {
			$team = $teamMemberData['Team'];
			$newRoleName = $this->TeamMember->getMemberRoleName($teamMember['new_role']);
			$additionalInfo = array('role_name' => $newRoleName);
			$params = array(
				'activity_type' => $this->__data['activity_type'],
				'sender_id' => $teamMember['user_id'],
				'recipient_id' => $teamMember['role_invited_by'],
				'additional_info' => $additionalInfo
			);
			return $this->Notification->addTeamNotification($team, $params);
		} else {
			return true;
		}
	}

	/**
	 * Function to add team role invitation email and site notifications
	 * 
	 * @return bool
	 */
	private function __addTeamRoleInvitationNotifications() {
		$teamMemberId = $this->__data['teamMemberId'];
		$teamMemberData = $this->TeamMember->findById($teamMemberId);
		if (!is_null($teamMemberData)) {
			$emailSuccess = $this->__sendTeamRoleInvitationMail($teamMemberData);
			$siteSuccess = $this->__addTeamRoleInvitationSiteNotification($teamMemberData);
			$success = ($emailSuccess && $siteSuccess);
		} else {
			$success = true;
		}
		return $success;
	}

	/**
	 * Function to send team role invitation email
	 * 
	 * @param array $teamMemberData
	 * @return bool
	 */
	private function __sendTeamRoleInvitationMail($teamMemberData) {
		$teamMember = $teamMemberData['TeamMember'];
		if ($teamMember['email_notification'] === true) {
			$teamId = $teamMemberData['Team']['id'];
			$link = Router::Url('/', TRUE) . "myteam/{$teamId}";
			$newRoleName = $this->TeamMember->getMemberRoleName($teamMember['new_role']);
			$data = array(
				'username' => $teamMemberData['User']['username'],
				'name' => $teamMemberData['RoleInvitedBy']['username'],
				'link' => $link,
				'team_name' => $teamMemberData['Team']['name'],
				'role' => $newRoleName
			);
			$toEmail = $teamMemberData['User']['email'];
			$templateId = EmailTemplateComponent::TEAM_ROLE_INVITATION_EMAIL_TEMPLATE;
			return $this->__sendHTMLMail($templateId, $data, $toEmail);
		} else {
			return true;
		}
	}

	/**
	 * Function to add team role invitation site notification
	 * 
	 * @param array $teamMemberData
	 * @return bool
	 */
	private function __addTeamRoleInvitationSiteNotification($teamMemberData) {
		$teamMember = $teamMemberData['TeamMember'];
		if ($teamMember['site_notification'] === true) {
			$team = $teamMemberData['Team'];
			$newRoleName = $this->TeamMember->getMemberRoleName($teamMember['new_role']);
			$additionalInfo = array('role_name' => $newRoleName);
			$params = array(
				'activity_type' => $this->__data['activity_type'],
				'sender_id' => $teamMember['role_invited_by'],
				'recipient_id' => $teamMember['user_id'],
				'additional_info' => $additionalInfo
			);
			return $this->Notification->addTeamNotification($team, $params);
		} else {
			return true;
		}
	}

	/**
	 * Function to add team role declined email and site notifications
	 * 
	 * @return bool
	 */
	private function __addTeamRoleDeclinedNotifications() {
		$teamMemberData = $this->__data['teamMemberData'];
		$emailSuccess = $this->__sendRoleDeclinedMailToOrganizer($teamMemberData);
		$siteSuccess = $this->__addTeamRoleDeclinedSiteNotification($teamMemberData);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to add demote organizer email and site notifications
	 * 
	 * @return bool
	 */
	private function __addDemoteOrganizerNotifications() {
		$teamMemberData = $this->__data['teamMemberData'];
		$demoteByUserId = $this->__data['demote_by'];
		$emailSuccess = $this->__sendDemoteOrganizerMailToUser($teamMemberData, $demoteByUserId);
		$siteSuccess = $this->__addDemoteOrganizerNotification($teamMemberData, $demoteByUserId);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email to the user about demote organizer
	 * 
	 * @param array $teamData
	 */
	private function __sendDemoteOrganizerMailToUser($teamMemberData, $demoteByUserId) {
		$team = $teamMemberData['Team'];
		$member = $teamMemberData['User'];
		$role = $this->TeamMember->getMemberRoleName(TeamMember::TEAM_ROLE_ORGANIZER);
		$emailData = array(
			'username' => $member['username'],
			'team_name' => $team['name'],
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}",
			'role' => $role
		);
		$toEmail = $member['email'];
		$templateId = EmailTemplateComponent::DEMOTE_TEAM_ORGANIZER_NOTIFICATION_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $emailData, $toEmail);
	}

	/**
	 * Function to send site notification to the user about demote organizer privilege
	 * 
	 * @param array $teamData
	 */
	private function __addDemoteOrganizerNotification($teamMemberData, $demoteByUserId) {
		$team = $teamMemberData['Team'];
		$teamMember = $teamMemberData['TeamMember'];

		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $demoteByUserId,
			'recipient_id' => $teamMember['user_id']
		);
		return $this->Notification->addTeamNotification($team, $params);
	}

	/**
	 * Function to send email to the organizer about role approve by member
	 * 
	 * @param array $teamData
	 */
	private function __sendRoleDeclinedMailToOrganizer($teamMemberData) {
		$teamMember = $teamMemberData['TeamMember'];
		if ($teamMember['email_notification'] === true) {
			$link = Router::Url('/', TRUE) . "myteam/{$teamMemberData['Team']['id']}";
			$role = $this->TeamMember->getMemberRoleName(TeamMember::TEAM_ROLE_ORGANIZER);
			$data = array(
				'username' => $teamMemberData['RoleInvitedBy']['username'],
				'name' => $teamMemberData['User']['username'],
				'link' => $link,
				'team_name' => $teamMemberData['Team']['name'],
				'role' => $role
			);
			$toEmail = $teamMemberData['RoleInvitedBy']['email'];
			$templateId = EmailTemplateComponent::ROLE_DECLINED_NOTIFICATION_EMAIL_TEMPLATE;
			return $this->__sendHTMLMail($templateId, $data, $toEmail);
		} else {
			return true;
		}
	}

	/**
	 * Function to add site notifications for the user who sent the invitation, 
	 * to notify that a member has declined the role invitation
	 * 
	 * @param array $teamMemberData
	 * @return bool
	 */
	private function __addTeamRoleDeclinedSiteNotification($teamMemberData) {
		$teamMember = $teamMemberData['TeamMember'];
		if ($teamMember['site_notification'] === true) {
			$team = $teamMemberData['Team'];
			$newRoleName = $this->TeamMember->getMemberRoleName($teamMember['new_role']);
			$additionalInfo = array('role_name' => $newRoleName);
			$params = array(
				'activity_type' => $this->__data['activity_type'],
				'sender_id' => $teamMember['user_id'],
				'recipient_id' => $teamMember['role_invited_by'],
				'additional_info' => $additionalInfo
			);
			return $this->Notification->addTeamNotification($team, $params);
		} else {
			return true;
		}
	}

	/**
	 * Function to add team approved email and site notifications
	 * 
	 * @return bool
	 */
	private function __addTeamApprovedNotifications() {
		$teamObj = $this->__data['teamObj'];
		$emailSuccess = $this->__sendTeamApprovedMailToOrganizer($teamObj);
		$siteSuccess = $this->__addTeamApprovedSiteNotification($teamObj['Team']);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email to the organizer about team approval by the patient
	 * 
	 * @param array $teamData
	 */
	private function __sendTeamApprovedMailToOrganizer($teamData) {
		$team = $teamData['Team'];
		$organizer = $teamData['Organizer'];
		$data = array(
			'username' => $organizer['username'],
			'name' => $teamData['Patient']['username'],
			'team_name' => $team['name'],
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}"
		);
		$toEmail = $organizer['email'];
		$templateId = EmailTemplateComponent::TEAM_APPROVED_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to add site notifications for the user who created the team, 
	 * to notify that patient has approved the team
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __addTeamApprovedSiteNotification($team) {
		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $team['patient_id'],
			'recipient_id' => $team['created_by']
		);
		return $this->Notification->addTeamNotification($team, $params);
	}

	/**
	 * Function to add team declined email and site notifications
	 * 
	 * @return bool
	 */
	private function __addTeamDeclinedNotifications() {
		$teamData = $this->__data['teamData'];
		$emailSuccess = $this->__sendTeamDeclinedMailToOrganizer($teamData);
		$siteSuccess = $this->__addTeamDeclinedSiteNotification($teamData['Team']);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email to the organizer about team decline by the patient
	 * 
	 * @param array $teamData
	 */
	private function __sendTeamDeclinedMailToOrganizer($teamData) {
		$team = $teamData['Team'];
		$organizer = $teamData['Organizer'];
		$data = array(
			'username' => $organizer['username'],
			'name' => $teamData['Patient']['username'],
			'team_name' => $team['name']
		);
		$toEmail = $organizer['email'];
		$templateId = EmailTemplateComponent::TEAM_DECLINED_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to add site notifications for the user who created the team, 
	 * to notify that patient has declined the team
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __addTeamDeclinedSiteNotification($team) {
		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $team['patient_id'],
			'recipient_id' => $team['created_by']
		);
		return $this->Notification->addTeamNotification($team, $params);
	}

	/**
	 * Function to send email and site notifications to all the members of a 
	 * team on privacy change
	 * 
	 * @return bool
	 */
	private function __addTeamPrivacyChangeNotifications() {
		$this->__data['old_privacy'] = Team::getTeamPrivacyName($this->__data['old_privacy']);
		$this->__data['new_privacy'] = Team::getTeamPrivacyName($this->__data['new_privacy']);
		$additionalInfo = array(
			'old_privacy' => $this->__data['old_privacy'],
			'new_privacy' => $this->__data['new_privacy']
		);
		return $this->__sendNotificationsToAllMembers($additionalInfo);
	}

	/**
	 * Function to send email notification about the change in privacy of a team
	 * 
	 * @param array $sender
	 * @param array $recipient
	 * @return bool
	 */
	private function __addTeamPrivacyChangeEmailNotification($sender, $recipient) {
		$team = $this->__team;
		$link = Router::Url('/', TRUE) . "myteam/{$team['id']}";
		$emailData = array(
			'username' => $recipient['username'],
			'name' => $sender['username'],
			'team_name' => $team['name'],
			'link' => $link,
			'old_privacy' => $this->__data['old_privacy'],
			'new_privacy' => $this->__data['new_privacy']
		);
		$toEmail = $recipient['email'];
		$templateId = EmailTemplateComponent::TEAM_PRIVACY_CHANGE_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $emailData, $toEmail);
	}

	/**
	 * Function to send email and site notification to the patient of a 
	 * team when organizer request to change privacy
	 * 
	 * @return bool
	 */
	private function __addTeamPrivacyChangeRequestNotifications() {
		$teamId = $this->__data['team_id'];
		$team = $this->Team->getTeam($teamId);
		$this->__data['old_privacy'] = Team::getTeamPrivacyName($this->__data['old_privacy']);
		$this->__data['new_privacy'] = Team::getTeamPrivacyName($this->__data['new_privacy']);
		$emailSuccess = $this->__sendTeamPrivacyChangeRequestMail($team);
		$siteSuccess = $this->__addTeamPrivacyChangeRequestSiteNotification($team);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email to the patient to notify that an organizer has 
	 * requested to change the privacy of the team
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __sendTeamPrivacyChangeRequestMail($team) {
		$patientUser = $this->User->findById($team['patient_id']);
		$patient = $patientUser['User'];
		$senderUser = $this->User->findById($this->__data['sender_id']);
		$sender = $senderUser['User'];
		$data = array(
			'username' => $patient['username'],
			'name' => $sender['username'],
			'team_name' => $team['name'],
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}",
			'old_privacy' => $this->__data['old_privacy'],
			'new_privacy' => $this->__data['new_privacy']
		);
		$toEmail = $patient['email'];
		$templateId = EmailTemplateComponent::TEAM_PRIVACY_CHANGE_REQUEST_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to add site notification to the patient to notify that an
	 * organizer has requested to change the privacy of the team
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __addTeamPrivacyChangeRequestSiteNotification($team) {
		$additionalInfo = array(
			'old_privacy' => $this->__data['old_privacy'],
			'new_privacy' => $this->__data['new_privacy']
		);
		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $this->__data['sender_id'],
			'recipient_id' => $team['patient_id'],
			'additional_info' => $additionalInfo
		);
		return $this->Notification->addTeamNotification($team, $params);
	}

	/**
	 * Function to send email and site notification to the organizer when 
	 * patient rejects the request to change team privacy
	 * 
	 * @return bool
	 */
	private function __addTeamPrivacyChangeRequestRejectedNotifications() {
		$teamId = $this->__data['team_id'];
		$team = $this->Team->getTeam($teamId);
		$this->__data['old_privacy'] = Team::getTeamPrivacyName($this->__data['old_privacy']);
		$this->__data['new_privacy'] = Team::getTeamPrivacyName($this->__data['new_privacy']);
		$emailSuccess = $this->__sendTeamPrivacyChangeRequestRejectedMail($team);
		$siteSuccess = $this->__addTeamPrivacyChangeRequestRejectedSiteNotification($team);
		$success = ($emailSuccess && $siteSuccess);
		return $success;
	}

	/**
	 * Function to send email to the organizer when patient rejects the request
	 * to change team privacy
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __sendTeamPrivacyChangeRequestRejectedMail($team) {
		$patientUser = $this->User->findById($team['patient_id']);
		$patient = $patientUser['User'];
		$organizerUser = $this->User->findById($this->__data['organizer_id']);
		$organizer = $organizerUser['User'];
		$data = array(
			'username' => $organizer['username'],
			'name' => $patient['username'],
			'team_name' => $team['name'],
			'link' => Router::Url('/', TRUE) . "myteam/{$team['id']}",
			'old_privacy' => $this->__data['old_privacy'],
			'new_privacy' => $this->__data['new_privacy']
		);
		$toEmail = $organizer['email'];
		$templateId = EmailTemplateComponent::TEAM_PRIVACY_CHANGE_REQUEST_REJECTED_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $data, $toEmail);
	}

	/**
	 * Function to add site notification to the organizer when patient rejects 
	 * the request to change team privacy
	 * 
	 * @param array $team
	 * @return bool
	 */
	private function __addTeamPrivacyChangeRequestRejectedSiteNotification($team) {
		$additionalInfo = array(
			'old_privacy' => $this->__data['old_privacy'],
			'new_privacy' => $this->__data['new_privacy']
		);
		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $team['patient_id'],
			'recipient_id' => $this->__data['organizer_id'],
			'additional_info' => $additionalInfo
		);
		return $this->Notification->addTeamNotification($team, $params);
	}

	/**
	 * Function to add team join request email and site notifications
	 * 
	 * @return bool
	 */
	private function __addTeamJoinRequestNotifications() {
		return $this->__sendNotificationToAllOrganizers();
	}

	/**
	 * Function to send email notification to organizer when a user request to 
	 * join the team
	 * 
	 * @param array $sender
	 * @param array $recipient
	 * @return bool
	 */
	private function __addTeamJoinRequestEmailNotification($sender, $recipient) {
		$team = $this->__team;
		$link = Router::Url('/', TRUE) . "myteam/{$team['id']}";
		$emailData = array(
			'username' => $recipient['username'],
			'name' => $sender['username'],
			'link' => $link,
			'team_name' => $team['name']
		);
		$toEmail = $recipient['email'];
		$templateId = EmailTemplateComponent::TEAM_JOIN_REQUEST_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $emailData, $toEmail);
	}

	/**
	 * Function to send notification to all organizers in a team about an activity
	 * 
	 * @return bool
	 */
	private function __sendNotificationToAllOrganizers() {
		$teamId = $this->__data['team_id'];
		$teamMemberBelongsTo = array('Team', 'InvitedBy', 'RoleInvitedBy');
		$this->TeamMember->unbindModel(array('belongsTo' => $teamMemberBelongsTo));
		$organizers = $this->TeamMember->getTeamOrganizers($teamId);
		return $this->__sendNotificationToMembers($organizers);
	}

	/**
	 * Function to filter recipients list 
	 * 
	 * Filter out duplicate recipients
	 * Removes senderid if it exists in the recipients list 
	 * 
	 * @param array $recipients
	 * @param int $senderId
	 * @return array
	 */
	private function __filterRecipientsList($recipients, $senderId) {
		if (!empty($recipients)) {
			$recipients = array_unique($recipients);
			if (in_array($senderId, $recipients)) {
				unset($recipients[$senderId]);
				$recipients = array_values($recipients);
			}
		}

		return array_filter($recipients);
	}

	/**
	 * Function to send HTML mail
	 * 
	 * @param int $templateId
	 * @param array $data
	 * @param string $toEmail
	 * @return bool
	 */
	private function __sendHTMLMail($templateId, $data, $toEmail) {
		$Api = new ApiController;
		$Api->constructClasses();
		$settings = array('module_info' => 'MyTeam');
		return $Api->sendHTMLMail($templateId, $data, $toEmail, $settings);
	}
	
	/**
	 * Function to add team request cancel site notifications
	 * 
	 * @return bool
	 */
	private function __addTeamRequestCancelNotifications() {
		$teamData = $this->__data['teamData'];
		$team = $teamData['Team'];
		$params = array(
			'activity_type' => $this->__data['activity_type'],
			'sender_id' => $this->__data['created_by'],
			'recipient_id' => $this->__data['patient_id']
		);
		$siteSuccess = $this->Notification->addTeamNotification($team, $params);
		return $siteSuccess;
	}
}
<?php

/**
 * The QueuePatientHealthChangeNotificationTask handles team notifications queue.
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
class QueuePatientHealthChangeNotificationTask extends AppShell {

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
	 * QueuePatientHealthChangeNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return bool Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			$this->__data = $data;
			$patientId = (int) $data['patient_id'];
			$this->__patientId = $patientId;
			$recipients = $this->__getNotificationRecipients($patientId);
			if (!empty($recipients)) {
				$siteNotificationsData = array();
				foreach ($recipients as $recipientId => $recipientData) {
					if (!empty($recipientData['email_notification_teams'])) {
						$recipient = $recipientData['user'];
						$teams = $recipientData['email_notification_teams'];
						$this->__addHealthStatusChangeEmailNotification($recipient, $teams);
					}
					if (!empty($recipientData['site_notification_teams'])) {
						$additionalInfo = array(
							'health_status' => $data['health_status'],
							'new_health_status' => $data['new_health_status']
						);
						$teams = $recipientData['site_notification_teams'];
						if (count($teams) > 1) {
							$teamNames = array();
							foreach ($teams as $team) {
								$teamNames[] = $team['name'];
							}
							$additionalInfo['team_names'] = $teamNames;
						} else {
							$additionalInfo['team_name'] = $teams[0]['name'];
						}
						$siteNotificationsData[] = array(
							'activity_type' => Notification::ACTIVITY_HEALTH_STATUS_CHANGE,
							'sender_id' => $patientId,
							'recipient_id' => $recipientId,
							'activity_in' => $patientId,
							'activity_in_type' => Notification::ACTIVITY_IN_PROFILE,
							'object_owner_id' => $patientId,
							'object_id' => $patientId,
							'object_type' => Notification::OBJECT_TYPE_PROFILE,
							'additional_info' => json_encode($additionalInfo)
						);
					}
				}
				if (!empty($siteNotificationsData)) {
					$this->Notification->saveMany($siteNotificationsData);
				}
			}
			return true;
		}
	}

	/**
	 * Function to get the recipients who need notification when patient health
	 * is changed
	 * 
	 * @param int $patientId
	 * @return array
	 */
	private function __getNotificationRecipients($patientId) {
		$recipients = array();
		$teamIds = $this->Team->getPatientApprovedTeamIds($patientId);
		if (!empty($teamIds)) {
			foreach ($teamIds as $teamId) {
				$teamMembers = $this->TeamMember->getApprovedTeamMembers($teamId);
				if (!empty($teamMembers)) {
					foreach ($teamMembers as $teamMemberData) {
						$team = $teamMemberData['Team'];
						$teamMember = $teamMemberData['TeamMember'];
						$memberId = (int) $teamMember['user_id'];
						if ($memberId !== $patientId) {
							if ($teamMember['email_notification'] === true) {
								$recipients[$memberId]['user'] = $teamMemberData['User'];
								$recipients[$memberId]['email_notification_teams'][] = $team;
							}
							if ($teamMember['site_notification'] === true) {
								$recipients[$memberId]['site_notification_teams'][] = $team;
							}
						}
					}
				}
			}
		}
		return $recipients;
	}

	/**
	 * Function to send email notification about the change in health status of
	 * patient in a user's team(s)
	 * 
	 * @param array $recipient
	 * @param array $teams
	 * @return bool
	 */
	private function __addHealthStatusChangeEmailNotification($recipient, $teams) {
		if (empty($this->__sender)) {
			$this->User->recursive = -1;
			$senderUser = $this->User->findById($this->__patientId);
			$this->__sender = $senderUser['User'];
		}
		$link = Router::Url('/', TRUE) . 'profile/' . $this->__sender['username'];
		App::uses('HealthStatus', 'Utility');
		$healthStatus = $this->__data['health_status'];
		$newHealthStatus = $this->__data['new_health_status'];
		$healthStatusText = '';
		if (!empty($healthStatus)) {
			$statusText = HealthStatus::getHealthStatusText($healthStatus);
			$healthStatusText = __('from "%s" ', $statusText);
		}
		$newStatusText = HealthStatus::getHealthStatusText($newHealthStatus);
		$healthStatusText.=__('to "%s"', $newStatusText);
		$teamNamesStr = $this->__getTeamNamesCombinedText($teams);
		$teamText = (count($teams) > 1) ? 'teams' : 'team';
		$emailData = array(
			'username' => $recipient['username'],
			'name' => $this->__sender['username'],
			'link' => $link,
			'health_status_text' => $healthStatusText,
			'team_text' => __($teamText),
			'team_name' => $teamNamesStr
		);
		$toEmail = $recipient['email'];
		$templateId = EmailTemplateComponent::HEALTH_STATUS_CHANGED_EMAIL_TEMPLATE;
		return $this->__sendHTMLMail($templateId, $emailData, $toEmail);
	}

	/**
	 * Function to get the combined list of team names
	 * 
	 * @param array $teams
	 * @return string
	 */
	private function __getTeamNamesCombinedText($teams) {
		$teamNamesList = array();
		foreach ($teams as $team) {
			$teamNamesList[] = $team['name'];
		}
		$teamNamesCount = count($teamNamesList);
		$lastIndex = $teamNamesCount - 1;
		$teamNamesStr = '';
		foreach ($teamNamesList as $index => $teamName) {
			if ($index > 0) {
				$teamNamesStr .=($teamNamesCount > 2) ? ', ' : ' ';
				if ($index === $lastIndex) {
					$teamNamesStr .= 'and ';
				}
			}
			$teamNamesStr.='"' . $teamName . '"';
		}
		return $teamNamesStr;
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
}
<?php

/**
 * The QueueCommunityDeleteNotificationTask handles community delete notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');
App::import('Controller', 'Api');

/**
 * Task class for adding community delete notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueueCommunityDeleteNotificationTask extends AppShell {

	public $uses = array('Notification', 'User', 'NotificationSetting');
	public $components = array('EmailTemplate');

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueueCommunityDeleteNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			$recipients = $data['recipients'];
			$communityName = $data['community_name'];

			// add site notifications
			$this->Notification->addCommunityDeleteNotifications($data);

			// send email notifications
			$Api = new ApiController;
			$Api->constructClasses();
			foreach ($recipients as $recipientId) {
				// check email preference for the user, before sending
				$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($recipientId, 'community_removed');
				if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
					$memberDetails = $this->User->getUserDetails($recipientId);
					$toEmail = $memberDetails['email'];
					$emailData = array(
						'communityname' => $communityName,
						'username' => Common::getUsername($memberDetails['user_name'], $memberDetails['first_name'], $memberDetails['last_name'])
					);
					$Api->sendHTMLMail(EmailTemplateComponent::DELETE_COMMUNITY_TEMPLATE, $emailData, $toEmail);
				}
			}

			return true;
		}
	}
}
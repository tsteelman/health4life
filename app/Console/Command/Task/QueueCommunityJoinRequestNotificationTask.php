<?php

/**
 * The QueueCommunityJoinRequestNotificationTask handles community join request 
 * notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');
App::import('Controller', 'Api');

/**
 * Task class for adding community join request notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueueCommunityJoinRequestNotificationTask extends AppShell {

	public $uses = array('Notification', 'User', 'NotificationSetting');
	public $components = array('EmailTemplate');

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueueCommunityJoinRequestNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			// add site notification
			$siteNotificationSuccess = $this->Notification->addCommunityJoinRequestNotification($data);

			// send emai notification if user is not online
			$recipientId = $data['recipient_id'];
			$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($recipientId, 'group_request');
			if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
				$receiverUser = $this->User->findById($recipientId);
				$Api = new ApiController;
				$Api->constructClasses();
				$senderUser = $this->User->getUserDetails($data['sender_id']);
				$senderUserName = $senderUser['user_name'];

				$receiverUserName = $receiverUser['User']['username'];
				$communityId = $data['community_id'];
				$link = Router::Url('/', TRUE) . "community/details/index/{$communityId}/members";
				$emailData = array(
					'username' => $receiverUserName,
					'name' => $senderUserName,
					'link' => $link,
					'communityname' => $data['community_name']
				);
				$toEmail = $receiverUser['User']['email'];
				$emailNotificationSuccess = $Api->sendHTMLMail(EmailTemplateComponent::COMMUNITY_JOIN_REQUEST_NOTIFICATION, $emailData, $toEmail);
				$success = ($siteNotificationSuccess && $emailNotificationSuccess);
			} else {
				$success = $siteNotificationSuccess;
			}
			return $success;
		}
	}
}
<?php

/**
 * The QueueSiteWideCommunityNotificationTask handles site wide community notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');
App::import('Controller', 'Api');

/**
 * Task class for adding site wide community notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueueSiteWideCommunityNotificationTask extends AppShell {

	public $uses = array('Notification', 'User', 'NotificationSetting');
	public $components = array('EmailTemplate');

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueueSiteWideCommunityNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			$communityOwnerId = $data['created_by'];

			$query = array(
				'conditions' => array(
					'status' => User::STATUS_ACTIVE,
					'id !=' => $communityOwnerId
				),
				'fields' => array('id', 'username', 'email')
			);
			$recipients = $this->User->find('all', $query);

			if (!empty($recipients)) {
				$communityId = $data['id'];
				$communityName = $data['name'];

				$Api = new ApiController;
				$Api->constructClasses();

				foreach ($recipients as $recipient) {
					$receiverUser = $recipient['User'];
					$siteNotificationRecipients[] = $recipientId = $receiverUser['id'];
					$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($recipientId, 'site_wide_community');
					if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
						// send email notification if user is not online
						$receiverUserName = $receiverUser['username'];
						$toEmail = $receiverUser['email'];
						$link = Router::Url('/', TRUE) . "community/details/index/{$communityId}";
						$emailData = array(
							'username' => $receiverUserName,
							'link' => $link,
							'communityname' => $communityName
						);
						$Api->sendHTMLMail(EmailTemplateComponent::SITE_WIDE_COMMUNITY_NOTIFICATION_EMAIL_TEMPLATE, $emailData, $toEmail);
					}
				}

				// add site notification
				$params = array(
					'community_id' => $communityId,
					'community_name' => $communityName,
					'sender_id' => $communityOwnerId,
					'recipients' => $siteNotificationRecipients
				);
				$this->Notification->addSiteWideCommunityNotification($params);
			}

			return true;
		}
	}
}
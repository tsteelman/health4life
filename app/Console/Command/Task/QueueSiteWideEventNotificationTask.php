<?php

/**
 * The QueueSiteWideEventNotificationTask handles site wide event notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');
App::import('Controller', 'Api');

/**
 * Task class for adding site wide event notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueueSiteWideEventNotificationTask extends AppShell {

	public $uses = array('Notification', 'User', 'NotificationSetting', 'EventMember');
	public $components = array('EmailTemplate');

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueueSiteWideEventNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			$eventId = $data['id'];
			$eventOwnerId = $data['created_by'];

			$eventMembers = $this->EventMember->getEventMembers($eventId);
			foreach ($eventMembers as $eventMember) {
				$eventMemberIds[] = $eventMember['EventMember']['user_id'];
			}

			$query = array(
				'conditions' => array(
					'status' => User::STATUS_ACTIVE,
					'id NOT' => $eventMemberIds
				),
				'fields' => array('id', 'username', 'email')
			);
			$recipients = $this->User->find('all', $query);

			if (!empty($recipients)) {
				$eventName = $data['name'];

				$Api = new ApiController;
				$Api->constructClasses();

				foreach ($recipients as $recipient) {
					$receiverUser = $recipient['User'];
					$siteNotificationRecipients[] = $recipientId = $receiverUser['id'];
					$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($recipientId, 'site_wide_event');
					if ($isEmailNotificationOn && (!$this->User->isUserOnline($recipientId))) {
						// send email notification if user is not online
						$receiverUserName = $receiverUser['username'];
						$toEmail = $receiverUser['email'];
						$link = Router::Url('/', TRUE) . "event/details/index/{$eventId}";
						$emailData = array(
							'username' => $receiverUserName,
							'link' => $link,
							'eventname' => $eventName
						);
						$Api->sendHTMLMail(EmailTemplateComponent::SITE_WIDE_EVENT_NOTIFICATION_EMAIL_TEMPLATE, $emailData, $toEmail);
					}
				}

				// add site notification
				$params = array(
					'event_id' => $eventId,
					'event_name' => $eventName,
					'sender_id' => $eventOwnerId,
					'recipients' => $siteNotificationRecipients
				);
				$this->Notification->addSiteWideEventNotification($params);
			}

			return true;
		}
	}
}
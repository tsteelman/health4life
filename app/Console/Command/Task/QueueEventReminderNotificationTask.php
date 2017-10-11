<?php

/**
 * The QueueEventReminderNotificationTask handles event reminder notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');

/**
 * Task class for adding event reminder notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueueEventReminderNotificationTask extends AppShell {

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueueEventReminderNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			$this->EventMember = ClassRegistry::init('EventMember');
			$this->Notification = ClassRegistry::init('Notification');
			$userId = $data['user_id'];
			$timezone = $data['timezone'];
			$events = $this->EventMember->getUserAttendingEventsHappeningToday($userId, $timezone);
			$this->Notification->addUserEventReminderNotifications($userId, $events);
			return true;
		}
	}
}
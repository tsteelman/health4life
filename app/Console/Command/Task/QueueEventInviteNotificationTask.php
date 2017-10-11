<?php

/**
 * The QueueEventInviteNotificationTask handles event invite notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');

/**
 * Task class for adding event invite notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueueEventInviteNotificationTask extends AppShell {

	public $uses = array('Notification');

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueueEventInviteNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			return $this->Notification->addEventInviteNotifications($data);
		}
	}
}
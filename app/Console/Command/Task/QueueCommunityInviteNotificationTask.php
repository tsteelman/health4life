<?php

/**
 * The QueueCommunityInviteNotificationTask handles community invite 
 * notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');

/**
 * Task class for adding community invite notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueueCommunityInviteNotificationTask extends AppShell {

	public $uses = array('Notification');

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueueCommunityInviteNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			return $this->Notification->addCommunityInviteNotifications($data);
		}
	}
}
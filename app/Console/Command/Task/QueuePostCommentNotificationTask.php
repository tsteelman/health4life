<?php

/**
 * The QueuePostCommentNotificationTask handles post comment notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');

/**
 * Task class for adding post comment notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueuePostCommentNotificationTask extends AppShell {

	public $uses = array('Notification');

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueuePostCommentNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			return $this->Notification->addPostCommentNotifications($data);
		}
	}
}
<?php

/**
 * The QueueQuestionAnswerNotificationTask handles question answer notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');

/**
 * Task class for adding question notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueueQuestionAnswerNotificationTask extends AppShell {

	public $uses = array('Notification');

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueueQuestionAnswerNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			return $this->Notification->addQuestionAnswerNotifications($data);
		}
	}
}
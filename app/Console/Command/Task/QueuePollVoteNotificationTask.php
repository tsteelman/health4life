<?php

/**
 * The QueuePollVoteNotificationTask handles poll vote notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');

/**
 * Task class for adding poll vote notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueuePollVoteNotificationTask extends AppShell {

	public $uses = array('Notification');

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueuePollVoteNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			$pollData = $data['pollData'];
			$postData = $data['postData'];
			$pollVoteData = $data['pollVoteData'];
			return $this->Notification->addPollVoteNotifications($pollData, $postData, $pollVoteData);
		}
	}
}
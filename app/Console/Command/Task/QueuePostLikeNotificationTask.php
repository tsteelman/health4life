<?php

/**
 * The QueuePostLikeNotificationTask handles post like notification queue.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('AppShell', 'Console/Command');

/**
 * Task class for adding post like notifications.
 * 
 * @author   Greeshma Radhakrishnan
 * @package  App.Console.Command.Task
 * @category Task 
 */
class QueuePostLikeNotificationTask extends AppShell {

	public $uses = array('Notification');

	/**
	 * @var boolean
	 */
	public $autoUnserialize = true;

	/**
	 * QueuePostLikeNotificationTask::run()
	 *
	 * @param mixed $data
	 * @return boolean Success
	 */
	public function run($data) {
		if (empty($data)) {
			return false;
		} else {
			$post = $data['post'];
			$likedUsersArray = $data['likedUsersArray'];
			$likedUserId = $data['likedUserId'];
			$this->Notification->savePostLikeNotification($post, $likedUsersArray, $likedUserId);
			return true;
		}
	}
}
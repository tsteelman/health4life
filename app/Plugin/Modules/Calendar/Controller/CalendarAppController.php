<?php

/**
 * MessageAppController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('FrontAppController', 'Controller');

/**
 * MessageAppController for frontend messages.
 * 
 * MessageAppController is the parent class file for Messages.
 *
 * @author      Ajay Arjunan
 * @package     Message
 * @category    Controllers 
 */
class CalendarAppController extends FrontAppController
{
	protected $_currentUserId;


	/**
	 * Override parent function to get the current dasboard item
	 *
	 * @param null
	 * @return String
	 */
	protected function getCurrentDashbaordItem()
	{
		return "calendar";
	}
}
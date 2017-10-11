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
class MessageAppController extends FrontAppController
{
	public $invalidMessage = "This message does not exist !";
	protected $_currentUserId;

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->_currentUserId = $this->Auth->user('id');
	}

	public function beforeRender()
	{
		parent::beforeRender();
		if($this->request->is('ajax'))
		{
			$this->layout = 'ajax';
		}
		$timezone = $this->Auth->user('timezone');
		$this->set('timezone', $timezone);
	}

	/**
	 * Override parent function to get the current dasboard item
	 *
	 * @param null
	 * @return String
	 */
	protected function getCurrentDashbaordItem()
	{
		return "message";
	}
}
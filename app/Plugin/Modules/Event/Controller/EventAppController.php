<?php

/**
 * EventAppController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('FrontAppController', 'Controller');

/**
 * EventAppController for the frontend event
 * 
 * EventAppController is used as the parent for all the Event controllers
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Event
 * @category	Controllers 
 */
class EventAppController extends FrontAppController {
	
	public $invalidMessage = "This event does not exist !";
	
	/**
	* Override parent function to set the current dasboard item
	*
	* @param null
	* @return String
	*/
	protected function getCurrentDashbaordItem() {
	
		return "event";
	}    
}
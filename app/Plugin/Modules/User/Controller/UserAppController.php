<?php

/**
 * UserAppController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('FrontAppController', 'Controller');

/**
 * UserAppController for the frontend user
 * 
 * UserAppController is used as the parent for all the User controllers
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class UserAppController extends FrontAppController {
    
    	/**
	 * Override parent function to get the current dasboard item
	 *
	 * @param null
	 * @return String
	 */
	protected function getCurrentDashbaordItem()
	{
		return "profile";
	}
}
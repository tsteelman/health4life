<?php

/**
 * CommunityAppController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('FrontAppController', 'Controller');

/**
 * CommunityAppController for frontend communities.
 * 
 * CommunityAppController is the parent class file for Communities.
 *
 * @author 		Ajay Arjunan
 * @package 	Community
 * @category	Controllers 
 */
class CommunityAppController extends FrontAppController {
	
	public $invalidMessage = "This community does not exist !";
    
    /**
    * Override parent function to get the current dasboard item
    *
    * @param null
    * @return String
    */    
	protected function getCurrentDashbaordItem() {
		
		return "community";
	}
}
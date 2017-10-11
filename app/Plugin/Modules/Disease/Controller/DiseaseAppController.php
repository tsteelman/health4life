<?php

/**
 * CommunityAppController class file.
 * 
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('FrontAppController', 'Controller');

/**
 * DiseaseAppController for frontend communities.
 * 
 * DiseaseAppController is the parent class file for Communities.
 * 
 * @package 	Community
 * @category	Controllers 
 */
class DiseaseAppController extends FrontAppController {
	
	public $invalidMessage = "This disease does not exist !";
    
    /**
    * Override parent function to get the current dasboard item
    *
    * @param null
    * @return String
    */    
	protected function getCurrentDashbaordItem() {
		
		return "disease";
	}
}

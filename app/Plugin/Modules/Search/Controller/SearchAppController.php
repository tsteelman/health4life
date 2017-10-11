<?php

/**
 * SearchAppController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('FrontAppController', 'Controller');

/**
 * SearchAppController for header search and search page
 *  * 
 *
 * @author 		Ajay Arjunan
 * @package 	Search
 * @category	Controllers 
 */
class SearchAppController extends FrontAppController {
		
    
    /**
    * Override parent function to get the current dasboard item
    *
    * @param null
    * @return String
    */    
	protected function getCurrentDashbaordItem() {
		
		return "home";
	}
}
<?php

/**
 * PostAppController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('FrontAppController', 'Controller');

/**
 * PostAppController for the frontend posting
 * 
 * PostAppController is used as the parent for all the Post controllers
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Post
 * @category	Controllers 
 */
class PostAppController extends FrontAppController {

	/**
	 * Message shown when an invalid post is accessed
	 * 
	 * @var string
	 */
	public $invalidMessage = 'This post does not exist!';

}
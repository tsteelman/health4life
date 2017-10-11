<?php

/**
 * MylibraryController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');

/**
 * MylibraryController for the user profile
 *
 * MylibraryController is used to show the "My Library" in the profile page
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers
 */
class TherapiesController extends ProfileController{

    /**
     * Profile -> My Library
     */
    public function index($username = null)
    {
        $this->_setUserProfileData();
        $this->set('title_for_layout',$this->Auth->user('username')."'s therapy");
    }

}

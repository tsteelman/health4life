<?php

/**
 * VideoController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');
App::import('Controller', 'Api');
App::uses('UserPrivacySettings', 'Lib');


/**
 * VideoController for the frontend
 *
 * VideoController is used for to show the videos added by a user
 *
 * @author      Ajay Arjunan
 * @package 	User
 * @category	Controllers
 */
class VideoController extends ProfileController {

    /**$userId
     * Models needed in the Controller
     *
     * @var array
     */
    protected $_mergeParent = 'ProfileController';
    
    public $uses = array(
        'Media',
        'User',
        'MyFriends'
    );


    /**
     * Profile -> Friends
     */
    public function index($username = null) {
        /*
         * Set the Profile data for the logged in user
         */
        $this->_setUserProfileData();
        
        
        $this->__setVideoPageData();
        
    }
    
    private function __setVideoPageData() {
        $profileUserId = $this->_requestedUser['id'];
        $currentUserId = (int) $this->Auth->user('id');
        $isOwnProfile = ((int) $profileUserId === $currentUserId) ? true : false;
        
        $this->set('isOwnProfile', $isOwnProfile);
        
       
        $this->__loadAllVideos($profileUserId);        
   
    }

        /**
     * Loads the photos of a user and sets them on view
     *
     * @param int $userId
     */
    private function __loadAllVideos($profileUserId) {
        
        $videos = $this->Media->getUserVideos($profileUserId, '');
        
        $this->set('allVideos', $videos);
    }
}
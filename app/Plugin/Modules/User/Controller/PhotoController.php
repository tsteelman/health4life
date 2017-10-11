<?php

/**
 * PhotoController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');
App::import('Controller', 'Api');
App::uses('UserPrivacySettings', 'Lib');


/**
 * PhotoController for the frontend
 *
 * PhotoController is used for to show the photos of a user
 *
 * @author      Ajay Arjunan
 * @package 	User
 * @category	Controllers
 */
class PhotoController extends ProfileController {

    /**$userId
     * Models needed in the Controller
     *
     * @var array
     */
    protected $_mergeParent = 'ProfileController';
    
    public $uses = array(
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
        
        
        $this->__setPhotoPageData();
        
    }
    
    private function __setPhotoPageData() {
        $profileUserId = $this->_requestedUser['id'];
        $currentUserId = (int) $this->Auth->user('id');
        $isOwnProfile = ((int) $profileUserId === $currentUserId) ? true : false;
        
        $this->set('isOwnProfile', $isOwnProfile);
        
       
        $this->__loadAllPhotos($profileUserId);        
   
    }

        /**
     * Loads the photos of a user and sets them on view
     *
     * @param int $userId
     */
    private function __loadAllPhotos($profileUserId) {
        
        $photos = $this->Photo->getRecentPhotos($profileUserId, '');
        
        $this->set('allPhotos', $photos);
    }
}
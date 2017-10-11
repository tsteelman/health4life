<?php

/**
 * CommunitiesController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');

/**
 * CommunitiesController for the user profile
 * 
 * CommunitiesController is used to show the communties in the profile page
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class CommunitiesController extends ProfileController {
    
    protected $_mergeParent = 'ProfileController';

    var $uses = array('Community', 'CommunityMember');
	/**
	 * Profile -> Communities
	 */
	public function index($username = null) {
		$this->_setUserProfileData();
                if(isset($this->_requestedUser['id'])) {
                    $this->set('title_for_layout',$this->_requestedUser['username']."'s communities");
                } else {
                    $this->set('title_for_layout',$this->Auth->user('username')."'s communities");
                }
                
                if ($this->_requestedUser['id'] != $this->Auth->user('id'))
		{
			$privacy = new UserPrivacySettings($this->_requestedUser['id']);
			$isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'],
				$this->Auth->user('id'));
			$viewSetting = array($privacy::PRIVACY_PUBLIC);
			if ($isFriend == MyFriends::STATUS_CONFIRMED)
			{
				array_push($viewSetting, $privacy::PRIVACY_FRIENDS);
			}
			if (!in_array($privacy->__get('view_your_communities'), $viewSetting))
			{
				$this->redirect( Common::getUserProfileLink(  $this->_requestedUser['username'], true) );
			}
		}
                
                $communityIds = $this->CommunityMember->getCommunityList($this->_requestedUser['id'], CommunityMember::STATUS_APPROVED);
                
                $communities = array();
                foreach ($communityIds as $communityId) {
                    $communities[]['Community'] = $this->Community->getCommunity($communityId);
                }
                
                $community_type = 6;
                
                $this->set(compact('communities', 'community_type'));
	}
        
}
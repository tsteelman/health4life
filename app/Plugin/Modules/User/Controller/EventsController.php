<?php

/**
 * EventsController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('ProfileController', 'User.Controller');

/**
 * EventsController for the user profile
 * 
 * EventsController is used to show the events in the profile page
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class EventsController extends ProfileController {

    protected $_mergeParent = 'ProfileController';
    var $uses = array('Event', 'EventMember', 'CommunityMember');

    /**
     * Profile -> Events
     */
    public function index($eventType = NULL) {
        $this->_setUserProfileData();
        if(isset($this->_requestedUser['id'])) {
            $this->set('title_for_layout',$this->_requestedUser['username']."'s events");
        } else {
            $this->set('title_for_layout',$this->Auth->user('username')."'s events");
        }
        
        if ($this->_requestedUser['id'] != $this->Auth->user('id')) {
            $privacy = new UserPrivacySettings($this->_requestedUser['id']);
            $isFriend = $this->MyFriends->getFriendStatus($this->_requestedUser['id'], $this->Auth->user('id'));
            $viewSetting = array($privacy::PRIVACY_PUBLIC);
            if ($isFriend == MyFriends::STATUS_CONFIRMED) {
                array_push($viewSetting, $privacy::PRIVACY_FRIENDS);
            }
            if (!in_array($privacy->__get('view_your_events'), $viewSetting)) {	
                $this->redirect(Common::getUserProfileLink( $this->_requestedUser['username'], true));
            }
        }
                
        $now = date("Y-m-d H:i:s");
        
        $eventIds = $this->EventMember->getEvents($this->_requestedUser['id'], 
                EventMember::STATUS_ATTENDING);
        
        $events = $this->Event->find('all', array(
            'conditions' => array(
                'Event.id' => $eventIds
            ),
            'order' => array('Event.start_date' => 'asc'),
            'fields' => array('Event.*')
        ));
        
        $user_communities = $this->CommunityMember->getCommunityList($this->_requestedUser['id'], CommunityMember::STATUS_APPROVED);
        
        $user = $this->_requestedUser;
        
        $event_type = Event::UPCOMING_USER_EVENTS;
        
        $this->set(compact('events', 'now', 'user', 'user_communities', 'event_type'));
        
    }

}
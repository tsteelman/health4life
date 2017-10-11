<?php

/**
 * Email Settings Controller class file.
 *
 * @author    Amith Hariharan <amit@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('AuthComponent', 'Controller/Component');
App::uses('NotificationSetting', 'Model');

class EmailSettingsController extends UserAppController {

    /**
     * Models used by this controller
     *
     * @var array
     */
    public $uses = array('User', 'NotificationSetting');
	
	public $components = array('EmailTemplate');
	
	public $unsubscribeTemplateIds = array(4,5,6,7,9,10,13,14,18,21,22,24,25,26,28,31,32,72,75);
    /**
     * Edit User email notification Settings
     */
    public function index() {
		$this->set('title_for_layout',"Email Settings");
        $inputDefaults = array(
            'label' => false,
            'div' => false,
            'class' => 'form-control'
        );
        $model = 'NotificationSetting';

        $userId = $this->Auth->user('id');
        $userData = $this->User->findById($userId);
        $type = $userData['User']['type'];
        $userImage = Common::getUserThumb($userId, $type, 'medium', 'img-responsive pull-left img-thumbnail', 'url');
        $profilePhotoClass = Common::getUserThumbClass($type);

        // get notification settings
        if (!$this->request->isPost()) {
            $userId = $this->Auth->user('id');
            $setting = $this->NotificationSetting->getEmailSettingsByUserId($userId); //debug($setting);
        }
        else {	//debug($this->request->data);
       
            if ($this->NotificationSetting->saveEmailSettings($userId, $this->request->data)) {
				$this->Session->setFlash(__('Notification settings updated successfully.'), 'success');
				$this->redirect($this->request->here);
			}
            else {
                $this->Session->setFlash(__('Notification settings updation failed.'), 'error');
            }
        }

		// set view variables
		$frequencyOptions = NotificationSetting::getFrequencyOptions();
		$this->request->data['NotificationSetting']['recommend_friends_frequency'] = $setting['recommend_friends_frequency'];
		$this->set(compact('model', 'inputDefaults', 'userId', 'userImage', 'profilePhotoClass', 'setting', 'frequencyOptions'));
	}
	
	/**
     * Function to unsubscribe notifiaction e-mails
     */
	public function unSubscribe() {
		$setting = $_GET['setting'];
		if (isset($setting) && in_array($setting, $this->unsubscribeTemplateIds)) {
			if (!$this->Auth->loggedIn() && isset($_GET['auto_login_token']) && isset($_GET['email'])) {
				$this->__autoLoginUser();
			}
			$userId = $this->Auth->user('id');
			$this->NotificationSetting->unsubscribeEmailSettings($userId, $setting);
			switch ($setting) {
				case EmailTemplateComponent::EVENT_INVITES_TEMPLATE : $description = 'Event invitation' ;
																	  break;
				case EmailTemplateComponent::MAIL_TO_EVENT_CREATOR_TEMPLATE : $description = 'Event RSVP' ;
																			  break;
				case EmailTemplateComponent::DELETE_EVENT_TEMPLATE  : $description = 'Event cancellation' ;
																	  break;
				case EmailTemplateComponent::UPDATE_EVENT_TEMPLATE  : $description = 'Event update' ;
																	  break;
				case EmailTemplateComponent::INVITE_COMMUNITY_MEMBER_TEMPLATE : $description = 'Community invitation' ;
																				break;
			    case EmailTemplateComponent::DELETE_COMMUNITY_TEMPLATE : $description = 'Community Removal' ;
																	     break;
				case EmailTemplateComponent::ADD_FRIEND_TEMPLATE     : $description = 'Friend request' ;
																	   break;
			    case EmailTemplateComponent::APPROVE_FRIEND_INVITE_TEMPLATE : $description = 'Friend request approval' ;
																	          break;
				case EmailTemplateComponent::MESSAGE_NOTIFICATION_TEMPLATE : $description = 'Message' ;
																	          break;
				case EmailTemplateComponent::PENDING_REQUEST_REMINDER_TEMPLATE : $description = 'Friend request reminder' ;
																	             break;
				case EmailTemplateComponent::HEALTH_STATUS_UPDATE_REMINDER_TEMPLATE : $description = 'Health status update' ;
																	                  break;
				case EmailTemplateComponent::COMMUNITY_JOIN_REQUEST_NOTIFICATION : $description = 'Community requests' ;
																	               break;
				case EmailTemplateComponent::POST_NOTIFICATION         : $description = 'Post on my wall' ;
																	    break;
			    case EmailTemplateComponent::POST_COMMENT_NOTIFICATION : $description = 'Comments on my post' ;
																	    break;
			    case EmailTemplateComponent::EVENT_REMINDER_TEMPLATE   : $description = 'Event reminder' ;
																	    break;
			    case EmailTemplateComponent::SITE_WIDE_EVENT_NOTIFICATION_EMAIL_TEMPLATE : $description = 'Site wide event' ;
																	                       break;
				case EmailTemplateComponent::SITE_WIDE_COMMUNITY_NOTIFICATION_EMAIL_TEMPLATE : $description = 'Site wide community' ;
																	                           break;
				case EmailTemplateComponent::FRIEND_RECOMMENDATION_EMAIL_TEMPLATE : $description = 'Friend recommendation' ;
																	                break;
				case EmailTemplateComponent::QUESTION_ANSWER_NOTIFICATION : $description = 'Answering my questions' ;
																	         break;
			}
			$this->set(compact('description'));
		} else {
			$this->Session->setFlash(__('Invalid email template for unsubscribing'), 'warning');
			$this->redirect('/dashboard');
		}
		
	}

}
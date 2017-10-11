<?php

/**
 * MessagesController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MessageAppController', 'Message.Controller');

/**
 * MessagesController for frontend messages.
 * 
 * MessagesController is used for managing messages.
 *
 * @author 		Ajay Arjunan
 * @author		Greeshma Radhakrishnan
 * @package 	Message
 * @category	Controllers 
 */
class MessagesController extends MessageAppController
{
	public $uses = array('UserMessage', 'SavedMessage', 'EmailQueue', 'EmailTemplate', 'User', 'NotificationSetting');
	public $components = array(
		'EmailQueue',
		'Email',
		'EmailTemplate'
	);

	/**
	 * Message list index
	 */
	public function index()
	{
		$userId = $this->_currentUserId;
		$inboxMessagesCount = $this->UserMessage->getInboxMessagesCount($userId);
		$this->set(compact('inboxMessagesCount'));
	}

	/**
	 * Lists the messages in the inbox of the logged in user
	 */
	public function inbox()
	{
		$searchTerm = '';
		if(isset($this->request->data['search_term']))
		{
			$searchTerm = $this->request->data['search_term'];
		}
		$userId = $this->_currentUserId;
		$messages = $this->UserMessage->getInboxMessages($userId, $searchTerm);
		$this->view = 'list';
		$this->set(compact('messages'));
	}

	/**
	 * Lists the messages sent by the logged in user
	 */
	public function sent()
	{
		$searchTerm = '';
		if(isset($this->request->data['search_term']))
		{
			$searchTerm = $this->request->data['search_term'];
		}
		$userId = $this->_currentUserId;
		$messages = $this->UserMessage->getOutboxMessages($userId, $searchTerm);
		$this->view = 'list';
		$this->set(compact('messages'));
	}

	/**
	 * Lists the messages saved by the logged in user
	 */
	public function saved()
	{
		$searchTerm = '';
		if(isset($this->request->data['search_term']))
		{
			$searchTerm = $this->request->data['search_term'];
		}
		$userId = $this->_currentUserId;
		$messages = $this->SavedMessage->getUserSavedMessages($userId, $searchTerm);
		$this->view = 'list';
		$this->set(compact('messages'));
	}

	/**
	 * Deletes conversations with selected users and the logged in user
	 */
	public function deleteConversations()
	{
		$this->autoRender = false;
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			if(isset($data['message_users']))
			{
				$otherUsers = $data['message_users'];
				$userId = $this->_currentUserId;
				if(isset($data['type']) && $data['type'] === 'saved')
				{
					$this->SavedMessage->deleteMessages($userId, $otherUsers);
				}
				else
				{
					$this->UserMessage->deleteConversations($userId, $otherUsers);
				}
			}
		}
	}

	/**
	 * Saves conversations with selected users and the logged in user
	 */
	public function saveConversations()
	{
		$this->autoRender = false;
		if($this->request->is('ajax'))
		{
			$data = $this->request->data;
			if(isset($data['message_users']))
			{
				$users = $data['message_users'];
				$userId = $this->_currentUserId;
				$this->SavedMessage->saveConversations($users, $userId);
			}
		}
	}

        
    /**
     * creates a message
     */
    public function createUserMessage() {
        $this->autoRender = false;
        $this->layout = 'ajax';
        $loginUserId = $this->_currentUserId;

        if ($this->request->is('post')) {
            $sendUserIds = explode(',', rtrim($this->request->data["user_ids"], ','));
            $userMessage = trim($this->request->data["message"]);

            foreach ($sendUserIds as $sendUserId) {
                $this->UserMessage->create();
                $this->UserMessage->set(array(
                    'current_user_id' => $loginUserId,
                    'other_user_id' => $sendUserId,
                    'message' => $userMessage,
                    'direction' => UserMessage::DIRECTION_OUT
                ));

                if ($this->UserMessage->save()) {

                    //save record as inbox message for other user. to handle conversation delete.
                    $this->UserMessage->create();
                    $this->UserMessage->set(array(
                        'current_user_id' => $sendUserId,
                        'other_user_id' => $loginUserId,
                        'message' => $userMessage,
                        'direction' => UserMessage::DIRECTION_IN
                    ));

                    $this->UserMessage->save();
					
					$this->UserMessage->realTimeNotifyUser($sendUserId);
					
					$isEmailNotificationOn = $this->NotificationSetting->isEmailNotificationOn($sendUserId, 'message');
					if ($isEmailNotificationOn && (!$this->User->isUserOnline($sendUserId))) {
						$user = $this->User->find('first', array(
							'conditions' => array(
								'User.id' => $sendUserId
						)));

						$message_link = Router::Url('/', TRUE) . 'message/details/index/'.$loginUserId;
						$sender_profile_link = Router::Url('/', TRUE) . 'profile/' . urlencode ( $this->Auth->user('username') );

						$emailData = array(
							'username' => $user['User']['username'],
							'message_link' => $message_link,
							'sender_username' => $this->Auth->user('username'),
							'sender_message' => nl2br(h($userMessage)),
							'sender_profile_link' => $sender_profile_link
						);
						//Getting email template from database
						$emailManagement = $this->EmailTemplate->getEmailTemplate(EmailTemplateComponent::MESSAGE_NOTIFICATION_TEMPLATE, $emailData);

						// email data to be saved
						$mailData = array(
							'subject' => $emailManagement['EmailTemplate']['template_subject'],
							'to_name' => $emailData['username'],
							'to_email' => $user['User']['email'],
							'content' => json_encode($emailData),
							'module_info' => 'Compose Message',
							'email_template_id' => EmailTemplateComponent::MESSAGE_NOTIFICATION_TEMPLATE
						);

						$this->EmailQueue->createEmailQueue($mailData);
					}
					
                    $result = array(
                        'success' => true,
                        'message' => __("Your messages have been successfully sent")
                    );
                } else {
                    $result = array(
                        'error' => true,
                        'message' => __('Sending failure. Please try after some time.')
                    );
                }
            }
            return json_encode($result);
        }
    }
}
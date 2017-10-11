<?php

/**
 * Message DetailsController class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('MessageAppController', 'Message.Controller');

/**
 * DetailsController for the message
 * 
 * DetailsController is used for viewing message details.
 *
 * @author 		Ajay Arjunan
 * @author 		Greeshma Radhakrishnan
 * @package 	Message
 * @category	Controllers 
 */
class DetailsController extends MessageAppController {

	public $uses = array('UserMessage', 'SavedMessage');
	public $components = array(
		'Paginator'
	);

	const MAX_RESULT_COUNT = 10;

	public function index($otherUserId) {
        $loginUserId = $this->_currentUserId;

        $this->UserMessage->markMessagesAsRead($loginUserId, $otherUserId);

        if (isset($this->request->data['current_page'])) { 
            /**
             * For handling real time message system and real time reply
             * If a user is in page 3 (view more) and if he reply we need to repaginate to correct
             * cakephp pagination and take 3 pages and show along with new reply or new
             * messages.
             */
            $limit = $this->request->data['current_page'] * self::MAX_RESULT_COUNT;
        } else {
            $limit = self::MAX_RESULT_COUNT; //handling normal flow ie, pressing view more, or first load
        }
        
        $messages = $this->__paginateUserMessageDetails($loginUserId, $otherUserId, $limit);

        if (!empty($messages)) {
            foreach ($messages as &$messsage) {
                $direction = intval($messsage['UserMessage']['direction']);
                if ($direction === UserMessage::DIRECTION_OUT) {
                    $messsage['User'] = $messsage['CurrentUser'];
                } else {
                    $messsage['User'] = $messsage['OtherUser'];
                }
                unset($messsage['CurrentUser']);
                unset($messsage['OtherUser']);
            }
        }
        $messages = array_reverse($messages);
        $inboxMessagesCount = $this->UserMessage->getInboxMessagesCount($loginUserId);
		$this->UserMessage->realTimeNotifyUser($loginUserId, $inboxMessagesCount);
		
        $enableReply = true;
        (isset($this->request->data['reply_success'])) ? $showMessage = true : $showMessage = FALSE;
        $this->set(compact('messages', 'inboxMessagesCount', 'otherUserId', 'enableReply', 'showMessage'));

        if ($this->request->is('ajax')) { // for more pagination
            if (isset($this->request->params['named']['page']) || (isset($this->request->data['current_page']))) {
                $this->view = 'details_list_view'; //only messages view without reply box
            } else {
                $this->view = 'conversations'; //view with reply box
            }
        }
    }

	/**
     * Shows the saved conversations with a user
     * 
     * @param int $otherUserId
     */
    public function saved($otherUserId) {
        $savedUserId = $this->_currentUserId;
        $this->paginate = $this->SavedMessage->getConversationPaginationSettings($savedUserId, $otherUserId);
        $messages = $this->paginate('SavedMessage');
        if (!empty($messages)) {
            foreach ($messages as &$messsage) {
                $direction = intval($messsage['UserMessage']['direction']);
                if ($direction === UserMessage::DIRECTION_OUT) {
                    $messsage['User'] = $messsage['SavedUser'];
                } else {
                    $messsage['User'] = $messsage['OtherUser'];
                }
                unset($messsage['SavedUser']);
                unset($messsage['OtherUser']);
            }
        }
        $messages = array_reverse($messages);
        $this->set(compact('messages', 'otherUserId'));
        if (isset($this->request->params['named']['page'])) {
            $this->view = 'details_list_view';
        } else {
            $this->view = 'conversations';
        }
    }

    private function __paginateUserMessageDetails($loginUserId, $otherUserId, $resultCount) {

        $this->loadModel('UserMessage'); //load the model if it is not loaded.
        $this->Paginator->settings = array(           
            'conditions' => array(
                    array('UserMessage.current_user_id' => $loginUserId,
                        'UserMessage.other_user_id' => $otherUserId,
                        ),
                'UserMessage.is_deleted' => UserMessage::STATUS_NOT_DELETED,
            ),
            'fields' => array(
                'CurrentUser.id',
                'CurrentUser.type',
                'CurrentUser.username',
                'OtherUser.id',
                'OtherUser.type',
                'OtherUser.username',
                'UserMessage.message',
                'UserMessage.created',
                'UserMessage.direction',
                'UserMessage.is_read'
            ),
            'limit' => $resultCount,
            'order' => array('UserMessage.created' => 'desc'),
        );

        $messages = $this->paginate('UserMessage');

        return $messages;
    }

}
<?php

/**
 * EmailInviteComponent class file.
 * 
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('component', 'controller');
App::uses('Validation', 'Utility');
App::import('Controller', 'Api');
App::uses('EmailTemplateComponent', 'Controller/Component');
App::uses('MyFriends', 'Model');

class EmailInviteComponent extends Component {

    const COULD_NOT_MAILED = 0;
    const REQUEST_ALREADY_SENT = 1;
    const ALREADY_FRIENDS = 2;
    const MAILED_TO_REGISTERED_USER = 3;
    const MAILED_TO_NON_REGISTERED_USER = 4;

    public $components = array('Otp', 'EmailTemplate', 'EmailQueue');

    /**
     * Initialises the Models
     * 
     * @param Controller $controller
     */
    public function initialize(Controller $controller) {
        $this->User = ClassRegistry::init("User");
        $this->MyFriends = ClassRegistry::init("MyFriends");
        $this->InvitedUser = ClassRegistry::init("InvitedUser");
    }

    /**
     * Functin to invite a frined by his email address.
     * Send a frined request mail if he is registered user.
     * Send a joining invitation along with the friend request
     * if he is not registered.
     * @param String $email
     * @param int $userId
     * @param number Status
     */
    public function inviteFriendByEmail($email, $userId) {
        if (Validation::email($email, true)) {

            $friendId = $this->getUserIdFromEmailAddress($email);

            // if it is a registered user
            if ($friendId != 0) {

                $userStatus = $this->getUserStatus($userId, $friendId);

                // check the user is already friend
                if ($userStatus == MyFriends::STATUS_CONFIRMED) {
                    return EmailInviteComponent::ALREADY_FRIENDS;

                    // check is already requested
                } else if ($userStatus == MyFriends::STATUS_REQUEST_SENT) {
                    return EmailInviteComponent::REQUEST_ALREADY_SENT;

                    // check is request recieved
                } else if ($userStatus == MyFriends::STATUS_REQUEST_RECIEVED ||
                        $userStatus == MyFriends::STATUS_SAME_USER) {
                    return EmailInviteComponent::COULD_NOT_MAILED;
                } else {
                    $this->mailToRegisteredUser($userId, $friendId);
                    return EmailInviteComponent::MAILED_TO_REGISTERED_USER;
                }
                // if it is not a registered user
            } else {
                return inviteNonRegisteredUser($userId, $email);
            }
        } else {
            return EmailInviteComponent::COULD_NOT_MAILED;
        }
    }

    public function inviteNonRegisteredUser($userId, $email) {
        // check for already invited
        if ($this->isInvitedUser($userId, $email)) {
            return EmailInviteComponent::REQUEST_ALREADY_SENT;
        } else {
            $this->mailToNonRegisteredUser($userId, $email);
            return EmailInviteComponent::MAILED_TO_NON_REGISTERED_USER;
        }
    }

    /**
     * Function to invite new contacts to our application.
     * 
     * @param array $contactEmails
     * @param int $userId
     * @return array
     */
    public function inviteNewContacts($contactEmails, $userId) {
        $result = array();
        if (!empty($contactEmails)) {
            foreach ($contactEmails as $email) {
                $status = $this->inviteNonRegisteredUser($userId, $email);
                $result[$status][] = $email;
            }

            if (isset($result[self::REQUEST_ALREADY_SENT])) {
                $alreadyInvitedEmails = $result[self::REQUEST_ALREADY_SENT];
                $alreadyInvitedEmailsCount = count($alreadyInvitedEmails);
                $errorMsg = sprintf('You have already sent friend request to %d contact (s).', $alreadyInvitedEmailsCount);
                $result['messages']['error'] = $errorMsg;
            }
            if (isset($result[self::MAILED_TO_NON_REGISTERED_USER])) {
                $invitedEmails = $result[self::MAILED_TO_NON_REGISTERED_USER];
                $invitedEmailsCount = count($invitedEmails);
                $successMsg = sprintf('You have successfully sent friend request to %d contact (s).', $invitedEmailsCount);
                $result['messages']['success'] = $successMsg;
            }
        }
        
        return $result;
    }

    /**
     * Get user id from email address
     * return 0 if not an existing user
     */
    public function getUserIdFromEmailAddress($email = NULL) {
        $userId = 0;
        if ($email != NULL) {
            if (Validation::email($email, true)) {
                $user = $this->User->findByEmail($email);
                if (!empty($user ['User'])) {
                    $userId = $user['User']['id'];
                }
            }
        }
        return $userId;
    }

    /**
     * Function to get friend status
     *
     * @param int $userId        	
     * @param int $friendId        	
     * @return int
     */
    public function getUserStatus($userId, $friendId) {
        return $this->MyFriends->getFriendStatus($userId, $friendId);
    }

    /**
     * Function to check for already invited email
     *
     * @return boolean
     */
    public function isInvitedUser($userId, $emailAddress) {
        return $this->InvitedUser->isInvitedUser($userId, $emailAddress);
    }

    /**
     * Function to send invitation mail to registered user
     * 
     * @param int $userId        	
     * @param int $friendId        	
     */
    function mailToRegisteredUser($userId, $friendId) {
        $this->MyFriends->setFriendStatus($userId, $friendId, MyFriends::STATUS_REQUEST_SENT);
        $this->MyFriends->setFriendStatus($friendId, $userId, MyFriends::STATUS_REQUEST_RECIEVED);
        $this->sendMailToRegisteredUser($userId, $friendId, EmailTemplateComponent::ADD_FRIEND_TEMPLATE);
    }

    /**
     * Function to send invitation mail to non registered user
     * 
     * @param int $userId        	
     * @param string $email        	
     */
    function mailToNonRegisteredUser($userId, $email) {        
        $tokenId = $this->sendMailToNonRegisteredUser($userId, $email, EmailTemplateComponent::INVITE_NONMEMBER_FRIEND);
        $this->setInvitedUser($userId, $email, $tokenId);
    }

    /**
     * Function to add userid to invited userlist
     */
    private function setInvitedUser($userId, $emailAddress, $tokenId) {
        $invitedUsers = $this->InvitedUser->setInvitedUser($userId, $emailAddress, $tokenId);
    }

    /**
     * Function to send mail to registered user
     */
    public function sendMailToRegisteredUser($userId, $friendId, $template) {
        /*
         * finad all user and friend details from users table
         */
        $user = $this->User->findById($userId);
        $friend = $this->User->findById($friendId);

        $toEmail = $friend['User']['email'];
        $emailData = array(
            'username' => Common::getUsername($friend['User']['username'], $friend['User']['username'], $friend['User']['username']),
            'friend_username' => Common::getUsername($user['User']['username'], $user['User']['username'], $user['User']['username']),
            // link to view invitee's profile
            'link' => Router::Url('/', TRUE) . 'profile/' . urlencode ($user['User']['username'] ),
            'accept_link' => Router::Url('/', TRUE) . 'user/friends/approveFriend/' . $friendId,
            'reject_link' => Router::Url('/', TRUE) . 'user/friends/rejectFriend/' . $friendId
        );
		
        $this->sendHTMLMail($template, $emailData, $toEmail);
    }

    /**
     * Function to send mail to non registered user
     * @return int ActionToken Id
     */
    public function sendMailToNonRegisteredUser($userId, $friendEmailAddress, $template) {
        $user = $this->User->findById($userId);

        /*
         * create action tocken
         */
        $actionToken = $this->createActionToken($userId, $friendEmailAddress);
        $toEmail = $friendEmailAddress;

        $emailData = array(
            'username' => $friendEmailAddress,
            'friend_username' => Common::getUsername($user['User']['username'], $user['User']['username'], $user['User']['username']),
            'link' => Router::Url('/', TRUE) . 'register?profile=true&token=' . $actionToken,
            'accept_link' => Router::Url('/', TRUE) . 'register?token=' . $actionToken,
            'reject_link' => Router::Url('/', TRUE) . 'register?rej=true&token=' . $actionToken
        );

        $this->sendHTMLMail($template, $emailData, $toEmail);
        
        $tokenId = $this->ActionToken->findByToken($actionToken, array('id'));
        return $tokenId['ActionToken']['id'];
    }

    /**
     * Function to send HTML mail using templates stored in database.
     *
     * @param int $templateId
     *        	template id
     * @param array $templateData
     *        	template data
     * @param string $toEmail
     *        	to email
     */
    public function sendHTMLMail($templateId, $templateData, $toEmail) {
        // getting email template from database
        $emailTemplateData = $this->EmailTemplate->getEmailTemplate($templateId, $templateData);
        $emailTemplate = $emailTemplateData ['EmailTemplate'];

        // email queue to be saved
        $mailData = array(
            'subject' => $emailTemplate ['template_subject'],
            'to_name' => $templateData ['username'],
            'to_email' => $toEmail,
            'content' => json_encode($templateData),
            'email_template_id' => $templateId,
            'module_info' => 'API Email',
            'priority' => Email::DEFAULT_SEND_PRIORITY
        );

        $this->EmailQueue->createEmailQueue($mailData);
    }

    /**
     * Function to create action token for friend invitation link
     *
     * @return string
     */
    public function createActionToken($friendId, $friendEmail) {
        /*
         * Load ActionToken model
         */        
        $this->ActionToken = ClassRegistry::init('ActionToken');

        $addFriendToken = $this->Otp->createOTP(array(
            'friend_email' => $friendEmail,
        	'friendId' => $friendId	
        ));

        /*
         * save token to action_tokens table
         */
        $friendData ['action'] = 'addFriend';
        $friendData ['user_id'] = $friendId;
        $friendData ['friend_email'] = $friendEmail;
        $actionJSON = json_encode($friendData);
        $this->ActionToken->create();
        $this->ActionToken->save(array(
            'token' => $addFriendToken,
            'action' => $actionJSON
        ));

        return $addFriendToken;
    }

    public function inviteFriendsByEmail($emailListArray, $userId) {
        $response = array();
        $registerdUser = array();
        $nonRegisteredUser = array();

        foreach ($emailListArray as $emailAddress) {
            if (Validation::email($emailAddress, true)) {

                $friendId = $this->getRegisteredUserId($emailAddress);
                // if it is a registered user
                if ($friendId != 0) {

                    $userStatus = $this->getUserStatus($userId, $friendId);
                    // check the user is already friend
                    if ($userStatus == MyFriends::STATUS_CONFIRMED) {
                        $response ['friends'] [] = $emailAddress;
                        // check is already requested
                    } else if ($userStatus == MyFriends::STATUS_REQUEST_SENT) {
                        $response ['request_sent'] [] = $emailAddress;
                        // check is request recieved
                    } else if ($userStatus == MyFriends::STATUS_REQUEST_RECIEVED ||
                            $userStatus == MyFriends::STATUS_SAME_USER) {
                        $response ['couldnotmailed'] [] = $emailAddress;
                    } else {
                        $registerdUser [] = $friendId;
                        $response ['mailed_list'] [] = $emailAddress;
                    }
                    //if it is not a registered user
                } else {
                    // chack for already invited
                    if (!$this->isInvitedUser($userId, $emailAddress)) {
                        $nonRegisteredUser [] = $emailAddress;
                        $response ['mailed_list'] [] = $emailAddress;
                    } else {
                        $response ['request_sent'] [] = $emailAddress;
                    }
                }
            } else {
                $response ['couldnotmailed'] [] = $emailAddress;
            }
        }

        /*
         * For each registered user send email with links approve, 
         * reject friend request and view invitee's profile
         *   
         */
        if (!empty($registerdUser)) {
            foreach ($registerdUser as $friendId) {
                $this->MyFriends->setFriendStatus($userId, $friendId, MyFriends::STATUS_REQUEST_SENT);
                $this->MyFriends->setFriendStatus($friendId, $userId, MyFriends::STATUS_REQUEST_RECIEVED);
                $this->sendMailToRegisteredUser($userId, $friendId, EmailTemplateComponent::ADD_FRIEND_TEMPLATE);
            }
        }

        /*
         * For each non-registered user send email with links approve, 
         * reject friend request with registration and view invitee's 
         * profile with registration
         */
        if (!empty($nonRegisteredUser)) {
            foreach ($nonRegisteredUser as $emailAddress) {                
                $tokenId = $this->sendMailToNonRegisteredUser($userId, $emailAddress, EmailTemplateComponent::INVITE_NONMEMBER_FRIEND);
                $this->setInvitedUser($userId, $emailAddress, $tokenId);
            }
        }

        return $response;
    }

    /**
     * Function to get user id from email address
     * @param string $emailAddress
     * @return number
     */
    public function getRegisteredUserId($emailAddress = NULL) {
        $Api = new ApiController;
        $Api->constructClasses();
        $emailAddress = trim($emailAddress);
        $userId = $Api->getUserIdFromEmailAddress($emailAddress);

        return $userId;
    }
}
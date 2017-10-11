<?php

/**
 * UnsubscribeController class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('UserAppController', 'User.Controller');
App::uses('File', 'Utility');
App::import('Controller', 'Api');

/**
 * UnsubscribeController for the frontend application.
 * 
 * UnsubscribeController is used for email unsubscription.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	User
 * @category	Controllers 
 */
class UnsubscribeController extends UserAppController {
	
	public $uses = array('User');
	public $components = array('Otp'); 
	public function beforeFilter() {
		parent::beforeFilter();
	
		$this->Auth->allow('index');
	
	}
	
	function index(){
		//echo $this->generateUnsubscriptionLink("adsjflkjdf");
		$email = null;
		$message = null; 
		/*
		 *If email and taken are set then validate the token with the email 
		 */
		if (isset ( $this->request->query ['e'] ) && isset ( $this->request->query ['token'] )) {
			$encodedEmail = $this->request->query ['e'];
			$token = $this->request->query ['token'];
			$email = base64_decode ( $encodedEmail ); 
			
			/*
			 * If the user subscribed news letter
			 */
			if ($this->isNewsletterSubscribed ( $email )) {
				/*
				 * authenticate the OTP token
				 */
				if ($this->Otp->authenticateOTP ( $token, array ( 'email' => $email))) {
					
					if($this->request->is('post')){
						$userId = $this->User->getUserIdFromEmail($email);
						$this->User->unsubscribeNewsletter($userId);		
						$message =__("You'll no longer receive this type of email notification.");
						$this->redirect ( array ("action" => "index") );
					}
				// show authenfification failed message
				}else{
					$message = __('email authentification failed');
				}
			} else {
				$this->set('alreadySubscribed',  true);
			}
		}else{
			 if ($this->Auth->loggedIn ()) {
			 	$email = $this->Auth->user('email'); 
			 	
			 	/*
			 	 * If the user subscribed news letter
			 	*/
			 	if ($this->isNewsletterSubscribed ( $email )) {
				 	if($this->request->is('post')){
				 		$userId = $this->User->getUserIdFromEmail($email);
				 		$this->User->unsubscribeNewsletter($userId);
				 		$message =__("You'll no longer receive this type of email notification.");
				 		$this->redirect ( array ("action" => "index") );
				 	}
			 	} else{
			 		$this->set('alreadySubscribed',  true);
			 	}
			} else{
				$this->set('noEmail',  true);
			 }
		}
		
		$this->set(compact('email', 'message'));
	}
	
	
	
	/**
	 * Function to check is newsletter subscribed for an email address
	 * @param string $email
	 * @return boolean
	 */
	public function isNewsletterSubscribed($email = NULL) {
		
		 return $this->User->isNewsletterSubscribed($email);
	}
}
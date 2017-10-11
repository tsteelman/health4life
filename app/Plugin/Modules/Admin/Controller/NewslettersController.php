<?php

/**
 * NewslettersController class file.
 *
 * @author    Varun Ashok <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
/**
 * Newsletters Management for the admin
 *
 * Newsletters Management Controller is used for admin to edit and create Newsletters
 *
 * @author Varun Ashok
 * @package Admin
 * @category Controllers
 */
App::uses ( 'Common', 'Utility' );
App::uses ( 'CakeTime', 'Utility' );
App::uses('Validation', 'Utility');

class NewslettersController extends AdminAppController {

	
	public $uses = array (
			'Newsletter',
			'NewsletterTemplate',
			'NewsletterQueueStatus',
			'User',
			'Community' 
	);

	public $components = array (
			'EmailQueue',
			'Unsubscribe' 
	);
	const PAGE_LIMIT = 10;
	
	/**
	 * Admin Newsletters Management home
	 */
	function index() {
	$this->layout = 'default';

	$this->paginate = array(
	    'limit' => 10
	);
	$newsletters = $this->paginate('Newsletter');
	$title_for_layout = 'Newsletters';

	$this->set(compact('newsletters', 'title_for_layout'));

	$newsletters = $this->paginate('Newsletter');
	$title_for_layout = 'Newsletters';

	$this->set(compact('newsletters', 'title_for_layout'));
	}

	
	/**
	 * Function to add new newsletter
	 */
	function add() {
		
		// title for view file
		$title_for_layout = 'Add Newsletter';
		
		/*
		 * Get the newsletter template as list
		 */
		$templateList = $this->NewsletterTemplate->find ( 'list' );
		
		/*
		 * Get all the predefined tags
		 */
		$tagList = array_combine ( $this->Newsletter->tags, $this->Newsletter->tags );
		
		// newsletter insert conents
		$detailsList = $this->Newsletter->insertContents;
		
//		$this->JQValidator->addValidation ( 'AddNewsletterForm', $this->Newsletter->validate, 'AddNewsletterForm' );
		$this->JQValidator->addValidation('Newsletter', $this->Newsletter->validate, 'AddNewsletterForm');
		
		$user_id = $this->Auth->user ( 'id' );
		
		/*
		 * If the request data is not empty then save the data
		 */
		if (! empty ( $this->data )) {
			
			/*
			 * Save the newsletter
			 */			
			if ($this->addNewsletter()) {
				$this->Session->setFlash ( __ ( 'The newsletter has been saved', true ), 'success' );
				$this->redirect ( array (
						'action' => 'index' 
				) );
			} else {
				$this->Session->setFlash ( __ ( 'The newsletter could not be saved. Please, try again.', true ), 'error' );
			}
		}
		
		$this->set ( compact ( 'title_for_layout', 'templateList', 'tagList', 'detailsList' ) );
	}
	
	/**
	 * Function to edit newsletter
	 *
	 * @param int $id        	
	 */
	function edit($id = null) {
		$title_for_layout = 'Edit Newsletter';
		$templateList = array ();
	
		$tagList = array_combine ( $this->Newsletter->tags, $this->Newsletter->tags );
		$detailsList = $this->Newsletter->insertContents;
	
		$this->JQValidator->addValidation('Newsletter', $this->Newsletter->validate, 'AddNewsletterForm');
	
		if ($id) {
				
			$user_id = $this->Auth->user ( 'id' );
				
			if (! empty ( $this->data )) {
	
	
				if ($this->addNewsletter()) {
					$this->Session->setFlash ( __ ( 'The newsletter has been updated', true ), 'success' );
					$this->redirect ( array (
							'action' => 'index'
					) );
				} else {
					$this->Session->setFlash ( __ ( 'The newsletter could not be updated. Please, try again.', true ), 'error' );
				}
			} else {
				$newsletter = $this->Newsletter->findById ( $id );
				$this->data = $newsletter;
			}
		}
	
		$this->set ( compact ( 'title_for_layout', 'templateList', 'tagList', 'detailsList' ) );
		$this->render ( 'add' );
	}	

	/**
	 * Delete newsletter
	 */
	function delete($id = null) {
		if (! $id) {
			$this->Session->setFlash ( __ ( 'Invalid Newsletter', true ) );
			$this->redirect ( array (
					'action' => 'index'
			) );
		}
		if ($this->Newsletter->deleteAll ( array (
				'Newsletter.id' => $id
		) )) {
			$this->Session->setFlash ( __ ( 'Newsletter deleted', true ) );
			$this->redirect ( array (
					'action' => 'index'
			) );
		}
		$this->Session->setFlash ( __ ( 'Newsletter was not deleted', true ) );
		$this->redirect ( array (
				'action' => 'index'
		) );
	}
	
	/**
	 * Function to search the newsletter
	 */
	function search() {
		$keyword = NULL;
		$title_for_layout = 'Newsletters';
	
		if (isset ( $this->request->query ['newsletter_name'] )) {
			$keyword = $this->request->query ['newsletter_name'];
			$this->paginate = array (
					'conditions' => array (
							'Newsletter.subject LIKE' => '%' . $keyword . '%'
					),
					'limit' => 10
			);
		} else {
			$this->paginate = array (
					'limit' => 10
			);
			$newsletters = $this->paginate ( 'Newsletter' );
		}
		$newsletters = $this->paginate ( 'Newsletter' );
		$this->set ( compact ( 'newsletters', 'title_for_layout', 'keyword' ) );
		if (sizeof ( $newsletters ) == 0) {
			$this->Session->setFlash ( __ ( 'No templates found.', true ) );
		}
		$this->render ( 'index' );
	}
	
	/**
	 * Function to get newsletter template contents
	 */
	public function getNewsletterTemplateContent() {
	    $this->autoRender = false;
	
		$id = $this->request->query ( 'id' );
		return $this->NewsletterTemplate->getNewsLetterTemplate ( $id );
	}
	

	function view($id = null) {
		$this->layout = 'newslettertemplate';
		if (isset ( $this->params ['url'] ['preview'] )) {
			$arg = $this->params ['url'] ['preview'];
			if ($arg === true) {
				$this->set ( 'preview', true );
			}
		} else {
			$this->set ( 'preview', false );
		}
		if (! $id && $arg != true) {
			$this->Session->setFlash ( __ ( 'Invalid Newsletter', true ) );
			$this->redirect ( array (
					'action' => 'index' 
			) );
		}
		$newsletter = $this->Newsletter->read ( null, $id );
		$this->set ( compact ( 'newsletter' ) );
	}
	
	/**
	 * Function to add new newsletter
	 * @return boolean
	 */
	public function addNewsletter() {
//		$this->JQValidator->addValidation('Newsletter', $this->Newsletter->validate, 'AddNewsletterForm');
	$user_id = $this->Auth->user('id');

	if (!empty($this->data)) {

	    $this->request->data ['Newsletter'] ['created_by'] = $user_id;
	    $this->request->data ['Newsletter'] ['modified_by'] = $user_id;
	    $this->Newsletter->create();
	    if ($this->Newsletter->save($this->data)) {
		if (!empty($this->data['Newsletter']['id'])) {
		    $lastInsertedId = $this->data['Newsletter']['id'];
		} else {
		    $lastInsertedId = $this->Newsletter->getLastInsertID();
		}
	    } else {
		$lastInsertedId = FALSE;
	    }
	    return $lastInsertedId;
	  }
        }
	
	/**
	 * View for send newsletter
	 * @param string $id
	 */
	public function sendNewsletter($id = null) { 
		if (! empty ( $this->data ['Newsletter'] ['id'] )) {
			$insertId = $this->data ['Newsletter'] ['id'];
		} else if (! empty ( $id )) {
			$insertId = $id;
		} else {
			$insertId = $this->addNewsletter ();
		}
		
		$type = array (
				'all' => 'All newsletter subscribers' ,
				'multi' => 'Email addresses'
		);
		$this->set ( compact ( 'type', 'insertId' ) );
	}
		
	/**
	 * Function to send the newsletter 
	 */
	public function sendNewsletterEmails() {
		//$this->autoRender = false;
		
		/*
		 * Get the saved newsletter id
		 */
		$insertId = $this->data ['Newsletter'] ['insertId'];
		
		// if newsletter id is not empty
		if (! empty ( $insertId )) {
			
			/*
			 * Get the newsletter from id
			 */
			$newsletter = $this->Newsletter->findById ( $insertId );
			
			/*
			 * Create new instance and save details in newsletter queue.
			 */
			$instanceId = $insertId . time ();
			$newsletterQueue = array (
					'instance_id' => $instanceId,
					'newsletter_id' => $insertId,
					'subject' => $newsletter ['Newsletter'] ['subject'],
					'status' => 0 
			);
			$this->NewsletterQueueStatus->save ( $newsletterQueue );
			
			/*
			 * Get the subscriber type
			 */
			$subscriberType = $this->data ['Newsletter'] ['subscribers'];
			
			/*
			 * Send the newsletter according to the newsletter type
			 */
			switch ($subscriberType) {
				case 'all' :
					$this->sendNewsletterToAll ( $newsletter, $instanceId, $insertId );
					break;
				case 'multi' :
					$emails = $this->data ['Newsletter'] ['email_address'];
					$this->sendNewsletterForTesting ( $emails, $instanceId, $insertId );
			}
			
			$this->Session->setFlash ( __ ( 'Newsletters added to email queue ', true ), 'success' );
		}
		
		/*
		 * Redirect to the index page
		 */
		$this->redirect ( array (
				'action' => 'index' 
		) );
	}
		
	/**
	 * Function to send newsletter to all users
	 * @param array $newsletter
	 * @param int $instanceId
	 * @param int $insertId
	 */
	public function sendNewsletterToAll($newsletter, $instanceId, $insertId) {
		
		/*
		 * Find all the users
		 */
		$users = $this->User->find ( 'all', array (
				'conditions' => array (
						'User.newsletter' => 1 
				),
				'fields' => array (
						'User.username',
						'User.email',
						'User.id' 
				)
		) );
		
		/*
		 * For each user send the newsleter
		 */
		foreach ( $users as $user ) {
			
			$content = $newsletter ['Newsletter'] ['content'];
			// define rewriting tags
			$param = array (
					'username' => $user ['User'] ['username'],
					'email' => $user ['User'] ['email'],
					'user_photo' => Common::getUserThumb ( $user ['User'] ['id'] ) 
			);
			
			/*
			 * rewirte the predefined tags
			*/
			$contentRewrited = $this->__emailContentRewrite ( $content, $param );
			
			$emailData = array (
					'username' => $user ['User'] ['username'],
					'content' => $contentRewrited 
			);
			// email data to be saved
			$mailData = array (
					'subject' => $newsletter ['Newsletter'] ['subject'],
					'to_name' => $user ['User'] ['username'],
					'to_email' => $user ['User'] ['email'],
					'content' => json_encode ( $emailData ),
					'module_info' => 'Admin Newsletter',
					'email_template_id' => $insertId,
					'instance_id' => $instanceId 
			);
			/*
			 * Save data to  email queue
			 */
			$this->EmailQueue->createEmailQueue ( $mailData );
		}
	}
	
	/**
	 * Function to  send newsletter to specific email ids
	 * @param string $emailAddresses
	 * @param int $instanceId
	 * @param int $insertId
	 */
	public function sendNewsletterForTesting($emailAddresses = NULL, $instanceId, $insertId) {
		
		/*
		 * Find newsletter by id
		 */
		$newsletter = $this->Newsletter->findById ( $insertId );
		
		/*
		 * If the email address field is not null then split comma separated emailids
		 */
		if ($emailAddresses != NULL && $emailAddresses != '') {
			
			/*
			 * Split the empalid with comma
			 */
			$emailAddressArray = explode ( ',', $emailAddresses );
			
			foreach ( $emailAddressArray as $email ) {
				$email = trim ( $email );
				
				// validate email address
				if (Validation::email ( $email, true )) {
					
					$content = $newsletter ['Newsletter'] ['content'];
					$param = array (
							'username' => $email,
							'email' => $email,
							'user_photo' => Common::getUserThumb(0) // get default image 
					);
					
					/*
					 * rewirte the predefined tags
					 */
					$contentRewrited = $this->__emailContentRewrite ( $content, $param );
					
					$emailData = array (
							'username' => $email,
							'content' => $contentRewrited 
					);
					// email data to be saved
					$mailData = array (
							'subject' => $newsletter ['Newsletter'] ['subject'],
							'to_name' => $email,
							'to_email' => $email,
							'content' => json_encode ( $emailData ),
							'module_info' => 'Admin Newsletter',
							'email_template_id' => $insertId,
							'instance_id' => $instanceId 
					);
					$this->EmailQueue->createEmailQueue ( $mailData );
				}
			}
		}
	}
	
	/**
	 * Function to rewrite the predefined tags in email
	 * @param string $content
	 * @param array $param
	 */
	private function __emailContentRewrite($content = NULL, $param = array()){
		
		/*
		 * Generate repalce value for predefined tags
		 */
		$replace = $this->__generateReplaceString ($param);
		
		/*
		 * Predefiened tag array
		 */
		$search = $this->Newsletter->tags;
		
		/*
		 * Replace the search array with replace string
		 */
		return $this->Newsletter->rewriteVariable ( $content, $search, $replace );
	}
	
	/**
	 * Function to generate predefined tag replace
	 * @param array $params
	 * @return array
	 */
	private function __generateReplaceString( $params = array()) {
	
		/*
		 * If param is not set then use the detials of
		* logged in user for generating replace string
		*/
		if (!is_array($params) || empty($params)) {
				
			$username = $this->Auth->user ( 'username' );
			$email_ddress = $this->Auth->user ( 'email' );
			$user_photo = Common::getUserThumb($this->Auth->user('id'));
	
		} else {
				
			if(isset($params['username'])){
				$username = $params['username'];
			}else{
				$username = null;
			}
				
			if(isset($params['email'])){
				$email_ddress = $params['email'];
			}else{
				$email_ddress = null;
			}
				
			if(isset($params['user_photo'])){
				$user_photo = $params['user_photo'];
			}else{
				$user_photo = null;
			}
				
		}
	
		$site_url = Router::Url ( '/', TRUE );
		$site_name = Configure::read ( 'App.name' );
		$site_about_link = Router::Url ( '/', TRUE ) . 'pages/about';
		$site_terms_of_service_link = Router::Url ( '/', TRUE ) . 'pages/terms_of_service';
		$site_contact_link = Router::Url ( '/', TRUE ) . 'pages/contact_us';
		$site_faq_link = Router::Url ( '/', TRUE ) . 'pages/faq';
		$site_login_link = Router::Url ( '/', TRUE ) . 'login';
		$site_register_link = Router::Url ( '/', TRUE ) . 'register';
		$site_forgot_password_link = Router::Url ( '/', TRUE ) . 'forgot_password';
		$unsubscribe_link = $this->Unsubscribe->generateUnsubscriptionLink ( $email_ddress );
	
		return array (
				$username,
				$email_ddress,
				$user_photo,
				$site_url,
				$site_name,
				$site_about_link,
				$site_terms_of_service_link,
				$site_contact_link,
				$site_faq_link,
				$site_login_link,
				$site_register_link,
				$site_forgot_password_link,
				$unsubscribe_link
		);
	}
		
	/**
	 * Function get newslette preview
	 * @return html
	 * response for ajax request
	 */	
	function getNewsletterPreview() {
		$this->autoRender = false;
	
		$html = $this->request->data;
		$search = $this->Newsletter->tags;
	
		/*
		 * Generate predefined tag replace
		*/
		$replace = $this->__generateReplaceString ();
	
		/*
		 * Replace predefined tags
		*/
		$html = $this->Newsletter->rewriteVariable ( $html, $search, $replace );
		return $html;
	}
	
	/**
	 * Function to get last five community details as html
	 * returon for ajax request
	 */
	public function getLastFiveCommunityHtml() {
		$this->autoRender = false;
		/*
		 * Last five community details
		*/
		$communities = $this->Community->getLastFiveCommunityDetails ();
		$this->set ( compact ( 'communities' ) );
		$View = new View ( $this, false );
		$results = $View->element ( 'last_communities' );
		echo $results;
	}
}

?>
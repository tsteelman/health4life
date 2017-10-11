<?php 
/**
 * SubscribersController class file.
 *
 * @author    Varun Ashok <varunashok@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
/**
 * Subscribers List Management for the admin
 *
 * SubscribersController is used for admin to edit subscribers
 *
 * @author 	Varun Ashok
 * @package 	Admin
 * @category	Controllers
 */
App::uses('Common', 'Utility');
App::uses('CakeTime', 'Utility');

class SubscribersController extends AdminAppController{
	public $uses =  array('User');
	
	/*
	 * Constsnts
	 */
	const PAGE_LIMIT = 10;
	
	/**
	 * Function to list all the user subscription details
	 */
	public function index() {
		$filter = 0;
		
		/*
		 * Filter user is not admin
		 */
		$condition = array (
                    'User.type !=' => NULL,
                    'User.is_admin' => 0,
                    'DATE(User.created) !=' => '0000-00-00'
		);
		
		/*
		 * If the filter is set add filter to the pagination conditions
		 */
		if ($this->request->query ( 'filter' ) && $this->request->query ( 'filter' ) != 0) {
			$filter = $this->request->query ( 'filter' );
			$condition ['User.type'] = $filter;
		}
		
		/*
		 * If the keyword is set search with keyword
		 */
		if ($this->request->query ( 'keyword' )) {
			$keyword = $this->request->query ( 'keyword' );
			$condition['OR'] ['User.username LIKE'] = '%' . $keyword . '%';
			$condition['OR'] ['User.email LIKE'] = '%' . $keyword . '%';
		}
		
		$this->paginate = array (
				'limit' => SubscribersController::PAGE_LIMIT,
				'conditions' => $condition,
				'fields' => array (
						'User.id',
						'User.username',
						'User.first_name',
						'User.last_name',
						'User.email',
						'User.newsletter',
						'User.created',
						'User.type' 
				),
				'order' => array (
						'User.username' => 'asc' 
				) 
		);
		$user_list = $this->paginate ( 'User' );
		
		/*
		 * Set view variables
		 */
		$this->set ( compact ( 'user_list', 'filter', 'keyword' ) );
	}
	
	/**
	 * Function to change status of newsletter subscription
	 */
	function changeStatus() {
		/*
		 * Get the user id
		 */
		$id = $this->request->query ['id'];
		$filter = $this->request->query ['filter'];
		if(isset($this->request->query ['page'])){
			$page = $this->request->query ['page'];
		}else{
			$page = 1;
		}
		/*
		 * If the user id is valied change the status
		 */
		if ($id !== null) {
			
			/*
			 * Get the changing action 
			 */
			$action = $this->request->query ['action'];
			
			/*
			 * If the action is activate then subscribe the newsletter for the user
			 * and show the updation message
			 */
			if ($action == 'activate') {
				if ($this->User->subscribeNewsletter ( $id )) {
					$this->Session->setFlash ( 'Activated newsletter subscription successfully.', 'success' );
				} else {
					$this->Session->setFlash ( 'Newsletter subscription activation faild.', 'error' );
				}
				
			//If the action is inactivate then unsubscribe the newsletter for the user
			} else {
				if ($this->User->unsubscribeNewsletter ( $id )) {
					$this->Session->setFlash ( 'Deactivated newsletter subscription successfully.', 'success' );
				} else {
					$this->Session->setFlash ( 'Newsletter subscription inactivation faild.', 'error' );
				}
			}
		}
		
		/*
		 * Parameters for the requesting url
		 */
		$params = array (
				"filter" => $filter 
		);
		
		if(isset( $this->request->query ['keyword'])){
			$params['keyword'] = $this->request->query ['keyword'];
		}
			/*
		 * Redirect to index
		 */
		$this->redirect ( array (
				"controller" => "Subscribers",
				"action" => "index",
				"page" => $page,
				"?" => $params 
		) );
	}
	
	/**
	 * Function to search in subscribers list
	 */
	public function search(){
		$condition = array('User.is_admin' => 0);
		
		if($this->request->query('keyword')){
			$keyword = $this->request->query('keyword');
		}else{
			
		}
	}
}
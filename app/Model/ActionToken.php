<?php
App::uses('AppModel', 'Model');

/**
 * Action Tocken Model
 *
 */
class ActionToken extends AppModel{
	
	function deleteWhereEmailPresentInAddFriend($email) {
		$actionIds = $this->find ( 'list', array (
				'conditions' => array (
						'action Like' => '%action":"addFriend%' ,
						'action Like' => '%friend_email":"' . $email . '%'
				) 
		) );
		foreach ($actionIds as $actionId){
			$this->delete($actionId);
		}
		
	}
	
	function isExistsToken($token){
		$tokenId = $this->findByToken($token);
		if(empty($tokenId)){
			return false;
		}else{
			return true;
		}
	}
}
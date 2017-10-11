<?php
App::uses ( 'AppModel', 'Model' );

/**
 * Invited User Model
 */
class InvitedUser extends AppModel {
	
	/**
	 * Function to get all invited users by email
	 * @param string $email_address
	 * @return array:
	 */
	public function getAllInvitedUsers($email_address) {
		$invited_users_list = array ();
		$users_list = $this->findByEmail ( $email_address );
		
		/*
		 * If there are useres invited this email
		 */
		if ($users_list) {
			$invited_users_list = json_decode ( $users_list ['InvitedUser'] ['invited_user_list'] );
		}
		
		if (isset ( $invited_users_list->user_list )) {
			return $invited_users_list->user_list;
		} else {
			return array ();
		}
	}
	
	/**
	 * Function to find id of invited userlist by email
	 * @param string $email_address
	 * @return int 
	 */
	public function findId($email_address) {
		$id = $this->find ( 'first', array (
				'conditions' => array (
						'email' => $email_address 
				),
				'fields' => array (
						'id' 
				) 
		) );
		if (isset ( $id ['InvitedUser'] )) {
			return $id ['InvitedUser'] ['id'];
		}
	}
	
	/**
	 * Function to save user id and email to invited user list
	 * @param int $user_id
	 * @param string $email_address
	 */
	public function setInvitedUser($user_id, $email_address, $token_id = 0) {
		
		/*
		 * get already invited useres
		 */
		$invited_users ['user_list'] = $this->getAllInvitedUsers ( $email_address );
		if (! empty ( $invited_users ['user_list'] )) {
			
			if (!$this->isInvitedUser($user_id, $email_address)) {
				$newInvite ['user_id'] = $user_id;
				$newInvite ['token_id'] = $token_id;
				$invited_users ['user_list'] [] = $newInvite;
				
				$id = $this->findId ( $email_address );
				$this->id = $id;
				$status = $this->save ( array (
						'invited_user_list' => json_encode ( $invited_users ) 
				) );
			}
		} else {
			$newInvite ['user_id'] = $user_id;
			$newInvite ['token_id'] = $token_id;
			$invited_users ['user_list'] [] = $newInvite;
			
			$this->create ();
			$status = $this->save ( array (
					'email' => $email_address,
					'invited_user_list' => json_encode ( $invited_users ) 
			) );
		}
	}
	
	/**
	 * Function to check whether the user is already invited the mail
	 *
	 * @param int $user_id        	
	 * @param string $email        	
	 * @return boolean
	 */
	public function isInvitedUser($user_id, $email) {
		$is_userExists = false;
		
		$user_list = $this->getAllInvitedUsers ( $email );
		foreach ( $user_list as $user ) {
			if ($user->user_id == $user_id) {
				$is_userExists = true;
				break;
			}
		}
		return $is_userExists;
	}
	
	/**
	 * Function to delete all the invited userlist by email
	 * @param string $email
	 */
	public function deleteAllInvitedUsers($email) {
		$id = $this->findId ( $email );
		$this->delete ( $id );
	}
}
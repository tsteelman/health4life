<?php
App::uses('AppModel', 'Model');

/**
 * Unsubscribe Model
 *
 */
class Unsubscribe extends AppModel {
	
	public $components = array('Otp');
	/**
	 * Function to generate unsubscription link
	 * @param string $email
	 * @return string|NULL
	 */
	public function generateUnsubscriptionLink($email = NULL){
		if ($email !== NULL) {
			$this->Otp = $this->Components->load('Otp');
			$encodedEmail = base64_encode ( $email );
			$token = $this->Otp->createOTP(array(
					'email' => $email
			));
			return Router::Url('/', TRUE) . 'user/unsubscribe?token='.$token.'&e=' . $encodedEmail;
		}
		return null;
	}
}
<?php

App::uses('component', 'controller');

class ForgotPasswordComponent extends Component {

    public $components = array('Session',
        'EmailQueue',
        'Email',
        'EmailTemplate',
        'Otp',
        'JQValidator.JQValidator');

    /*
     * Function to send forgot password mail
     */

    public function sendMail($data, $userType) {

        /*
         * Loading UserModel
         */
        $userObj = ClassRegistry::init('User');

        if (!empty($data)) {

			$email = $data['email'];
			
			$conditions = array('User.email' => $email);
			if ($userType === 'admin') {
				$conditions['User.is_admin'] = User::ADMIN_USER;
			}
			$user = $userObj->find('first', array('conditions' => $conditions));

			if ($user) {

                // setup the TIME TO LIVE (valid until date) for the next one day
                $now = microtime(true);
                $timelimit = $now + 24 * 3600; // the invitation is good for the next one day
                // create the OTP - TTL = time to live
                $forgot_password_code = $this->Otp->createOTP(array('email' => $email, 'userid' => $user['User']['id'], 'timelimit' => $timelimit));

                //Saving forgot_password_code to database.
                $userObj->id = $user['User']['id'];
                $userObj->saveField('forgot_password_code', $forgot_password_code);

                //Generating link
                if ($userType === 'admin') {
                    $link = Router::Url('/', TRUE) . 'admin/users/resetpassword/' . $timelimit . '/' . $forgot_password_code;
                } else {
                    $link = Router::Url('/', TRUE) . 'user/forgotPassword/resetpassword/' . $timelimit . '/' . $forgot_password_code;
                }

                $emailData = array(
                    'username' => Common::getUsername($user['User']['username'], $user['User']['first_name'], $user['User']['last_name']),
                    'link' => $link
                );


                //Getting email template from database
                $emailManagement = $this->EmailTemplate->getEmailTemplate(EmailTemplateComponent::RESET_PASSWORD_TEMPLATE, $emailData);

                // data to be saved
                $mailData = array(
                    'subject' => $emailManagement['EmailTemplate']['template_subject'],
                    'to_name' => $emailData['username'],
                    'to_email' => $user['User']['email'],
                    'content' => json_encode($emailData),
                    'module_info' => 'Forgot Password',
                    'email_template_id' => EmailTemplateComponent::RESET_PASSWORD_TEMPLATE,
                    'priority' => 3
                );

                $this->EmailQueue->createEmailQueue($mailData);

                $this->Session->setFlash(__('You will receive an email with instructions about how to reset your password in a few minutes.'), 'success');

                return TRUE;
            } else {

                $this->Session->setFlash(__('This email is not registered with us.'), 'error');

                return FALSE;
            }
        } else {

            $this->Session->setFlash(__('Enter the email id'), 'error');

            return FALSE;
        }
    }

    function resetPassword($data, $timelimit, $forgot_password_code) {

        /*
         * Loading UserModel
         */
        $userObj = ClassRegistry::init('User');

        $user = $userObj->find('first', array('conditions' => array('User.forgot_password_code' => $forgot_password_code)));
        if ($user) {
            //Check if forgot_password_code has expired
            $now = microtime(true);
            if ($now < $timelimit) {

                //Validate forgot_password_code
                if ($this->Otp->authenticateOTP($forgot_password_code, array('email' => $user['User']['email'], 'userid' => $user['User']['id'], 'timelimit' => $timelimit))) {

                    if ($data) {

                        if (strlen($data['ResetPasswordForm']['password']) < 6) {

                            $this->Session->setFlash(__('Password should be atleast 6 characters long'), 'error');

                            return FALSE;
                        } else {

                            if ($data['ResetPasswordForm']['password'] === $data['ResetPasswordForm']['confirm-password']) {

                                $save_data = array('id' => $user['User']['id'], 'password' => $data['ResetPasswordForm']['password'], 'forgot_password_code' => '');
                                $userObj->save($save_data);

                                $this->Session->setFlash(__('Password successfully reset. Please login with your new credentials.'), 'success');

                                return TRUE;
                            } else {

                                $this->Session->setFlash(__('Password and confirm password do not match'), 'error');

                                return FALSE;
                            }
                        }
                    }
                } else {

                    $this->Session->setFlash(__('Invalid link'), 'error');

                    return TRUE;
                }
            } else {

                $this->Session->setFlash(__('Link has expired'), 'error');

                return TRUE;
            }
        } else {

            $this->Session->setFlash(__('Invalid link / link has expired'), 'error');

            return TRUE;
        }
    }
}
?>
<?php

/**
 * One time password generation class file.
 *
 * @author    Ajay Arjunan <ajay@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */

/**
 * OtpComponent for all users
 * 
 * OtpComponent is used to generate forgot_password_code for reseting password of users.
 *
 * @author 	Ajay Arjunan
 * @package 	Admin
 * @category	Controllers 
 */

class OtpComponent extends Component {
    var $components = array('Auth');
    
    function createOTP($parameters){
           return  $this->Auth->password(implode("", $parameters));
    }

    function authenticateOTP($otp,$parameters ){
        return $otp == $this->Auth->password(implode("", $parameters));
    }

} 

?>

<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('FrontAppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends FrontAppController {

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array('User', 'ContactUsForm', 'Configuration', 'PrelaunchUser');

    public function beforeFilter() {
        parent::beforeFilter(); 
        
        /*
         * Check if the coming soon veriable enabled
         * 
         */
        if ( !Configure::read('COMING_SOON') ) {

            /*
             * Commenting the code as per the request in #143700
             * Showing all the static pages and adding the checking
             * only for the home page.
             */
            if ($this->Auth->loggedIn() && ($this->request->here == "/")) {
                $this->redirect($this->Auth->loginRedirect);
            }

            $this->Auth->allow('display', 'contactUs');
        }
    }

    /**
     * Displays a view
     *
     * @param mixed What page to display
     * @return void
     * @throws NotFoundException When the view file could not be found
     * 	or MissingViewException in debug mode.
     */
    public function display() {
		$path= func_get_args();
 
        /*
         * Details to set contact us email form
         */
        $formId = 'ContactUsForm';
        $this->JQValidator->addValidation('ContactUsForm', $this->ContactUsForm->validate, $formId);
        /*         * **************************************************************************************** */

        $userData = array();

        if ($this->Auth->loggedIn()) {
            if ($this->Auth->user()) {
                $userData = $this->Auth->user();
            }
        }

        if ($path[0] == 'contactUs') {
            $this->contactUs();
            exit;
        }

        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        $page = $subpage = $title_for_layout = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if (!empty($path[$count - 1])) {
            $title_for_layout = Inflector::humanize($path[$count - 1]);
        }
        $new_members_list = $this->__getLatestMembers();

        if (!empty($userData)) {
            //To be used when viewing contact us page after logging in to application
            $this->set(compact('page', 'subpage', 'title_for_layout', 'new_members_list', 'formId', 'userData'));
        } else {
            $this->set(compact('page', 'subpage', 'title_for_layout', 'new_members_list', 'formId'));
        }

        try {
            $this->render(implode('/', $path));
        } catch (MissingViewException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }

    public function contactUs() {

        $this->autoRender = false;

        if ($this->request->is('post')) {
            $emails = $this->Configuration->getValue('contact_email');
            $postData = $this->request->data['ContactUsForm'];

            if ($this->Auth->loggedIn()) {
                if ($this->Auth->user()) {
                    //Collecting details of logged in user.
                    $postData['username'] = $this->Auth->user('username');
                    $postData['firstName'] = $this->Auth->user('first_name');
                    $postData['lastName'] = $this->Auth->user('last_name');
                    $postData['email'] = $this->Auth->user('email');
                    if ($this->Auth->user('phone_number') != NULL) {
                        $postData['phone'] = $this->Auth->user('phone_number');
                    }
                }
            }

            if ($postData['username'] != '') {
                $sender = $postData['username'];
            } else {
                $sender = $postData['email'];
            }

            $senderMessage = 'First Name: ' . $postData['firstName'];

            if ($postData['middleName'] != '') {
                $senderMessage = $senderMessage . '<br />Middle Name: ' . $postData['middleName'];
            }

            if ($postData['lastName'] != '') {
                $senderMessage = $senderMessage . '<br />Last Name: ' . $postData['lastName'];
            }

            if ($postData['suffix'] != '') {
                $senderMessage = $senderMessage . '<br />Suffix: ' . $postData['suffix'];
            }

            $senderMessage = $senderMessage . '<br />Email: ' . $postData['email'];

            if ($postData['phone'] != '') {
                $senderMessage = $senderMessage . '<br />Phone Number: ' . $postData['phone'];
            }

            if ($postData['username'] != '') {
                $senderMessage = $senderMessage . '<br />Username: ' . $postData['username'] . '<br />';
            }

            $senderMessage = $senderMessage . '<br />Comment or Question: <br />' . nl2br($this->request->data['ContactUsForm']['enquiry']);

            $data['sender_username'] = $sender;
            $data['sender_message'] = $senderMessage;

            $toEmails = explode(',', $emails);

            $this->EmailTemplate = $this->Components->load('EmailTemplate');
            $emailTemplate = $this->EmailTemplate->getEmailTemplate(EmailTemplateComponent::CONTACT_US_TEMPLATE, $data);

            foreach ($toEmails as $toEmail) {
//                Code to send mails

                $mailData = array(
                    'subject' => $emailTemplate['EmailTemplate']['template_subject'],
                    'to_name' => 'Admin',
                    'to_email' => $toEmail,
                    'content' => json_encode($data),
                    'email_template_id' => 19,
                    'module_info' => 'Contact Us Mail',
                    'priority' => Email::DEFAULT_SEND_PRIORITY
                );

                $this->EmailQueue->createEmailQueue($mailData);
            }

            $result = array(
                'success' => true,
                'message' => __("Your query has been successfully updated to " .Configure::read ( 'App.name' ). " Team.")
            );
        }
        echo json_encode($result);
    }

    function __getLatestMembers() {

        $static_user_array = array(
            array(
                "User" => array
                    (
                    "id" => "border_family user_medium_thumb ",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "AlisonRenee",
                    "type" => "border_family user_medium_thumb ",
                    "profile_picture" => "member_1.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "California"
                ),
                "City" => array
                    (
                    "description" => " Palo Alto"
                ),
                "Disease" => "Crohn's"
            ),
            array(
                "User" => array
                    (
                    "id" => "border_patient user_medium_thumb ",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Alexander",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_2.png",
                ),
                "Country" => array
                    (
                    "short_name" => "India"
                ),
                "State" => array
                    (
                    "description" => "Kerala"
                ),
                "City" => array
                    (
                    "description" => " Ernakulum, Kochi,"
                ),
                "Disease" => "Rheumatoid Arthritis"
            ),
            array(
                "User" => array
                    (
                    "id" => "border_other user_medium_thumb ",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "MarissaL",
                    "type" => "border_patient user_medium_thumb ",
                    "profile_picture" => "member_3.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Texas"
                ),
                "City" => array
                    (
                    "description" => "Austin"
                ),
                "Disease" => "Colitis"
            ),
            array(
                "User" => array
                    (
                    "id" => "border_caregiver user_medium_thumb",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Jasim",
                    "type" => "border_caregiver user_medium_thumb",
                    "profile_picture" => "member_4.png",
                ),
                "Country" => array
                    (
                    "short_name" => "Bangladesh"
                ),
                "State" => array
                    (
                    "description" => "Sylhet"
                ),
                "City" => array
                    (
                    "description" => ""
                ),
                "Disease" => "Lupus"
            ),
            array(
                "User" => array
                    (
                    "id" => "5",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "NinaA-Canada",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_5.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "California"
                ),
                "City" => array
                    (
                    "description" => "Los Angeles"
                ),
                "Disease" => "Rheumatoid arthritis"
            ),
            array(
                "User" => array
                    (
                    "id" => "6",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Troy-Landreau",
                    "type" => "border_family user_medium_thumb ",
                    "profile_picture" => "member_6.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Louisiana"
                ),
                "City" => array
                    (
                    "description" => "Baton Rouge"
                ),
                "Disease" => "Scleroderma"
            ),
            array(
                "User" => array
                    (
                    "id" => "7",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Johnson",
                    "type" => "border_caregiver user_medium_thumb",
                    "profile_picture" => "member_7.png",
                ),
                "Country" => array
                    (
                    "short_name" => "Canada"
                ),
                "State" => array
                    (
                    "description" => "Quebec"
                ),
                "City" => array
                    (
                    "description" => "Montreal"
                ),
                "Disease" => "Lupus"
            ),
            array(
                "User" => array
                    (
                    "id" => "8",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Will-Wadsworth",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_8.png",
                ),
                "Country" => array
                    (
                    "short_name" => "Canada"
                ),
                "State" => array
                    (
                    "description" => "British Columbia"
                ),
                "City" => array
                    (
                    "description" => "Vancouver"
                ),
                "Disease" => "Rheumatoid arthritis"
            ),
            array(
                "User" => array
                    (
                    "id" => "9",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "AmandaHintz",
                    "type" => "border_family user_medium_thumb ",
                    "profile_picture" => "member_9.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Connecticut"
                ),
                "City" => array
                    (
                    "description" => "Bridgeport"
                ),
                "Disease" => "Scleroderma"
            ),
            array(
                "User" => array
                    (
                    "id" => "10",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Tamasin",
                    "type" => "border_caregiver user_medium_thumb",
                    "profile_picture" => "member_10.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Nevada"
                ),
                "City" => array
                    (
                    "description" => "Las Vegas"
                ),
                "Disease" => "Crohn's"
            ),
            array(
                "User" => array
                    (
                    "id" => "11",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Zykima",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_11.png",
                ),
                "Country" => array
                    (
                    "short_name" => "UK"
                ),
                "State" => array
                    (
                    "description" => "England"
                ),
                "City" => array
                    (
                    "description" => "London WS1"
                ),
                "Disease" => "Scleroderma"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Peter Smith",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_12.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Texas"
                ),
                "City" => array
                    (
                    "description" => "Austin"
                ),
                "Disease" => "Crohn's"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Patrica",
                    "type" => "border_caregiver user_medium_thumb ",
                    "profile_picture" => "member_13.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Austin"
                ),
                "City" => array
                    (
                    "description" => "Texas"
                ),
                "Disease" => "RSD"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Jennifer",
                    "type" => "border_patient user_medium_thumb ",
                    "profile_picture" => "member_14.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Washington"
                ),
                "City" => array
                    (
                    "description" => "Seattle"
                ),
                "Disease" => "Crohn's"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Karen",
                    "type" => "border_family user_medium_thumb ",
                    "profile_picture" => "member_15.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Oklahoma"
                ),
                "City" => array
                    (
                    "description" => "Boston"
                ),
                "Disease" => "Lupus"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Christine",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_16.png",
                ),
                "Country" => array
                    (
                    "short_name" => "USA"
                ),
                "State" => array
                    (
                    "description" => "Arizona"
                ),
                "City" => array
                    (
                    "description" => "Boston"
                ),
                "Disease" => "Lupus"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Charly.Shaun",
                    "type" => "border_family user_medium_thumb ",
                    "profile_picture" => "member_17.png",
                ),
                "Country" => array
                    (
                    "short_name" => "Australia"
                ),
                "State" => array
                    (
                    "description" => "Adelaide"
                ),
                "City" => array
                    (
                    "description" => "Adelaide"
                ),
                "Disease" => " Colitis"
            ),
            array(
                "User" => array
                    (
                    "id" => "12",
                    "first_name" => "New",
                    "last_name" => "Patient",
                    "username" => "Nicole",
                    "type" => "border_other user_medium_thumb ",
                    "profile_picture" => "member_18.png",
                ),
                "Country" => array
                    (
                    "short_name" => "UK"
                ),
                "State" => array
                    (
                    "description" => "London"
                ),
                "City" => array
                    (
                    "description" => "Croydon"
                ),
                "Disease" => "Lyme Disease"
            ),            
        );
        return $static_user_array;
        //return $this->User->getLatestMembers();  
    }

    public function comingSoon(){
        $this->JQValidator->addValidation('PrelaunchUser', $this->PrelaunchUser->validate, 'PrelaunchUserComingSoonForm');
        
        $this->layout = 'p4l_coming_soon_layout';
        $this->render('p4l_coming_soon');
        if ( $this->request->is('post') ){
                $data = $this->request->data;
                $email = $this->PrelaunchUser->findByEmail( $data['PrelaunchUser']['email'] );
                if ( empty( $email )) {
                        if ( $this->PrelaunchUser->save( $data ) ) {
                                $result['success'] = true;
                        } else {
                                $result['error'] = true;
                        }
                } else {
                        $result['success'] = true;
                }
                echo json_encode( $result );
                exit;
        }
    }
}
?>

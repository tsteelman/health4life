<?php

/**
 * ImportContactsComponent class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');

/**
 * ImportContactsComponent for importing contacts from other sites.
 * 
 * This class is used to import contacts from other sites.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Controller.Component
 * @category	Component 
 */
class ImportContactsComponent extends Component {

    /**
     * Constructor
     * 
     * Initialises the models
     */
    public function __construct() {
        $this->User = ClassRegistry::init('User');
        $this->MyFriends = ClassRegistry::init('MyFriends');
        $this->InvitedUser = ClassRegistry::init('InvitedUser');
    }

    /**
     * Initialises the component
     * 
     * @param Controller $controller
     */
    public function initialize(Controller $controller) {
        $this->controller = $controller;
        $user = $controller->Auth->user();
        $this->user = $user;
        $this->currentUserId = $user['id'];
    }

    /**
     * Function to import contacts from google
     * 
     * @return array
     */
    public function importGoogleContacts() {
        $result = array();
        $accesstoken = $this->controller->Session->read('google_access_token');
        if (($accesstoken === '') || ($accesstoken === null) || ($accesstoken === false)) {           
            if (isset($_GET['code'])) {
                $authCode = $_GET['code'];
                $accesstoken = $this->getGoogleAccessToken($authCode);
                $this->controller->Session->write('google_access_token', $accesstoken);
            } else {
                $result = array(
                    'error' => true,
                    'message' => 'Invalid access. Auth code not present.'
                );
            }
        }

        if (($accesstoken === '') || ($accesstoken === null) || ($accesstoken === false)) {
            $result = array(
                'error' => true,
                'message' => 'Invalid Access. Access token not present.'
            );
        } else {
            $result = $this->fetchGoogleContacts($accesstoken);
        }

        return $result;
    }
    
    /**
     * Function to import contacts from google
     * 
     * @return array
     */
    public function importfbContacts() {
        $result = array();
        $accesstoken = $this->controller->Session->read('fb_access_token');

        if (($accesstoken === '') || ($accesstoken === null) || ($accesstoken === false)) {

            if (isset($_GET['code'])) {
                
                $authCode = $_GET['code'];
                $accesstoken = $this->getfbAccessToken($authCode);                
                $this->controller->Session->write('fb_access_token', $accesstoken);
            } else {
                $result = array(
                    'error' => true,
                    'message' => 'Invalid access. Auth code not present.'
                );
            }
        }

        if (($accesstoken === '') || ($accesstoken === null) || ($accesstoken === false)) {
            $result = array(
                'error' => true,
                'message' => 'Invalid Access. Access token not present.'
            );
        } else {
            $result = $this->fetchfbContacts($accesstoken);
        }

        return $accesstoken;
    }


    /**
     * Function to get the contents of a file using curl
     * 
     * @param string $url
     * @return string
     */
    public function curlFileGetContents($url) {
        $curl = curl_init();
        $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';

        curl_setopt($curl, CURLOPT_URL, $url); //The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5); //The number of seconds to wait while trying to connect.	

        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //To stop cURL from verifying the peer's certificate.
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

        $contents = curl_exec($curl);
        curl_close($curl);
        return $contents;
    }

    /**
     * Function to get google access token using auth code
     * 
     * @param string $authCode
     * @return string
     */
    protected function getGoogleAccessToken($authCode) {
        $googleConfig = Configure::read('API.Google');
        $clientId = $googleConfig['CLIENT_ID'];
        $clientSecret = $googleConfig['SECRET_KEY'];
        $redirectUri = $googleConfig['REDIRECT_URL'];
        $fields = array(
            'code' => urlencode($authCode),
            'client_id' => urlencode($clientId),
            'client_secret' => urlencode($clientSecret),
            'redirect_uri' => urlencode($redirectUri),
            'grant_type' => urlencode('authorization_code')
        );
        $post = '';
        foreach ($fields as $key => $value) {
            $post .= $key . '=' . $value . '&';
        }
        $post = rtrim($post, '&');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
        curl_setopt($curl, CURLOPT_POST, 5);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($result);
        $accesstoken = $response->access_token;
        return $accesstoken;
    }

        /**
     * Function to get google access token using auth code
     * 
     * @param string $authCode
     * @return string
     */
    protected function getfbAccessToken($authCode) {
        $fbConfig = Configure::read('API.Facebook');
        $clientId = $fbConfig['APP_ID'];
        $clientSecret = $fbConfig['APP_SECRET'];
        $redirectUri = $fbConfig['REDIRECT_URL'];
        $fields = array(
            'code' => urlencode($authCode),
            'client_id' => urlencode($clientId),
            'client_secret' => urlencode($clientSecret),
            'redirect_uri' => urlencode($redirectUri)
            
        );
        $post = '';
        foreach ($fields as $key => $value) {
            $post .= $key . '=' . $value . '&';
        }
        $post = rtrim($post, '&');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token');
        curl_setopt($curl, CURLOPT_POST, 4);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        $response = $result;
        
        $params = null;
        parse_str($response, $params);        
        
        $accesstoken = $params['access_token'];
        return $accesstoken;
    }
    
    /**
     * Function to fetch contacts from Google using accesstoken
     * 
     * @param string $accesstoken
     * @return array
     */
    protected function fetchGoogleContacts($accesstoken) {
        $contacts = array();
        $maxResults = 500;
        $contactsAPIUrl = 'https://www.google.com/m8/feeds/contacts/default/full';
        $url = $contactsAPIUrl . '?max-results=' . $maxResults . '&alt=json&v=3.0&oauth_token=' . $accesstoken;
        $jsonresponse = $this->curlFileGetContents($url);
        if ((strlen(stristr($jsonresponse, 'Authorization required')) > 0) && (strlen(stristr($jsonresponse, 'Error ')) > 0)) {
            $result = array(
                'error' => true,
				'message' => 'OOPS !! Something went wrong. Please try reloading the page.'
			);
		} else {
			$temp = json_decode($jsonresponse, true);
			$contactsEntry = $temp['feed']['entry'];
			if (!empty($contactsEntry)) {
				$contactEmails = array();
				foreach ($contactsEntry as $contact) {
					if (isset($contact['gd$email'])) {
						if (isset($contact['gd$name']['gd$fullName'])) {
							$name = $contact['gd$name']['gd$fullName']['$t'];
						} else {
							$name = '';
						}
						$email = $contact['gd$email'][0]['address'];
						$emailRegex = '/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/';
						if (preg_match($emailRegex, $email)) {
							if (!in_array($email, $contactEmails)) {
								$contactEmails[] = $email;
								$contacts[] = array(
									'name' => $name,
									'email' => $email
								);
							}
						}
					}
				}

				$result = array(
					'success' => true,
					'contacts' => $contacts
				);
			} else {
				$result = array(
                    'error' => true,
                    'message' => 'No contacts'
                );
            }
        }
        return $result;
    }

    /**
     * Function to fetch contacts from fb using accesstoken
     * 
     * @param string $accesstoken
     * @return array
     */
    protected function fetchfbContacts($accesstoken) {
        $contacts = array();
        $maxResults = 100;
        $contactsAPIUrl = 'https://graph.facebook.com/me/friends';
        $url = $contactsAPIUrl . '?access_token='. $accesstoken;
        $jsonresponse = $this->curlFileGetContents($url);
        

    }

    /**
     * Function to get the information about the contacts
     * 
     * @param array $contacts
     * @return array
     */
    public function getContactsInfo($contacts) {
        $data = array();
        $existingUsers = array();

        // prepare the list of emails of the contacts
        foreach ($contacts as $contact) {            
            if (isset($contact['email'])) {
            $contactEmails[] = $contact['email'];
            }
        }
        if(!empty($contactEmails)){
        $contactEmails = array_unique($contactEmails);
        // find existing users using the contact's email
        $existingUsers = $this->User->getUsersByEmail($contactEmails);
        }        

        // prepare the list of emails of existing users
		$existingEmails = array();
        if (!empty($existingUsers)) {
            foreach ($existingUsers as $existingUser) {
                $existingEmails[] = $existingUser['User']['email'];
            }
        }

        // separate out contacts who are new to our site
        $newUsers = array();
        foreach ($contacts as $contact) {
            if (isset($contact['email'])) {
            $contactEmail = $contact['email'];
            if (!in_array($contactEmail, $existingEmails)) {
                $newUsers[] = $contact;
            }
            }
        }

        // remove the existing users who are already in the friends list
        if (!empty($existingUsers)) {
            foreach ($existingUsers as $key => $existingUser) {
                $contactUserId = $existingUser['User']['id'];
                $contactFriendshipStatus = $this->MyFriends->getFriendStatus($this->currentUserId, $contactUserId);
                if ($contactFriendshipStatus !== 0) {
                    unset($existingUsers[$key]);
                }
            }
            $existingUsers = array_values($existingUsers);
        }
        $data['existingUsers'] = $existingUsers;
        $data['existingUsersCount'] = count($existingUsers);

        // remove the new users who are already invited
        if (!empty($newUsers)) {
            $currentUserId = $this->currentUserId;
            foreach ($newUsers as $key => $newUserEmail) {
                $isInvited = $this->InvitedUser->isInvitedUser($currentUserId, $newUserEmail);
                if ($isInvited) {
                    unset($newUsers[$key]);
                }
            }
            $newUsers = array_values($newUsers);
        }
        $data['newUsers'] = $newUsers;
        $data['newUsersCount'] = count($newUsers);

        // count the total number of contacts
        $data['contactsCount'] = $data['existingUsersCount'] + $data['newUsersCount'];

        return $data;
    }

     /**
     * Function to change the csv array into our format array
     * 
     * @param array $contacts
     * @return array
     */
     public function formatCSVContactInfo($contacts) {

        $contacts = array_map(function($contact) {
                    if (array_key_exists('E-mail 1 - Value', $contact)) { //Google 
                        
                        if (!isset($contact['Name'])){
                            $emailSlice = explode("@", $contact['E-mail 1 - Value']);
                            $username = $emailSlice[0];
                            $name = $username;
                        }
                        else {
                           $name = $contact['Name']; 
                        }
                        
                        return array(                            
                            'name' => $name,                            
                            'email' => trim($contact['E-mail 1 - Value'])
                        );
                    }
                    else if (array_key_exists('E-mail Address', $contact)) { //Outlook  
                        if (isset($contact['First Name']) && (isset($contact['Last Name'])) ){
                            $name = $contact['First Name'].' '.$contact['Last Name'];                            
                        }                        
                        else {
                            $emailSlice = explode("@", $contact['E-mail Address']);
                            $username = $emailSlice[0];
                            $name = $username;
                        }
                        
                        return array(
                            'name' => $name,
                            'email' => trim($contact['E-mail Address'])
                        ); 
                    }
                    else if (array_key_exists('Email', $contact)) { //Yahoo   
                        if (isset($contact['First']) && (isset($contact['Last'])) ){
                            $name = $contact['First'].' '.$contact['Last'];                            
                        }                        
                        else {
                            $emailSlice = explode("@", $contact['Email']);
                            $username = $emailSlice[0];
                            $name = $username;
                        }
                        
                        return array(
                            'name' => $name,
                            'email' => trim($contact['Email'])
                        ); 
                    }
//                    else { //return the array as such
//                        return $contact;
//                    }
                }, $contacts);
        return $contacts;
    }
    
    /**
     * Dummy contacts
     * 
     * @return array
     */
    public function getDummyContacts() {
        $contacts = array
            (array
                (
                'name' => 'sugunan',
                'email' => 'greeshma+123@qburst.com'
            ),
            array
                (
                'name' => 'ajay',
                'email' => 'greeshma+456@qburst.com'
            ),
            array
                (
                'name' => 'greeshma',
                'email' => 'greeshma@qburst.com'
            ),
            array
                (
                'name' => 'varun',
                'email' => 'greeshma+789@qburst.com'
            ),
            array
                (
                'name' => 'roshan',
                'email' => 'greeshma+101@qburst.com'
            ),
            array
                (
                'name' => '',
                'email' => 'greeshma+29@qburst.com'
            ),
            array
                (
                'name' => 'greeshma15',
                'email' => 'greeshma+15@qburst.com'
            ),
            array
                (
                'name' => 'greeshma11',
                'email' => 'greeshma11'
            ),
            array
                (
                'name' => '',
                'email' => 'greeshma+22@qburst.com'
            ),
            array
                (
                'name' => 'greeindia',
                'email' => 'greeshma+7@qburst.com'
            ),
            array
                (
                'name' => '',
                'email' => 'greeshma+2013@qburst.com'
            ),
            array
                (
                'name' => '',
                'email' => 'greeshma+222@qburst.com'
            ),
            array
                (
                'name' => 'greeshma4',
                'email' => 'greeshma+4@qburst.com'
            ),
            array
                (
                'name' => '',
                'email' => 'greeshma+25@qburst.com'
            ),
            array
                (
                'name' => 'otheruser',
                'email' => 'greeshmaa52@yahoo.com'
            ),
            array
                (
                'name' => 'test user 1',
                'email' => 'greeshma+101@qburst.com'
            ),
            array
                (
                'name' => 'test user 2',
                'email' => 'greeshma+102@qburst.com'
            ),
            array
                (
                'name' => 'test user 3',
                'email' => 'greeshma+103@qburst.com'
            ),
            array
                (
                'name' => 'test user 4',
                'email' => 'greeshma+104@qburst.com'
            ),
            array
                (
                'name' => 'test user 5',
                'email' => 'greeshma+105@qburst.com'
            ),
            array
                (
                'name' => 'test user 6',
                'email' => 'greeshma+106@qburst.com'
            ),
            array
                (
                'name' => 'test user 7',
                'email' => 'greeshma+107@qburst.com'
            ),
            array
                (
                'name' => 'test user 8',
                'email' => 'greeshma+108@qburst.com'
            ),
            array
                (
                'name' => 'test user 9',
                'email' => 'greeshma+109@qburst.com'
            ),
            array
                (
                'name' => 'test user 10',
                'email' => 'greeshma+101@qburst.com'
            ),
            array
                (
                'name' => 'test user 11',
                'email' => 'greeshma+101@qburst.com'
            ),
            array
                (
                'name' => 'test user 12',
                'email' => 'greeshma+102@qburst.com'
            ),
        );
        $result = array('success' => true, 'contacts' => $contacts);
        return $result;
    }
}
<?php

/**
 * PostingComponent class file.
 * 
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('Email', 'Model');

class EmailQueueComponent extends Component {

    /**
     * Constructor
     * 
     * Initialises the models
     */
    public function __construct() {
        $this->Email = ClassRegistry::init('Email');
    }

    /**
     * Creates a Email Queue
     * 
     * @return boolean
     */
    public function createEmailQueue($emailData) {

        $Email = new CakeEmail();
        $fromArray = $Email->config('mailServerSettings')->from();//get cakephp email settings
        
        if (!(array_key_exists('from_email', $emailData)) || empty($emailData['from_email'])) {
            $emailData['from_email'] = key($fromArray);
        }
        if (!(array_key_exists('from_name', $emailData)) || empty($emailData['from_name'])) {
            $emailData['from_name'] = current($fromArray);
        }
        if (!(array_key_exists('priority', $emailData)) || empty($emailData['priority'])) {
            $emailData['priority'] = Email::DEFAULT_SEND_PRIORITY;
        }
	if (!(array_key_exists('instance_id', $emailData)) || empty($emailData['instance_id'])) {
            $emailData['instance_id'] = '';
        }
        
        $this->Email->create();
        if ($this->Email->save($emailData, false)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
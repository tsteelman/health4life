<?php

App::uses('AppModel', 'Model');

/**
 * NewsletterTemplate Model
 *
 */
class NewsletterTemplate extends AppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'template_name';
    
    public $validate = array(
        'template_name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Newsletter template name is required'
            )
        ),
        'template_body' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Newsletter template is required'
            )
        )
    );
    
    /**
     * Function to get newsletter template content
     * @param unknown $id
     */
	public function getNewsLetterTemplate($id) {
		if ($id) {
			$newsletterTemplate = $this->findById ( $id );
			if ($newsletterTemplate) {
				return ($newsletterTemplate ['NewsletterTemplate'] ['template_body']);
			}
		}
	}
}

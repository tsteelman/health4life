<?php

App::uses('AppModel', 'Model');

/**
 * NewsletterTemplate Model
 *
 */
class Newsletter extends AppModel {

	var $tags = array (
			'|@username@|',
			'|@email-address@|',			
			'|@user-photo@|',			
			'|@site-url@|',
			'|@site-name@|',
			'|@site-about-link@|',
			'|@site-terms-of-service-link@|',
			'|@site-contact-link@|',
			'|@site-faq-link@|',
			'|@site-login-link@|',
			'|@site-register-link@|',
			'|@site-forgot-password-link@|',
			'|@unsubscribe-link@|'
	)
	;
	
	var $insertContents = array (
			'getLastFiveCommunityHtml' => 'Latest 5 Communities' 
	);
	
    /**
     * Display field
     *
     * @var string
     */
  public $validate = array(
        'subject' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Newsletter subject is required'
            )
        ),
        'content' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Newsletter content is required'
            )
        )
    );

  
  function rewriteVariable($HtmlData = NULL, $search = array(), $replace = array()){
  	if($HtmlData){
  		$HtmlData = str_replace($search, $replace, $HtmlData);
  	}
  	
  	return $HtmlData;
  }
}

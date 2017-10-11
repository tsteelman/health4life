<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 *  If the coming soon variable is enabled
 *  Then show the coming soon page
 */
    if ( Configure::read('COMING_SOON') ) {
                Router::connect('/*', array('controller' => 'pages', 'action' => 'comingSoon', 'coming_soon'));
    }
    
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
        
        

/**
 * Custom routes
 */
 /*   Commenting out the disease page checking
    //Domain names for diseases    
    $domains = array(
        'diabetes-4life.com' => 5,
        'aids4life.com' => 27,
        'rheumatoidarthritis4life.com' => 2982,
        'RSD4life.com' => 2368,
        'Scleroderma4life.com' => 1834,
        'arthritis4life' => 1036
    );
    
    //Get current domain name.
    $domain_name = $_SERVER['HTTP_HOST'];
   
    //Check if the domain name entered is a disease domain
    if(key_exists($domain_name, $domains)) {
        $disease_id = $domains[$domain_name];
        //Redirect to disease page on entering disease domain.
        Router::redirect('/**', Configure::read('App.fullBaseUrl') . '/condition/index/' . $disease_id);
    }
*/
    Router::connect('/register', array('controller' => 'register', 'action' => 'index', 'plugin' => 'User'));
    Router::connect('/register/success', array('controller' => 'register', 'action' => 'success', 'plugin' => 'User'));
    Router::connect('/login', array('controller' => 'users', 'action' => 'login', 'plugin' => 'User'));
    Router::connect('/import', array('controller' => 'friends', 'action' => 'csvContactImport', 'plugin' => 'User'));
    Router::connect('/logout', array('controller' => 'users', 'action' => 'logout', 'plugin' => 'User'));
    Router::connect('/event/edit/*', array('controller' => 'edit', 'action' => 'index', 'plugin' => 'Event'));
    Router::connect('/community/edit/*', array('controller' => 'edit', 'action' => 'index', 'plugin' => 'Community'));
    Router::connect(
        '/community/:communityId/event/add',
         array('controller' => 'events', 'action' => 'add', 'plugin' => 'Community'),
         array('communityId' => '[0-9]+')
    );
    Router::connect(
            '/community/details/:communityId/*',
		    array('controller' => 'details', 'plugin' => 'Community'),
		    array('communityId' => '[0-9]+')
    );
     Router::connect(
            '/condition/',
		    array('controller' => 'diseases', 'plugin' => 'Disease')
    );    
     Router::connect(
            '/condition/index/:diseaseId/*',
		    array('controller' => 'diseases', 'plugin' => 'Disease'),
		    array('diseaseId' => '[0-9]+')
    );
     Router::connect(
            '/condition/events/:diseaseId/*',
		    array('controller' => 'diseases', 'plugin' => 'Disease', 'action' => 'getDiseaseEventList'),
		    array('diseaseId' => '[0-9]+')
    );

    Router::connect(
            '/condition/community/:diseaseId/*',
		    array('controller' => 'diseases', 'plugin' => 'Disease', 'action' => 'getDiseaseCommunityList'),
		    array('diseaseId' => '[0-9]+')
    );
//    Router::connect(
//            '/message/details/*',
//		    array('controller' => 'details', 'plugin' => 'Message')
//
//    );
    Router::connect('/dashboard', array('controller' => 'dashboard', 'action' => 'index', 'plugin' => 'User'));
	/*
	 * For User profile
	 */
	$usernameRegex = '[a-z0-9A-Z.!@\#\$%^&*()?~_-]+';
        $dateRegex = '(19|20)\d\d[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])$';
	$profileSections = '(photo|video|blog|friends|communities|events|myhealth|mysymptom|mylibrary|videochat|mycondition|mynutrition|myteam|therapies|charts|paintracker|healthtracker|tracker|following)';
	Router::connect(
			'/profile',
			array('controller' => 'profile', 'action' => 'index', 'plugin' => 'user')
	);
	
	Router::connect(
			'/profile/mylibrary',
			array('action' => 'index', 'plugin' => 'user','controller' => 'mylibrary')
	);
	
	Router::connect(
			'/profile/:controller',
			array('action' => 'index', 'plugin' => 'User'),
			array('controller' => $profileSections)
	);
	Router::connect(
			'/profile/:username',
			array('controller' => 'profile', 'action' => 'index', 'plugin' => 'user'),
			array(
				'username' => $usernameRegex,
				'pass' => array('username')
			)
	);
	Router::connect(
			'/profile/:username/hovercard',
			array('controller' => 'profile', 'action' => 'hovercard', 'plugin' => 'User'),
			array('username' => $usernameRegex)
	);
	Router::connect(
			'/profile/:username/:controller',
			array('action' => 'index', 'plugin' => 'User'),
			array(
				'username' => $usernameRegex,
				'controller' => $profileSections
			)
	);
        Router::connect(
			'/mysymptom/:symptomid',
			array('action' => 'detail', 'plugin' => 'User', 'controller' => 'mysymptom'),
			array('symptomid' => '[0-9]+')
	);
        Router::connect(
			'/mysymptom/:username/:symptomid',
			array('action' => 'detail', 'plugin' => 'User', 'controller' => 'mysymptom'),
			array('symptomid' => '[0-9]+')
	);
        Router::connect(
			'/symptom/list',
			array('action' => 'showLatestAddedSymptom', 'plugin' => 'User', 'controller' => 'mysymptom')
	);
        Router::connect(
			'/symptom/history/list',
			array('action' => 'filterSymptomHistory', 'plugin' => 'User', 'controller' => 'mysymptom')
	);
        Router::connect(
			'/symptom/delete',
			array('action' => 'deleteUserSymptom', 'plugin' => 'User', 'controller' => 'mysymptom')
                        
        );
        Router::connect(
			'/mysymptom/delete/:usersymptomid/:timestamp',
			array('action' => 'deleteUserHistory', 'plugin' => 'User', 'controller' => 'mysymptom'),
                        array('usersymptomid' => '[0-9]+')
	);
	Router::connect('/message', array('controller' => 'messages', 'action' => 'index', 'plugin' => 'Message'));
        Router::connect('/forgot_password', array('controller' => 'forgotPassword', 'action' => 'index', 'plugin' => 'User'));
        Router::connect('/calendar/:date',
                array('controller' => 'calendar', 'action' => 'index', 'plugin' => 'Calendar'),
                array('date' => $dateRegex));
     
        Router::connect(
            '/survey/index/:surveyKey/*',
		    array('controller' => 'survey', 'plugin' => 'Survey'),
		    array('surveyKey' => '[0-9a-zA-Z]+')
        );
        
     
        Router::connect(
            '/healthrecord',
		    array('controller' => 'HealthRecord', 'plugin' => 'HealthRecord', 'action' => 'index')
        );
         Router::connect(
			'/profile/myhealth/painMarkedImage',
			array('action' => 'painMarkedImage', 'plugin' => 'User', 'controller' => 'myhealth')
	);
         
        Router::connect(
            '/myteam',
            array('controller' => 'MyTeam', 'plugin' => 'MyTeam', 'action' => 'index')
        );
        
        Router::connect(
            '/myteam/:teamId',
            array('action'=>'index','controller' => 'home', 'plugin' => 'MyTeam'),
			array('teamId' => '[0-9]+')
        );
		
        Router::connect(
            '/myteam/api/:action',
            array('controller' => 'Api', 'plugin' => 'MyTeam')
		);
		
        Router::connect(
            '/myteam/:teamId/:controller/*',
            array('controller' => 'MyTeam', 'plugin' => 'MyTeam'),
            array(
                'teamId' => '[0-9]+',
                'controller' => '[a-z]+'
            )                
        );  
		
        Router::connect(
            '/myteam/create/*',
            array('controller' => 'Create', 'plugin' => 'MyTeam', 'action' => 'index')
        );

        Router::connect(
            '/myteam/:action/*',
            array('controller' => 'MyTeam', 'plugin' => 'MyTeam')
        );

        Router::connect(
            '/tracker/history/list',
            array('action' => 'filterTrackerHistory', 'plugin' => 'User', 'controller' => 'manageTracker')
        );
		
		Router::connect(
            '/healthinfo/print',
            array('action' => 'printGraph', 'plugin' => 'User', 'controller' => 'print')
        );
		
        Router::connect(
            '/healthTracker/delete/:recordType/:timestamp',
            array('action' => 'deleteTrackerHistory', 'plugin' => 'User', 'controller' => 'manageTracker'),
            array('recordType' => '[0-9]+')
        );
        
        Router::connect(
            '/scheduler',
            array('plugin' => 'User', 'controller' => 'Scheduler')
        ); 
         Router::connect(
            '/hashtag',
		    array('controller' => 'hashtags', 'plugin' => 'Hashtag')
        ); 
		Router::connect(
			'/listForumQuestions/:diseaseId/*',
			array('controller' => 'diseases', 'plugin' => 'Disease','action'=>'listForumQuestions'),
			array('diseaseId' => '[0-9]+')
		);
		Router::connect(
            '/unsubscribe',
		    array('controller' => 'EmailSettings', 'plugin' => 'User', 'action' => 'unSubscribe' )
        ); 
	
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
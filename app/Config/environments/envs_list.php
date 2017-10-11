<?php

// ***********************************************************************************************************************************************
// CakePHP Environment
// Based on http://www.developpez.net/forums/d834850/php/bibliotheques-frameworks/cakephp/configurer-plusieurs-environnements-application-cakephp/
// Licensed under The MIT License
// ***********************************************************************************************************************************************
// This file defines your environments keywords
// -----------------------------------------------------------------------------------------------------------------------------------------------
Environment::configure('development', array('server' => 'development'), array(
    'debug' => 2,
    'security' => 'low',
    'API' => array(
        'Vimeo' => array(
            'CONSUMER_KEY' => '54b29435e7b08b28d93d24244226a9798a5dadf0',
            'CONSUMER_SECRET' => '35a905b4a8d5381f8e039fb2c0259d3bc26db64b',
            'ACCESS_TOKEN' => 'bc277d0f415a84d8b67176c583ed5d95',
            'ACCESS_TOKEN_SECRET' => '65c198f7bec48c428bb121703f38b368b5474eab'
        ),
        'Google' => array(
            'CLIENT_ID' => '184116916985-4a6qccgal08c53446290cn2htpqdib5l.apps.googleusercontent.com',
            'SECRET_KEY' => 'wxFbejRK3X1IfEJ7syDclTpf',
            'REDIRECT_URL' => 'http://patient4life.local:8085/user/friends/inviteGoogleContacts'
        ),
        'Facebook' => array(
            'APP_ID' => '268720493293501',
            'APP_SECRET' => 'f03da3f61e5cc08b05b6b36d9d344733',
            'REDIRECT_URL' => 'http://patient4life.local:8085/user/friends/invitefbContacts'
        ),
		'SmartyStreets' => array(
			'AUTH_ID' => 'e716ee1f-6bd9-4f9f-a258-b98aeb97df82',
			'AUTH_TOKEN' => 'GZpL7DR1SeP6gv9D4aAV'
		)
    ),
    
    'SOCKET' => array ('URL' => 'http://10.4.0.74:8000')
    
    )
);

Environment::configure('qa', array('server' => 'qa'), array(
    'debug' => 0,
    'security' => 'low',
    'API' => array(
        'Vimeo' => array(
            'CONSUMER_KEY' => '54b29435e7b08b28d93d24244226a9798a5dadf0',
            'CONSUMER_SECRET' => '35a905b4a8d5381f8e039fb2c0259d3bc26db64b',
            'ACCESS_TOKEN' => 'bc277d0f415a84d8b67176c583ed5d95',
            'ACCESS_TOKEN_SECRET' => '65c198f7bec48c428bb121703f38b368b5474eab'
        ),
        'Google' => array(
            'CLIENT_ID' => '184116916985-4a6qccgal08c53446290cn2htpqdib5l.apps.googleusercontent.com',
            'SECRET_KEY' => 'wxFbejRK3X1IfEJ7syDclTpf',
            'REDIRECT_URL' => 'http://qa.patients4life.qburst.com/user/friends/inviteGoogleContacts'
        ),
        'Facebook' => array(
            'APP_ID' => '613722332029225',
            'APP_SECRET' => '698540277fe7a10895c67f72748c18ae'
        ),
		'SmartyStreets' => array(
			'AUTH_ID' => 'e716ee1f-6bd9-4f9f-a258-b98aeb97df82',
			'AUTH_TOKEN' => 'GZpL7DR1SeP6gv9D4aAV'
		)
    ),
    
    'SOCKET' => array ('URL' => 'http://qa.patients4life.qburst.com:7789')
   )
        
    
);

Environment::configure('staging', array('server' => 'staging'), array(
    'debug' => 0,
    'security' => 'low',
    'API' => array(
        'Vimeo' => array(
            'CONSUMER_KEY' => '54b29435e7b08b28d93d24244226a9798a5dadf0',
            'CONSUMER_SECRET' => '35a905b4a8d5381f8e039fb2c0259d3bc26db64b',
            'ACCESS_TOKEN' => 'bc277d0f415a84d8b67176c583ed5d95',
            'ACCESS_TOKEN_SECRET' => '65c198f7bec48c428bb121703f38b368b5474eab'
        ),
        'Google' => array(
            'CLIENT_ID' => '184116916985-4a6qccgal08c53446290cn2htpqdib5l.apps.googleusercontent.com',
            'SECRET_KEY' => 'wxFbejRK3X1IfEJ7syDclTpf',
            'REDIRECT_URL' => 'http://patients4life.qburst.com/user/friends/inviteGoogleContacts'
        ),
        'Facebook' => array(
            'APP_ID' => '',
            'APP_SECRET' => ''
        ),
		'SmartyStreets' => array(
			'AUTH_ID' => 'e716ee1f-6bd9-4f9f-a258-b98aeb97df82',
			'AUTH_TOKEN' => 'GZpL7DR1SeP6gv9D4aAV'
		)
    ),
    
    'SOCKET' => array ('URL' => 'http://patients4life.qburst.com:7790')
   )
);
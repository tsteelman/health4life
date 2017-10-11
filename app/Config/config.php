<?php

$config = array(
	'App.fullBaseUrl' => 'http://10.4.0.74:81',
        'App.pmrUrl' => 'http://pmr.qburst.com/dashboard',    
	'App.name' => 'Health4life',
	'App.address' => '701 Brazos Street Suite 1601,<br/>Austin, TX 78701',
	'App.email' => 'health4life.com',
	'App.logo' => 'logo_h4l.png',
	'App.favicon' => 'favicon_p4l.png',    
	'App.newsletterLogo' => 'logo_h4l.png',
	'App.onlineTimeout' => 120,
	'App.imageExtensions' => array('jpg', 'jpeg', 'png', 'gif'),
	'App.signUpVideo' => '//www.youtube.com/watch?v=wyI7mtSFLdo',
	'App.emailSettings' => array(
		'smtp' => array(
			'from_email' => 'p4l.qburst@gmail.com',
			'host' => 'ssl://email-smtp.us-east-1.amazonaws.com',
			'username' => 'AKIAJSY3G53R25JEY6UA',
			'password' => 'AuqqGSSj3ycvdGR4P11G4Ezcgmfjs6tneaxnr6ldVLUY'
		),
		'mailServerSettings' => array(
			'username' => 'p4l.qburst@gmail.com',
			'password' => 'p4l.qburst123$',
			'from_email' => 'admin@health4life.com',
		)
	),
	'App.fbLink' => '//www.facebook.com/pages/Health4Life/657993407633006',
	'App.twitterLink' => '//twitter.com/Health4Life_H4L',
	'App.googleLink' => 'https://plus.google.com/b/100872745064596774144/100872745064596774144/posts',
	'App.linkedInLink' => 'http://linkedin.com',
	'App.youtubeLink' => 'http://youtube.com',
	'Url.forgotPassword' => '/forgot_password',
	'Url.register' => '/register',
	'Url.registerSuccess' => 'register/success',
	'Url.login' => 'login',
	'Url.logout' => 'logout',
	'Url.registerSuccess' => 'register/success',
	'Url.condition' => '/condition/',
        'Url.health' => 'myhealth' ,
	'App.comingSoon' => false
);
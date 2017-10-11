<?php

Router::connect('/admin', array(
	'controller' => 'users',
	'action' => 'login',
	'plugin' => 'admin'
		)
);
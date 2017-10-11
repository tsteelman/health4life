<?php
/**
 *  Get Cake's root directory
 */
define('APP_DIR', 'app');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(dirname(dirname(__FILE__)))));
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS);


/**
 * This only needs to be changed if the "cake" directory is located
 * outside of the distributed structure.
 * Full path to the directory containing "cake". Do not add trailing directory separator
 */

if (!defined('CAKE_CORE_INCLUDE_PATH')) {
	define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'lib');
}
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}


if (!defined('CAKE_CORE_INCLUDE_PATH')) {    
	if (function_exists('ini_set')) {            
		ini_set('include_path', ROOT . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
	}
	if (!include ('Cake' . DS . 'bootstrap.php')) {
		$failed = true;
	}        
} else {    
	if (!include (CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php')) {
		$failed = true;
	}
}
if (!empty($failed)) {
	trigger_error("CakePHP core could not be found. Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php. It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}
App::import('Core','Session');
if(App::import('Core','Session')) { 
   $session = new CakeSession(); 
   $session->start();    
} 
App::uses('CakeSession', 'Model/Datasource');
$value = CakeSession::read();

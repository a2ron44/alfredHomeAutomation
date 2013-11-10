<?php

define('PS', PATH_SEPARATOR);
define('DS', DIRECTORY_SEPARATOR);

// Define path to application directory
defined('APPLICATION_BASE')
|| define('APPLICATION_BASE', realpath(dirname(__FILE__)));


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', determine_env());

// Ensure library/ is on include_path

set_include_path(implode(PS, array(
		realpath(APPLICATION_BASE .  DS . 'library'),
		realpath(APPLICATION_BASE .  DS . 'application'),
		get_include_path(),
)));

date_default_timezone_set('US/Eastern');

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . DS . 'configs' . DS . 'application.xml'
);
Zend_Registry::set('config', new Zend_Config_Xml(APPLICATION_PATH . DS . 'configs' . DS . 'application.xml', APPLICATION_ENV));
$application->bootstrap()
            ->run();

/**
 * Can set application_env in httpd.conf 
 */
function determine_env() {
	if (!empty($_SERVER['APPLICATION_ENV'])) {
		return $_SERVER['APPLICATION_ENV'];
	}
	if ('localhost' == $_SERVER ['HTTP_HOST']) {
		return 'staging';
	}
	if (preg_match ( '/^test./i', $_SERVER ['HTTP_HOST'] )) {
		return 'testing';
	}
	return 'production';
}
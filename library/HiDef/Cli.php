#!/usr/bin/php
<?php
/**
 * Command-line interface script
 *
 * Provides a mechanism for execution of arbitrary controller actions via
 * the command-line interface (CLI). It should be executed as follows:
 * 
 * Cli.php -h
 *
 * for further help.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */

// Any custom namespaces to load. Note that the "HiDef" namespace will get loaded automatically
$namespaces = array('');

/* ********** DON'T CHANGE ANYTHING BELOW THIS LINE ********* */

ini_set('display_errors', true);
error_reporting(E_ALL);

// Set correct include path for ZF. This assumes that this file exists in /bin/
set_include_path('.' . PATH_SEPARATOR
	. dirname(__FILE__) . '/../library/' . PATH_SEPARATOR
	. dirname(__FILE__) . '/../application/' . PATH_SEPARATOR
	. get_include_path());

date_default_timezone_set('UTC');

// Set up some constants for later discovery, in particular CLI_EXECUTION
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(__DIR__) . '/../application/');
defined('CLI_EXECUTION') || define('CLI_EXECUTION', true);

// Get and set up the Zend autoloader
require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();
$namespaces[] = 'HiDef';
foreach ($namespaces as $namespace) {
	$loader->registerNamespace($namespace);
}

// Parse command line options
$options = new Zend_Console_Getopt(
	array(
		'help|h' => 'Displays usage information.',
		'action|a=s' => 'Action to perform in format of module/controller/action/param1/param1value/param2/param2value/param3/param3value',
		'env|e-s' => 'Defines application environment (defaults to "development")',
	)
);

try {
	$options->parse();
}
catch (Zend_Console_Getopt_Exception $e) {
	echo $e->getUsageMessage();
	return false;
}

if ($options->getOption('h') || !$options->getOption('a')) {
	echo $options->getUsageMessage();
	return true;
}

$env = $options->getOption('e');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', ($env === null) ? 'development' : $env);

// Instantiate a new application instance
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');

// Get the front controller
$front = $application->getBootstrap()
	->bootstrap('frontController')
	->getResource('frontController');

// Parse module, controller, action
@list($module, $controller, $action) = explode('/', $options->getOption('a'));

// Parse params and create an associative array from the result
$rawparams = array_slice(explode('/', $options->getOption('a')), 3);
$params = array();
for ($i = 0; $i < count($rawparams); $i = $i + 2) {
	$params[$rawparams[$i]] = $rawparams[$i+1];
}

$request = new Zend_Controller_Request_Simple($action, $controller, $module, $params);

// Turn off the view renderer and layout
$front->setRequest($request)
	->setResponse(new Zend_Controller_Response_Cli())
	->setRouter(new HiDef_Controller_Router_Cli())
	->setParam('noViewRenderer', true)
	->throwExceptions(true);

Zend_Layout::startMvc();
$layout = Zend_Layout::getMvcInstance();
$layout->disableLayout();

// Run!
$application->bootstrap()
	->run();

?>
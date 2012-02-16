<?php
/**
 * Custom router for CLI
 *
 * Provides a mechanism for custom/dummy routing when executing the application
 * via command-line interface.
 *
 * LICENSE
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Controller
 * @subpackage	Router
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
/**
 * This class is used simply as a dummy class to provide no routing to the front
 * controller for CLI operations. Each method is intended to be empty.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Controller
 * @subpackage	Router
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
class HiDef_Controller_Router_Cli extends Zend_Controller_Router_Abstract
	implements Zend_Controller_Router_Interface
{
	// Intentionally empty as the CLI script sets up the Zend_Controller_Request_Abstract object anyway
	public function route(Zend_Controller_Request_Abstract $request) {}
    
	public function assemble($userParams, $name = null, $reset = false, $encode = true) {}
	public function getFrontController() {}
	public function setFrontController(Zend_Controller_Front $controller) {}
	public function setParam($name, $value) {}
	public function setParams(array $params) {}
	public function getParam($name) {}
	public function getParams() {}
	public function clearParams($name = null) {}
	public function addConfig() {}
}

?>
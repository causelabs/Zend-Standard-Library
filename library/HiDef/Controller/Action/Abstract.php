<?php
/**
 * Provides several convenient action controller utility methods
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Controller
 * @subpackage	Action
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
/**
 * Provides several convenient action controller utility methods
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Controller
 * @subpackage	Action
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
class HiDef_Controller_Action_Abstract extends Zend_Controller_Action
{
	/**
	 * Redirect to another URL or named route
	 *
	 * Proxies to {@link Zend_Controller_Action_Helper_Redirector::gotoUrl()} or
	 * potentially {@link Zend_Controller_Action_Helper_Redirector::gotoRoute()}.
	 *
	 * @param mixed $url If string, the URL to redirect to; if array, then the options
	 * 	for the named route
	 * @param mixed $options If array, then the options to be used when redirecting;
	 * 	if string, the name of the route to redirect to
	 * @return void
	 */
	protected function _redirect($url, $options = array())
	{
		if (is_array($url) && is_string($options)) {
			$this->_helper->redirector->gotoRoute($url, $options);
		}
		else {
			parent::_redirect($url, $options);
		}
	}
}

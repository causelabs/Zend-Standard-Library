<?php

class HiDef_View_Helper_ControllerName extends Zend_View_Helper_Abstract
{
	public function init() {}
	
	public function controllerName()
	{
		return Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
	}
}
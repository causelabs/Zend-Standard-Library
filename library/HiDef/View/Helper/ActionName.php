<?php

class HiDef_View_Helper_ActionName extends Zend_View_Helper_Abstract
{
	public function init() {}
	
	public function actionName()
	{
		return Zend_Controller_Front::getInstance()->getRequest()->getActionName();
	}
}
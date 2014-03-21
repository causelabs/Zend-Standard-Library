<?php

class HiDef_View_Helper_JsonEncode extends Zend_View_Helper_Abstract
{
	public function init() {}
	
	public function jsonEncode($object)
	{
		return Zend_Json::encode($object->toJson());
	}
}
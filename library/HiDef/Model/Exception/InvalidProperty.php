<?php
/**
 * Invalid property exception
 *
 * Thrown when trying to access an invalid property on a class extended
 * from {@link HiDef_Model_Abstract}.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Model
 * @subpackage	Exception
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
/**
 * Invalid property exception
 *
 * Thrown when trying to access an invalid property on a class extended
 * from {@link HiDef_Model_Abstract}.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Model
 * @subpackage	Exception
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
class HiDef_Model_Exception_InvalidProperty extends Exception
{
	/**
	 * Default constructor
	 * 
	 * @param string $property Property name
	 * @param string $class    Class name
	 */
	public function __construct($property, $class)
	{
		$this->message = _('Invalid property "'.$property.'" on model class "'.$class.'"');
	}
}

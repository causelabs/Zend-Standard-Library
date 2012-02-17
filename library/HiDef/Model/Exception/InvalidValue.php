<?php
/**
 * Invalid property value exception
 *
 * Thrown when trying to set a property on a class extended
 * from {@link HiDef_Model_Abstract} with an invalid value.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Model
 * @subpackage	Exception
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
/**
 * Invalid property value exception
 *
 * Thrown when trying to set a property on a class extended
 * from {@link HiDef_Model_Abstract} with an invalid value.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Model
 * @subpackage	Exception
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
class HiDef_Model_Exception_InvalidValue extends Exception
{
	/**
	 * Default constructor
	 * 
	 * @param string $property Property name
	 * @param string $value    Value
	 */
	public function __construct($parameter, $value)
	{
		$this->message = _('Invalid value "'.$value.'" on parameter "'.$parameter.'"');
	}
}

?>

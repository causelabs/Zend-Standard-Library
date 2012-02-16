<?php
/**
 * Provides an interface for serializing to JSON
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Model
 * @subpackage	Interface
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
/**
 * Provides an interface for serializing to JSON
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_Model
 * @subpackage	Interface
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
interface HiDef_Model_Interface_SerializableAsJson
{
	/**
	 * Outputs the object as valid JSON
	 * 
	 * @return string
	 */
	public function toJson();
}

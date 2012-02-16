<?php
/**
 * Custom hostname validation
 *
 * Provides a workaround for development or offline access when running under
 * MAMP for Mac OS 10.6.x. See http://bugs.php.net/bug.php?id=49267 for a
 * more detailed explanation.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef
 * @subpackage	Validate
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
/**
 * This class is used primarily for calls to Zend_Validate_EmailAddress, since
 * that validator includes an instance of Zend_Validate_Hostname to validate
 * hostname portion of the email address. It simply defaults to 'true' if the
 * application's environment variable APPLICATION_ENV is set to either 'offline'
 * or 'development' and the web server is running on a port other than 80.
 * Otherwise it, simply passes validation through to Zend_Validate_Hostname.
 *
 * @see			Zend_Validate_EmailAddress
 * @see			Zend_Validate_Hostname
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef
 * @subpackage	Validate
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
class HiDef_Validate_Hostname extends Zend_Validate_Hostname
{
	/**
	 * Validates that the given value is valid
	 * @param  string  $value The hostname to validate
	 * @return boolean
	 */
	public function isValid($value)
	{
		if (in_array(APPLICATION_ENV, array('offline', 'development')) && $_SERVER['SERVER_PORT'] != 80) {
			return true;
		}
		else {
			return parent::isValid($value);
		}
	}
}

?>

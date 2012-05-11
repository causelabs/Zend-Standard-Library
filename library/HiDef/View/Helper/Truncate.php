<?php

/**
 * String truncation view helper
 *
 * Provides a mechanism for truncating a string to a fixed length with an optional
 * trailing string appended
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_View
 * @subpackage	Helper
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Shane Stillwell <shane@hidef.co>
 * @version		$Id:$
 */
 /**
 * View helper to truncate a string to a fixed length with an optional
 * trailing string appended
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_View
 * @subpackage	Helper
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Shane Stillwell <shane@hidef.co>
 * @version		$Id:$
 */
class HiDef_View_Helper_Truncate
{
	/**
	 * Truncates a string to a provided length with an optional trailing
	 * string appended
	 *
	 * @param string $string String to truncate
	 * @param integer $length Length to truncate to
	 * @param string $postfix optional Trailing string to append to truncated string
	 *
	 * @return string Truncated string
	 */
	public function truncate($string, $length = 100, $postfix = '...')
	{
		if (strlen($string) > $length) {
			$string = wordwrap($string, $length);
			$string = substr($string, 0, strpos($string, "\n"));
			$string .= $postfix;
		}

		return $string;
	}
}
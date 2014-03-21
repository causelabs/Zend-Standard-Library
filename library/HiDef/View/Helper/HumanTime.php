<?php
/**
 * Human time view helper
 *
 * Provides a quick-and-dirty means to achieve a "time since" label for a
 * given DateTime instance
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_View
 * @subpackage	Helper
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
 /**
 * View helper to provides a quick-and-dirty means to achieve a "time since"
 * label for a given DateTime instance
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef_View
 * @subpackage	Helper
 * @copyright	Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */
class HiDef_View_Helper_HumanTime extends Zend_View_Helper_Abstract
{
	public function init() {}

	/**
	 * Returns a string in the format "x y ago", where x is an integer and y
	 * is a token of time, e.g. "seconds", "minutes", "hours", "days", etc.
	 *
	 * @param \DateTime $time Date and time to calculate from now
	 *
	 * @return string
	 */
	public function humanTime(\DateTime $time)
	{
		$time = time() - $time->getTimestamp(); // to get the time since that moment

		$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);

		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
		}
	}
}
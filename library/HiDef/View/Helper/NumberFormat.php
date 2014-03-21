<?php

class HiDef_View_Helper_NumberFormat extends Zend_View_Helper_Abstract
{
	public function init() {}

	public function numberFormat($number, $decimals = 0)
	{
		if ($number > 999999) {
			return number_format($number / 1000000, $decimals) . 'm';
		}
		if ($number > 99999) {
			return number_format($number / 1000, $decimals) . 'k';
		}
		else {
			return number_format($number);
		}
	}
}

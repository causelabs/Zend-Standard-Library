<?php

interface HiDef_View_Helper_AddressFormatterInterface {
	public function getAddress1String();
	public function getAddress2String();
	public function getCityString();
	public function getStateString();
	public function getPostalCodeString();
	public function getCountryString();
}

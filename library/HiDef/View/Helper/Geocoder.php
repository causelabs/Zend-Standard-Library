<?php

class HiDef_View_Helper_Geocoder extends Zend_View_Helper_Abstract
{
	const GEOCODE_URL = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address=';

	public function init() {}

	public function geocoder($address, $city, $state, $postalCode, $country = 'US')
	{
		if (strpos($state, ':') !== false) {
			$state = substr($state, strpos($state, ':') + 1);
		}

		$formatted = urlencode($address.' '.$city.' '.$state.' '.$postalCode);

		$json = json_decode(file_get_contents(self::GEOCODE_URL.$formatted));

		return array(
			'latitude' => $json->results[0]->geometry->location->lat,
			'longitude' => $json->results[0]->geometry->location->lng,
		);
	}
}

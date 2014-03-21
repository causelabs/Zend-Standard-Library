<?php

class HiDef_Date_DateTimeZone {

	/**
	 * Breaks out the timezone identifiers into a hierarchical list
	 * suitable for display in a dropdown menu
	 *
	 * @return array
	 */
	public static function listIdentifiersHierarchically()
	{
		$results = array();
		$zones = DateTimeZone::listIdentifiers();

		foreach ($zones as $zone) {
			$continent = $region = $city = '';

			$display = explode('/', $zone);
			$continent = $display[0];

			if (isset($display[1])) {
				$region = str_replace('_', ' ', $display[1]);
				if (isset($display[2])) {
					$city = str_replace('_', ' ', $display[2]);
				}

				$key = $continent . '/' . $display[1] . (isset($display[2]) ? '/' . $display[2] : '');
				$value = $region . ($city !== '' ? ' - ' . $city : '');

				$results[ $continent ][ $key ] = $value;
			}
			else {
				$results[ $continent ][ $continent ] = $continent;
			}
		}

		return $results;
	}
}

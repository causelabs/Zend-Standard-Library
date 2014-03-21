<?php

class HiDef_View_Helper_AddressFormatter extends Zend_View_Helper_Abstract
{
	public function init() {}

	public function addressFormatter(HiDef_View_Helper_AddressFormatterInterface $container)
	{
		$options = array(
			'address1' => $container->getAddress1String(),
			'address2' => $container->getAddress2String(),
			'city' => $container->getCityString(),
			'state' => $container->getStateString(),
			'postal_code' => $container->getPostalCodeString(),
			'country' => $container->getCountryString(),
		);

		$result = '';

		if (!$this->_empty($options['address1'])) {
			$result .= $options['address1'] . '<br>';
		}
		if (!$this->_empty($options['address2'])) {
			$result .= $options['address2'] . '<br>';
		}
		if (!$this->_empty($options['city'])) {
			$result .= $options['city'];

			if (!$this->_empty($options['state'])) {
				$result .= ', ' . $options['state'];
			}
		}
		elseif (!$this->_empty($options['state'])) {
			$result .= $options['state'];
		}
		if (!$this->_empty($options['postal_code'])) {
				$result .= ' ' . $options['postal_code'];
		}
		if (!$this->_empty($options['country'])) {
			$result .= '<br>' . $options['country'];
		}

		return $result;
	}

	private function _empty($value)
	{
		return ($value === '' || $value === null);
	}
}

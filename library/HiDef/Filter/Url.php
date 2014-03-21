<?php

class HiDef_Filter_Url implements Zend_Filter_Interface
{
	const PROTOCOL_HTTP = 'http';
	const PROTOCOL_HTTPS = 'https';

	protected $_protocol = self::PROTOCOL_HTTP;

	/**
	 * Sets filter options
	 *
	 * @param  string|array|Zend_Config $options
	 * @return void
	 */
	public function __construct($options = null)
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		else if (!is_array($options)) {
			$options = func_get_args();
			$temp['protocol'] = array_shift($options);
			$options = $temp;
		}

		if (array_key_exists('protocol', $options)) {
			$this->setProtocol($options['protocol']);
		}
	}

	/**
	 * Gets the current protocol
	 *
	 * @return string
	 */
	public function getProtocol()
	{
		return $this->_protocol;
	}

	/**
	 * Sets the protocol
	 *
	 * @param string
	 * @return HiDef_Filter_Url Provides a fluent interface
	 */
	public function setProtocol($protocol = null)
	{
		if ($protocol === null) {
			$protocol = self::PROTOCOL_HTTP;
		}
		$this->_protocol = (string) $protocol;
		return $this;
	}

	public function filter($value)
	{
		if (!preg_match('/^'.$this->getProtocol().'/', $value) && strlen($value) > 0) {
			return $this->getProtocol() . '://' . $value;
		}

		return $value;
	}
}

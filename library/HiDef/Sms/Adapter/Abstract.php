<?php
/**
 * Abstract class for SMS service adapters
 *
 * Sets up a basic template for common operations on SMS HTTP endpoints.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef
 * @subpackage	SMS_Adapter
 * @copyright	Copyright (c) 2013 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */

/**
 * Abstract class for SMS service adapters
 *
 * Sets up a basic template for common operations on SMS HTTP endpoints.
 *
 * @category	HiDef_ZendStandardLibrary
 * @package		HiDef
 * @subpackage	SMS_Adapter
 * @copyright	Copyright (c) 2013 HiDef, Inc. (http://www.hidef.co)
 * @author		Mark Horlbeck <mark@hidef.co>
 * @version		$Id:$
 */

abstract class HiDef_Sms_Adapter_Abstract
{
	protected $_rawRequestParameters = array();

	protected $_sender;
	protected $_recipients;
	protected $_message;
	protected $_timestamp;

    /**
     * Constructor.
     *
     * $config is an array of key/value pairs or an instance of Zend_Config
     * containing configuration options.  These options are common to most adapters:
     *
     * request_parameters => (array) The raw array of POST parameters received by the adapater
     *
     * @param  array|Zend_Config $config An array or instance of Zend_Config having configuration data
     * @throws HiDef_Sms_Adapter_Exception
	 */
	public function __construct($config)
	{
		/*
		 * Verify that adapter parameters are in an array.
		 */
		if (!is_array($config)) {
			/*
			 * Convert Zend_Config argument to a plain array.
			 */
			if ($config instanceof Zend_Config) {
				$config = $config->toArray();
			}
			else {
				/**
				 * @see HiDef_SMS_Adapter_Exception
				 */
				require_once 'HiDef/Sms/Adapter/Exception.php';
				throw new HiDef_Sms_Adapter_Exception('Adapter parameters must be in an array or a Zend_Config object');
			}
		}

		if (array_key_exists('request_parameters', $config)) {
			if (!empty($config['request_parameters'])) {
				$this->_rawRequestParameters = $config['request_parameters'];
			}
		}

		if (is_array($this->_rawRequestParameters)) {
			$this->parseRequestParameters();
		}
	}

	/**
	 * Gets request parameters
	 *
	 * @return string
	 */
	public function getRequestParameters()
	{
		return $this->_rawRequestParameters;
	}

	/**
	 * Sets request parameters for further processing
	 *
	 * @param array|null
	 * @return HiDef_Sms_Adapter_Abstract Provides a fluent interface
	 */
	public function setRequestParameters(array $params = null)
	{
		$this->_rawRequestParameters = $params;
		return $this;
	}

	/**
	 * Returns the sender
	 *
	 * @return string
	 */
	public function getSender()
	{
		return $this->_sender;
	}

	/**
	 * Sets the sender
	 *
	 * @return HiDef_Sms_Adapter_Abstract Provides a fluent interface
	 */
	protected function setSender($sender)
	{
		$this->_sender = (string) $sender;
		return $this;
	}

	/**
	 * Returns the recipients
	 *
	 * @return string
	 */
	public function getRecipients()
	{
		return $this->_recipients;
	}

	/**
	 * Returns the message
	 *
	 * @return string
	 */
	public function getMessage()
	{
		return $this->_message;
	}

	/**
	 * Sets the message
	 *
	 * @return HiDef_Sms_Adapter_Abstract Provides a fluent interface
	 */
	protected function setMessage($message)
	{
		$this->_message = (string) $message;
		return $this;
	}

	/**
	 * Returns the message timestamp
	 *
	 * @return \DateTime
	 */
	public function getTimestamp()
	{
		return $this->_timestamp;
	}

	/**
	 * Sets the message timestamp
	 *
	 * @return HiDef_Sms_Adapter_Abstract Provides a fluent interface
	 */
	protected function setTimestamp(\DateTime $timestamp)
	{
		$this->_timestamp = $timestamp;
		return $this;
	}

	/**
	 * Abstract methods
	 */

	/**
	 * Parses the raw request parameters.
	 *
	 * Minimally, it should set the "sender", "recipients", "message" content,
	 * and "timestamp" for a received message.
	 *
	 * @return HiDef_Sms_Adapter_Abstract Returns a fluent interface
	 */
	abstract public function parseRequestParameters();
}

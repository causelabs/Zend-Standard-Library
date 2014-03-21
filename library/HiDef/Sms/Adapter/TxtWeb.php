<?php

require_once 'HiDef/Sms/Adapter/Abstract.php';

class HiDef_Sms_Adapter_TxtWeb extends HiDef_Sms_Adapter_Abstract
{
	const TYPE_SMS = 1000;
	const TYPE_USSD = 1001;
	const TYPE_CALL = 1002;
	const TYPE_WEB = '200x';
	const TYPE_EMULATOR = 2100;
	const TYPE_IM = '220x';
	const TYPE_ANDROID = 3000;
	const TYPE_UNKNOWN = 'na';

	protected $_acceptableTypes = array(
		self::TYPE_SMS,
		self::TYPE_USSD,
		self::TYPE_EMULATOR,
		self::TYPE_UNKNOWN,
	);

	/**
	 * Message ID, for MMS if needed
	 *
	 * @var string
	 */
	protected $_id;

	/**
	 * Type of message, consistent with TYPE_* constants
	 *
	 * @var string
	 */
	protected $_type;

	/**
	 * "Secret" parameter for verifying webhook requests
	 *
	 * @var string
	 */
	protected $_secret;

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


		parent::__construct($config);
	}

	/**
	 * Gets currently-set message ID
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Sets the message ID
	 *
	 * @param string
	 * @return HiDef_Sms_Adapter_TxtWeb Provides a fluent interface
	 */
	public function setId($id)
	{
		$this->_id = (string) $id;
		return $this;
	}

	/**
	 * Gets the currently set type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * Sets the type
	 *
	 * @param string
	 * @return HiDef_Sms_Adapter_TxtWeb Provides a fluent interface
	 */
	public function setType($type)
	{
		if (in_array($type, $this->_acceptableTypes)) {
			$this->_type = $type;
		}
		else {
			$this->_type = self::TYPE_UNKNOWN;
		}
		return $this;
	}

	/**
	 * Gets the currently set Telerivet secret
	 *
	 * @return string
	 */
	public function getSecret()
	{
		return $this->_secret;
	}

	/**
	 * Sets the verifcation parameter
	 *
	 * @param string
	 * @return HiDef_Sms_Adapter_TxtWeb Provides a fluent interface
	 */
	public function setSecret($text)
	{
		$this->_secret = (string) $text;
		return $this;
	}

	/**
	 * Overridden abstract method
	 *
	 * @return HiDef_Sms_Adapter_TxtWeb Provides a fluent interface
	 */
	public function parseRequestParameters()
	{
		// Validate request parameters
		if (isset($this->_rawRequestParameters['txtweb-mobile'])) {
			$this->setSender($this->_rawRequestParameters['txtweb-mobile']);
		}

		if (isset($this->_rawRequestParameters['txtweb-message'])) {
			$this->setMessage($this->_rawRequestParameters['txtweb-message']);
		}

		if (isset($this->_rawRequestParameters['txtweb-protocol'])) {
			$this->setType($this->_rawRequestParameters['txtweb-protocol']);
		}

		if (isset($this->_rawRequestParameters['txtweb-verifyid'])) {
			$this->setSecret($this->_rawRequestParameters['txtweb-verifyid']);
		}

		if (isset($this->_rawRequestParameters['txtweb-id'])) {
			$this->setId($this->_rawRequestParameters['txtweb-id']);
		}

		$this->_timestamp = new \DateTime();

		return $this;
	}
}

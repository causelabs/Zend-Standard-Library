<?php

require_once 'HiDef/Sms/Adapter/Abstract.php';

class HiDef_Sms_Adapter_Telerivet extends HiDef_Sms_Adapter_Abstract
{
	const API_URL_MESSAGE_RETRIEVAL = 'https://api.telerivet.com/v1/messages/%s';

	const TYPE_SMS = 'sms';
	const TYPE_MMS = 'mms';
	const TYPE_USSD = 'ussd';
	const TYPE_CALL = 'call';
	const TYPE_UNKNOWN = 'na';

	protected $_acceptableTypes = array(
		self::TYPE_SMS,
		self::TYPE_MMS,
		self::TYPE_USSD,
		self::TYPE_CALL,
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

	/**
	 * API key to allow retrieval of message properties, mainly around MMS
	 *
	 * @var string
	 */
	protected $_apiKey;


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
	 * @return HiDef_Sms_Adapter_Telerivet Provides a fluent interface
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
	 * @return HiDef_Sms_Adapter_Telerivet Provides a fluent interface
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
	 * Sets the Telerivet secret parameter for verification
	 *
	 * @param string
	 * @return HiDef_Sms_Adapter_Telerivet Provides a fluent interface
	 */
	public function setSecret($text)
	{
		$this->_secret = (string) $text;
		return $this;
	}

	/**
	 * Gets the currently-set API key
	 *
	 * @return string
	 */
	public function getApiKey()
	{
		return $this->_apiKey;
	}

	/**
	 * Sets the API key
	 *
	 * @param string
	 * @return HiDef_Sms_Adapter_Telerivet Provides a fluent interface
	 */
	public function setApiKey($key)
	{
		$this->_apiKey = (string) $key;
		return $this;
	}

	/**
	 * Overridden abstract method
	 *
	 * @return HiDef_Sms_Adapter_Telerivet Provides a fluent interface
	 */
	public function parseRequestParameters()
	{
		// Validate request parameters
		if (!isset($this->_rawRequestParameters['secret'])) {
			/**
			 * @see HiDef_SMS_Adapter_Exception
			 */
			require_once 'HiDef/Sms/Adapter/Exception.php';
			throw new HiDef_Sms_Adapter_Exception('Secret not valid');
		}
		else {
			$this->_secret = $this->_rawRequestParameters['secret'];
		}

		if (isset($this->_rawRequestParameters['from_number'])) {
			$this->_sender = $this->_rawRequestParameters['from_number'];
		}

		if (isset($this->_rawRequestParameters['to_number'])) {
			$this->_recipients = $this->_rawRequestParameters['to_number'];
		}

		if (isset($this->_rawRequestParameters['content'])) {
			$this->_message = $this->_rawRequestParameters['content'];
		}

		if (isset($this->_rawRequestParameters['time_created'])) {
			$this->_timestamp = new \DateTime('@'.$this->_rawRequestParameters['time_created']);
		}

		if (isset($this->_rawRequestParameters['message_type'])) {
			$this->_type = $this->_rawRequestParameters['message_type'];
		}

		if (isset($this->_rawRequestParameters['id'])) {
			$this->_id = $this->_rawRequestParameters['id'];
		}

		return $this;
	}

	public function isMms()
	{
		return $this->getType() === self::TYPE_MMS;
	}

	public function getMmsParts()
	{
		if ($this->getType() !== self::TYPE_MMS) {
			/**
			 * @see HiDef_SMS_Adapter_Exception
			 */
			require_once 'HiDef/Sms/Adapter/Exception.php';
			throw new HiDef_Sms_Adapter_Exception('Message is not of type MMS');
		}

		$key = $this->getApiKey();
		if ($key === null || $key === '') {
			/**
			 * @see HiDef_SMS_Adapter_Exception
			 */
			require_once 'HiDef/Sms/Adapter/Exception.php';
			throw new HiDef_Sms_Adapter_Exception('API key is not set; Telerivet requires usage of an API key to retrieve message specifics');
		}

		// Retrieve Telerivet message parts
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, sprintf(self::API_URL_MESSAGE_RETRIEVAL, $this->getId()));
		curl_setopt($curl, CURLOPT_USERPWD, $this->getApiKey());
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$json = curl_exec($curl);
		curl_close($curl);

		$response = json_decode($json, true);

		$results = array();
		if (isset($response['error'])) {
			/**
			 * @see HiDef_SMS_Adapter_Exception
			 */
			require_once 'HiDef/Sms/Adapter/Exception.php';
			throw new HiDef_Sms_Adapter_Exception('API key is not set; Telerivet requires usage of an API key to retrieve message specifics');
		}
		else {
			foreach ($response['mms_parts'] as $part) {
				$results[] = array(
					'filename' => $part['filename'],
					'type' => $part['type'],
					'url' => $part['url'],
				);
			}
		}

		return $results;
	}
}

<?php

class HiDef_Validate_InternationalPhoneNumber extends Zend_Validate_Abstract
{
	const INVALID_DATA = 'invalidData';

	protected $_messageTemplates = array(
		self::INVALID_DATA => '\'%value%\' is not a valid phone number',
	);

	/**
	 * Numeric country code, defaults to 1 for United States
	 *
	 * @var string
	 */
	protected $_countryCode = '1';

	/**
	 * Whether to include country-code checking when validating
	 *
	 * @var boolean
	 */
	protected $_requireCountryCode = true;

	/**
	 * Number of digits to validate against
	 *
	 * @var int
	 */
	protected $_digitCount = 10;

	public function __construct($options = array())
	{
		if ($options instanceof Zend_Config) {
			$options = $options->toArray();
		}
		else if (!is_array($options)) {
			$options = func_get_args();
			$temp['countryCode'] = array_shift($options);
			if (!empty($options)) {
				$temp['digitCount'] = array_shift($options);
			}
			if (!empty($options)) {
				$temp['requireCountryCode'] = array_shift($options);
			}

			$options = $temp;
		}

		if (!array_key_exists('countryCode', $options)) {
			$options['country_code'] = '1';
		}
		$this->setCountryCode($options['countryCode']);
		if (array_key_exists('digitCount', $options)) {
			$this->setDigitCount($options['digitCount']);
		}
		if (array_key_exists('requireCountryCode', $options)) {
			$this->setRequireCountryCode($options['requireCountryCode']);
		}
	}

	public function getCountryCode()
	{
		return $this->_countryCode;
	}
	public function setCountryCode($text)
	{
		$this->_countryCode = (string) $text;
		return $this;
	}
	public function getRequireCountryCode()
	{
		return $this->_requireCountryCode;
	}
	public function setRequireCountryCode($text)
	{
		$this->_requireCountryCode = (bool) $text;
		return $this;
	}
	public function getDigitCount()
	{
		return $this->_digitCount;
	}
	public function setDigitCount($text)
	{
		$this->_digitCount = (int) $text;
		return $this;
	}

	public function isValid($value)
	{
		$this->_setValue($value);

		// Helper validators
		$regex = '/^';

		if ($this->getRequireCountryCode()) {
			$regex .= $this->getCountryCode();
		}
		else {
			$regex .= '('.$this->getCountryCode().'|)';
		}
		$regex .= '[0-9]{'.$this->getDigitCount().'}$/';

		$mobileValidator = new Zend_Validate_Regex($regex);

		$valid = $mobileValidator->isValid($value);

		if (!$valid) {
			$this->_messageTemplates[self::INVALID_DATA] = _($this->_messageTemplates[self::INVALID_DATA]);
			$this->_error(self::INVALID_DATA);
			return false;
		}

		return true;
	}

}
?>
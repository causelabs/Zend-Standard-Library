<?php
/**
 * Custom authentication
 *
 * Provides a mechanism for abstracting database authentication.
 *
 * @category HiDef_ZendStandardLibrary
 * @package HiDef_Auth
 * @subpackage Adapter
 * @copyright Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author Mark Horlbeck <mark@hidef.co>
 * @version $Id:$
 */

/**
 * This class is used to authenticate users logging into the site using a
 * custom password scheme, typically involving some sort of digest and salt.
 * The digest relies on PHP digest functions, not database.
 *
 * @category HiDef_ZendStandardLibrary
 * @package HiDef_Auth
 * @subpackage Adapter
 * @copyright Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author Mark Horlbeck <mark@hidef.co>
 * @version $Id:$
 */
class HiDef_Auth_Adapter_DbTable extends Zend_Auth_Adapter_DbTable
{
	protected $_digestMethod = null;
	protected $_useSalt = false;
	protected $_saltColumn = null;

	public function __construct($digestMethod = null,
		$useSaltColumn = null,
		$saltColumn = null,
		$usersTable = 'users',
		$usernameColumn = 'username',
		$passwordColumn = 'password'
	) {
		// If no defaults were passed in for authentication method, load them from the registry
		if ($digestMethod == null || $useSaltColumn == null || $saltColumn == 'null') {
			$config = Zend_Registry::get('config');
			$auth = $config->resources->db->params->userAuthentication;
		}

		// Coalesce variables
		if ($digestMethod == null) {
			$digestMethod = $auth->method;
		}
		if ($useSaltColumn == null) {
			$useSaltColumn = $auth->useSalt;
		}
		if ($saltColumn == null) {
			$saltColumn = $auth->saltColumn;
		}

		$this->_digestMethod = $digestMethod;
		$this->_useSalt = (bool) $useSaltColumn;
		$this->_saltColumn = $saltColumn;

		$credentialTreatment = strtoupper($this->_digestMethod).'(?)';

		// Note that this is hard-coded against MySQL, as it uses MySQL-specific DDL
		if ($useSaltColumn && $saltColumn != null) {
			$credentialTreatment = strtoupper($this->_digestMethod).'(CONCAT(?, '.$saltColumn.'))';
		}

		parent::__construct(null, $usersTable, $usernameColumn, $passwordColumn, $credentialTreatment);
	}

	/**
	 * Generates a random salt value
	 *
	 * @return string Salt value
	 */
	public function generateSalt()
	{
		$salt = '';
		for ($i = 0; $i < 40; $i++) {
			$salt .= chr(rand(33, 126));
		}
		return $salt;
	}

	/**
	 * Computes digest on given string with an optional salt value
	 *
	 * @param string $text Text to compute digest
	 * @param string $salt Salt string
	 * @return string Computed digest
	 */
	public function getDigest($text, $salt='')
	{
		$digest = $this->_digestMethod;

		return $digest($text.$salt);
	}
}

?>
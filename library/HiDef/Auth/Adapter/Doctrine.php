<?php
/**
 * Custom authentication
 *
 * Provides a mechanism for abstracting database authentication via Doctrine;
 * requires the Bisna library to operate
 *
 * @link https://github.com/guilhermeblanco/ZendFramework1-Doctrine2 Bisna library
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
class HiDef_Auth_Adapter_Doctrine
	implements Zend_Auth_Adapter_Interface
{
	protected $_digestMethod = 'sha1';

	protected $_userEntity = 'App\Entities\User';

	protected $_usernameField = 'username';

	protected $_passwordField = 'password';

	protected $_salt = '';

	protected $_doctrine = null;

	protected $_username;
	protected $_password;

	public function __construct(array $config = array())
	{
		if (isset($config['digestMethod'])) {
			$this->_digestMethod = $config['digestMethod'];
		}
		if (isset($config['userEntity'])) {
			$this->_userEntity = $config['userEntity'];
		}
		if (isset($config['usernameField'])) {
			$this->_usernameField = $config['usernameField'];
		}
		if (isset($config['passwordField'])) {
			$this->_passwordField = $config['passwordField'];
		}
		if (isset($config['salt'])) {
			$this->_salt = $config['salt'];
		}

		// Relies on the Bisna library, defined in the registry.
		$this->_doctrine = Zend_Registry::get('doctrine');
	}

	/**
	 * Returns the currently set identity
	 *
	 * @return string
	 */
	public function getIdentity()
	{
		return $this->_username;
	}

	/**
	 * Sets the identity
	 *
	 * @param string
	 * @return HiDef_Auth_Adapter_Doctrine Provides a fluent interface
	 */
	public function setIdentity($text)
	{
		$this->_username = (string) $text;
		return $this;
	}

	/**
	 * Gets the digested version of the currently set credential
	 *
	 * @return string
	 */
	public function getCredentialDigest()
	{
		if (empty($this->_salt)) {
			$this->_salt = $this->generateSalt();
		}

		return $this->getDigest($this->getCredential(), $this->getSalt());
	}

	/**
	 * Gets the currently set credential
	 *
	 * @return string
	 */
	public function getCredential()
	{
		return $this->_credential;
	}

	/**
	 * Sets the credential
	 *
	 * @param string
	 * @return HiDef_Auth_Adapter_Doctrine Provides a fluent interface
	 */
	public function setCredential($text)
	{
		$this->_credential = (string) $text;
		return $this;
	}

	/**
	 * Gets the currently set salt value
	 *
	 * @return string
	 */
	public function getSalt()
	{
		return $this->_salt;
	}

	/**
	 * Sets the salt value
	 *
	 * @param string
	 * @return HiDef_Auth_Adapter_Doctrine Provides a fluent interface
	 */
	public function setSalt($text)
	{
		$this->_salt = (string) $text;
		return $this;
	}

	/**
	 * Authenticates the user against the supplied user table using Doctrine.
	 *
	 * Defined by Zend_Auth_Adapter_Interface.
	 *
	 * @throws Zend_Auth_Adapter_Exception If answering the authentication query is impossible
	 * @return Zend_Auth_Result
	 */
	public function authenticate()
	{
		$digestMethod = $this->_digestMethod;

		$params = array(
			$this->_usernameField => $this->getIdentity(),
			$this->_passwordField => $digestMethod($this->getCredential().$this->_salt),
		);

		$em = $this->_doctrine->getEntityManager();
		$user = $em->getRepository($this->_userEntity)->findOneBy($params);

		return new Zend_Auth_Result(
			null !== $user ? Zend_Auth_Result::SUCCESS : Zend_Auth_Result::FAILURE,
			$user,
			array()
		);
	}

	/**
	 * Generates a random salt value
	 *
	 * @return string Salt value
	 */
	public function generateSalt($length = 40)
	{
		$salt = '';
		for ($i = 0; $i < $length; $i++) {
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
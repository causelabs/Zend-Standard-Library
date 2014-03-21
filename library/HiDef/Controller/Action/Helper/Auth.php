<?php

class HiDef_Controller_Action_Helper_Auth extends Zend_Controller_Action_Helper_Abstract
{
	const ACL_RESOURCE_TYPE = 'controller';
	
	/**
	 * Zend_Acl object for creating dynamic access control lists
	 *
	 * @var Zend_Acl
	 */
	protected $_acl;
	
	/**
	 * Currently logged-in user
	 *
	 * @var Default_Model_User
	 */
	protected $_identity;
	
	public function init($resources = array())
	{
		// Get identity
		$auth = Zend_Auth::getInstance();

		if ($auth->hasIdentity()) {
			$this->_identity = $auth->getIdentity();

			// Set up authorization
			$factory = new HiDef_AclFactory();

			if (is_string($resources)) {
				$resources = array($resources, $this->getRequest()->getControllerName());
			}
			if (is_array($resources) && count($resources) == 0) {
				$resources[] = $this->getRequest()->getControllerName();
			}
			$this->_acl = $factory->create($resources, $this->_identity);
		}
	}
	
	public function direct()
	{
		return $this->getIdentity();
	}
	
	public function getIdentity()
	{
		return $this->_identity;
	}
	
	public function isAllowed($resource = null, $privilege = null)
	{
		// Coalesce options
		if ($resource === null) {
			$resource = self::ACL_RESOURCE_TYPE.':'.$this->getRequest()->getControllerName();
		}
		if ($privilege === null) {
			$privilege = $this->getRequest()->getActionName();
		}
		
		return $this->_acl->isAllowed(
			$this->_identity,
			$resource,
			$privilege
		);
	}
	
}
?>
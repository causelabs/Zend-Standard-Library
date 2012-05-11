<?php
/**
 * Access control list ("ACL") factory class
 *
 * Allows for authorization checking of a specific controller and action to
 * a particular user. Designed originally for use with Crowdseed.
 *
 * @category HiDef_ZendStandardLibrary
 * @package HiDef
 * @subpackage Acl
 * @copyright Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author Mark Horlbeck <mark@hidef.co>
 * @version $Id:$
 */
/**
 * Access control list ("ACL") factory class
 *
 * Allows for authorization checking of a specific controller and action to
 * a particular user. Designed originally for use with Crowdseed.
 *
 * @category HiDef_ZendStandardLibrary
 * @package HiDef
 * @subpackage Acl
 * @copyright Copyright (c) 2012 HiDef, Inc. (http://www.hidef.co)
 * @author Mark Horlbeck <mark@hidef.co>
 * @version $Id:$
 */
class HiDef_Acl_Factory
{
	/**
	 * Creates a dynamic ACL for the supplied controller and user
	 *
	 * @static
	 * @param string|array $controllers Name of controller(s) to set permissions for
	 * @param Zend_Acl_Role_Interface $user User to set permissions for
	 * @return Zend_Acl ACL object containing privileges for specific request
	 */
	public static function create($controllers, Zend_Acl_Role_Interface $user)
	{
		$acl = new Zend_Acl();
		self::_createRoles($acl, $user);

		if (!is_array($controllers)) {
			$controllers = array($controllers);
		}

		// Add resource in question. For now, this is just for controllers.
		$mapper = new Application_Model_Mapper_SecurityPrivileges();
		foreach ($controllers as $controller) {
			$acl->add(new Zend_Acl_Resource('controller:'.$controller));

			/// Set all privileges
			$privileges = $mapper->getByResource($controller, 'controller');
			self::_setPrivileges($acl, $privileges);
		}

		return $acl;
	}

	/**
	 * Creates available roles for the specific user, based on associated security groups
	 * and the user itself
	 *
	 * @static
	 * @param Zend_Acl $acl The ACL to populate
	 * @param Zend_Acl_Role_Interface $user The user to retrieve security information for and set permissions
	 * @return void
	 */
	private static function _createRoles(Zend_Acl $acl, Zend_Acl_Role_Interface $user)
	{
		// Add groups first so that the user can inherit from them
		$mapper = new Default_Model_Mapper_Users();
		$groups = $mapper->getSecurityGroups($user->id);
		foreach ($groups as $group) {
			$acl->addRole($group);
		}

		// Add the user, which inherits from all the groups
		$acl->addRole($user, $groups);
	}

	/**
	 * Sets privileges in the ACL based on the originally retrieved resource
	 *
	 * @static
	 * @param Zend_Acl $acl The ACL to populate
	 * @param array $privileges An array of Application_Model_SecurityPrivilege objects
	 * @return void
	 * @author Mark Horlbeck
	 */
	private static function _setPrivileges(Zend_Acl $acl, array $privileges)
	{
		// Iterate through privileges and allow privileges. Basically, the absence
		// of a privilege in the ACL will indicate denial of service.
		foreach ($privileges as $privilege) {
			$role = $privilege->role_type.':'.$privilege->role_id;
			$resource = $privilege->resource_type.':'.$privilege->resource_id;

			// If the role doesn't exist in the ACL, move on to the next one.
			// We don't care about populating privileges that our role doesn't have.
			if (!$acl->hasRole($role)) {
				continue;
			}

			// Each privilege assignment per resource can have multiple modes allowed.
			// These translate into controller actions that can be accessed, e.g.
			//	index,create,read for securitygroup:10
			foreach ($privilege->modes as $action) {
				$acl->allow($role, $resource, $action);
			}
		}
	}
}

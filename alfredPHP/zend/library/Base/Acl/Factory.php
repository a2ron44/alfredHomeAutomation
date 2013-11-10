<?php
class Base_Acl_Factory
{
	private static $_objAuth;
	private static $_objAclSession;
	private static $_objAcl;

	
	public static function get(Zend_Auth $objAuth,$clearACL=false) {

		self::$_objAuth = $objAuth;
		self::$_objAclSession = new Zend_Session_Namespace(Base_Setup::SESSION_ACL);

		if($clearACL) {
			self::_clear();
		}
		if(isset(self::$_objAclSession->acl)) {
			return self::$_objAclSession->acl;
		} else {
			return self::_loadAclFromDB();
		}
	}

	private static function _clear() {
		unset(self::$_objAclSession->acl);
	}

	private static function _saveAclToSession() {
		self::$_objAclSession->acl = self::$_objAcl;
	}

	private static function _loadAclFromDB() {

		$aclModel = new Base_Models_BaseAcl();
		$aclRoles = $aclModel->getAllRoles();
		$aclResources = $aclModel->getAllResources();
		$aclRoleAccess =  $aclModel->getAllAccess();

		self::$_objAcl = new Zend_Acl();

		foreach($aclRoles as $role) {
				self::$_objAcl->addRole(new Zend_Acl_Role($role['role']),$role['parent']);
		}

		// add all resources to the acl
		foreach($aclResources as $resource) {
			self::$_objAcl->addResource($resource['id']);
			self::$_objAcl->allow(Base_Setup::ROLE_SUPER,$resource['id']);
		}

		// 		// allow roles to resources
		foreach($aclRoleAccess as $roleResource) {
			self::$_objAcl->allow($roleResource['role'],$roleResource['resource_id']);
		}

		self::_saveAclToSession();
		return self::$_objAcl;
	}


}
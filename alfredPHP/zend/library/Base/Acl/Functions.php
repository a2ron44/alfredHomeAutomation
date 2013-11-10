<?php
class Base_Acl_Functions {
	
	protected function canAccessResource($module, $controller, $action, $role){
		
	}
	
	public function loginUser($userEmail, $password, $sitePin = null){
		$config = Zend_Registry::get ( 'config' );
		$users = new Model_Users ();
		
		// clear old sessions
		Zend_Session::namespaceUnset ( Base_Setup::SESSION_ACL );
			

		$authAdapter = $this->getAuthAdapter ();
		$passPre = $users::PASSWORD_PREFIX;
		$authAdapter->setIdentity ( $userEmail )->setCredential ( $passPre . $password );
		$auth = Zend_Auth::getInstance ();
			
		$result = $auth->authenticate ( $authAdapter );
		
		// for extra security measures
		Zend_Session::regenerateId ();
			
		if ($result->isValid ()) {
			// get users info
			$identity = $authAdapter->getResultRowObject ( array (
					'user_id',
					'fst_name',
					'lst_name',
					'role'
			) );
		
			// set identity
			$authStorage = $auth->getStorage ();
			$authStorage->write ( $identity );
		
			// create new session
			$authNamespace = new Zend_Session_Namespace ( Base_Setup::SESSION_ACL );
			$expireSecs = $config ['phpSettings'] ['sessionexpiresecs'];
			$authNamespace->setExpirationSeconds ( $expireSecs );

			$authNamespace->loginCheck = 1;
		
			return true;
			
		} else {
			throw new Base_Exception_Allowed ( "Invalid Login.  Please try again." );
		}
		
		return false;
	}
	
	private function setLoginSessions(){
		
	}
	
	private function getAuthAdapter() {
		$authAdapter = new Zend_Auth_Adapter_DbTable ( Zend_Db_Table::getDefaultAdapter () );
		$authAdapter->setTableName ( 'base_users' )->setIdentityColumn ( 'user_id' )->setCredentialColumn ( 'pswrd' )->setCredentialTreatment ( 'md5(?)' );
	
		return $authAdapter;
	}
	
	
}
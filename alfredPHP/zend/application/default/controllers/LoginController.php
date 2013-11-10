<?php
class LoginController extends Base_Controller_Action {
	public function loginAction() {
		try {
			$this->setPageTitle ( "Site Login" );
			
			if (Zend_Auth::getInstance ()->hasIdentity ()) {
				$this->addFlashMessage ( "You are logged in!" );
				$this->_redirect ( 'default/index/index' );
			}
			
			$request = $this->getRequest ();
			$form = new Form_Login ();
			
			// check for login redirect if session is set.
			$session = new Zend_Session_Namespace ( Base_Setup::SESSION_LASTREQUEST );
			$lastRequest = $session->lastRequestUri;
			
			if ($request->isPost ()) {
				if ($form->isValid ( $request->getPost () )) {
					$data = $form->getValues ();
					$loginEmail = $data ['email'];
					$loginPass = $data ['pswrd'];
					
					if ($data ['remember'] == '1') {
						setcookie ( Base_Setup::COOKIE_REMEMBER, '1', time () + (60 * 60 * 24 * 90), '/' );
						setcookie ( Base_Setup::COOKIE_REMEMBER_USER, $loginEmail, time () + (60 * 60 * 24 * 90), '/' );
					} else {
						setcookie ( Base_Setup::COOKIE_REMEMBER, '0', time () + (60 * 60 * 24 * 90), '/' );
						setcookie ( Base_Setup::COOKIE_REMEMBER_USER, '', time () + (60 * 60 * 24 * 90), '/' );
					}
					
					$loginFunc = new Base_Acl_Functions ();
					
					$loginRes = $loginFunc->loginUser ( $loginEmail, $loginPass );
					
					if ($loginRes == true) {
						if (isset ( $lastRequest )) {
							$this->_redirect ( $lastRequest );
						} else {
							$this->_redirect ( 'default/index/index' );
						}
					} else {
						throw new Base_Exception_Allowed ( 'Unexpected Login Error' );
					}
				}
			} else {
				$remember = $request->getCookie ( Base_Setup::COOKIE_REMEMBER, null );
				$rememberUser = $request->getCookie ( Base_Setup::COOKIE_REMEMBER_USER, null );
				
				if ($remember == '1') {
					$form->getElement ( 'remember' )->setValue ( '1' );
					$form->getElement ( 'email' )->setValue ( $rememberUser );
				}
			}
			
			$this->view->form = $form;
		} catch ( Base_Exception_Allowed $e ) {
			$this->view->form = $form;
			$this->view->errorMessage = $e->getMessage ();
		}
	}
	public function logoutAction() {
		// clear sessions and identity variables
		Zend_Auth::getInstance ()->clearIdentity ();
		Zend_Session::namespaceUnset ( Base_Setup::SESSION_ACL );
		$storage = new Zend_Auth_Storage_Session ();
		$storage->clear ();
		
		$this->_redirect ( 'default/login/login' );
	}
}


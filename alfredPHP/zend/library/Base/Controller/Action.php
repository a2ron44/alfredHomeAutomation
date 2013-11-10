<?php

class Base_Controller_Action extends Zend_Controller_Action
{
	protected $_user;
	protected $flashMes;
	protected $_log;


	public function init()
	{
		//default page title
		$this->setPageTitle('');

		//setup flash messenger
		$this->flashMes = $this->_helper->getHelper('FlashMessenger');
		$this->view->flashmessages = $this->flashMes->getMessages();

		//setup user variable
		$auth = Zend_Auth::getInstance();
		$this->_user = $auth->getIdentity();

		// check for session expire
		$authNamespace = new Zend_Session_Namespace(Base_Setup::SESSION_ACL);
			
		if ( $auth->hasIdentity()  ) {
			//check for session expiration
			if($authNamespace->loginCheck != 1){
				$auth->clearIdentity();
				Zend_Session::namespaceUnset(Base_Setup::SESSION_ACL);
				self::addFlashMessage('Timeout from inactivity.  Please log back in.');
				$this->_redirect('/login/login');
			} else {
				// session is good, reset and set lift
				$config = Zend_Registry::get('config');

				$expireSecs = $config['phpSettings']['sessionexpiresecs'];
				$authNamespace->setExpirationSeconds($expireSecs);
				$this->_user = $auth->getIdentity();

			}

		}

		unset($auth);
	}

	public function setPageTitle($pageTitle)
	{
		$this->view->pageTitle = $pageTitle;
		unset($pageTitle);
	}

	public function addFlashMessage($msg)
	{
		$this->flashMes->addMessage($msg);
		unset($msg);
	}

	public function ajaxDisableLayout()
	{
		$this->_helper->layout->disableLayout();
	}

	public function ajaxSetNoViewRender()
	{
		$this->getHelper('viewRenderer')->setNoRender();
	}

	public function redirectError($e, $altMessage = null){
		if($altMessage){
			$msg = $altMessage;
		} else {
			$msg = $e->getMessage();
		}
		$this->addFlashMessage($msg);

		$this->_helper->redirector('error','error','default');
		unset($msg);
	}

	/**
	 * Print out the first message of Filter input
	 * @param array $inputMsgs - $input->getMessages()
	 */
	public function printValidatorMsg($inputMsgs){
		foreach($inputMsgs as $errKey => $errVal){
			foreach($errVal as $m){
				return $m;
			}
		}
	}

	public function canLink($module, $controller, $action){
		try{
			$auth = Zend_Auth::getInstance();
			$objAcl = Base_Acl_Factory::get($auth);

			//if logged in
			if($auth->hasIdentity()) {
				$arrUser = $auth->getIdentity();
				$roleId = $arrUser->role;

				$resModel = new Base_Models_BaseAcl();
					
				$id = $resModel->getResourceId($module, $controller, $action);
					
				if(empty($id)){
					throw new Base_Exception_Permission('Link not found.  Resource not setup');
				}
					
				if($objAcl->isAllowed($roleId, $id)) {
					return true;
				}
			}
			return false;
		} catch (Base_Exception_Permission $e){
			self::redirectError($e);
		}
	}
}
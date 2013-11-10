<?php 
class Base_View_Helper_CanLink extends Zend_View_Helper_Abstract
{
	public function canLink($module, $controller, $action){
		try{
			$auth = Zend_Auth::getInstance();
			$objAcl = Base_Acl_Factory::get($auth);

			//if logged in
			if($auth->hasIdentity()) {
				$arrUser = $auth->getIdentity();
				$roleId = $arrUser->role;

				//@BASE   add superuser override
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
			$flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
			$flash->addMessage($e->getMessage());
			$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
			$redirector->gotoUrl('/default/error/error');
		}
	}
}
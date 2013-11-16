<?php
class Api_ErrorController extends Base_Controller_Action {

	public function init(){
		$this->ajaxDisableLayout();
		$this->ajaxSetNoViewRender();
	}
	public function errorAction(){

		$errors = $this->_getParam('error_handler');
		
		switch($errors->type){
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				echo Zend_Json::encode('Page not found');
				return;
				break;
			default:
				// application error
				Base_Log::warn('Unknown Error: ' . $errors->exception->getMessage());
				$this->getRequest()->setHttpResponseCode(500);
				echo Zend_Json::encode('Server Error');
				return;
		}
	}

	public function accessdeniedAction(){

		$this->getResponse()->setHttpResponseCode(401);
		echo Zend_Json::encode('Unauthorized access');
	}
}


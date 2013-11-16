<?php
class Plugin_RestHandler extends Zend_Controller_Plugin_Abstract {

	public $restModules = array('api');

// 	public function routeShutdown(Zend_Controller_Request_Abstract $request){
		
// 		$path =  trim($this->_request->getPathInfo(),'/');
// 		$path = explode('/', $path);
 
// 		$module = $request->getModuleName();
// 		$controller = $request->getControllerName();
// 		$action = $request->getActionName();
// 		$front = Zend_Controller_Front::getInstance ();
// 		$dispatcher = $front->getDispatcher();
 
// 		Zend_Debug::dump($request->getParams() );   
// 		if (! $dispatcher->isValidModule($module)) {
// 			Zend_Debug::dump( 'xx' );  die();
// 		}
	
		
// 		if(method_exists($this, $action . 'Action')){
// 			echo 't';
// 		} else {
// 			echo 'f';
// 		}
// 		die();
// 	}

	public function preDispatch(Zend_Controller_Request_Abstract $request){

		$module = $request->getModuleName();
		
		// don't run this plugin unless we are in the rest module
		if(! in_array($module, $this->restModules)){
			return;
		}
		
		$errorHandler = Zend_Controller_Front::getInstance()->getPlugin('Zend_Controller_Plugin_ErrorHandler');
		
		// change the error handler being used from the one in the default module, to the one in the rest module
		$errorHandler->setErrorHandlerModule('api');
	}
}
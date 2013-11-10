<?php

class ErrorController extends Base_Controller_Action
{

    public function errorAction()
    {

    	$errors = $this->_getParam('error_handler');

    	$this->view->showEmailLink = true;
    	
        if (!$errors || !$errors instanceof ArrayObject) {
        	return;
        }
        
        $config = Zend_Registry::get('config');
        
        //show error?
        $this->view->showDebug = false; 
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Unexpected Error';
                $this->view->showEmailLink = true;
                Base_Log::warn('Unknown Error ' . $errors->exception->getMessage() );
                break;
        }
        

        //only show if debug it turned on.  Never for production!
        if($config['site']['debug'] == 'true') {
	         $this->view->exception = $errors->exception;
	         $this->view->request = $errors->request;
	         $this->view->showDebug = true;
        }

    }
    
    
    public function accessdeniedAction() 
    {	
    	$errors = $this->_request->getParams();
    	$this->view->page = '.../' .$errors['module'] . '/'. 
    		$errors['controller'] . '/' . $errors['action'];
    }
}


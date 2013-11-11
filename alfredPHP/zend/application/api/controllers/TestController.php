<?php
class Api_TestController extends Zend_Rest_Controller {
	public function init() {
		// $front = Zend_Controller_Front::getInstance();
		// $front->returnResponse(true);
		$this->_helper->viewRenderer->setNoRender ();
		$this->_helper->layout->disableLayout ();
		
		$this->_todo = array (
				"1" => "Buy milk",
				"2" => "Pour glass of milk",
				"3" => "Eat cookies" 
		);
		
		
		//authentication
		
// 		$apiKey = $this->getRequest()->getHeader('apikey');
		
// 		if(empty($apiKey)){
// 			$this->getResponse()->setHttpResponseCode(403)
// 			->appendBody("Invalid API Key\n");
// 			$this->getRequest()->setDispatched(true);
// 			return;
// 		}

		
	}
	public function indexAction() {
		$this->getResponse ()->setHttpResponseCode ( 200 );
		// echo Zend_Json::encode ( $this->_todo );
	}
	public function headAction() {
		// action body
	}
	public function getAction() {
		// not found
// 		$this->getResponse ()->setHttpResponseCode ( 404 );
		
		//$id = $this->_request->getParam ( 'id' );
		
		echo Zend_Json::encode ( $this->_todo ['1'] );
	}
	public function postAction() {
		$this->getResponse ()->setHttpResponseCode ( 201 );
		$item = $this->_request->getPost ( 'item' );
		
		$this->_todo [count ( $this->_todo ) + 1] = $item;
		
		echo Zend_Json::encode ( $this->_todo );
	}
	public function putAction() {
		// action body
	}
	public function deleteAction() {
		// action body
		$this->getResponse ()->setHttpResponseCode ( 204 );
	}
}
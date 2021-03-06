<?php
class Api_TestController extends Base_Rest_Controller {
	
	public function init(){

		parent::init();
		
		$this->_todo = array(
				"1" => "Buy milk",
				"2" => "Pour glass of milk",
				"3" => "Eat cookies" 
		);
		

	}

	public function indexAction(){
		parent::indexAction();
		
		Base_Log::info("index Action");

		echo Zend_Json::encode($this->_todo);
	}

	public function listAction(){

		Base_Log::info("list Action");
		$this->_forward('index');
	}

	public function getAction(){
		parent::getAction();
		Base_Log::info("get Action");
		$this->getResponse()
			->setHttpResponseCode(200);
		
		$id = $this->getParam('id');
		
		echo Zend_Json::encode($this->_todo[$id]);
	}

	public function postAction(){
		parent::postAction();
		Base_Log::info("post Action");

		$item = $this->_request->getPost('item');
		
		$this->_todo[count($this->_todo) + 1] = $item;
		
		echo Zend_Json::encode($this->_todo);
	}

	public function putAction(){
		parent::putAction();
		Base_Log::info("put Action");
		// action body
	}

	public function deleteAction(){
		parent::deleteAction();
		Base_Log::info("delete Action");
		// action body

	}

	public function headAction(){
		// action body
	}
}
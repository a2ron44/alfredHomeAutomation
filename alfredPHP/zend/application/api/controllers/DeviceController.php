<?php
class Api_DeviceController extends Base_Rest_Controller {

	public function init(){

		parent::init();
		
		$this->_todo = array(
				"1" => "Buy milk",
				"2" => "Pour glass of milk",
				"3" => "Eat cookies" 
		);
		
		$this->setRequireAuth();
	}

	public function indexAction(){

		Base_Log::info("index Action");
		
		try{
			$deviceModel = new Model_Device();
			
			$group = $this->_request->getParam('group', null);
			
			$devices = $deviceModel->getAllDevices(1, $group);
			echo Zend_Json::encode($devices);
		} catch(Exception $e){
			Base_Log::warn($e->getMessage());
			$this->setHttpResponse(500);
			echo Zend_Json::encode('Error receiving devices');
		}
	}

	public function getAction(){

		Base_Log::info("get Action");
		$this->getResponse()
			->setHttpResponseCode(200);
		
		$id = $this->getParam('id');
		
		echo Zend_Json::encode($this->_todo[$id]);
	}

	public function postAction(){

		Base_Log::info("post Action");
		
		$item = $this->_request->getPost('item');
		
		$this->_todo[count($this->_todo) + 1] = $item;
		
		echo Zend_Json::encode($this->_todo);
	}

	public function putAction(){

		Base_Log::info("put Action");
		
		try{
			$deviceModel = new Model_Device();
			$accountId = '1';
			$deviceId = $this->_request->getParam('device_id', null);
			$state = $this->_request->getParam('state', null);
				
			$x = $deviceModel->changeState($accountId, $deviceId, $state);
			echo Zend_Json::encode('Done');
		} catch(Exception $e){
			Base_Log::warn($e->getMessage());
			$this->setHttpResponse(500);
			echo Zend_Json::encode('Error receiving devices');
		}
	}

	public function deleteAction(){

		Base_Log::info("delete Action");
		// action body
	}

	public function headAction(){
		// action body
	}
}
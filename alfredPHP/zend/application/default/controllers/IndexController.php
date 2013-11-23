<?php
class IndexController extends Base_Controller_Action {

	public function indexAction(){

		$this->setPageTitle("Site Main");
		
		$objInstance = Zend_Auth::getInstance();
		
		if(! $objInstance->hasIdentity()){
			$this->_helper->redirector('login', 'login');
		}
	}

	public function testlistAction(){

		$this->ajaxDisableLayout();
		$this->ajaxSetNoViewRender();
		
		$url = "http://localhost/alfred/alfredPHP/zend/api/device";
		
		$config = array(
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(
						CURLOPT_SSL_VERIFYPEER => false,
						CURLOPT_SSLVERSION => 3 
				)
				 
		);
		$client = new Zend_Http_Client($url, $config);
		$client->setParameterGet('group', '1');
		$response = $client->request('GET')
			->getBody();
		
		Zend_Debug::dump(Zend_Json::decode($response));
		
		//Zend_Debug::dump( $client->request('GET')->getStatus() );  
	}
	
	public function testAction(){
	
		$this->ajaxDisableLayout();
		$this->ajaxSetNoViewRender();
	
		$url = "http://localhost/alfred/alfredPHP/zend/api/device";
	
		$config = array(
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array(
						CURLOPT_SSL_VERIFYPEER => false,
						CURLOPT_SSLVERSION => 3
				)
					
		);
		$client = new Zend_Http_Client($url, $config);
		$client->setParameterGet('device_id', '1');
		$client->setParameterGet('state', '1');
		$response = $client->request('PUT')
		->getBody();
	
		Zend_Debug::dump(Zend_Json::decode($response));

	}
}


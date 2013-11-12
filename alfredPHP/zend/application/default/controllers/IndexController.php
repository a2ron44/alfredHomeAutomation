<?php
class IndexController extends Base_Controller_Action {
	public function indexAction() {
		$this->setPageTitle ( "Site Main" );
		
		$objInstance = Zend_Auth::getInstance ();
		
		if (! $objInstance->hasIdentity ()) {
			$this->_helper->redirector ( 'login', 'login' );
		}
	}
	public function testAction() {
		$this->ajaxDisableLayout ();
		$this->ajaxDisableLayout ();
		// $ch = curl_init ();
		
		// curl_setopt ( $ch, CURLOPT_URL, "http://whatworkswith.com/zend/api/test/get" );
		// //curl_setopt ( $ch, CURLOPT_POST, 0 );
		// // curl_setopt($ch, CURLOPT_POSTFIELDS, "query='where post_parameter = query'");
		
		// // receive server response ...
		// curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// $server_output = curl_exec ( $ch );
		// Zend_Debug::dump ( $server_output );
		
		$url = "http://whatworkswith.com/zend/api/test/index";
		
		$config = array (
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curloptions' => array (
						CURLOPT_SSL_VERIFYPEER => false 
				) 
		);
		$client = new Zend_Http_Client ( $url, $config );
		$response = $client->request('GET');
		
		Zend_Debug::dump ( $response );
	}
}


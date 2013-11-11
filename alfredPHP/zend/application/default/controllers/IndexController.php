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
 		$url = "http://whatworkswith.com/zend/api/test/index";
		
// 		//$url ="http://example.com";
// 		$data = "The updated text message";
// 		$ch = curl_init();
// 		curl_setopt($ch,CURLOPT_URL,$url);
// 		curl_setopt($ch, CURLOPT_POST, true);
// 		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");  //for updating we have to use PUT method.
// 		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
// 		$result = curl_exec($ch);
// 		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// 		curl_close($ch);
// 		Zend_Debug::dump( $httpCode );  
// 		die();
// 		$ClientOptions = array (
// 				'ssl' => array (
// 						// Verify server side certificate,
// 						// do not accept invalid or self-signed SSL certificates
// 						'verify_peer' => true,
// 						'allow_self_signed' => false 
// 				) 
// 		);
		
// 		$adapter = new Zend_Http_Client_Adapter_Socket ();
// 		$adapter->setStreamContext ( $ClientOptions );
// 		$client = new Zend_Http_Client ();
// 		$client->setAdapter ( $adapter );
		
		$restClient = new Zend_Rest_Client ( $url );
		// $htp = $restClient->getHttpClient ();
		// $htp->setConfig ( array (
		// 'ssl' => array (
		// 'verify_peer' => false,
		// 'allow_self_signed' => true
		// )
		// ) );
// 		 $restClient->setHttpClient($client);
		//$response = $restClient->restGet ( 'test/index' );

		$client = new Zend_Rest_Client($url);
		
// 		$base_url = 'https://whatworkswith.com';
// 		$endpoint = '/';
// 		$data = array('param1' => 'value1', 'param2' => 'value2', 'param3' => 'value3');
// 		$client = new Zend_Rest_Client($base_url);
// 		$response = $client->restPost($endpoint, $data);

		Zend_Debug::dump ( $response );
	}
	private function _test($request) {
		return $request->getParam ( 'loc' );
	}
}


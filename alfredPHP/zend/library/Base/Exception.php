<?php
class Base_Exception extends Zend_Exception
{
	
	public function __construct($msg = '',$showTrace = true, $showRequest = true,$showUser = false, $level = 'warn', $code = 0, Exception $previous = null)
	{
		
		if(is_array($msg)){
			$msg .= print_r($msg, true);
		}
		

		$browser = (! empty ( $_SERVER ['HTTP_USER_AGENT'] )) ? get_browser ( null, true ) : array ();
		$server = $_SERVER ['REMOTE_ADDR'] . ' -> ';
		
		$serverInfo = $server;
		if (isset ($browser['browser'])) {
			$serverInfo .= $browser['browser'];
		}
		if (isset($browser['version'])) {
			$serverInfo .= "[v{$browser['version']}]";
		}
		
		parent::__construct($msg, (int) $code, $previous);

		$string = '#############  ->' . $serverInfo . PHP_EOL  . PHP_EOL;
		
		$string .= 'MSG:' . $msg . PHP_EOL  . PHP_EOL;
		if($showRequest){
			$fc = Zend_Controller_Front::getInstance();
			$r = $fc->getRequest()->getParams();
			$string .= print_r($r, true);
		}

		if($showUser){
			$objInstance = Zend_Auth::getInstance();
			if($objInstance->hasIdentity()){
				$arrUser = $objInstance->getIdentity();
				$userArray = array('User'=> $arrUser->user_id, 'Role' => 'role');
				$string .= print_r($userArray, true);
			}
		}
		
		$string .= 'FILE:' . 
			$this->getFile() . ' - ' . 'LINE:' . $this->getLine() . ' -  CODE:' . $this->getCode() . PHP_EOL ;
				
		if($showTrace){
			$string .= 'Trace:' . $this->getTraceAsString() . PHP_EOL;
		}

		$string .= '#################################################' . PHP_EOL;
		
		Base_Log::$level($string);
		
		unset($fc, $r, $server, $serverInfo, $string, $level, $msg);
	}



}
<?php
class Base_Log
{

	protected static $_log;

	public static function info($msg, $showRequest = false){
		self::_getLog();
		self::_createMessage('info', $msg, $showRequest);
	}

	public static function warn($msg, $showRequest = false){
		self::_getLog();
		self::_createMessage('warn', $msg,  $showRequest);
	}

	public static function alert($msg,  $showRequest = false){
		self::_getLog();
		self::_createMessage('alert', $msg,  $showRequest);
	}

	private static function _getLog()
	{
		self::$_log = Zend_Registry::get('log');
	}

	private static function _createMessage($level, $msg,  $showRequest)
	{
		if(is_array($msg)){
			$msg = print_r($msg, true);
		}
		if($showRequest){
			$fc = Zend_Controller_Front::getInstance();
			$r = $fc->getRequest()->getParams();
			$msg .= PHP_EOL .  print_r($r, true);
		}
		self::$_log->$level($msg);
	}

}
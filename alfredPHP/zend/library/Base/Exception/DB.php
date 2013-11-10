<?php
class Base_Exception_DB extends Base_Exception
{

	public function __construct($msg = '',$showTrace = false, $showRequest = true,$showUser = false,  $level = 'warn', $code = 0, Exception $previous = null)
	{
		
		parent::__construct('DB_' . $msg,$showTrace, $showRequest, $showUser, $level, $code,  $previous);

	}

}
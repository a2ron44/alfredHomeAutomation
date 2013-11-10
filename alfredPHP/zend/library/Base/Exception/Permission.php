<?php
class Base_Exception_Permission extends Base_Exception
{

	public function __construct($msg = '',$showTrace = true, $showRequest = true,$showUser = true,  $level = 'warn', $code = 0, Exception $previous = null)
	{
		
		parent::__construct('_Permission: ' . $msg,$showTrace, $showRequest, $showUser, $level, $code,  $previous);

	}

}
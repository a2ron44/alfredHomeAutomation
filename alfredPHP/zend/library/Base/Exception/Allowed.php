<?php
class Base_Exception_Allowed extends Base_Exception {
	public function __construct($msg = '', $showTrace = false, $showRequest = true, $showUser = true, $level = 'warn', $code = 0, Exception $previous = null) {
		parent::__construct ( $msg, $showTrace, $showRequest, $showUser, $level, $code, $previous );
	}
}
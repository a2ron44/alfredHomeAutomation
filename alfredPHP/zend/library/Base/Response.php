<?php

class Base_Response {
	
	public $returnCode;
	
	public $msg;
	
	public $data;
	
	public $html;
	
	public function __construct(){
		$this->returnCode = 0;
	}
	
	public function toJSON(){
		$return = array('returnCode' => $this->returnCode,
				'html' => $this->html, 
				'data'=> $this->data,
				'msg' => $this->msg);
		
		return Zend_Json::encode($return);
	}
	
}
<?php
class Base_Db_Table extends Zend_Db_Table
{
	protected $_createUser;
	protected $_createDt;

	protected function _setup()
	{

		parent::_setup();

		$auth = Zend_Auth::getInstance();
		
		//set some defaults to 
		if($auth->hasIdentity()){
			$this->_createUser = $auth->getIdentity()->user_id;
		} else {
			$this->_createUser = Base_Setup::DB_SYS_USERNAME;
		}
		$this->_createDt = date('Y-m-d H:i:s');

	}
}
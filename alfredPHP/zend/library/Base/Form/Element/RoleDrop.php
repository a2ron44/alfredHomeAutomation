<?php
class Base_Form_Element_RoleDrop extends Base_Form_Element_Select
{
	
	public function init()
	{
		parent::init();
		
		$auth = Zend_Auth::getInstance();
		$user = $auth->getIdentity();
		
		$model = new Base_Models_BaseAclRoles();
		$data = $model->getRolesAtOrBelowAccess($user->role);
			
		$list = array();
		foreach ($data as $row){
			$list[$row['role'] ] = $row['role'] . ' - ' . $row['level'];
		}
		
		$this->setLabel('Role')
		->addMultiOptions($list);

	}
	
}
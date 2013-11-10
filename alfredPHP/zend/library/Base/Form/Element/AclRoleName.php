<?php
class Base_Form_Element_AclRoleName extends Base_Form_Element_TextAlnum
{
	
	public function init()
	{
		parent::init();
		
		$this->setLabel("Role Name")
		->setAttrib('maxlength', '20');
	}
	
}
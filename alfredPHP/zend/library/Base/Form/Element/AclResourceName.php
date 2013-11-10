<?php
class Base_Form_Element_AclResourceName extends Base_Form_Element_TextAlnum
{
	
	public function init()
	{
		
		parent::init();
		
		$this->setLabel('Resource Name')
		->setAttrib('maxlength', '50');

	}
	
}
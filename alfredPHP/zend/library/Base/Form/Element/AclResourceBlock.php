<?php
class Base_Form_Element_AclResourceBlock extends Base_Form_Element_TextAlnum
{
	
	public function init(){
		
		parent::init();
		
		$this->setLabel('Resource')
		->setAttrib('maxlength', '40')
		->addFilter('StringToLower');
	}
	
}
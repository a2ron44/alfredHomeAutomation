<?php
class Base_Form_Element_Hidden extends Zend_Form_Element_Hidden
{
	
	public function init()
	{
		//remove default zend tags
		$this->setLabel('Save')
		->setDecorators(array(array('ViewHelper'), ));
	}
	
}
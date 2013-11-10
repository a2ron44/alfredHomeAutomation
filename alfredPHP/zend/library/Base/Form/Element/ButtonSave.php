<?php
class Base_Form_Element_ButtonSave extends Zend_Form_Element_Button
{
	
	public function init()
	{
		parent::init();
		
		$this->setLabel('Save')
		->setAttrib('class', 'saveBtn')
		->setDecorators(array(
		array('ViewHelper'),
		array('Description'),
		array('Errors'),
		array('HtmlTag', array('tag' => 'span', 'class'=>'submit-group')),
		));
	}
	
}
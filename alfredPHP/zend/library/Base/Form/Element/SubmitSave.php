<?php
class Base_Form_Element_SubmitSave extends Zend_Form_Element_Submit
{
	
	public function init()
	{
		//overwrite zends default tags.  Puts in a span with class of 'submit-group'
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
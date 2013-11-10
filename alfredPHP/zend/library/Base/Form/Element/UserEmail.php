<?php
class Base_Form_Element_UserEmail extends Base_Form_Element_Email
{

	//text with alnum validator
	public function __construct($spec, $options = null, $lableType = 'label')
	{
		parent::__construct($spec, $options, $lableType);
	}

	public function init()
	{

		parent::init();
		$this->setLabel('UserEmail')
		->addValidator('NotEmpty', true, array('messages' => 'Enter UserEmail'))
		->addFilter('StringToLower')
		->setAttrib('maxlength', '40');

	}

}
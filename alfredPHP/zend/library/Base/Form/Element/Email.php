<?php
class Base_Form_Element_Email extends Base_Form_Element_Text
{

	//text with alnum validator
	public function __construct($spec, $options = null, $lableType = 'label')
	{
		parent::__construct($spec, $options, $lableType);
	}

	public function init()
	{

		parent::init();
		$this->setLabel('Text')
		->addFilter('StringToLower')
		->addValidator('EmailAddress', true);
		$this->addErrorMessage('Enter Valid Email Address');

	}

}
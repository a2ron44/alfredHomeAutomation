<?php
class Base_Form_Element_UserId extends Base_Form_Element_TextAlnum
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
		->setLabel('UserID')
		->addFilter('StringToUpper')
		->setAttrib('maxlength', '25')
		->addValidator('NotEmpty', true, array('messages' => 'Enter UserID'))
		->addValidator('StringLength', true, array(0, 25, 'messages' => 'Max of 25 characters'))
		->addValidator('Alnum', true, array('messages' => 'Enter a Valid User ID',  'allowWhiteSpace' => true));

	}

}
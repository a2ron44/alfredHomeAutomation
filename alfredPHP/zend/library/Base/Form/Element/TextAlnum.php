<?php
class Base_Form_Element_TextAlnum extends Base_Form_Element_Text
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
		->addValidator('Alnum', true, array('messages' => 'Letters and number only',  'allowWhiteSpace' => true));

	}

}
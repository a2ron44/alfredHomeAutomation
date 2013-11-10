<?php
class Base_Form_Element_TextUnder extends Base_Form_Element_Text
{
	//allows underscore
	public function __construct($spec, $options = null, $lableType = 'label')
	{
		parent::__construct($spec, $options, $lableType);
	}

	public function init()
	{
		parent::init();
		$this->setLabel('Text')
		->addValidator('Regex', false, array('pattern' => '/[a-zA-Z0-9_]$/', 'messages' => 'Only letters,numbers, underscore')); // Only chars from a-z, numbers, underline
	}

}
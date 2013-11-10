<?php
class Base_Form_Element_Qty extends Zend_Form_Element_Text
{
	
	protected $_labelType;
	
	public function __construct($spec, $options = null, $lableType = 'label')
	{
		$this->_labelType =  $lableType;
		parent::__construct($spec, $options);
	}
	
	public function init()
	{
		//overwrites zend's default dt/dd tags.  Puts element and label inside a span with class 'element-group'
		
		$this->setLabel('Qty')
		->addFilter('StringTrim')
		->setAttrib('maxlength', '7')
		->setDecorators(array(
		array('ViewHelper'),
		array('Description', array('escape' => false, 'tag' => 'span')),
		array('Label', array('class' => $this->_labelType, 'requiredPrefix' => '* ')),
		array('Errors'),
		array('HtmlTag', array('tag' => 'span', 'class'=>'element-group')),
		))
		->addValidator('NotEmpty', true, array('messages' => "Enter Qty"))
		->addValidator('Int', true, array('messages' => 'Whole Numbers Only', 'allowWhiteSpace' => false));
	}
	
}
<?php
class Base_Form_Element_CheckBox extends Zend_Form_Element_Checkbox
{
	
	//label type is 'label' or 'rfLabel'  rfLabel is smaller for mobile css
	protected $_labelType;
	
	public function __construct($spec, $options = null, $lableType = 'label')
	{
		$this->_labelType =  $lableType;
		parent::__construct($spec, $options);
	}
	
	public function init()
	{
		//overwrite zends default tags.  Puts in span with class 'element-group'
		$this->setLabel('CheckBox')
		->setDecorators(array(
		array('ViewHelper'),
		array('Description', array('escape' => false, 'tag' => 'span')),
		array('Label', array('class' => $this->_labelType, 'requiredPrefix' => '* ')),
		array('Errors'),
		array('HtmlTag', array('tag' => 'span', 'class'=>'element-group')),
		));

	}
	
}
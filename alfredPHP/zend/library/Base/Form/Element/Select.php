<?php
class Base_Form_Element_Select extends Zend_Form_Element_Select
{
	
	protected $_labelType;
	protected $_showAllOption = false;
	protected $_showAllText = 'Choose';
	protected $_list = array();
	
	public function __construct($spec, $options = null, $lableType = 'label')
	{
		$this->_labelType =  $lableType;
		
		if((!empty($options)) && array_key_exists('show_all', $options)){
			if($options['show_all'] == true){
				$this->_showAllOption = true;
			}
		}
		
		parent::__construct($spec, $options);
	}
	
	public function init()
	{
			
		//overwrite zends default tags.  Puts in a span with class of 'element-group'
		
		$this->setLabel('Select')
		->setDecorators(array(
		array('ViewHelper'),
		array('Description', array('escape' => false, 'tag' => 'span')),
		array('Label', array('class' => $this->_labelType, 'requiredPrefix' => '* ')),
		array('Errors'),
		array('HtmlTag', array('tag' => 'span', 'class'=>'element-group')),
		));

	}
	
}
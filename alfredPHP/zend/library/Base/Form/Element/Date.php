<?php
class Base_Form_Element_Date extends Base_Form_Element_Text
{
	//label type is 'label' or 'rfLabel'  rfLabel is smaller for mobile css
	protected $_labelType;
	protected $_includeTime = false;
	
	public function __construct($spec,$includeTime = false, $options =  array('autocomplete' => 'off'), $lableType = 'label')
	{
		$this->_labelType =  $lableType;
		
		if($includeTime == true){
			$this->_includeTime = true;
		}
		parent::__construct($spec, $options);
	}
	
	public function init()
	{
		$view = Zend_Layout::getMvcInstance()->getView();

		if($this->_includeTime == true){
			$this->setOptions(array('class' => 'datetimepick'));
			$this->addValidator('Date', true, array( 'format' => 'm/d/Y H:i',  'messages' => 'Incorrect Format'));
		} else{
			$this->setOptions(array('class' => 'datepick'));
			$this->addValidator('Date', true, array( 'format' => 'm/d/Y',  'messages' => 'Incorrect Format'));
		}
		$this->setLabel('Date')
		->setDecorators(array(
		array('ViewHelper'),
		array('Description', array('escape' => false, 'tag' => 'span')),
		array('Label', array('class' => $this->_labelType, 'requiredPrefix' => '* ')),
		array('Errors'),
		array('HtmlTag', array('tag' => 'span', 'class'=>'element-group date-element')),
		))
		->addValidator('NotEmpty', true, array('messages' => 'Enter Date'))
		->setDescription('<img class="dateCalBtn" src="' . $view->baseUrl(). '/public/images/calendar.gif" onclick="$(\'#' . $this->_name .'\').focus()"/>' .
				''
				);
	}
	
}
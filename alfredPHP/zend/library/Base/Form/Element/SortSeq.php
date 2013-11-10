<?php
class Base_Form_Element_SortSeq extends Base_Form_Element_Qty
{
	
	public function __construct($spec, $options = null, $lableType = 'label')
	{
		parent::__construct($spec, $options, $lableType);
	}
	
	public function init()
	{
		parent::init();
		
		$this->setLabel('Sort Seq')
		->setAttrib('maxlength', '4');
	}
	
}
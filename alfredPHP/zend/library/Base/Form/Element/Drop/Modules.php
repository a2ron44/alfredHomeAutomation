<?php
class Base_Form_Element_Drop_Modules extends Base_Form_Element_Select
{
	
	public function __construct($spec, $options = null, $lableType = 'label')
	{
		parent::__construct($spec, $options, $lableType);
	}
	
	public function init()
	{

		parent::init();
		$model = new Base_Models_BaseAcl();
		$data = $model->getAllModules();
			
		$list = array();
		$list[''] = 'Any Module';
		foreach ($data as $row){
			$list[$row['module'] ] = $row['module'];
		}

		$this->setLabel('Modules')
		->addMultiOptions($list);

	}
	
}
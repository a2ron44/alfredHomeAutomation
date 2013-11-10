<?php
class Base_Form_Element_Drop_Controllers extends Base_Form_Element_Select
{
	
	public function __construct($spec, $options = null, $lableType = 'label')
	{
		parent::__construct($spec, $options, $lableType);
	}
	
	public function init()
	{

		parent::init();
		
		$model = new Base_Models_BaseAcl();
		$data = $model->getAllControllers();
			
		$list = array();
		$list[''] = 'Any Controller';
		foreach ($data as $row){
			$list[$row['controller'] ] = $row['controller'];
		}

		$this->setLabel('Controllers')
		->addMultiOptions($list);

	}
	
}
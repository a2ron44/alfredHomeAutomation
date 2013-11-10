<?php
class Base_Form_Element_Drop_Resources extends Base_Form_Element_Select
{
	
	public function __construct($spec, $options = null, $lableType = 'label')
	{
		parent::__construct($spec, $options, $lableType);
	}
	
	public function init()
	{

		parent::init();
		
		$model = new Base_Models_BaseAcl();
		$data = $model->getAllResources();
			
		$list = array();
		$list[''] = 'Any Controller';
		foreach ($data as $row){
			$list[$row['id'] ] = $row['resource_name'];
		}

		$this->setLabel('Resource')
		->addMultiOptions($list);

	}
	
}
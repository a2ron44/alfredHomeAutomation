<?php
class Base_Filter_Input extends Zend_Filter_Input{
	
	public function __construct($filterRules, $validatorRules, array $data = null, array $options = null)
	{
		$options = array(self::MISSING_MESSAGE => '%field% is required');
		
		parent::__construct($filterRules, $validatorRules, $data, $options);
	}
}
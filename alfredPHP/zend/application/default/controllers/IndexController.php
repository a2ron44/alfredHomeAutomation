<?php

class IndexController extends Base_Controller_Action
{

	public function indexAction()
	{

		$this->setPageTitle("Site Main");
 
		$objInstance = Zend_Auth::getInstance();

		if(! $objInstance->hasIdentity()){
			$this->_helper->redirector('login','login');
		}
	}

	public function testAction(){

		$validators = array(

				'loc' => array(
						new Liquid_Validate_Loc(false),
						'presence' => 'required',
						'messages' => array(array(
								Liquid_Validate_Loc::INVALID =>'Invalid Loc')
						),
				),
		);

		$filters = array();

		$input = new Base_Filter_Input($filters, $validators, $this->_request->getParams());

		if(! $input->isValid()){
			throw new Base_Exception_Validate($this->printValidatorMsg($input->getMessages()) );
		}

		Zend_Debug::dump( $input->loc );
	}

	private function _test($request){

		return $request->getParam('loc');
	}
}


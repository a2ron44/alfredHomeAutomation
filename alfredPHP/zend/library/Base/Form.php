<?php

class Base_Form extends Zend_Form {

	protected $isAjax = false;
	protected $formId = null;
	protected $nextFunction = '';
	protected $returnFormOnInvalid = true;

	public function __construct($id = null, $options = array()){

		if(!empty($id)){
			$this->formId = $id;
		}

		parent::__construct($options);
	}

	public function getAjax(){
		return $this->isAjax;
	}

	public function setAjax($bool = true){
		if(! is_bool($bool)){
			throw new Base_Exception(__FUNCTION__ . ' - must pass boolean');
		}
		if($bool == true){
			$this->isAjax = true;
			$this->setAttrib('class', 'ajaxForm');

			if(empty($this->formId)){
				throw new Base_Exception('Form must have an ID.  Construct with ID param set to unique ID');
			}
		}
	}

	public function setNextFunction($functionName){
		$this->nextFunction = $functionName;
	}

	public function getNextFunction(){
		return $this->nextFunction;
	}

	private function _prepFormOutput(){
		if(!empty($this->formId)){
			$this->setAttrib('id', $this->formId);
		}

		if($this->isAjax === true){
			if(empty($this->formId)){
				throw new Base_Exception('Form must have an ID.  Construct with ID param set to unique ID');
			}

			$this->setAttrib('onSubmit', 'getFormElements_' . $this->formId . '();return false;');
			//get javascript to make ajax form
		}
	}

	private function _getFormOutput(){
		self::_prepFormOutput();
		return parent::render();
	}

	public function render(Zend_View_Interface $view = null, $wrapInDiv = true){

		if($this->isAjax === true){
			echo self::_getJavascript();
		}

		$html = '';
		if($wrapInDiv == true){
			$html = "<div id='formParent_" . $this->formId . "'>";
		}
		
		$html.= self::_getFormOutput();
		
		if($wrapInDiv == true){
			$html.= '</div>';
		}
		return $html;
	}

	/**
	 * Check for valid Ajax form
	 * @param request $request
	 * @param bool $returnFormOnFalse - will return a False and not send form back on ajax request.  basically an override
	 * @return boolean
	 */
	public function isValidNew($request, $returnFormOnInvalid = true){

		$this->returnFormOnInvalid = $returnFormOnInvalid;
		 
		//if ajax call, will disable layout/view and return only the form
		if ($this->getAjax() && $request->isXmlHttpRequest() ){
			//disable view
			$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
			$viewRenderer->setNoRender();
			//disable layout
			$layout = Zend_Controller_Action_HelperBroker::getStaticHelper('layout');
			$layout->disableLayout();

			$return = array('returnCode' => 0, 'html' => 'Unexpected Error');

			if($this->isValid($request->getParams())){
				return true;
			}

			if($this->returnFormOnInvalid == true){
				self::returnFailure(self::_getFormOutput());
			} else {
				return false;
			}
		} else {
			if($request->isPost()){
				if(! $this->isValid($request->getParams())){
					return false;
				} else {
					return true;
				}
			}
		}
	}

	private function _getJavascript(){
		$html = '<script type="text/javascript">';

		$html.= ' function getFormElements_' . $this->formId . '(){' . PHP_EOL;
		$html.= ' var obj = new Object;' . PHP_EOL;

		foreach($this->getElements() as $element){
			$element instanceof Zend_Form_Element;
			if($element instanceof Zend_Form_Element_Checkbox){
				$html.= " if($('#" .  $element->getName() . "').is(':checked') == true){ " . PHP_EOL.
				"obj." . $element->getName() . " = 1; } else { obj." . $element->getName() . " = 0;};";
			} else{
				$html.= 'obj.' . $element->getName() . ' = '. "$('#" . $element->getName() . "').val();" . PHP_EOL;
			}
		}
		$html.= PHP_EOL;


		$html.= "
		$.ajax({
		data: obj,
		dataType: 'json',
		success: function(result){

		if(result.returnCode == 1){
			$('#formParent_" . $this->formId . " .errors').remove();
		";
		
		if(!empty($this->nextFunction)){
			$html.= ' ' . $this->nextFunction . ';';
		}
		$html.=" } else {

		$('#formParent_" . $this->formId . "').html(result.html);" . PHP_EOL ."
	}
	}
	})

	";

		$html.= '}';

		$html.= '</script>';

		return $html;
	}
	
	
	public function returnSucess(){
		$return = array('returnCode' => 1, 'html' => '');
		echo Zend_Json::encode($return);
		die();
	}
	
	public function returnFailure($html){
		$return = array('returnCode' => 0, 'html' => $html);
		echo Zend_Json::encode($return);
		die();
	}

}
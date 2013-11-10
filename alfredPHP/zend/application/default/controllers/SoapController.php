<?php

class SoapController extends Zend_Controller_Action
{
	public function preDispatch()
	{
		$this->getHelper('layout')->disableLayout();
		$this->getHelper('viewRenderer')->setNoRender();
		$this->getResponse()->setHeader('Cache-Control', 'cache, must-revalidated');
		$this->getResponse()->setHeader('Pragma', 'public');

		/*
		 * If the environment is anything other than production, do not cache
		* any of the WSDL's.  For development, this eliminates the concern of
		* working with a cached copy and allows for immediate feedback of changes.
		*/
		if (APPLICATION_ENV != 'production') {
			ini_set("soap.wsdl_cache_enabled", 0);
		}

		//Use the rad/write adapter as a default adapter for the web services
		//$db = Sg_Db_Manager::getDBO(Sg_Db_Manager::DBRW);
		//Zend_Db_Table::setDefaultAdapter($db);
	}

	public function bufferCleanup($buffer) {
		header('Content-type: text/xml; charset=utf-8');
		$doc = new DOMDocument();
		$doc->formatOutput = true;
		if ($doc->loadXML($buffer)) {
			return $doc->saveXML();
		} else {
			return $buffer;
		}
	}

	/**
	 * Dispatches the actual service request
	 *
	 * @return void
	 */
	public function dispatchAction()
	{
		ob_start(array($this, 'bufferCleanup'));
		$class = $this->_getParam('service', false);

		if (!$class) {
			$this->getResponse()->setBody("No Service Specified")
			->setHttpResponseCode(500)->sendResponse();
			exit (0);
		}

		$className = 'Services_Soap_' . ucfirst($class);

		if (!class_exists($className, true)) {
			$this->getResponse()->setBody("Invalid Service Specified")
			->setHttpResponseCode(500)->sendResponse();
			exit (0);
		}
		
		$params = array_keys($this->_request->getParams());
		$params = array_map('strtolower', $params);

		if (in_array('wsdl', $params)) {
			if (property_exists($className, '_wsdl')) {
				echo eval("return $className::\$_wsdl;");
				exit();
			} else if (property_exists ($className, '_wsdlUri')) {
				readfile(eval("return $className::\$_wsdlUri;"));
				exit ();
			} else {
				$autodiscover = new Zend_Soap_AutoDiscover();

				/*
				 * Calling class can override the default operation style by
				* setting the _operationStyle property which sets options for
				* all the binding operations soap:body elements.
				*/
				if (property_exists($className, '_operationStyle')) {
					// TODO - FIXME Replace this eval with Upgrade to php5.3
					$operationStyle = eval("return $className::\$_operationStyle;");
					$autodiscover->setOperationBodyStyle($operationStyle);
				}

				if (property_exists($className, '_complexStrategy')) {
					// @TODO Replace this eval with Upgrade to php5.3
					// @FIXME This is pure evil and needs to be exterminated with 5.3
					$strategy = eval("return $className::\$_complexStrategy;");
					 
					//Ensure the Class exists and is an instance of the Strategy Interface (messy code warning)
					if (class_exists($strategy, true) &&
							in_array('Zend_Soap_Wsdl_Strategy_Interface', class_implements($strategy))) {
						 
						$autodiscover->setComplexTypeStrategy(new $strategy());
					}
				}

				$autodiscover->setClass($className);
				$autodiscover->handle();
				exit ();
			}
		} else {
			$classMap = array();
			 
			if (property_exists($className, '_classmap')) {
				// @TODO Replace this eval with Upgrade to php5.3
				// @FIXME This is pure evil and needs to be exterminated with 5.3
				$classMap = eval("return $className::\$_classmap;");
			}
			 
			$protocol = ($_SERVER ['SERVER_PORT'] == 443) ? 'https://' : 'http://';
			$request = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$request .= (strpos($request, '?') ? '&' : '?') . 'WSDL';
			 
			$server = new Zend_Soap_Server (
					$protocol . $request,
					array('classmap' => $classMap)
			);
			 
			$server->setClass($className);
			$server->setEncoding('utf-8');
			$server->handle();

			if (APPLICATION_ENV != 'production' ) {
				Base_Log::info($server->getLastRequest());
				Base_Log::info($server->getLastResponse());
			}
		}
	}

}


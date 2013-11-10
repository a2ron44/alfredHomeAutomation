<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Initializes the Controller Modules and Directories
	 *
	 * @return void
	 */
	protected function _initAutoLoad()
	{

		//Add autoloader empty namespace
		$autoLoader = Zend_Loader_Autoloader::getInstance();
		$autoLoader->registerNamespace('Base_');
		$autoLoader->setFallbackAutoloader(true);

		//Resources renamed.
		$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
				'basePath'      =>  APPLICATION_PATH,
				'namespace'     =>  '',
				'resourceTypes' =>  array(
						'form'  => array(
								'path'      =>'forms/',
								'namespace' =>'Form_'
						),
						'model'  => array(
								'path'      =>'models/',
								'namespace' =>'Model_'
						),
						'plugin'  => array(
								'path'      =>'plugins/',
								'namespace' =>'Plugin_'
						),

				)
		));

		$fc  = Zend_Controller_Front::getInstance();

		/*
		 * Toggle comments to enable/disable plugins
		*/

		//$fc->registerPlugin(new Plugin_AclCheck());

		$fc->throwExceptions(false);
		unset( $fc);
		//Return to bootstrap
		return $autoLoader;
	}


	protected function _initConfig()
	{
		//grabs config from application/configs/application.xml
		$config = $this->getOptions();
		Zend_Registry::set('config', $config);
		$apConfig = Zend_Registry::get('config');

		unset($apConfig);
		return $config;

	}

	/**
	 * Needed for routing.  Override for web services
	 */
	protected function _initRoutes()
	{

		//Generic Services Route - sets up access to  ....url/services/[service name]

		$routes[] = new Zend_Controller_Router_Route ('services/:controller/:service/*',
				array ('module' => 'default',
						'action' => 'dispatch'));

		$front = Zend_Controller_Front::getInstance ();
		$router = $front->getRouter ();
		foreach($routes as $key=>$route) {
			$router->addRoute ( "custom{$key}", $route );
		}
		
		$restRoute = new Zend_Rest_Route($front,array(), array ('module' => 'api'));
		$router->addRoute('api', $restRoute);

	}

	/**
	 * Setup for modules.  in example url call,  [myUrl]/default/index/edit
	 * [myUrl] = site.com ,  default = "default" module ,  index = "index" controller ,  edit = "edit" action
	 */
	protected function _initModules()
	{
		$frontController = Zend_Controller_Front::getInstance ();

		/*
		 * Each folder in application/ must be added if it is to be a "module".
		*/
		$frontController->setControllerDirectory(array(
				'default' => APPLICATION_PATH . '/default/controllers',
				'setup' => APPLICATION_PATH . '/setup/controllers',
				'admin' => APPLICATION_PATH . '/admin/controllers',
				'api' => APPLICATION_PATH . '/api/controllers',
		));

		$frontController->setDefaultModule('default');

	}

	/**
	 * Setup for layout.  Can add skins to toggle css
	 */
	protected function _initView()
	{
		//Initialize View
		$view = new Zend_View();
		$view->doctype('XHTML1_STRICT');
		$view->headTitle(Base_Setup::SITE_HEAD_TITLE);
		$view->skin = 'blue';

		// Add it to ViewRenderer
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);

		//return it to the bootstrap
		return $view;
	}

	//No helpers used, but this is how you would add a default path.
	public function _initViewHelperPath() {

		$this->bootstrap('view');
		$view = $this->getResource('view');

		$view->addHelperPath(APPLICATION_PATH . '/default/views/helpers', 'Base_View_Helper'
				// <- this should be your helper class prefix.
		);
	}

	/**
	 * Will create a log file for each day in the log folder insite a directory with the name in sitePin config setting
	 * To make only one log file for every day, just take out the date('Y-m-d') in the file name
	 */
	protected function _initLog()
	{
		$config = $this->getOptions();

		$format = '%timestamp% %priorityName%: %message% ' . PHP_EOL;

		$formatter  = new Zend_Log_Formatter_Simple($format);
		try{
			$writer     = new Zend_Log_Writer_Stream($config['os']['path']['log'] . DS .
				// $config['os']['path']['siteFolder'] . DS. 
					$config['os']['path']['logPrefix'] . date('Y-m-d') . '.log', 'ab');
			$writer->setFormatter($formatter);
			$fireBug = new Zend_Log_Writer_Firebug();
			$writer->addFilter(new Zend_Log_Filter_Priority(Zend_Log::DEBUG, '!='));
			$log = new Zend_Log();
			$log->addWriter($writer);
			$log->addWriter($fireBug);
			Zend_Registry::set('log', $log);
		} catch (Exception $e) {
			echo print_r($e);
		}
		//cleanup
		unset ($config, $format, $formatter, $writer, $log, $browser);
	}


	protected function _initMail()
	{

		/*
		 * Default mail using a gmail smtp. settings come from application.xml file
		*/
		$apConfig = Zend_Registry::get('config');
		// set database configuration parameters
		$smptssl = $apConfig['site']['email']['server']['ssl'];
		$smptport = $apConfig['site']['email']['server']['port'];
		$smptauth = $apConfig['site']['email']['server']['auth'];
		$smptusername = $apConfig['site']['email']['server']['username'];
		$smptpassword = $apConfig['site']['email']['server']['password'];

		$fromEmail = $apConfig['site']['email']['fromAddress'];
		$fromName = $apConfig['site']['email']['fromName'];

		$mailConfig = array(
				'ssl' => $smptssl,
				'port' =>$smptport,
				'auth' => $smptauth,
				'username' => $smptusername,
				'password' => $smptpassword);
			
		$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $mailConfig);
		//Use this if you do not want to specify $transport evertime you use Zend_Mail->send()
		Zend_Mail::setDefaultTransport($transport);
		Zend_Mail::setDefaultFrom($fromEmail, $fromName);
		unset($apConfig, $transport, $mailConfig);
	}

	protected function _initDB()
	{

		$apConfig = Zend_Registry::get('config');
		// set database configuration parameters
		$adapter = $apConfig['db']['adapter'];
		$params = $apConfig['db']['config'];

		// initialize database
		$db = Zend_Db::factory($adapter, $params);

		// set database as default
		Zend_Db_Table_Abstract::setDefaultAdapter($db);

		// save database object in Zend_Registry
		Zend_Registry::set('db', $db);

		//set fireBug db profiler - see all queries in Firbug
		// 		$db->setProfiler(
		// 		array('enabled' => true, 'class' => "Zend_Db_Profiler_Firebug"));

		unset($apConfig, $db, $params, $adapter);
	}

	protected function _initAuth()
	{
		//overwrites default auth session from Zend Default;
		$auth = Zend_Auth::getInstance();
		$auth->setStorage(new Zend_Auth_Storage_Session(Base_Setup::SESSION_ACL));

		return $auth;
	}
}


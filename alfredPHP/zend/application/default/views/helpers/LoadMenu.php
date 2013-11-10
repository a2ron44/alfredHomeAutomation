<?php

class Base_View_Helper_LoadMenu extends Zend_View_Helper_Abstract
{
	public function loadMenu()
	{
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$base = Zend_Controller_Front::getInstance()->getBaseUrl();
				
			$menuAll = Zend_Registry::get('menu');
				
			if(!empty($menuAll)){
				echo '<ul>';
				foreach($menuAll as $menuItem){
					echo '<li><a href="' . $base .'/' . $menuItem['module'] . '/' . $menuItem['controller'] .
					'/'. $menuItem['action'] . '">' . $menuItem['resource_name'] .'</a></li>';

				}
				echo '</ul>';
			}
				
		}
	}
}
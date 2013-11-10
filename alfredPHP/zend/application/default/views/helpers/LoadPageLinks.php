<?php

class Base_View_Helper_LoadPageLinks extends Zend_View_Helper_Abstract
{
	public function loadPageLinks()
	{
		$auth = Zend_Auth::getInstance();

		$objAcl = Base_Acl_Factory::get($auth);
		$base = Zend_Controller_Front::getInstance()->getBaseUrl();

		$request = Zend_Controller_Front::getInstance()->getRequest();

		//if logged in
		if($auth->hasIdentity()) {
			
			$aclModel = new Base_Models_BaseAcl();
			$resourceId = $aclModel->getResourceId($request->getModuleName(), $request->getControllerName(), $request->getActionName());
				
			//get this page's resource menu id
			$menuModel = new Base_Models_BaseMenuMain();
			
			$mainId = $menuModel->getMenuIdByResource($resourceId);
			
			if(!empty($mainId)){
					
				$arrUser = $auth->getIdentity();
				$role = $arrUser->role;

				$menuResModel = new Base_Models_BaseMenuResource();
				$items = $menuResModel->getMenuChildren(strtoupper($mainId));

				foreach($items as $i){
					if($objAcl->isAllowed($role, $i['rec_id'])) {
							
						$recName = $i['rec_name'];

						if(empty($i['link_image'])){
							$imglink = Base_Setup::DEFAULT_LINK_IMAGE;
						} else {
							$imglink = $i['link_image'];
						}
						echo '<a class="indexPageLink" href="'. $base . '/' . $i['module']. '/' .
								$i['controller'] . '/' . $i['action'] . '">' .
								'<img src="' .$base. '/public/images/' . $imglink .'" />' . $recName . '</a>';
					}
				}
			}
		}

	}
}
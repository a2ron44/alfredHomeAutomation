<?php
class Base_Models_BaseMenuMain extends Base_Db_Table
{

	protected function _setup()
	{
		$this->_name = 'acl_menu_main';
		$this->_primary = array('main_id');
		parent::_setup();
	}

	public function addMenuHeader($resourceId, $sortSeq)
	{
		$data = array('resource_id' => $resourceId,'sort_seq' => $sortSeq);
		$this->insert($data );

	}
	
	public function deleteMenuHeader($mainId)
	{
		
		$menuResModel = new Base_Models_BaseMenuResource();
		
		//get all children links
		$children = $menuResModel->getMenuChildren($mainId);
		
		//remove all children resource links
		foreach($children as $child){
			$menuResModel->removeMenuChild($mainId, $child['mr_id']);			
		}
		
		//delete menu main id
		
		$w = $this->getAdapter()->quoteInto('main_id = ?', $mainId);
		$this->delete($w );
	
	}

	public function getMainMenus()
	{
		try{
			$select = $this->getAdapter()->select();
			$select->from(array('main' => 'acl_menu_main'),	array('main_id'	=> 	'main_id',
					'resource_id' => 'resource_id',
					'rec_name'	=>	'r.resource_name',
					'module'	=>	'r.module',
					'controller'=>	'r.controller',
					'action'	=>	'r.action',
					'sort_seq'	=>	'sort_seq'))
					->join(array('r' => 'acl_resource'), 'r.id = main.resource_id', array())
					->order(array('sort_seq'));

			return $this->getAdapter()->query($select)->fetchAll();

		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}
	}

	public function getMainMenuItems($role)
	{
		try{

			$select = $this->getAdapter()->select();
			$select->from(array('main' => 'acl_menu_main'),	array('main_id'	=> 	'main_id',
					'resource_name' => 'r.resource_name',
					'module'	=>	'r.module',
					'controller'=>	'r.controller',
					'action'	=>	'r.action',
					))
			->join(array('r' => 'acl_resource'), 'r.id = main.resource_id', array())
			->order(array('sort_seq'))
			->distinct();

			if($role != Base_Setup::ROLE_SUPER){
				$roleModel = new Base_Models_BaseAclRoles();
				//get resources for current user and parents of role
				$allAccess = $roleModel->getThisRoleAccess($role, true);			
				$select->join(array('acc' => 'acl_access'), "acc.resource_id = r.id", array());
				$select->join(array('all' => $allAccess), "all.resource_id = acc.resource_id", array());
			}

			return $this->getAdapter()->query($select)->fetchAll();

		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}
	}

	public function getUserMenu()
	{
		try{
			$auth = Zend_Auth::getInstance();
			if($auth->hasIdentity()) {
				$menuHeads = $this->getMainMenuItems($auth->getIdentity()->role);

				if(empty($menuHeads)){
					throw new Base_Exception('No Menu Found for User');
				}
				
				return $menuHeads;
			} else {
				return null;
			}
		} catch (Base_Exception $e){
			throw $e;
		}
	}
	
	public function getMenuResourceInfo($mainId){
		
		$select = $this->getAdapter()->select();
		$select->from(array('main' => $this->_name),	array('main_id'	=> 	'main_id',
				'resource_name'  => 'r.resource_name',
				'module'	=>	'r.module',
				'controller'=>	'r.controller',
				'action'	=>	'r.action',
		))
		->join(array('r' => 'acl_resource'), 'r.id = main.resource_id', array())
		->where("main_id = ?", $mainId);
		
		return $this->getAdapter()->fetchRow($select);
	}
	
	public function getMenuIdByResource($resourceId){
	
		$select = $this->getAdapter()->select();
		$select->from(array('main' => $this->_name),	array('main_id'	=> 	'main_id',
		))
		->where("resource_id = ?", $resourceId)
		->limit(1);
	
		return $this->getAdapter()->fetchOne($select);
	}
}
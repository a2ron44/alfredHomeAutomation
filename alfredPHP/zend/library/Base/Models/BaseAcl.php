<?php
class Base_Models_BaseAcl extends Base_Db_Table
{

	protected function _setup()
	{
		$this->_name = 'acl_resource';
		$this->_primary = array('id');
		parent::_setup();
	}

	public function getAllRoles($removeRolesForView = false)
	{

		$select = $this->getAdapter()->select();
		$select->from('acl_roles',	array(
				'role'	=>	'role',
				'descr'	=>	'descr',
				'parent'=>	'parent',
				'level'	=>	'level'));
		if($removeRolesForView){
			$select->where('role != ?', Base_Setup::ROLE_SUPER);
		}
		$select->order(array('level','role'));

		return $this->getAdapter()->query($select)->fetchAll();

	}


	public function getAllResources()
	{

		$select = $this->getAdapter()->select();
		$select->from($this->_name,	array('id'	=> 	'id',
				'resource_name' => 'resource_name',
				'module'	=>	'module',
				'controller'=>	'controller',
				'action'	=>	'action',
				'link_image'=>	'link_image'))
				->order(array('module','controller','action'));

		return $this->getAdapter()->query($select)->fetchAll();

	}

	public function saveResource($data)
	{
		try{
			$row = null;
			if(array_key_exists('id', $data)){
				$row = $this->find($data['id'])->current();
			}

			if(empty($row)){
				$row= $this->createRow();
			}
			$row->resource_name = $data['resource_name'];
			$row->module = $data['new_module'];
			$row->controller = $data['new_controller'];
			$row->action = $data['new_action'];
			
			if(array_key_exists('link_image', $data)){
				if(! empty($data['link_image'])){
					$row->link_image = $data['link_image'];
				} else {
					$row->link_image = null;
				}
			}
			

			$row->save();
			return true;
		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}

	}

	public function getResourceInfo($resourceId){
		$select = $this->getAdapter()->select();
		$select->from($this->_name)
		->where('id = ?', $resourceId);

		return $this->getAdapter()->fetchRow($select);
	}

	public function getResourceId($module, $controller, $action)
	{
		$select = $this->getAdapter()->select();
		$select->from($this->_name,	array('id'	=> 	'id',
		))
		->where('module = ?', $module)
		->where('controller = ?', $controller)
		->where('action = ?', $action);

		return $this->getAdapter()->fetchOne($select);
	}

	public function getAllAccess()
	{
		$select = $this->getAdapter()->select();
		$select->from('acl_access',	array('id'	=> 	'id',
				'role'	=>	'role',
				'resource_id' => 'resource_id',
		));

		return $this->getAdapter()->query($select)->fetchAll();
	}

	public function getAllModules()
	{
		$select = $this->getAdapter()->select();
		$select->from($this->_name,	array(
				'module'	=>	'module',
		))
		->distinct()
		->order(array('module'));

		return $this->getAdapter()->query($select)->fetchAll();
	}

	public function getAllControllers()
	{
		$select = $this->getAdapter()->select();
		$select->from($this->_name,	array(
				'controller'	=>	'controller',
		))
		->distinct()
		->order(array('controller'));

		return $this->getAdapter()->query($select)->fetchAll();
	}

}
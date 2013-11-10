<?php
class Base_Models_BaseAclRoles extends Base_Db_Table
{

	protected function _setup()
	{
		$this->_name = 'acl_roles';
		$this->_primary = array('role');
		parent::_setup();
	}


	public function saveRole($data)
	{
		try{
			$row = $this->find($data['role'])->current();
				
			if(empty($row)){
				$row= $this->createRow();
				$row->role = $data['role'];
			}
			$row->descr = $data['descr'];
			$row->level = $data['level'];
			
			//everyone extends base role to get essential resources
			$row->parent = Base_Setup::ROLE_BASE;
			if(array_key_exists('parent', $data)){
				if(!empty($data['parent'])){
					$row->parent = $data['parent'];
				}
			}

			return $row->save();

		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}

	}
	
	public function deleteRoleAndAccess($role){
		try{
			$row = $this->find($role)->current();
		
			if(empty($row)){
				throw new Base_Exception('Role not found');
			}
			$where = $this->getAdapter()->quoteInto('role = ?', $role);
			
			//delete all access settings
			$this->getAdapter()->delete('acl_access',$where );

			return $this->delete($where);
		
		} catch (Base_Exception $e){
			throw $e;
		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}
	}

	public function getRoleById($role)
	{
		$select = $this->getAdapter()->select();
		$select->from($this->_name)
		->where('role = ?', $role);

		return $this->getAdapter()->fetchRow($select);
	}

	public function getParentRoleAccess($role)
	{
		try{
			$parentIn = '000';
			$parents = self::getRoleParents($role);

			$selectPaccess = $this->getAdapter()->select();
			$selectPaccess->from('acl_access',	array('sort'	=>	new Zend_Db_Expr('0'),
					'id' => 'id' ,
					'role'	=> 	'role',
					'resource_id'	=> 'resource_id',

			));
			if(!empty($parents)){
				$parentIn =  $parents;
			}
			$selectPaccess->where("role in (?)", $parentIn);

			return $selectPaccess;
		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}
	}

	public function getRoleParents($role)
	{
		try{
			$parents = array();
			$parentsRawArray = array();
			$select = $this->getAdapter()->select();
			$select->from($this->_name,	array('role' => 'role' ,
					'parent' => 'parent',
			));

			$parentRows =  $this->getAdapter()->fetchAll($select);
			foreach($parentRows as $prow){
				$parentsRawArray[$prow['role']] = $prow['parent'];
			}
			$parents = self::_findParents($parentsRawArray, $role, $parents);

			return $parents;
		} catch(Zend_Db_Exception $e){
				
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}

	}

	private function _findParents($parentsRawArray, $role, $parentsArray)
	{

		if(!empty($parentsRawArray[$role])){
			$parentsArray[] = $parentsRawArray[$role];
			$parentsArray = self::_findParents($parentsRawArray, $parentsRawArray[$role], $parentsArray);
		}
			
		return $parentsArray;

	}

	public function getThisRoleAccess($role, $returnSelect = false)
	{

		$selectPaccess = self::getParentRoleAccess($role);

		$selectThis = $this->getAdapter()->select();
		$selectThis->from('acl_access',	array( 'sort'	=>	new Zend_Db_Expr('1'),
				'id' => 'id' ,
				'role'	=> 	'role',
				'resource_id'	=> 'resource_id',
		))
		->where(" role = ?", $role);

		$selectUnion = $this->getAdapter()->select();
		$selectUnion->union(array($selectPaccess, $selectThis));

		$select = $this->getAdapter()->select();
		$select->from(array('all' => $selectUnion),	array(	'id'			=>	'all.id',
				'sort'			=>	'sort',
				'role'		=>	'all.role',
				'resource_id'	=>	'all.resource_id',
				'resource_name'		=>	'rec.resource_name',
				'module'		=>	'rec.module',
				'controller'		=>	'rec.controller',
				'action'		=>	'rec.action',
		))
		->join($this->_name, 'all.role = acl_roles.role', array())
		->join(array('rec' => 'acl_resource'), 'all.resource_id = rec.id', array())
		->order(array('sort', 'module','controller','action'));

		if($returnSelect){
			return $select;
		} else {
			return $this->getAdapter()->fetchAll($select);
		}
	}


	public function getAllResourcesNotAllowed($roleId)
	{

		$allowed = self::getThisRoleAccess($roleId, true);

		$select = $this->getAdapter()->select();
		$select->from('acl_resource',	array('id'	=> 	'acl_resource.id',
				'resource_name' => 'resource_name',
				'module'	=>	'module',
				'controller'=>	'controller',
				'action'	=>	'action'))
				->joinLeft(array('allow' => $allowed), 'acl_resource.id = allow.resource_id', array())
				->where('allow.resource_id is null')
				->order(array('module','controller','action'));

		return $this->getAdapter()->query($select)->fetchAll();

	}

	public function getRolesAtOrBelowAccess($currentRole)
	{

		try{
			$roleinfo = $row = $this->find($currentRole)->current();

			if(! $roleinfo){
				throw new Zend_Exception('Role:' . $currentRole . ' not found');
			}

			$currentLevel = $roleinfo['level'];

			$select = $this->getAdapter()->select();
			$select->from($this->_name,	array(
					'role' 	=> 'role',
					'descr' => 'descr',
					'parent'=>	'parent',
					'level'	=>	'level',
			))
			->where('level <= ?', $currentLevel)
			->where('level != 0')
			->order(array('level','role'));

			return $this->getAdapter()->query($select)->fetchAll();

		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}
	}
}
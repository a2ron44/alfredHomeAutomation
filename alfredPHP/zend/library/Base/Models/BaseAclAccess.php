<?php
class Base_Models_BaseAclAccess extends Base_Db_Table
{

	protected function _setup()
	{
		$this->_name = 'acl_access';
		$this->_primary = array('id');
		parent::_setup();
	}


	public function insertAccess($role, $resourceId)
	{
		try{
			$row= $this->createRow();
			$row->role = $role;
			$row->resource_id = $resourceId;

			return $row->save();

		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}

	}

	public function deleteAccess($accId, $role )
	{
		try{
			$row = $this->find($accId)->current();

			if($row->role == $role){
				return $row->delete();
				
			} else {
				throw new Base_Exception('Role does not match expected access role');
			}
			
		} catch (Base_Exception $e) {
			throw $e;
		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}
	}
	
	public function massGetRolesNoAccess($role, $module = null, $controller = null)
	{
		try{

			if( empty($module) && empty($controller)){
				throw new Base_Exception_Validate('Mods and Controller not set');
			}
			
			$whereMod = 'TRUE';
			$whereCont = 'TRUE';
			if(!empty($module)){
				$whereMod = $this->getAdapter()->quoteInto('acl_resource.module = ?', $module);
			}
			if(!empty($controller)){
				$whereCont = $this->getAdapter()->quoteInto('acl_resource.controller = ?', $controller);
			}
			
			$r = $this->getAdapter()->quoteInto('acl_access.role = ?', $role);
			
			$select = $this->getAdapter()->select()
			->from('acl_resource', array('id' => 'id'))
			->joinLeft('acl_access', 'acl_resource.id = acl_access.resource_id and ' . $r , array())
			->where('acl_access.id is null')
			->where($whereCont)
			->where($whereMod);
			
			return $this->getAdapter()->query($select)->fetchAll();
			
		} catch (Base_Exception_Validate $e) {
			throw $e;
		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}
		
	}
	
	public function massRolesToRemove($role, $module = null, $controller = null)
	{
		try{
	
			if( empty($module) && empty($controller)){
				throw new Base_Exception_Validate('Mods and Controller not set');
			}
				
			$whereMod = 'TRUE';
			$whereCont = 'TRUE';
			if(!empty($module)){
				$whereMod = $this->getAdapter()->quoteInto('acl_resource.module = ?', $module);
			}
			if(!empty($controller)){
				$whereCont = $this->getAdapter()->quoteInto('acl_resource.controller = ?', $controller);
			}
				
			$r = $this->getAdapter()->quoteInto('acl_access.role = ?', $role);
				
			$select = $this->getAdapter()->select()
			->from('acl_resource', array('accid' => 'acl_access.id'))
			->join('acl_access', 'acl_resource.id = acl_access.resource_id and ' . $r , array())
			->where($whereCont)
			->where($whereMod);
				
			return $this->getAdapter()->query($select)->fetchAll();
				
		} catch (Base_Exception_Validate $e) {
			throw $e;
		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}
	
	}
	
}
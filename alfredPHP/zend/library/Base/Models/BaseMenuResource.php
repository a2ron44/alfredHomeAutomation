<?php
class Base_Models_BaseMenuResource extends Base_Db_Table
{

	protected function _setup()
	{
		$this->_name = 'acl_menu_resource';
		$this->_primary = array('id');
		parent::_setup();
	}

	public function getMenuChildren($mainId = null)
	{
		try{
			
			$mainMenu = 'TRUE';
			if(!empty($mainId)){
				$mainMenu = $this->getAdapter()->quoteInto('mm.main_id = ?', $mainId);
			}
			
			$select = $this->getAdapter()->select();
			$select->from(array('menu' => 'acl_menu_resource'),	array('main_id'	=> 	'main_id',
					'rec_name'	=>	'r.resource_name',
					'rec_id'	=>	'r.id',
					'module'	=>	'r.module',
					'controller'=>	'r.controller',
					'action'	=>	'r.action',
					'mr_id'		=>	'menu.id',
					'link_image'=>	'r.link_image',
					'sort_seq'	=>	'sort_seq'))
					->join(array('mm' => 'acl_menu_main'), 'menu.main_id = mm.main_id', array())
					->join(array('r' => 'acl_resource'), 'r.id = menu.resource_id', array())
					->where($mainMenu)
					->order(array('sort_seq', 'r.resource_name'));

			return $this->getAdapter()->query($select)->fetchAll();

		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}
	}

	public function addMenuChild($mainId, $resId, $sortSeq)
	{
		try{
			//new row.  Insert Row with defaults set
			$row= $this->createRow();
			$row->main_id = $mainId;
			$row->resource_id = $resId;
			$row->sort_seq = $sortSeq;
				
			$row->save();
			return true;

		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}

	}
	
	public function removeMenuChild($mainId, $resId)
	{
		try{
			//find row.  if not exists, create, otherwise update
			$row = $this->find($resId)->current();

			if(!$row) {
				throw new Base_Exception('Menu Resource ID not found');
			}
			
			if($row['main_id'] != $mainId){
				throw new Base_Exception('Id does not belong to Main ID');
			}
	
			$row->delete();
			return true;
	
		} catch (Base_Exception $e){
			throw $e;
		} catch(Zend_Db_Exception $e){
			throw new Base_Db_Exception(__METHOD__ . $e->getMessage());
		}
	
	}
}
<?php
class Model_Device extends Base_Db_Table {

	protected function _setup(){

		$this->_name = 'device';
		$this->_primary = array(
				'id' 
		);
		parent::_setup();
	}

	/**
	 *
	 * @param unknown $data        	
	 * @throws Base_Exception_Validate
	 * @throws Base_Exception
	 * @return Ambigous <mixed, multitype:>
	 */
	public function save($data){

		try{
			// find row. if not exists, create, otherwise update
			$row = $this->find($data->id)
				->current();
			
			if(! $row){
				// new row. Insert Row with defaults set
				
				if(self::doesNameExist($obj->device_name)){
					throw new Base_Exception_Validate('Device Name "' . $data->device_name . '" already exists');
				}
				
				$row = $this->createRow();
				
				// get default state
				
				$row->state = $data->state;
			}
			
			// set the row data
			$row->device_name = $data->device_name;
			$row->device_type = $data->device_type;
			$row->group_id = $data->group_id;
			
			$row->ctrl_user = $this->_createUser;
			$row->ctrl_dt = $this->_createDt;
			// save the updated row
			
			return $row->save();
		} catch(Zend_Db_Exception $e){
			throw new Base_Exception(__METHOD__ . $e->getMessage());
		}
	}

	public function getAllDevices($account, $groupId = null){

		$select = $this->getAdapter()
			->select()
			->from($this->_name, array(
				'id' => 'id',
				'acc_id' => 'acc_id',
				'device_name' => 'device_name',
				'device_type' => 'device_type',
				'group_id' => 'group_id',
				'state' => 'state',
				'cmd_0' => 'cmd_0',
				'cmd_1' => 'cmd_1',
				'cmd_2' => 'cmd_2',
				'ctrl_user' => 'ctrl_user',
				'ctrl_dt' => 'ctrl_dt',
				'group_name' => 'group.group_name',
				'sort_seq' => 'sort_seq' 
		))
			->join('group', 'device.group_id = group.group_id', array(
				'group_name' => 'group_name',
				'group_sort_seq' => 'sort_seq'
		))
			->join('device_type', 'device.device_type = device_type.device_type', array(
				'type_code' => 'type_code',
				'dflt_state' 
		))
			->where('acc_id = ?', $account)
			->order((array(
				'group.sort_seq',
				'device_name' 
		)));
		
		if(! empty($groupId)){
			$select->where('device.group_id = ?', $groupId);
		}
		
		return $this->getAdapter()
			->query($select)
			->fetchAll();
	}

	public function changeState($accountId, $deviceId, $newState){

		try{
			$row = $this->find($deviceId)
				->current();
			
			if(empty($row)){
				throw new Base_Exception_Validate('Device not found');
			}
			if($row->acc_id != $accountId){
				throw new Base_Exception_Validate('Device not accessible');
			}
			
			$row->state = $newState;
			$row->ctrl_dt = $this->_createDt;
			$row->ctrl_user = $this->_createUser;
			
			$row->save();
		} catch(Base_Exception_Validate $e){
			throw $e;
		} catch(Zend_Db_Exception $e){
			throw new Base_Exception(__METHOD__ . $e->getMessage());
		}
	}
}
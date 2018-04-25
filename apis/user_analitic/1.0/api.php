<?php
/*
	GoodCoder 2018
*/
class GcUserAnalitic
{
	public static $type = 'user_analitic';
	public static $version = '1.0';
	public static $action = '';

	public static $actionForApi = array(
											'setUserInfo',
											'getUserInfo',
											'getUsersInfo',
											'getRowInfo',
											'updateRowInfo',
											'deleteUserInfo'
										);

	public $db;
	public $api_params = array();

	public function run()
	{
		global $db;
		$this->db = $db;
		self::$action = GC_Tools::getValue('gc_api_action');
		if (empty(self::$action))
		{
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('api action cant be empty!'), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
		}
		if (!in_array(self::$action, self::$actionForApi))
		{
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('this api not have called action!'), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
		}
		$this->api_params = json_decode(GC_Tools::getValue('gc_api_params'), true);
		$action = self::$action;
		$this->$action();
	}



	public function updateRowInfo()
	{
		$apiParams = json_decode(GC_Tools::getValue('gc_api_params'), true);
		if (!isset($apiParams['row_id']))
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('row_id cant be empty'), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
		
		$rowId = $apiParams['row_id'];
		unset($apiParams['row_id']);
		$res = $this->db->update('api_user_analitic', 
					array(
						'body' => json_encode($apiParams),
					),
					array(
						'id' => $rowId
					));
		if ($res)
			GC_Tools::printRes(array('error' => false, 'message' => GC_Tools::__('User data was be updated!'), 'row_id' => $rowId, 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
		else
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('User data was not be updated!'), 'row_id' => $rowId, 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
	}


	public function deleteUserInfo()
	{
		if (!isset($this->api_params['user_id']))
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('user_id cant be empty'), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));

		$res = $this->db->delete(
								'api_user_analitic',
								array(
									'api_key' => GC_Tools::getValue('gc_api_key'),
									'user_id' => $this->api_params['user_id'],
								)
							);
		if ($res)
			GC_Tools::printRes(array('error' => false, 'message' => GC_Tools::__('Information about this user was be delete'), data => array(), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
		else
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('You can`t delete information about this user'), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
	}



	public function getRowInfo()
	{
		if (!isset($this->api_params['row_id']))
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('row_id cant be empty'), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
		$res = $this->db->select(
								'api_user_analitic',
								array(
									'conditions' => array(
											'api_key' => GC_Tools::getValue('gc_api_key'),
											'id' => $this->api_params['row_id'],
										),
									'fields' => 'id as row_id, user_id, body, ip, time as create_time'
								)
							);
		GC_Tools::printRes(array('error' => false, 'message' => GC_Tools::__('User info was be get'), 'data' => json_encode($res), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
	}


	public function getUsersInfo()
	{
		$res = $this->db->select(
								'api_user_analitic',
								array(
									'conditions' => array(
											'api_key' => GC_Tools::getValue('gc_api_key'),
										),
									'fields' => 'id as row_id, user_id, body, ip, time as create_time'
								)
							);
		GC_Tools::printRes(array('error' => false, 'message' => GC_Tools::__('User info was be get'), 'data' => json_encode($res), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
	}


	public function getUserInfo()
	{
		if (!isset($this->api_params['user_id']))
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('user_id cant be empty'), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
		$res = $this->db->select(
								'api_user_analitic',
								array(
									'conditions' => array(
											'user_id' => $this->api_params['user_id'],
											'api_key' => GC_Tools::getValue('gc_api_key'),
										),
									'fields' => 'id as row_id, user_id, body, ip, time as create_time'
								)
							);
		GC_Tools::printRes(array('error' => false, 'message' => GC_Tools::__('User info was be get'), 'data' => json_encode($res), 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
	}


	public function setUserInfo()
	{
		if (isset($this->api_params['user_id']))
			$user_id = $this->api_params['user_id'];
		else 
			$user_id = '';
		$res = $this->db->insert('api_user_analitic', array(
					'api_key' => GC_Tools::getValue('gc_api_key'),
					'body' => GC_Tools::getValue('gc_api_params'),
					'user_id' => $user_id,
					'ip' => GC_Tools::getValue('ip'),
					'time' => time()
				));
		if ($res)
			GC_Tools::printRes(array('error' => false, 'message' => GC_Tools::__('User data was be saved!'), 'row_id' => $res, 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
		else
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('User data was not be saved!'), 'row_id' => $res, 'gc_api_type' => self::$type, 'gc_api_version' => self::$version, 'gc_api_action' => self::$action));
	}
}

?>
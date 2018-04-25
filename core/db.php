<?php
/*
	GoodCoder 2018
*/
class GcDB
{
	public static $host = 'localhost';
	public static $user = 'root';
	public static $password = '';
	public static $db_name = 'gc_apis';
	public static $tablePrefix = 'gc_';


	public function __construct()
	{
		$this->db = new PDO('mysql:host='.self::$host.';dbname='.self::$db_name.';charset=utf8', self::$user, self::$password);
	}

	public function insert($table, $data)
	{
		$tableName = self::$tablePrefix.$table;
		$columnNames = array();
		$columnValues = array();
		foreach ($data as $columnName => $columnValue)
		{
			$columnNames[] = addslashes($columnName);
			$columnValues[] = addslashes($columnValue);
		}
		$sql = "INSERT INTO ".$tableName." (".implode(', ', $columnNames).") VALUES ('".implode("', '", $columnValues)."')";

		$prepere = $this->db->prepare($sql);
		if ($prepere->execute())
			return $this->db->lastInsertId();
		else
			return false;
	}


	public function select($table, $params = array())
	{
		$wereParams = '1=1';
		if (isset($params['conditions']) && !empty($params['conditions']))
		{
			if (gettype($params['conditions']) == 'string')
				$wereParams .= ' AND ('.$params['conditions'].')';
			else if (gettype($params['conditions']) == 'array')
			{
				$where = array();
				foreach ($params['conditions'] as $fieldName => $fieldValue)
				{
					$where[] = $fieldName."='".addslashes($fieldValue)."'";
				}
				$wereParams .= ' AND ('.implode(' AND ', $where).')';
			}
		}
		$fileds = '*';
		if (isset($params['fields']) && !empty($params['fields']))
		{
			if (gettype($params['fields']) == 'string')
				$fileds = $params['fields'];
		}
		$tableName = self::$tablePrefix.$table;
		$sql = "SELECT ".$fileds." FROM ".$tableName." WHERE ".$wereParams;
		$statement = $this->db->prepare($sql);
		$statement->execute();
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		return $rows;
	}



	public function update($table, $values, $where)
	{
		if (empty($table) || empty($values) || empty($where) || !is_array($values) || !is_array($where))
			return false;
		$tableName = self::$tablePrefix.$table;
		$value_for_set = '';
		foreach ($values as $field_name => $field_value) 
		{
			$value_for_set .= $field_name." = '".addslashes($field_value)."',";
		}
		$value_for_set = trim($value_for_set, ',');
		$where_update = '';
		foreach ($where as $where_fild_name => $where_field_value)
		{
			$where_update = $where_fild_name."='".addslashes($where_field_value)."' AND ";
		}
		$where_update = trim($where_update);
		$where_update = trim($where_update, 'AND');
		$where_update = trim($where_update);

		$sql = "UPDATE ".$tableName." SET ".$value_for_set." WHERE ".$where_update;
		$statement = $this->db->prepare($sql);
		return $statement->execute();
	}



	public function delete($table, $params)
	{
		if (empty($params))
			return false;
		$tableName = self::$tablePrefix.$table;
		$where = '';
		if (gettype($params) == 'string')
			$where = $params;
		else if (is_array($params))
		{
			foreach ($params as $filesdName => $fieldValue)
			{
				if (empty($where))
					$where .= $filesdName."='".addslashes($fieldValue)."'";
				else
					$where .= " AND ".$filesdName."='".addslashes($fieldValue)."'";
			}
		}
		if (empty($where))
			return false;
		$sql = "DELETE FROM ".$tableName." WHERE ".$where;
		$statement = $this->db->prepare($sql);
		$statement->execute();
		if ($statement->rowCount() > 0) 
		{
		  return true;
		}
		else 
		{
			return false;
		}
	}
}	
?>
<?php
/*
	GoodCoder 2018
*/
class GC_Tools
{
	public static function getValue($name = '')
	{
		if (empty($name))
		{
			return array_merge($_POST, $_GET, $_FILES);
		}
		if (isset($_POST[$name]))
			return $_POST[$name];
		if (isset($_GET[$name]))
			return $_GET[$name];
		if (isset($_FILES[$name]))
			return $_FILES[$name];
		return '';
	}

	public static function getRequest()
	{
		$request = array();
		if (!empty($_POST))
		{
			$request = array_merge($request, $_POST);
		}
		if (!empty($_GET))
		{
			$request = array_merge($request, $_GET);
		}
		return $request;
	}


	public static function printRes($array = array())
	{
		echo json_encode($array);
		exit();
	}


	public static function __($str = '')
	{
		return $str;
	}
}
?>
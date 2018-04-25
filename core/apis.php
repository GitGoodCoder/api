<?php
/* 
	GoodCoder 2018
*/
class APIS
{
	/* md5('GcUserAnalitic'.time()) - key generate */

	public static $gefaultApiFile = 'api.php';
	public static $runApiMethod = 'run';
	public static $globalApiKey = '117684b9ac9809286908fd483bf957d1';

	public static $apis_array = array(
										'user_analitic' => array(
															'1.0' => array(
																			'class' => 'GcUserAnalitic',
																			'apis_key' => 'ac83f3e4a9f6a03b63ca2bed1827ccbb',
																			'free' => true
																			)
														)
									);


	public function __construct($type, $version)
	{
		if (!isset(self::$apis_array[$type]))
		{
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('this api type was not found!'), 'gc_api_type' => $type));
		}

		$apiTypes = self::$apis_array[$type];
		if (!isset($apiTypes[$version]))
		{
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('this api type was not found!'), 'gc_api_type' => $type, 'gc_api_version' => $version));
		}

		$apisDetail = $apiTypes[$version];
		if ($this->checkAccess($apisDetail))
		{
			$requireFile = GC_APIS_FOLDER.$type.DS.$version.DS.self::$gefaultApiFile;
			include_once($requireFile);
			$apiClassName = $apisDetail['class'];
			$runAction = self::$runApiMethod;
			$apiObject = new $apiClassName();
			$apiObject->$runAction();
		}
	}


	public function checkAccess($apiDetails)
	{
		global $db;
		$db->insert('apis_requests', array(
			'api_key' => GC_Tools::getValue('gc_api_key'),
			'api_type' => GC_Tools::getValue('gc_api_type'),
			'api_version' => GC_Tools::getValue('gc_api_version'),
			'api_action' => GC_Tools::getValue('gc_api_action'),
			'api_request' => json_encode(GC_Tools::getValue()),
			'time' => time(),
		));

		if ($apiDetails['free'])
			return true;

		$res = $db->select('apis_access', array(
				'conditions' => array(
						'api_key' => GC_Tools::getValue('gc_api_key'),
						'apis_key' => $apiDetails['apis_key'],
						'access' => 1
					)
				
			));
		if (empty($res))
		{
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('this api key was not have access for this api!'), 'gc_api_type' => GC_Tools::getValue('gc_api_type'), 'gc_api_version' => GC_Tools::getValue('gc_api_version')));
		}
		return true;
	}

}
?>
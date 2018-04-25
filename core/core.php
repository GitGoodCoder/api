<?php
/* 
	GoodCoder 2018
*/
class GC_Core_Api
{

	/*
		gc_api_type = '',
		gc_api_version = '',
		gc_api_key = ''
	*/

	public function __construct()
	{
		$this->request = GC_Tools::getRequest();
		if (!isset($this->request['gc_api_type']) || empty($this->request['gc_api_type']))
		{
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('api type was not found!')));
		}

		if (!isset($this->request['gc_api_version']) || empty($this->request['gc_api_version']))
		{
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('api version was not found!')));
		}

		if (!isset($this->request['gc_api_key']) || empty($this->request['gc_api_key']))
		{
			GC_Tools::printRes(array('error' => true, 'message' => GC_Tools::__('api key was not found!')));
		}

		$type = $this->request['gc_api_type'];
		$version = $this->request['gc_api_version'];

		$APIS = new APIS($type, $version);
	}

}
?>
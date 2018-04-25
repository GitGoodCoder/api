<?php
/*
	GoodCoder 2018
*/

function debug($var = Null, $exit = true){echo '<pre>'; var_dump($var); echo '</pre>'; if ($exit) exit();}



$GcApi = new GcApi('user_analitic', '1.0', '117684b9ac9809286908fd483bf957d1');
debug($GcApi->call('updateRowInfo', array('row_id' => 7,'user_id' => 1, 'user_name' => 'Oleg', 'user_sename' => 'Revera')));









class GcApi
{
	protected $gc_api_type;
	protected $gc_api_version;
	protected $gc_api_key;

	protected $gc_api_action;
	protected $gc_api_params = array();

	protected $requestUrl = 'http://api.net';

	public function __construct($gc_api_type = '', $gc_api_version = '', $gc_api_key = '')
	{
		$this->gc_api_type = $gc_api_type;
		$this->gc_api_version = $gc_api_version;
		$this->gc_api_key = $gc_api_key;
		if (empty($this->gc_api_type))
			$this->error('api type cant be empty!');
		if (empty($this->gc_api_version))
			$this->error('api version cant be empty!');
		if (empty($this->gc_api_key))
			$this->error('api key cant be empty!');
	}


	public function call($action = '', $params = array())
	{
		if (empty($action))
			$this->error('api action cant be empty!');
		if (!is_array($params))
			$this->error('api params must be array!');
		$this->gc_api_action = $action;
		$this->gc_api_params = $params;
		return $this->sendRequest();
	}



	protected function sendRequest()
	{
		$sendParams = array(
								'gc_api_type' => $this->gc_api_type,
								'gc_api_version' => $this->gc_api_version,
								'gc_api_key' => $this->gc_api_key,
								'gc_api_action' => $this->gc_api_action,
								'gc_api_params' => json_encode($this->gc_api_params),
								'ip' => $this->get_client_ip()
							);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->requestUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sendParams);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		if ($info['http_code'] == '200')
		{
			$res = json_decode($output, true);
			return $res;
		}
		else
			$this->error('API server does not respond! Please write to our support. https://goodcoder.pw');
	}



	protected function error($error = Null)
	{	
		echo '<span style="color: red; font-weight: bold;">GC Api error:</span><pre>';
		var_dump($error);
		echo '</pre>';
		exit();
	}


	protected function get_client_ip() 
	{
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}

?>
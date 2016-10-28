<?php 

abstract class Loop54_Utils
{
	static function escape($val)
	{
		//type hinting
		if (!is_string($val)) {
			trigger_error("Argument val must be string.");
			return;
		}
    
		$val = str_replace("\"","\\\"",$val);
	
		return $val;
	}

	static function randomString($length)
	{
		//type hinting
		if (!is_int($length)) {
			trigger_error("Argument length must be int.");
			return;
		}

		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		
		return $randomString;
	}

	static function getUser()
	{
		$existingCookie = null;
		
		if(isset($_COOKIE{'Loop54User'}))
			$existingCookie = $_COOKIE{'Loop54User'};
		
		if($existingCookie !== null)
			return $existingCookie;
			
		$userId = str_replace(":","",Loop54_Utils::getIP()) . "_" . Loop54_Utils::randomString(10);
		
		setCookie('Loop54User',$userId,time() + (86400 * 365),"/"); // 1 year cookie
		
		return $userId;
	}

	static function setCookie($key,$value,$time)
	{
		try {

			setcookie($key,$value,$time); //set the cookie
			$_COOKIE{$key} = $value; //set this so that subsequent calls on same pageview get the value
		}
		catch(Exception $exception){
			//TODO: log this
		}
	}


	static function getIP()
	{
		//Just get the headers if we can or else use the SERVER global
		if ( function_exists( 'apache_request_headers' ) ) {

			$headers = apache_request_headers();

		} else {

			$headers = $_SERVER;

		}

		//Get the forwarded IP if it exists
		if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
			$the_ip = $headers['X-Forwarded-For'];
		} elseif ( array_key_exists( 'HTTP_X_FORWARDED_FOR', $headers ) && filter_var( $headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 )
		) {
			$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
		} else {
			$the_ip = filter_var( $_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 );
		}

		return $the_ip;
	}
	
	static function getUserAgent()
	{
		if(isset($_SERVER{'HTTP_USER_AGENT'}))
			return $_SERVER{'HTTP_USER_AGENT'};
		
		return null;
	}
	
	static function getUrl()
	{
		if(isset($_SERVER{'REQUEST_URI'}))
			return $_SERVER{'REQUEST_URI'};
		
		return null;
	}
	
	static function getReferer()
	{
		if(isset($_SERVER{'HTTP_REFERER'}))
			return $_SERVER{'HTTP_REFERER'};
		
		return null;
	}

	static function fixEngineUrl($url)
	{
		if (!is_string($url)) {
			trigger_error("Argument url must be string.");
			return;
		}
		
		$url = trim($url);
		if($url === "")
		{
			trigger_error("Argument url cannot be empty.");
			return;
		}
		
		$url = strtolower($url);
		
		if(!Loop54_Utils::stringBeginsWith($url,"http"))
			$url = "http://" . $url;
			
		if(!Loop54_Utils::stringEndsWith($url,"/"))
			$url = $url . "/";
			
		return $url;
	}
	
	static function stringBeginsWith( $str, $sub ) {
		return ( substr( $str, 0, strlen( $sub ) ) === $sub );
	}

	// return tru if $str ends with $sub
	static function stringEndsWith( $str, $sub ) {
		return ( substr( $str, strlen( $str ) - strlen( $sub ) ) === $sub );
	}
	
	static function serializeObject($data)
	{
		if ($data instanceof Loop54_Entity)
			return $data->serialize();
		else if ($data instanceof Loop54_Event)
			return $data->serialize();
		else if (is_array($data))
		{
			if(Loop54_Utils::isAssoc($data))
			{
				$ret = "{";
				foreach ($data as $key => $value)
				{
					$ret .= Loop54_Utils::serializeObject($key) . ":" . Loop54_Utils::serializeObject($value) . ",";
				}
				
				$ret = rtrim($ret,',') . "}";
				
				return $ret;
			}
			else
			{
				$ret = "[";
				foreach($data as $dataVal)
				{
					$ret .= Loop54_Utils::serializeObject($dataVal) . ",";
				}
				
				$ret = rtrim($ret,',') . "]";
				
				return $ret;
			}
		}
		else
		{
			return json_encode($data);
		}
	}
	
	static function isAssoc($arr)
	{
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}

?>
<?php



abstract class Loop54_RequestHandling
{

	public static function getResponse($engineUrl, Loop54_Request $request)
	{
		//type hinting
		if (!is_string($engineUrl)) {
			trigger_error("Argument engineUrl must be string.");
			return;
		}
	
		$engineUrl = Loop54_Utils::fixEngineUrl($engineUrl);
		
		$data = "{" . $request->serialize() . "}";
	
		try {
		
			$s = curl_init($engineUrl);
			
			curl_setopt($s,CURLOPT_POST,1); 
			curl_setopt($s,CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt($s,CURLOPT_POSTFIELDS,$data);
			curl_setopt($s,CURLOPT_TIMEOUT, 10);
			curl_setopt($s,CURLOPT_HTTPHEADER,array('Content-Type: text/plain; charset=UTF-8'));
			
			$response = curl_exec($s);
			
			if(curl_errno($s)){
				trigger_error('Curl error: ' . curl_error($s));
				return;
			}
			
			curl_close($s);
			
		}
		catch(Exception $ex)
		{
			trigger_error("Could not retrieve a response from " . $engineUrl);
			return;
		}

		
		$ret = new Loop54_Response($response,$request->name);
		
		return $ret;
	}
}
?>
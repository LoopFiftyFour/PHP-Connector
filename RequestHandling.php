<?php

abstract class Loop54_RequestHandling
{
	public static function getResponse($engineUrl, $request)
	{
		//type hinting
		if (!is_string($engineUrl)) {
			throw new Exception("Argument engineUrl must be string.");
		}
	
		$engineUrl = Loop54_Utils::fixEngineUrl($engineUrl);
		
		//inf V2.6 (and above) the quest name is in the Url
		if(!$request->options->v25Url)
			$engineUrl .= "/" . $request->name;
		
		$data = $request->serialize();
	
		try {
			$s = curl_init($engineUrl);
			
			curl_setopt($s,CURLOPT_POST,1); 
			curl_setopt($s,CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt($s,CURLOPT_POSTFIELDS,$data);
			curl_setopt($s,CURLOPT_TIMEOUT, $request->options->timeout);
			curl_setopt($s,CURLOPT_HTTPHEADER,array('Content-Type: text/plain; charset=UTF-8'));
			
			$response = curl_exec($s);
			
			if(curl_errno($s)){
				throw new Exception('Curl error: ' . curl_error($s));
			}
			
			curl_close($s);
		}
		catch(Exception $ex)
		{
			throw new Exception("Could not retrieve a response from " . $engineUrl);
		}

		$ret = new Loop54_Response($response,$request);
		
		return $ret;
	}
}

?>
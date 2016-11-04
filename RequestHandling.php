<?php

abstract class Loop54_RequestHandling
{	
	public static function getResponse($engineUrl, $request)
	{
		//type hinting
		if (!is_string($engineUrl)) {
			throw new Exception("Argument engineUrl must be string.");
		}
	
		$engineUrl = Loop54_Utils::fixEngineUrl($engineUrl). "/" . $request->name;
		
		$data = $request->serialize();
	
		try {
			$s = curl_init($engineUrl);
			
			curl_setopt($s,CURLOPT_POST,1); 
			curl_setopt($s,CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt($s,CURLOPT_POSTFIELDS,$data);
			curl_setopt($s,CURLOPT_TIMEOUT, $request->options->timeout);
			curl_setopt($s,CURLOPT_HTTPHEADER,array('Content-Type: text/plain; charset=UTF-8','Lib-Version: PHP:[VersionNumber]','Api-Version: V26'));
			
			if($request->options->gzip)
				curl_setopt($s,CURLOPT_ENCODING , "gzip");
			
			//cURL uses Keep-Alive by default, although unclear if connections are reused across multiple PHP requests
			
			$response = curl_exec($s);
			
			
			$length = curl_getinfo ($s,CURLINFO_CONTENT_LENGTH_DOWNLOAD );
			
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
		
		$ret->contentLength = $length;
		
		return $ret;
	}
}

?>
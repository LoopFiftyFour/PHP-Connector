<?php

class Loop54_Options
{
	public $v22Collections = false;
	public $v25Url = false;
	public $timeout = 10;
	public $gzip = true;
}

class Loop54_Request
{
	private $version = "[VersionNumber]";

	public $IP = null;
	public $userId = null;
	public $name = null;
	public $userAgent=null;
	public $url=null;
	public $referer=null;
	
	public $options = null;
	
	private $_data = array();
	
	function __construct($requestName,$options = null)
	{
		$this->name = $requestName;
		
		if($options)
			$this->options = $options;
		else
			$this->options = new Loop54_Options();
	}
	
	public function setValue($key,$value)
	{
		$this->_data[$key] = $value;
	}
	
	public function serialize()
	{
		if ($this->userId === null)
			$this->userId = Loop54_Utils::getUser();

		if ($this->IP === null)
			$this->IP = Loop54_Utils::getIP();
			
		if ($this->userAgent === null)
			$this->userAgent = Loop54_Utils::getUserAgent();

		if ($this->url === null)
			$this->url = Loop54_Utils::getUrl();
			
		if ($this->referer === null)
			$this->referer = Loop54_Utils::getReferer();
			
		$ret = "{";
			
		//in V2.5 (and below), all request data is wrapped in the quest name
		if($this->options->v25Url)
			$ret .= "\"" . $this->name . "\":{";

		if ($this->IP !== null)
			$ret .= "\"IP\":\"" . Loop54_Utils::escape($this->IP) . "\",";

		if ($this->userId !== null)
			$ret .= "\"UserId\":\"" . Loop54_Utils::escape($this->userId) . "\",";
			
		if ($this->userAgent !== null)
			$ret .= "\"UserAgent\":\"" . Loop54_Utils::escape($this->userAgent) . "\",";
			
		if ($this->url !== null)
			$ret .= "\"Url\":\"" . Loop54_Utils::escape($this->url) . "\",";
			
		if ($this->referer !== null)
			$ret .= "\"Referer\":\"" . Loop54_Utils::escape($this->referer) . "\",";
			
		$ret .= "\"LibraryVersion\":" . Loop54_Utils::serializeObject($this->version) . ",";

		foreach ($this->_data as $key=>$value)
		{
			if($value===null)
				continue;

			$ret .= "\"" . $key . "\":" . Loop54_Utils::serializeObject($value) . ",";
		}

		$ret = rtrim($ret,',');
		
		//in V2.5 (and below), all request data is wrapped in the quest name
		if($this->options->v25Url)
			$ret .= "}";
			
		$ret .= "}";
		
		return $ret;
	}
}

?>
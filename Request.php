<?php

class Loop54_Options
{
	public $v22Collections = false;
	public $v25Url = false;
}

class Loop54_Request
{
	public $IP=null;
	public $userId=null;
	
	
	public $name=null;
	public $options=null;
	
	private $_data=array();
	


	function __construct($requestName,$options=null)
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

		$ret = "{";
			
		//in V2.5 (and below), all request data is wrapped in the quest name
		if($this->options->v25Url)
			$ret .= "\"" . $this->name . "\":{";

		if ($this->IP !== null)
			$ret .= "\"IP\":\"" . Loop54_Utils::escape($this->IP) . "\",";

		if ($this->userId !== null)
			$ret .= "\"UserId\":\"" . Loop54_Utils::escape($this->userId) . "\",";

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
		
		//echo $ret;
		
		return $ret;
	}

}

?>
<?php



class Loop54_Request
{
	public $IP=null;
	public $userId=null;
	
	private $_data=array();
	
	public $name=null;
	
	

	function __construct($requestName)
	{
		$this->name = $requestName;
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

		$ret = "\"" . $this->name . "\":{";

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
			
		$ret .= "}";
		
		//echo $ret;
		
		return $ret;
	}

}

?>
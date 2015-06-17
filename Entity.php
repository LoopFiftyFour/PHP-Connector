<?php

class Loop54_Entity
{
	public $externalId;
	public $entityType;
	
	private $_attributes;

	function __construct($entityType, $externalId)
	{
		$this->entityType = $entityType;
		$this->externalId = $externalId;
		$this->_attributes = array();
	}
	
	public function hasAttribute($key)
	{
		return isset($_attributes[$key]);
	}
	
	public function getAttribute($key)
	{
		if(!array_key_exists($key,$this->_attributes))
			return null;
		
		return $this->_attributes[$key];
	}
	
	public function getStringAttribute($key)
	{
		if(!array_key_exists($key,$this->_attributes))
			return "";
		
		$ret = "";
		foreach($this->_attributes[$key] as $value)
		{
			$ret .= $value . ", ";
		}
		
		return rtrim(rtrim($ret,' '),',');
	}

	public function addAttribute($key, $value)
	{
		if(!array_key_exists($key,$this->_attributes))
			$this->_attributes[$key] = array();
		
		$this->_attributes[$key][] = $value;
	}

	public function setAttribute($key, $values)
	{
		if(is_array($values))
			$this->_attributes[$key] = $values;
		else
		{
			$list = array();
			$list[] = $values;
			
			$this->_attributes[$key] = $list;
		}
	}
	
	public function removeAttribute($key)
	{
		unset($_attributes[$key]);
	}

	public function serialize()
	{
		$ret = "{";
		$ret .= "\"EntityType\":\"" . $this->entityType . "\",";
		$ret .= "\"ExternalId\":\"" . $this->externalId . "\",";
		$ret .= "\"Attributes\":{";

		foreach ($this->_attributes as $key=>$values)
		{
			$ret .= "\"" . $key . "\":[";
			
			foreach ($values as $value)
			{
				if($value===null)
					continue;

				if (is_string($value))
					$ret .= "\"" . Loop54_Utils::escape($value) . "\",";
				else if ($value instanceof DateTime)
					$ret .= "\"" . $value . "\",";
				else
					$ret .= $value . ",";
			}

			$ret = rtrim($ret,',');
			$ret .= "],";
		}

		$ret = rtrim($ret,',');
		$ret .= "}}";

		return $ret;
	}
}

?>
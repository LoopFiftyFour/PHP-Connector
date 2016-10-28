<?php

class Loop54_EngineResponse
{
	public $success;
	public $requestId;
	public $_data;
	public $options = null;
	public $contentLength = null;
	
	function __construct($stringData, $request)
	{
		$this->options = $request->options;
		
		try {
			$json = json_decode($stringData);
		}
		catch(Exception $ex)
		{
			throw new Exception("Engine returned incorrectly formed JSON " . $ex . ": " . $stringData);
		}

		if($json === null)
		{
			throw new Exception("Engine returned incorrectly formed JSON: " . $stringData);
		}

		$responseObj = $json;

		//fail
		if ((bool)$responseObj->{"Success"} != true)
		{
			$this->success = false;
			$this->requestId = (string)$responseObj->{"RequestId"};

			return;
		}
		
		$data = $responseObj->{"Data"};
		
		$this->_data = $data;

		//success
		$this->success = true;
	}
}

class Loop54_Response extends Loop54_EngineResponse
{
	public function hasData($key)
	{
		return isset($this->_data->{$key});
	}
	
	public function getValue($key)
	{
		if(is_array($key))
			throw new Exception($key . " is a collection.");
	
		return $this->_data->{$key};
	}
	
	public function getCollection($key)
	{
		$origVal = $this->_data->{$key};
		
		if(!is_array($origVal))
			throw new Exception($key . " is not a collection.");
			
		$ret = array();
		
		foreach($origVal as $item)
		{
			if(is_object($item))
			{
				$i = new Loop54_Item();
				
				if(isset($item->{"Key"}))
				{
					$val = $item->{"Key"};
					
					if(is_object($val) && property_exists($val,"ExternalId") && property_exists($val,"EntityType"))
						$i->key = $i->entity = $this->ParseEntity($val);
					else if(is_string($val))
						$i->key = $i->string = $val;
					else
						$i->key = $val;
				}
			
				$i->value = $item->{"Value"};
				$ret[] = $i;
			}
			else
			{
				$ret[] = $item;
			}
		}
		
		return $ret;
	}
	
	private function ParseEntity($value)
	{
		$entity = new Loop54_Entity($value->{"EntityType"},$value->{"ExternalId"});
					
		if(isset($value->{"Attributes"}))
		{
			if(is_object($value->{"Attributes"}))
			{
				foreach($value->{"Attributes"} as $attrName => $attrValue)
				{
					$entity->setAttribute($attrName,$attrValue);
				}
			}
			else if(is_array($value->{"Attributes"}))
			{
				foreach($value->{"Attributes"} as $obj)
				{

					$attrName = $obj->{"Key"};
					$attrValue = $obj->{"Value"};
					$entity->setAttribute($attrName,$attrValue);
				}
			}
		}
		
		return $entity;
	}
}

class Loop54_Item 
{
	public $entity;
	public $string;
	public $value;
	public $key;
}

?>
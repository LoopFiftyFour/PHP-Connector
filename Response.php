<?php



class Loop54_EngineResponse
{
	public $success;
	public $errorCode;
	public $errorMessage;
	public $requestId;
	
	public $_data;
	
	function __construct($stringData, $questName)
	{

		try {
			$json = json_decode($stringData);
		}
		catch(Exception $ex)
		{
			trigger_error("Engine returned incorrectly formed JSON " . $ex . ": " . $stringData);
			return;
		}

		if($json==null)
		{
			trigger_error("Engine returned incorrectly formed JSON: " . $stringData);
			return;
		}

		$responseObj = $json;

		//fail
		if ((bool)$responseObj->{"Success"} != true)
		{
			$this->success = false;
			$this->errorCode = (int)$responseObj->{"Error_Code"};
			$this->errorMessage = (string)$responseObj->{"Error_Message"};
			$this->requestId = (string)$responseObj->{"RequestId"};

			return;
		}
		
		$data = $responseObj->{"Data"};
		
		$this->_data = $data->{$questName};

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
			trigger_error($key . " is a collection.");
	
		return $this->_data->{$key};
	}
	
	public function getCollection($key)
	{
		$origVal = $this->_data->{$key};
		
		if(!is_array($origVal))
			trigger_error($key . " is not a collection.");
			
		$ret = array();
		
		foreach($origVal as $item)
		{
			if(is_object($item))
			{
				$i = new Loop54_Item();
				
			
				if(isset($item->{"Entity"}))
				{
					$value = $item->{"Entity"};
					
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
					
					$i->entity = $entity;
				}
				
				if(isset($item->{"String"}))
				{
					$i->string = $item->{"String"};
					
				}
				
				$i->value = $item->{"Value"};
				$ret[]=$i;
				
			}
			else
			{
				$ret[] = $item;
			}
		}
		
		return $ret;
		
	}
}

class Loop54_Item 
{
	public $entity;
	public $string;
	public $value;

}

?>
<?php

class Loop54_Event
{
	public $entity=null;
	public $string=null;
	public $revenue=0;
	public $orderId;
	public $type;
	public $quantity=1;

	public function serialize()
	{
	
		$str = "{".
			"\"OrderId\":\"" . $this->orderId . "\"".
			",\"Type\":\"" . $this->type . "\"".
			",\"Revenue\":" . $this->revenue;
			",\"Quantity\":" . $this->quantity;
			
			if ($this->string != null)
			{
				$str .= ",\"String\":" . $this->string . "\"";
			}
				
			if ($this->entity != null)
			{
				$str .= ",\"Entity\":" . $this->entity->serialize();
			}
		$str .= "}";

		return $str;
	}

}

?>
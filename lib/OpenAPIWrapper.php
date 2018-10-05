<?php
namespace Loop54\API;

trait OpenAPIWrapper
{
    private $openAPIObject;

    private function wraps($openAPIObject)
    {
        $this->openAPIObject = $openAPIObject;
    }

    public function getRaw()
    {
        return $this->openAPIObject;
    }
}

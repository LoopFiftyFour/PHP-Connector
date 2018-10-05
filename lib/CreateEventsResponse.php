<?php
namespace Loop54\API;

class CreateEventsResponse implements Response
{
    use OpenAPIWrapper;

    public function __construct($response)
    {
        $this->wraps($response);
    }
}

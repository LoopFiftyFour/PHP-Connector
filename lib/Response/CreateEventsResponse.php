<?php
namespace Loop54\API\Response;

use Loop54\API\OpenAPIWrapper;

class CreateEventsResponse implements Response
{
    use OpenAPIWrapper;

    public function __construct($response)
    {
        $this->wraps($response);
    }
}

<?php
namespace Loop54\API\Response;

use Loop54\API\Entity;
use Loop54\API\OpenAPIWrapper;

class GetRecentEntitiesResponse implements Response
{
    use OpenAPIWrapper;

    public function __construct($response)
    {
        $this->wraps($response);
    }

    public function getFacets()
    {
        return $this->getRaw()->getResults()->getFacets();
    }

    public function getResults()
    {
        return array_map(
            function ($entity) {
                return new Entity($entity);
            },
            $this->getRaw()->getResults()->getItems()
        );
    }
}

<?php
namespace Loop54\API;

class GetBasketRecommendationsResponse implements Response
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

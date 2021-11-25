<?php
namespace Loop54\API;

class GetBasketRecommendationsRequest implements Request
{
    use OpenAPIWrapper;

    public function __construct($entities)
    {
        $this->wraps(new OpenAPI\Model\GetBasketRecommendationsRequest([
            'entities' => array_map(function ($entity) { return $entity->getRaw(); }, $entities)
		]));
    }

    /**
     * Manipulate the options (skip, take, facets, filters, etc) for the
     * results.
     *
     * @return ResultsOptions
     */
    public function resultsOptions()
    {
        if ($this->getRaw()->getResultsOptions() == null) {
            $this->getRaw()->setResultsOptions(
                new OpenAPI\Model\EntityCollectionParameters()
            );
        }
        return new ResultsOptions($this->getRaw()->getResultsOptions());
    }

    public function perform(
        $instance,
        $apiversion,
        $libversion,
        $apikey,
        $userid,
        $userip,
        $useragent,
        $referer
    ) {
        return new GetBasketRecommendationsResponse(
            $instance->getBasketRecommendationsPost(
                $apiversion,
                $userid,
                $this->getRaw(),
                $userip,
                $useragent,
                $referer,
                $libversion,
                $apikey
            )
        );
    }
}

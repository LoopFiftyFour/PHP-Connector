<?php
namespace Loop54\API\Request;

use Loop54\API\OpenAPI;
use Loop54\API\OpenAPIWrapper;
use Loop54\API\Request\Parameters\ResultsOptions;
use Loop54\API\Response\GetRecommendedEntitiesResponse;

class GetRecommendedEntitiesRequest implements Request
{
    use OpenAPIWrapper;

    public function __construct()
    {
        $this->wraps(new OpenAPI\Model\GetRecommendedEntitiesRequest());
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
        return new GetRecommendedEntitiesResponse(
            $instance->getRecommendedEntitiesPost(
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

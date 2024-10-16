<?php
namespace Loop54\API;

class GetPopularEntitiesRequest implements Request
{
    use OpenAPIWrapper;
    const CURRENT_USER_PLACEHOLDER = "(CurrentUser)";

    public function __construct($behaviorType, $entityType, $forUserId)
    {
        $this->wraps(new OpenAPI\Model\GetRecentEntitiesRequest([
            'behavior_type' => $behaviorType,
            'entity_type' => $entityType,
            'for_user_id' => $forUserId,
        ]));
    }

    public static function GetPopularEntitiesRequestForCurrentUser($behaviorType, $entityType)
    {
        return new GetPopularEntitiesRequest($behaviorType, $entityType, self::CURRENT_USER_PLACEHOLDER);
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
        return new GetPopularEntitiesResponse(
            $instance->getPopularEntitiesPost(
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

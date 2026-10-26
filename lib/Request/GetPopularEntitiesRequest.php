<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\OpenAPI\Model;
use Loop54\API\Request\Parameters\ResultsOptions;
use Loop54\API\Response\GetPopularEntitiesResponse;

final class GetPopularEntitiesRequest implements Request
{
    public const CURRENT_USER_PLACEHOLDER = "(CurrentUser)";

    private readonly Model\GetRecentEntitiesRequest $raw;

    /**
     * @param string $behaviorType
     *    Interaction or navigation type to include (such as click, purchase
     *    or search).
     *
     * @param string[]|null $entityType
     *    Entity types to include (such as Product or Query) or null for all
     *    types.
     *
     * @param string|null $forUserId
     *    User ID to retrieve the most common entities for that user, or null
     *    to retrieve the globally most common entities.
     */
    public function __construct(string $behaviorType, ?array $entityType, ?string $forUserId)
    {
        $this->raw = new Model\GetRecentEntitiesRequest([
            'behavior_type' => $behaviorType,
            'entity_type' => $entityType,
            'for_user_id' => $forUserId,
        ]);
    }

    /**
     * @param string[]|null $entityType
     */
    public static function GetPopularEntitiesRequestForCurrentUser(string $behaviorType, ?array $entityType): self
    {
        return new self($behaviorType, $entityType, self::CURRENT_USER_PLACEHOLDER);
    }

    public function getRaw(): Model\GetRecentEntitiesRequest
    {
        return $this->raw;
    }

    /**
     * Manipulate the options (skip, take, facets, filters, etc) for the
     * results.
     */
    public function resultsOptions(): ResultsOptions
    {
        if ($this->raw->getResultsOptions() === null) {
            $this->raw->setResultsOptions(new Model\EntityCollectionParameters());
        }
        return new ResultsOptions($this->raw->getResultsOptions());
    }

    public function perform(RequestContext $context): GetPopularEntitiesResponse
    {
        return new GetPopularEntitiesResponse($context->userInitiatedApi->getPopularEntitiesPost(
            api_version: $context->apiVersion,
            user_id: $context->userId,
            get_popular_entities_request: $this->raw,
            user_ip: $context->userIp,
            user_useragent: $context->userAgent,
            user_referer: $context->referer,
            lib_version: $context->libVersion,
            loop54_key: $context->apiKey,
        ));
    }
}

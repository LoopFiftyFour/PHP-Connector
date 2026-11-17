<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\Entity;
use Loop54\API\OpenAPI\Model;
use Loop54\API\Request\Parameters\ResultsOptions;
use Loop54\API\Response\GetBasketRecommendationsResponse;

final class GetBasketRecommendationsRequest implements Request
{
    private readonly Model\GetBasketRecommendationsRequest $raw;

    /**
     * @param Entity[] $entities
     *    The set of entities in a basket for which to fetch recommendations for.
     */
    public function __construct(array $entities)
    {
        $this->raw = new Model\GetBasketRecommendationsRequest([
            'entities' => array_map(fn (Entity $entity) => $entity->getRaw(), $entities),
        ]);
    }

    public function getRaw(): Model\GetBasketRecommendationsRequest
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

    public function perform(RequestContext $context): GetBasketRecommendationsResponse
    {
        return new GetBasketRecommendationsResponse($context->userInitiatedApi->getBasketRecommendationsPost(
            api_version: $context->apiVersion,
            user_id: $context->userId,
            get_basket_recommendations_request: $this->raw,
            user_ip: $context->userIp,
            user_useragent: $context->userAgent,
            user_referer: $context->referer,
            lib_version: $context->libVersion,
            loop54_key: $context->apiKey,
        ));
    }
}

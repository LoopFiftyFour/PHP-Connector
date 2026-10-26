<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\Entity;
use Loop54\API\OpenAPI\Model;
use Loop54\API\Request\Parameters\ResultsOptions;
use Loop54\API\Response\GetComplementaryEntitiesResponse;

final class GetComplementaryEntitiesRequest implements Request
{
    private readonly Model\GetComplementaryEntitiesRequest $raw;

    public function __construct(Entity $entity)
    {
        $this->raw = new Model\GetComplementaryEntitiesRequest(['entity' => $entity->getRaw()]);
    }

    public function getRaw(): Model\GetComplementaryEntitiesRequest
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

    public function perform(RequestContext $context): GetComplementaryEntitiesResponse
    {
        return new GetComplementaryEntitiesResponse($context->userInitiatedApi->getComplementaryEntitiesPost(
            api_version: $context->apiVersion,
            user_id: $context->userId,
            get_complementary_entities_request: $this->raw,
            user_ip: $context->userIp,
            user_useragent: $context->userAgent,
            user_referer: $context->referer,
            lib_version: $context->libVersion,
            loop54_key: $context->apiKey,
        ));
    }
}

<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\OpenAPI\Model;
use Loop54\API\Request\Parameters\ResultsOptions;
use Loop54\API\Response\GetEntitiesResponse;

final class GetEntitiesRequest implements Request
{
    private readonly Model\GetEntitiesRequest $raw;

    public function __construct()
    {
        $this->raw = new Model\GetEntitiesRequest();
    }

    public function getRaw(): Model\GetEntitiesRequest
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

    public function perform(RequestContext $context): GetEntitiesResponse
    {
        return new GetEntitiesResponse($context->userInitiatedApi->getEntitiesPost(
            api_version: $context->apiVersion,
            user_id: $context->userId,
            get_entities_request: $this->raw,
            user_ip: $context->userIp,
            user_useragent: $context->userAgent,
            user_referer: $context->referer,
            lib_version: $context->libVersion,
            loop54_key: $context->apiKey,
        ));
    }
}

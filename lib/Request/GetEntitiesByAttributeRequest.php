<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\OpenAPI\Model;
use Loop54\API\Request\Parameters\RequestAliasFields;
use Loop54\API\Request\Parameters\ResultsOptions;
use Loop54\API\Response\GetEntitiesByAttributeResponse;

final class GetEntitiesByAttributeRequest implements Request
{
    private readonly Model\GetEntitiesByAttributeRequest $raw;

    /**
     * @param string $name
     *    The attribute to inspect.
     *
     * @param string|int|float|bool|array<string|int|float|bool> $value
     *    Include only entities with this value (or one of these values) for
     *    the attribute.
     */
    public function __construct(string $name, string|int|float|bool|array $value)
    {
        $this->raw = new Model\GetEntitiesByAttributeRequest([
            'attribute' => new Model\AttributeNameValuePair([
                'name' => $name,
                'value' => $value,
            ]),
        ]);
    }

    public function getRaw(): Model\GetEntitiesByAttributeRequest
    {
        return $this->raw;
    }

    /**
     * Manipulate the request alias for the request.
     */
    public function requestAlias(): RequestAliasFields
    {
        if ($this->raw->getRequestAlias() === null) {
            $this->raw->setRequestAlias(new Model\RequestAliasFields());
        }
        return new RequestAliasFields($this->raw->getRequestAlias());
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

    public function perform(RequestContext $context): GetEntitiesByAttributeResponse
    {
        return new GetEntitiesByAttributeResponse($context->userInitiatedApi->getEntitiesByAttributePost(
            api_version: $context->apiVersion,
            user_id: $context->userId,
            get_entities_by_attribute_request: $this->raw,
            user_ip: $context->userIp,
            user_useragent: $context->userAgent,
            user_referer: $context->referer,
            lib_version: $context->libVersion,
            loop54_key: $context->apiKey,
        ));
    }
}

<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\OpenAPI\Model;
use Loop54\API\Response\CustomResponse;

final class CustomRequest implements Request
{
    private readonly Model\CustomRequest $raw;

    /**
     * @param array<string, mixed>|object $customData
     */
    public function __construct(private readonly string $requestName, array|object $customData)
    {
        $this->raw = new Model\CustomRequest(['custom_data' => $customData]);
    }

    public function getRaw(): Model\CustomRequest
    {
        return $this->raw;
    }

    public function perform(RequestContext $context): CustomResponse
    {
        return new CustomResponse($context->otherApi->requestNamePost(
            request_name: $this->requestName,
            api_version: $context->apiVersion,
            loop54_key: $context->apiKey,
            custom_request: $this->raw,
            lib_version: $context->libVersion,
        ));
    }
}

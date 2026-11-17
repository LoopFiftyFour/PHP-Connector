<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\OpenAPI\Model;
use Loop54\API\Response\AutoCompleteResponse;

final class AutoCompleteRequest implements Request
{
    private readonly Model\AutoCompleteRequest $raw;

    /**
     * Construct an autocomplete request for a partial query.
     */
    public function __construct(string $query)
    {
        $this->raw = new Model\AutoCompleteRequest(['query' => $query]);
    }

    public function getRaw(): Model\AutoCompleteRequest
    {
        return $this->raw;
    }

    /**
     * Get the partial query this request will find autocompletions for.
     */
    public function getQuery(): string
    {
        return $this->raw->getQuery();
    }

    /**
     * Ask for at most this many autocomplete suggestions.
     */
    public function take(int $take): static
    {
        $this->queriesOptions()->setTake($take);
        return $this;
    }

    private function queriesOptions(): Model\QueryCollectionParameters
    {
        if ($this->raw->getQueriesOptions() === null) {
            $this->raw->setQueriesOptions(new Model\QueryCollectionParameters());
        }
        return $this->raw->getQueriesOptions();
    }

    public function perform(RequestContext $context): AutoCompleteResponse
    {
        return new AutoCompleteResponse($context->userInitiatedApi->autoCompletePost(
            api_version: $context->apiVersion,
            user_id: $context->userId,
            auto_complete_request: $this->raw,
            user_ip: $context->userIp,
            user_useragent: $context->userAgent,
            user_referer: $context->referer,
            lib_version: $context->libVersion,
            loop54_key: $context->apiKey,
        ));
    }
}

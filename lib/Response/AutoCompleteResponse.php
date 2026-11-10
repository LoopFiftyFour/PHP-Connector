<?php

declare(strict_types=1);

namespace Loop54\API\Response;

use Loop54\API\OpenAPI\Model;
use Loop54\API\Request\SearchRequest;

final class AutoCompleteResponse implements Response
{
    private readonly Model\AutoCompleteResponse $raw;

    public function __construct(Model\AutoCompleteResponse $response)
    {
        $this->raw = $response;
    }

    public function getRaw(): Model\AutoCompleteResponse
    {
        return $this->raw;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getScopedResult(): ?array
    {
        return $this->raw->getScopedQuery();
    }

    /**
     * @deprecated
     *
     * @return array<string, mixed>|null
     */
    public function getScopedResults(): ?array
    {
        trigger_error(
            'getScopedResults() has been renamed to getScopedResult()',
            E_USER_DEPRECATED
        );
        return $this->getScopedResult();
    }

    /**
     * @return array<array<string, mixed>>|null
     */
    public function getUnscopedResults(): ?array
    {
        return $this->raw->getQueries()->getItems();
    }

    /**
     * Get all autocomplete results (both scoped and unscoped) as instances of
     * {@see SearchRequest}, pre-filled with the suggested query and scope.
     * These can then either be sent off to the search engine as they are, or
     * presented in a regular search form where the user gets to further
     * refine their search.
     *
     * @return SearchRequest[]
     */
    public function getResultsAsSearchRequests(): array
    {
        $unScopedRequests = array_map(
            SearchRequest::fromQueryResult(...),
            $this->getUnscopedResults()
        );

        $scopedResult = $this->getScopedResult();
        if (!isset($scopedResult['query'])) {
            return $unScopedRequests;
        }

        $scopedRequest = SearchRequest::fromQueryResult($scopedResult);
        return array_merge(
            $scopedRequest,
            $unScopedRequests
        );
    }
}

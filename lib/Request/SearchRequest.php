<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\OpenAPI\Model;
use Loop54\API\Request\Parameters\ResultsOptions;
use Loop54\API\Response\SearchResponse;

final class SearchRequest implements Request
{
    private readonly Model\SearchRequest $raw;

    public function __construct(string $query)
    {
        $this->raw = new Model\SearchRequest(['query' => $query]);
    }

    /**
     * Create a search request from the query/queries suggested by a query
     * result. Returns an array of search requests (one for each scope value)
     * if the suggested query is scoped.
     *
     * @param array<string, mixed>|\ArrayAccess<string, mixed> $queryResult
     *
     * @return SearchRequest|SearchRequest[]
     */
    public static function fromQueryResult(array|\ArrayAccess $queryResult): self|array
    {
        $request = new self($queryResult['query']);
        if (!isset($queryResult['scopes'])) {
            return $request;
        }
        $requests = [];
        foreach ($queryResult['scopes'] as $value) {
            $request = new self($queryResult['query']);
            $request->resultsOptions()->addDistinctFacet(
                $queryResult['scopeAttributeName'],
                $queryResult['scopeAttributeName'],
                [$value]
            );
            $requests[] = $request;
        }
        return $requests;
    }

    public function getRaw(): Model\SearchRequest
    {
        return $this->raw;
    }

    /**
     * Get the query this search request is for.
     */
    public function getQuery(): string
    {
        return $this->raw->getQuery();
    }

    /**
     * Manipulate the options (skip, take, facets, filters, etc) for the
     * direct results.
     */
    public function resultsOptions(): ResultsOptions
    {
        if ($this->raw->getResultsOptions() === null) {
            $this->raw->setResultsOptions(new Model\EntityCollectionParameters());
        }
        return new ResultsOptions($this->raw->getResultsOptions());
    }

    /**
     * Manipulate the options (skip, take, facets, filters, etc) for the
     * related results.
     */
    public function relatedResultsOptions(): ResultsOptions
    {
        if ($this->raw->getRelatedResultsOptions() === null) {
            $this->raw->setRelatedResultsOptions(new Model\EntityCollectionParameters());
        }
        return new ResultsOptions($this->raw->getRelatedResultsOptions());
    }

    /**
     * Add Content Search options for one content entity type (e.g. "Brand").
     * Call once per content type to search. If options for that type were
     * already added, the existing options are returned instead of adding
     * a duplicate.
     *
     * @return ResultsOptions
     *    The options for that content type (skip, take, facets, filters, etc).
     *
     * @throws \InvalidArgumentException if $entityType is empty.
     */
    public function addContentResultsOptions(string $entityType): ResultsOptions
    {
        if (trim($entityType) === '') {
            throw new \InvalidArgumentException('entityType must be a non-empty string');
        }
        $all = $this->raw->getContentResultsOptions() ?? [];
        foreach ($all as $existing) {
            if ($existing?->getEntityType() === $entityType) {
                return new ResultsOptions($existing);
            }
        }
        $options = new Model\TypedEntityCollectionParameters(['entity_type' => $entityType]);
        $all[] = $options;
        $this->raw->setContentResultsOptions($all);
        return new ResultsOptions($options);
    }

    public function perform(RequestContext $context): SearchResponse
    {
        return new SearchResponse($context->userInitiatedApi->searchPost(
            api_version: $context->apiVersion,
            user_id: $context->userId,
            search_request: $this->raw,
            user_ip: $context->userIp,
            user_useragent: $context->userAgent,
            user_referer: $context->referer,
            lib_version: $context->libVersion,
            loop54_key: $context->apiKey,
        ));
    }
}

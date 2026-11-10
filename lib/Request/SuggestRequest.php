<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\OpenAPI\Model;
use Loop54\API\Request\Parameters\ResultsOptions;
use Loop54\API\Response\SuggestResponse;

final class SuggestRequest implements Request
{
    private readonly Model\SuggestRequest $raw;

    /**
     * Construct a suggest request for a partial query. The response contains
     * query suggestions as well as search results.
     */
    public function __construct(string $query)
    {
        $this->raw = new Model\SuggestRequest(['query' => $query]);
    }

    public function getRaw(): Model\SuggestRequest
    {
        return $this->raw;
    }

    /**
     * Get the partial query this request will find suggestions for.
     */
    public function getQuery(): string
    {
        return $this->raw->getQuery();
    }

    /**
     * Ask for at most this many query suggestions.
     */
    public function take(int $take): static
    {
        $this->queriesOptions()->setTake($take);
        return $this;
    }

    /**
     * Search with the top query suggestion instead of the query itself.
     */
    public function useTopSuggestionForSearch(bool $use = true): static
    {
        $this->raw->setUseTopSuggestionForSearch($use);
        return $this;
    }

    /**
     * Manipulate the options (skip, take, facets, filters, etc) for the
     * search results.
     */
    public function resultsOptions(): ResultsOptions
    {
        if ($this->raw->getResultsOptions() === null) {
            $this->raw->setResultsOptions(new Model\EntityCollectionParameters());
        }
        return new ResultsOptions($this->raw->getResultsOptions());
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

    private function queriesOptions(): Model\QueryCollectionParameters
    {
        if ($this->raw->getQueriesOptions() === null) {
            $this->raw->setQueriesOptions(new Model\QueryCollectionParameters());
        }
        return $this->raw->getQueriesOptions();
    }

    public function perform(RequestContext $context): SuggestResponse
    {
        return new SuggestResponse($context->userInitiatedApi->suggestPost(
            api_version: $context->apiVersion,
            user_id: $context->userId,
            suggest_request: $this->raw,
            user_ip: $context->userIp,
            user_useragent: $context->userAgent,
            user_referer: $context->referer,
            lib_version: $context->libVersion,
            loop54_key: $context->apiKey,
        ));
    }
}

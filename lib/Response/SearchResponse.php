<?php

declare(strict_types=1);

namespace Loop54\API\Response;

use Loop54\API\Entity;
use Loop54\API\OpenAPI\Model;
use Loop54\API\Request\SearchRequest;

final class SearchResponse implements Response
{
    private readonly Model\SearchResponse $raw;

    public function __construct(Model\SearchResponse $response)
    {
        $this->raw = $response;
    }

    public function getRaw(): Model\SearchResponse
    {
        return $this->raw;
    }

    public function getMakesSense(): ?bool
    {
        return $this->raw->getMakesSense();
    }

    /**
     * @return array<array<string, mixed>>|null
     */
    public function getSpellingSuggestions(): ?array
    {
        return $this->raw->getSpellingSuggestions()->getItems();
    }

    /**
     * @return array<SearchRequest|SearchRequest[]>
     */
    public function getSpellingSuggestionsAsSearchRequests(): array
    {
        return array_map(
            SearchRequest::fromQueryResult(...),
            $this->getSpellingSuggestions()
        );
    }

    /**
     * @return array<array<string, mixed>>|null
     */
    public function getRelatedQueries(): ?array
    {
        return $this->raw->getRelatedQueries()->getItems();
    }

    /**
     * @return array<SearchRequest|SearchRequest[]>
     */
    public function getRelatedQueriesAsSearchRequests(): array
    {
        return array_map(
            SearchRequest::fromQueryResult(...),
            $this->getRelatedQueries()
        );
    }

    /**
     * @return array<array<string, mixed>>|null
     */
    public function getFacets(): ?array
    {
        return $this->raw->getResults()->getFacets();
    }

    /**
     * @return Entity[]
     */
    public function getResults(): array
    {
        return array_map(
            fn ($entity) => new Entity($entity),
            $this->raw->getResults()->getItems()
        );
    }

    /**
     * @return Entity[]
     */
    public function getRelatedResults(): array
    {
        return array_map(
            fn ($entity) => new Entity($entity),
            $this->raw->getRelatedResults()->getItems()
        );
    }

    /**
     * Get the raw Content Search results for one content entity type.
     *
     * @return Model\EntityCollection|null
     *    Null if Content Search did not return results for that type.
     */
    public function getContentSearchResults(string $entityType): ?Model\EntityCollection
    {
        foreach ($this->raw->getContentSearchResults() ?? [] as $item) {
            if ($item->getEntityType() === $entityType) {
                return $item->getResults();
            }
        }
        return null;
    }

    /**
     * Get the Content Search results for one content entity type.
     *
     * @return Entity[] Empty if there are no results for that type.
     */
    public function getContentResults(string $entityType): array
    {
        $results = $this->getContentSearchResults($entityType);
        if ($results === null || $results->getItems() === null) {
            return [];
        }
        return array_map(
            fn ($entity) => new Entity($entity),
            $results->getItems()
        );
    }

    /**
     * Get the facets of the Content Search results for one content entity type.
     *
     * @return array<array<string, mixed>> Empty if there are no results for that type.
     */
    public function getContentFacets(string $entityType): array
    {
        $results = $this->getContentSearchResults($entityType);
        if ($results === null || $results->getFacets() === null) {
            return [];
        }
        return $results->getFacets();
    }
}

<?php

declare(strict_types=1);

namespace Loop54\API\Response;

use Loop54\API\Entity;
use Loop54\API\OpenAPI\Model;

final class SuggestResponse implements Response
{
    private readonly Model\SuggestResponse $raw;

    public function __construct(Model\SuggestResponse $response)
    {
        $this->raw = $response;
    }

    public function getRaw(): Model\SuggestResponse
    {
        return $this->raw;
    }

    public function getMakesSense(): ?bool
    {
        return $this->raw->getMakesSense();
    }

    /**
     * Get the query as it was sent in the request.
     */
    public function getOriginalQuery(): ?string
    {
        return $this->raw->getOriginalQuery();
    }

    /**
     * Get the query the search results were found for. Differs from the
     * original query when the top suggestion was used for search.
     */
    public function getQueryUsedForSearch(): ?string
    {
        return $this->raw->getQueryUsedForSearch();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getScopedQuery(): ?array
    {
        return $this->raw->getScopedQuery();
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getQueries(): array
    {
        return $this->raw->getQueries()?->getItems() ?? [];
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getFacets(): array
    {
        return $this->raw->getResults()?->getFacets() ?? [];
    }

    /**
     * @return Entity[]
     */
    public function getResults(): array
    {
        return array_map(
            fn ($entity) => new Entity($entity),
            $this->raw->getResults()?->getItems() ?? []
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
        return array_map(
            fn ($entity) => new Entity($entity),
            $this->getContentSearchResults($entityType)?->getItems() ?? []
        );
    }

    /**
     * Get the facets of the Content Search results for one content entity type.
     *
     * @return array<array<string, mixed>> Empty if there are no results for that type.
     */
    public function getContentFacets(string $entityType): array
    {
        return $this->getContentSearchResults($entityType)?->getFacets() ?? [];
    }
}

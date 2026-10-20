<?php
namespace Loop54\API;

class SearchResponse implements Response
{
    use OpenAPIWrapper;

    public function __construct($response)
    {
        $this->wraps($response);
    }

    public function getMakesSense()
    {
        return $this->getRaw()->getMakesSense();
    }

    public function getSpellingSuggestions()
    {
        return $this->getRaw()->getSpellingSuggestions()->getItems();
    }

    public function getSpellingSuggestionsAsSearchRequests()
    {
        return array_map(
            '\Loop54\API\SearchRequest::fromQueryResult',
            $this->getSpellingSuggestions()
        );
    }

    public function getRelatedQueries()
    {
        return $this->getRaw()->getRelatedQueries()->getItems();
    }

    public function getRelatedQueriesAsSearchRequests()
    {
        return array_map(
            '\Loop54\API\SearchRequest::fromQueryResult',
            $this->getRelatedQueries()
        );
    }

    public function getFacets()
    {
        return $this->getRaw()->getResults()->getFacets();
    }

    public function getResults()
    {
        return array_map(
            function ($entity) {
                return new Entity($entity);
            },
            $this->getRaw()->getResults()->getItems()
        );
    }

    public function getRelatedResults()
    {
        return array_map(
            function ($entity) {
                return new Entity($entity);
            },
            $this->getRaw()->getRelatedResults()->getItems()
        );
    }

    /**
     * Get the raw Content Search results for one content entity type.
     *
     * @param string $entityType
     *
     * @return OpenAPI\Model\EntityCollection|null
     *    Null if Content Search did not return results for that type.
     */
    public function getContentSearchResults($entityType)
    {
        foreach ($this->getRaw()->getContentSearchResults() ?? [] as $item) {
            if ($item->getEntityType() === $entityType) {
                return $item->getResults();
            }
        }
        return null;
    }

    /**
     * Get the Content Search results for one content entity type.
     *
     * @param string $entityType
     *
     * @return Entity[] Empty if there are no results for that type.
     */
    public function getContentResults($entityType)
    {
        $results = $this->getContentSearchResults($entityType);
        if ($results === null || $results->getItems() === null) {
            return [];
        }
        return array_map(
            function ($entity) {
                return new Entity($entity);
            },
            $results->getItems()
        );
    }

    /**
     * Get the facets of the Content Search results for one content entity type.
     *
     * @param string $entityType
     *
     * @return array Empty if there are no results for that type.
     */
    public function getContentFacets($entityType)
    {
        $results = $this->getContentSearchResults($entityType);
        if ($results === null || $results->getFacets() === null) {
            return [];
        }
        return $results->getFacets();
    }
}

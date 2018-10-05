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
}

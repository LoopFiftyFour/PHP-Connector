<?php
namespace Loop54\API;

class AutoCompleteResponse implements Response
{
    use OpenAPIWrapper;

    public function __construct($response)
    {
        $this->wraps($response);
    }

    public function getScopedResults()
    {
        return $this->getRaw()->getScopedQuery();
    }

    public function getUnscopedResults()
    {
        return $this->getRaw()->getQueries()->getItems();
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
    public function getResultsAsSearchRequests()
    {
        return array_merge(
            SearchRequest::fromQueryResult($this->getScopedResults()),
            array_map(
                '\Loop54\API\SearchRequest::fromQueryResult',
                $this->getUnscopedResults()
            )
        );
    }
}

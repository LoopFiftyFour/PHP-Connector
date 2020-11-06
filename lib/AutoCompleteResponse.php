<?php
namespace Loop54\API;

class AutoCompleteResponse implements Response
{
    use OpenAPIWrapper;

    public function __construct($response)
    {
        $this->wraps($response);
    }

    public function getScopedResult()
    {
        return $this->getRaw()->getScopedQuery();
    }

    /**
     * @deprecated
     */
    public function getScopedResults()
    {
        trigger_error(
            'getScopedResults() has been renamed to getScopedResult()',
            E_USER_DEPRECATED
        );
        return $this->getScopedResult();
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
        $unScopedRequests = array_map(
                '\Loop54\API\SearchRequest::fromQueryResult',
                $this->getUnscopedResults()
            );
        
        $scopedResult = $this->getScopedResult();
        if(!isset($scopedResult['query']))
            return $unScopedRequests;
        
        $scopedRequest = SearchRequest::fromQueryResult($scopedResult);
        return array_merge(
            $scopedRequest,
            $unScopedRequests
        );
    }
}

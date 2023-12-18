<?php
namespace Loop54\API;

class SearchRequest implements Request
{
    use OpenAPIWrapper;

    public function __construct($query)
    {
        $this->wraps(new OpenAPI\Model\SearchRequest([
            'query' => $query
        ]));
    }

    /**
     * Create a search request from the query/queries suggested by the given
     * {@see OpenAPI\Model\QueryResult}. Returns an array of search requests
     * (one for each scope value) if the suggested query is scoped.
     *
     * @param OpenAPI\Model\ScopedQueryResult|OpenAPI\Model\UnScopedQueryResult $queryResult
     *
     * @return SearchRequest|SearchRequest[]
     */
    public static function fromQueryResult($queryResult)
    {
        $request = new SearchRequest($queryResult['query']);
        if (!isset($queryResult['scopes'])) {
            return $request;
        }
        $requests = [];
        foreach ($queryResult['scopes'] as $value) {
            $request = new SearchRequest($queryResult['query']);
            $request->resultsOptions()->addDistinctFacet(
                $queryResult['scopeAttributeName'],
                $queryResult['scopeAttributeName'],
                [$value]
            );
            $requests[] = $request;
        }
        return $requests;
    }

    /**
     * Get the query this search request is for.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->getRaw()->getQuery();
    }

    /**
     * Manipulate the options (skip, take, facets, filters, etc) for the
     * direct results.
     *
     * @return ResultsOptions
     */
    public function resultsOptions()
    {
        if ($this->getRaw()->getResultsOptions() == null) {
            $this->getRaw()->setResultsOptions(
                new OpenAPI\Model\EntityCollectionParameters()
            );
        }
        return new ResultsOptions($this->getRaw()->getResultsOptions());
    }

    /**
     * Manipulate the options (skip, take, facets, filters, etc) for the
     * related results.
     *
     * @return ResultsOptions
     */
    public function relatedResultsOptions()
    {
        if ($this->getRaw()->getRelatedResultsOptions() == null) {
            $this->getRaw()->setRelatedResultsOptions(
                new OpenAPI\Model\EntityCollectionParameters()
            );
        }
        return new ResultsOptions($this->getRaw()->getRelatedResultsOptions());
    }

    public function perform(
        $instance,
        $apiversion,
        $libversion,
        $apikey,
        $userid,
        $userip,
        $useragent,
        $referer
    ) {
        return new SearchResponse($instance->searchPost(
            $apiversion,
            $userid,
            $this->getRaw(),
            $userip,
            $useragent,
            $referer,
            $libversion,
            $apikey
        ));
    }
}

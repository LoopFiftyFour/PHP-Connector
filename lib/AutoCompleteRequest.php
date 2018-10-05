<?php
namespace Loop54\API;

class AutoCompleteRequest implements Request
{
    use OpenAPIWrapper;

    /**
     * Construct an autocomplete request for a partial query.
     *
     * @param string $query
     *    The partial query we want autocompletions for.
     */
    public function __construct($query)
    {
        $this->wraps(new OpenAPI\Model\AutoCompleteRequest([
            'query' => $query
        ]));
    }

    /**
     * Get the query this autocomplete request will be for.
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->getRaw()->getQuery();
    }

    /**
     * Ask for at most a specific amount of autocomplete suggestions.
     *
     * @param int $take
     *    The amount of autocomplete suggestions requested.
     *
     * @return AutoComplete
     */
    public function take($take)
    {
        $this->queriesOptions()->setTake($take);
        return $this;
    }

    private function queriesOptions()
    {
        $queriesOptions = $this->getRaw()->getQueriesOptions();
        if (empty($queriesOptions)) {
            $queriesOptions = new OpenAPI\Model\QueryCollectionParameters();
            $this->getRaw()->setQueriesOptions($queriesOptions);
        }
        return $queriesOptions;
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
        return new AutoCompleteResponse($instance->autoCompletePost(
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

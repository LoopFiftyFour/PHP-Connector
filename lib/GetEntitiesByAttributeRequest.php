<?php
namespace Loop54\API;

class GetEntitiesByAttributeRequest implements Request
{
    use OpenAPIWrapper;

    public function __construct($name, $value)
    {
        $this->wraps(new OpenAPI\Model\GetEntitiesByAttributeRequest([
            'attribute' => new OpenAPI\Model\AttributeNameValuePair([
                'name' => $name,
                'value' => $value
            ])
        ]));
    }

    /**
     * Manipulate the request alias for the request
     *
     * @return RequestAliasFields
     */
    public function requestAlias()
    {
        if ($this->getRaw()->getRequestAlias() == null) {
            $this->getRaw()->setRequestAlias(
                new OpenAPI\Model\RequestAliasFields()
            );
        }

        return new RequestAliasFields($this->getRaw()->getRequestAlias());
    }

    /**
     * Manipulate the options (skip, take, facets, filters, etc) for the
     * results.
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
        return new GetEntitiesByAttributeResponse(
            $instance->getEntitiesByAttributePost(
                $apiversion,
                $userid,
                $this->getRaw(),
                $userip,
                $useragent,
                $referer,
                $libversion,
                $apikey
            )
        );
    }
}

<?php
namespace Loop54\API;

class ResultsOptions
{
    use OpenAPIWrapper;

    public function __construct($entityCollectionParameters)
    {
        $this->wraps($entityCollectionParameters);
    }

    /* Ordering constants */
    const ORDER_ASC =
        OpenAPI\Model\EntitySortingParameter::ORDER_ASC;
    const ORDER_DESC =
        OpenAPI\Model\EntitySortingParameter::ORDER_DESC;

    /* Entity sorting constants */
    const TYPE_ATTRIBUTE =
        OpenAPI\Model\EntitySortingParameter::TYPE_ATTRIBUTE;
    const TYPE_ID =
        OpenAPI\Model\EntitySortingParameter::TYPE_ID;
    const TYPE_TYPE =
        OpenAPI\Model\EntitySortingParameter::TYPE_TYPE;
    const TYPE_RELEVANCE =
        OpenAPI\Model\EntitySortingParameter::TYPE_RELEVANCE;
    const TYPE_POPULARITY =
        OpenAPI\Model\EntitySortingParameter::TYPE_POPULARITY;

    /* Facet type constants */
    const TYPE_DISTINCT =
        OpenAPI\Model\FacetParameter::TYPE_DISTINCT;
    const TYPE_RANGE =
        OpenAPI\Model\FacetParameter::TYPE_RANGE;

    /* Facet sorting constants */
    const TYPE_ITEM =
        OpenAPI\Model\DistinctFacetItemSortingParameter::TYPE_ITEM;
    const TYPE_COUNT =
        OpenAPI\Model\DistinctFacetItemSortingParameter::TYPE_COUNT;
    const TYPE_SELECTED =
        OpenAPI\Model\DistinctFacetItemSortingParameter::TYPE_SELECTED;

    /**
     * Skip some of the first results (useful when paginating).
     *
     * @param int $skip
     *    The count of results to skip.
     *
     * @return ResultsOptions
     */
    public function skip($skip)
    {
        $this->getRaw()->setSkip($skip);
        return $this;
    }

    /**
     * Limit how many search results are requested (useful when paginating).
     *
     * @param int $take
     *    The count of results requested.
     *
     * @return ResultsOptions
     */
    public function take($take)
    {
        $this->getRaw()->setTake($take);
        return $this;
    }

    public function facets()
    {
        return $this->getRaw()->getFacets();
    }

    /**
     * Add a distinct facet (faceting over discrete values) to the query.
     *
     * @param string $name
     *    The name seen by the user as the name of the facet.
     *
     * @param string $attributeName
     *    The internal name of the attribute being faceted on.
     *
     * @param mixed[] $selected
     *    Which values of the facet that are selected by the user.
     *
     * @return ResultsOptions
     */
    public function addDistinctFacet(
        $name,
        $attributeName,
        $selected = [],
        $sortBy = null,
        $order = null
    ) {
        $sort_by = new OpenAPI\Model\DistinctFacetItemSortingParameter([
            'type' => $sortBy,
            'order' => $order
        ]);
        $this->addFacet(
            new OpenAPI\Model\DistinctFacetParameter([
                'name' => $name,
                'attribute_name' => $attributeName,
                'type' => self::TYPE_DISTINCT,
                'selected' => $selected,
                'sort_by' => [$sort_by]
            ])
        );
        return $this;
    }

    /**
     * Add a range facet (faceting over a continuous range) to the query.
     *
     * @param string $name
     *    The name seen by the user as the name of the facet.
     *
     * @param string $attributeName
     *    The internal name of the attribute being faceted on.
     *
     * @param int|null $selectedMin
     *    What is the lowest value the user has selected for this facet?
     *
     * @param int|null $selectedMax
     *    What is the highest value the user has selected for this facet?
     *
     * @return ResultsOptions
     */
    public function addRangeFacet(
        $name,
        $attributeName,
        $selectedMin = null,
        $selectedMax = null
    ) {
        $this->addFacet(
            new OpenAPI\Model\RangeFacetParameter([
                'name' => $name,
                'attribute_name' => $attributeName,
                'type' => self::TYPE_RANGE,
                'selected' => new OpenAPI\Model\RangeFacetSelectedParameter([
                    'min' => $selectedMin,
                    'max' => $selectedMax
                ])
            ])
        );
        return $this;
    }

    /**
     * Sort both direct and related results by the same property.
     *
     * @param string $type
     *    What property to order the results by. Must be one of
     *    ResultsOptions::TYPE_ATTRIBUTE, ResultsOptions::TYPE_ID,
     *    ResultsOptions::TYPE_TYPE, ResultsOptions::TYPE_RELEVANCE,
     *    ResultsOptions::TYPE_POPULARITY.
     *
     * @param string $order
     *    Whether the ordering should be ascending or descending. Must be one
     *    of ResultsOptions::ORDER_ASC, ResultsOptions::ORDER_DESC.
     *
     * @param string|null $attributeName
     *    If sorting by attribute, the name of the attribute to sort by.
     *
     * @return ResultsOptions
     */
    public function sortBy($type, $order, $attributeName = null)
    {
        $sortBy = new OpenAPI\Model\EntitySortingParameter([
            'type' => $type,
            'order' => $order,
            'attribute_name' => $attributeName
        ]);
        $this->getRaw()->setSortBy([$sortBy]);
        return $this;
    }

    /**
     * Add a filter to the query. Filters are a very flexible way of removing
     * results using arbitrarily complex logic on entity attributes.
     *
     * @param FilterParameter $filter
     *
     * @return ResultsOptions
     */
    public function filter($filter)
    {
        $this->getRaw()->setFilter($filter->getRaw());
        return $this;
    }

    private function addFacet($facetParameter)
    {
        $facets = $this->getRaw()->getFacets();
        if (empty($facets)) {
            $facets = [];
        }
        $facets[] = $facetParameter;
        $this->getRaw()->setFacets($facets);
        return $this;
    }
}

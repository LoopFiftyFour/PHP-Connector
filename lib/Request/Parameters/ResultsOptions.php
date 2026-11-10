<?php

declare(strict_types=1);

namespace Loop54\API\Request\Parameters;

use Loop54\API\OpenAPI\Model;

final class ResultsOptions
{
    /* Ordering constants */
    public const ORDER_ASC =
        Model\EntitySortingParameter::ORDER_ASC;
    public const ORDER_DESC =
        Model\EntitySortingParameter::ORDER_DESC;

    /* Entity sorting constants */
    public const TYPE_ATTRIBUTE =
        Model\EntitySortingParameter::TYPE_ATTRIBUTE;
    public const TYPE_ID =
        Model\EntitySortingParameter::TYPE_ID;
    public const TYPE_TYPE =
        Model\EntitySortingParameter::TYPE_TYPE;
    public const TYPE_RELEVANCE =
        Model\EntitySortingParameter::TYPE_RELEVANCE;
    public const TYPE_POPULARITY =
        Model\EntitySortingParameter::TYPE_POPULARITY;

    /* Facet type constants */
    public const TYPE_DISTINCT =
        Model\FacetParameter::TYPE_DISTINCT;
    public const TYPE_RANGE =
        Model\FacetParameter::TYPE_RANGE;

    /* Facet sorting constants */
    public const TYPE_ITEM =
        Model\DistinctFacetItemSortingParameter::TYPE_ITEM;
    public const TYPE_COUNT =
        Model\DistinctFacetItemSortingParameter::TYPE_COUNT;
    public const TYPE_SELECTED =
        Model\DistinctFacetItemSortingParameter::TYPE_SELECTED;

    private readonly Model\EntityCollectionParameters $raw;

    public function __construct(Model\EntityCollectionParameters $entityCollectionParameters)
    {
        $this->raw = $entityCollectionParameters;
    }

    public function getRaw(): Model\EntityCollectionParameters
    {
        return $this->raw;
    }

    /**
     * Skip some of the first results (useful when paginating).
     */
    public function skip(int $skip): static
    {
        $this->raw->setSkip($skip);
        return $this;
    }

    /**
     * Limit how many search results are requested (useful when paginating).
     */
    public function take(int $take): static
    {
        $this->raw->setTake($take);
        return $this;
    }

    /**
     * @return Model\FacetParameter[]|null
     */
    public function facets(): ?array
    {
        return $this->raw->getFacets();
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
     * @param mixed[]|null $selected
     *    Which values of the facet that are selected by the user.
     *
     * @param string|null $sortBy
     *    One of ResultsOptions::TYPE_ITEM, ResultsOptions::TYPE_COUNT,
     *    ResultsOptions::TYPE_SELECTED.
     *
     * @param string|null $order
     *    One of ResultsOptions::ORDER_ASC, ResultsOptions::ORDER_DESC.
     */
    public function addDistinctFacet(
        string $name,
        string $attributeName,
        ?array $selected = [],
        ?string $sortBy = null,
        ?string $order = null
    ): static {
        $sort_by = new Model\DistinctFacetItemSortingParameter([
            'type' => $sortBy,
            'order' => $order
        ]);
        $this->addFacet(
            new Model\DistinctFacetParameter([
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
     * @param int|float|string|null $selectedMin
     *    What is the lowest value the user has selected for this facet?
     *
     * @param int|float|string|null $selectedMax
     *    What is the highest value the user has selected for this facet?
     */
    public function addRangeFacet(
        string $name,
        string $attributeName,
        int|float|string|null $selectedMin = null,
        int|float|string|null $selectedMax = null
    ): static {
        $facet = new Model\RangeFacetParameter([
            'name' => $name,
            'attribute_name' => $attributeName,
            'type' => self::TYPE_RANGE,
        ]);


        if (isset($selectedMin) && isset($selectedMax)) {
            $facet->setSelected(
                new Model\RangeFacetSelectedParameter([
                    'min' => $selectedMin,
                    'max' => $selectedMax
                ])
            );
        } elseif (isset($selectedMin) || isset($selectedMax)) {
            throw new \InvalidArgumentException(
                'When $selectedMin is specified, $selectedMax must also be, '
                . 'and vice versa.'
            );
        }

        $this->addFacet($facet);
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
     */
    public function sortBy(string $type, string $order, ?string $attributeName = null): static
    {
        $sortBy = new Model\EntitySortingParameter([
            'type' => $type,
            'order' => $order,
            'attribute_name' => $attributeName
        ]);
        $this->raw->setSortBy([$sortBy]);
        return $this;
    }

    /**
     * Add a filter to the query. Filters are a very flexible way of removing
     * results using arbitrarily complex logic on entity attributes.
     */
    public function filter(FilterParameter $filter): static
    {
        $this->raw->setFilter($filter->getRaw());
        return $this;
    }

    private function addFacet(Model\FacetParameter $facetParameter): static
    {
        $facets = $this->raw->getFacets();
        if (empty($facets)) {
            $facets = [];
        }
        $facets[] = $facetParameter;
        $this->raw->setFacets($facets);
        return $this;
    }
}

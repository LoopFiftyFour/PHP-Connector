<?php

declare(strict_types=1);

namespace Loop54\API\Request\Parameters;

use Loop54\API\OpenAPI\Model;

final class FilterParameter
{
    public const TYPE_ATTRIBUTE =
        Model\AttributeFilterParameter::TYPE_ATTRIBUTE;
    public const TYPE_TYPE =
        Model\AttributeFilterParameter::TYPE_TYPE;
    public const TYPE_ID =
        Model\AttributeFilterParameter::TYPE_ID;

    public const COMPARISON_MODE_EQUALS =
        Model\AttributeFilterParameter::
        COMPARISON_MODE_EQUALS;
    public const COMPARISON_MODE_GREATER_THAN =
        Model\AttributeFilterParameter::
        COMPARISON_MODE_GREATER_THAN;
    public const COMPARISON_MODE_GREATER_THAN_OR_EQUALS =
        Model\AttributeFilterParameter::
        COMPARISON_MODE_GREATER_THAN_OR_EQUALS;
    public const COMPARISON_MODE_LESS_THAN =
        Model\AttributeFilterParameter::
        COMPARISON_MODE_LESS_THAN;
    public const COMPARISON_MODE_LESS_THAN_OR_EQUALS =
        Model\AttributeFilterParameter::
        COMPARISON_MODE_LESS_THAN_OR_EQUALS;
    public const COMPARISON_MODE_CONTAINS =
        Model\AttributeFilterParameter::
        COMPARISON_MODE_CONTAINS;

    private readonly ?Model\FilterParameter $raw;

    /**
     * @param Model\FilterParameter|null $filter
     *    The filter to wrap. Leave it out to get an empty instance, which is
     *    only useful for building filters with the methods below.
     */
    public function __construct(?Model\FilterParameter $filter = null)
    {
        $this->raw = $filter;
    }

    public function getRaw(): ?Model\FilterParameter
    {
        return $this->raw;
    }

    public function attributeExists(string $name): self
    {
        return new self(
            new Model\AttributeExistsFilterParameter([
                'attribute_name' => $name
            ])
        );
    }

    public function id(mixed $value, ?string $comparisonMode = null): self
    {
        return new self(
            new Model\AttributeFilterParameter([
                'type' => self::TYPE_ID,
                'value' => $value,
                'comparison_mode' => $comparisonMode
            ])
        );
    }

    public function type(mixed $value, ?string $comparisonMode = null): self
    {
        return new self(
            new Model\AttributeFilterParameter([
                'type' => self::TYPE_TYPE,
                'value' => $value,
                'comparison_mode' => $comparisonMode
            ])
        );
    }

    public function attribute(string $name, mixed $value, ?string $comparisonMode = null): self
    {
        return new self(
            new Model\AttributeFilterParameter([
                'type' => self::TYPE_ATTRIBUTE,
                'attribute_name' => $name,
                'value' => $value,
                'comparison_mode' => $comparisonMode
            ])
        );
    }

    public function invert(): self
    {
        return new self(
            new Model\InverseFilterParameter([
                'not' => $this->raw
            ])
        );
    }

    public function and(FilterParameter $other): self
    {
        return new self(
            new Model\AndFilterParameter([
                'and' => [$this->raw, $other->getRaw()]
            ])
        );
    }

    public function or(FilterParameter $other): self
    {
        return new self(
            new Model\OrFilterParameter([
                'or' => [$this->raw, $other->getRaw()]
            ])
        );
    }
}

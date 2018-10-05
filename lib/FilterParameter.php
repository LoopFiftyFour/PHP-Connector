<?php
namespace Loop54\API;

class FilterParameter
{
    use OpenAPIWrapper;

    const TYPE_ATTRIBUTE =
        OpenAPI\Model\AttributeFilterParameter::TYPE_ATTRIBUTE;
    const TYPE_TYPE =
        OpenAPI\Model\AttributeFilterParameter::TYPE_TYPE;
    const TYPE_ID =
        OpenAPI\Model\AttributeFilterParameter::TYPE_ID;

    const COMPARISON_MODE_EQUALS =
        OpenAPI\Model\AttributeFilterParameter::
        COMPARISON_MODE_EQUALS;
    const COMPARISON_MODE_GREATER_THAN =
        OpenAPI\Model\AttributeFilterParameter::
        COMPARISON_MODE_GREATER_THAN;
    const COMPARISON_MODE_GREATER_THAN_OR_EQUALS =
        OpenAPI\Model\AttributeFilterParameter::
        COMPARISON_MODE_GREATER_THAN_OR_EQUALS;
    const COMPARISON_MODE_LESS_THAN =
        OpenAPI\Model\AttributeFilterParameter::
        COMPARISON_MODE_LESS_THAN;
    const COMPARISON_MODE_LESS_THAN_OR_EQUALS =
        OpenAPI\Model\AttributeFilterParameter::
        COMPARISON_MODE_LESS_THAN_OR_EQUALS;
    const COMPARISON_MODE_CONTAINS =
        OpenAPI\Model\AttributeFilterParameter::
        COMPARISON_MODE_CONTAINS;


    public function __construct($filter = null)
    {
        $this->wraps($filter);
    }

    public function attributeExists($name)
    {
        return new FilterParameter(
            new OpenAPI\Model\AttributeExistsFilterParameter([
                'attribute_name' => $name
            ])
        );
    }

    public function id($value, $comparisonMode = null)
    {
        return new FilterParameter(
            new OpenAPI\Model\AttributeFilterParameter([
                'type' => self::TYPE_ID,
                'value' => $value,
                'comparison_mode' => $comparisonMode
            ])
        );
    }

    public function type($value, $comparisonMode = null)
    {
        return new FilterParameter(
            new OpenAPI\Model\AttributeFilterParameter([
                'type' => self::TYPE_TYPE,
                'value' => $value,
                'comparison_mode' => $comparisonMode
            ])
        );
    }

    public function attribute($name, $value, $comparisonMode = null)
    {
        return new FilterParameter(
            new OpenAPI\Model\AttributeFilterParameter([
                'type' => self::TYPE_ATTRIBUTE,
                'attribute_name' => $name,
                'value' => $value,
                'comparison_mode' => $comparisonMode
            ])
        );
    }

    public function invert()
    {
        return new FilterParameter(
            new OpenAPI\Model\InverseFilterParameter([
                'not' => $this->getRaw()
            ])
        );
    }

    public function and($other)
    {
        return new FilterParameter(
            new OpenAPI\Model\AndFilterParameter([
                'and' => [$this->getRaw(), $other->getRaw()]
            ])
        );
    }

    public function or($other)
    {
        return new FilterParameter(
            new OpenAPI\Model\OrFilterParameter([
                'or' => [$this->getRaw(), $other->getRaw()]
            ])
        );
    }
}

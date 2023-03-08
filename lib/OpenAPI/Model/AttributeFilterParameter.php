<?php
/**
 * AttributeFilterParameter
 *
 * PHP version 5
 *
 * @category Class
 * @package  Loop54\API\OpenAPI
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Loop54 Engine
 *
 * Loop54 Search is a learning search engine for e-commerce. It helps online shoppers find what they’re looking for, and allows them to explore a wider range of relevant products in a retailer’s catalogue.
 *
 * OpenAPI spec version: V3
 * 
 * Generated by: https://openapi-generator.tech
 * OpenAPI Generator version: 3.3.4
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace Loop54\API\OpenAPI\Model;
use \Loop54\API\OpenAPI\ObjectSerializer;

/**
 * AttributeFilterParameter Class Doc Comment
 *
 * @category Class
 * @description Used for filtering entities that have a certain attribute value.
 * @package  Loop54\API\OpenAPI
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class AttributeFilterParameter extends FilterParameter 
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'attributeFilterParameter';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'type' => 'string',
        'attribute_name' => 'string',
        'value' => 'object',
        'comparison_mode' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'type' => null,
        'attribute_name' => null,
        'value' => null,
        'comparison_mode' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes + parent::openAPITypes();
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats + parent::openAPIFormats();
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'type' => 'type',
        'attribute_name' => 'attributeName',
        'value' => 'value',
        'comparison_mode' => 'comparisonMode'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'type' => 'setType',
        'attribute_name' => 'setAttributeName',
        'value' => 'setValue',
        'comparison_mode' => 'setComparisonMode'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'type' => 'getType',
        'attribute_name' => 'getAttributeName',
        'value' => 'getValue',
        'comparison_mode' => 'getComparisonMode'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return parent::attributeMap() + self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return parent::setters() + self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return parent::getters() + self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }

    const TYPE_ATTRIBUTE = 'attribute';
    const TYPE_TYPE = 'type';
    const TYPE_ID = 'id';
    const COMPARISON_MODE_EQUALS = 'equals';
    const COMPARISON_MODE_GREATER_THAN = 'greaterThan';
    const COMPARISON_MODE_GREATER_THAN_OR_EQUALS = 'greaterThanOrEquals';
    const COMPARISON_MODE_LESS_THAN = 'lessThan';
    const COMPARISON_MODE_LESS_THAN_OR_EQUALS = 'lessThanOrEquals';
    const COMPARISON_MODE_CONTAINS = 'contains';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getTypeAllowableValues()
    {
        return [
            self::TYPE_ATTRIBUTE,
            self::TYPE_TYPE,
            self::TYPE_ID,
        ];
    }
    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getComparisonModeAllowableValues()
    {
        return [
            self::COMPARISON_MODE_EQUALS,
            self::COMPARISON_MODE_GREATER_THAN,
            self::COMPARISON_MODE_GREATER_THAN_OR_EQUALS,
            self::COMPARISON_MODE_LESS_THAN,
            self::COMPARISON_MODE_LESS_THAN_OR_EQUALS,
            self::COMPARISON_MODE_CONTAINS,
        ];
    }
    


    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        parent::__construct($data);

        $this->container['type'] = isset($data['type']) ? $data['type'] : null;
        $this->container['attribute_name'] = isset($data['attribute_name']) ? $data['attribute_name'] : null;
        $this->container['value'] = isset($data['value']) ? $data['value'] : null;
        $this->container['comparison_mode'] = isset($data['comparison_mode']) ? $data['comparison_mode'] : 'equals';
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = parent::listInvalidProperties();

        if ($this->container['type'] === null) {
            $invalidProperties[] = "'type' can't be null";
        }
        $allowedValues = $this->getTypeAllowableValues();
        if (!is_null($this->container['type']) && !in_array($this->container['type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'type', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        if ($this->container['value'] === null) {
            $invalidProperties[] = "'value' can't be null";
        }
        $allowedValues = $this->getComparisonModeAllowableValues();
        if (!is_null($this->container['comparison_mode']) && !in_array($this->container['comparison_mode'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'comparison_mode', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets type
     *
     * @return string
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param string $type Type of the filter. Attribute, Id or Type. If the type of the filter is Attribute, the name of the attribute needs to be specified in the AttributeName property.
     *
     * @return $this
     */
    public function setType($type)
    {
        $allowedValues = $this->getTypeAllowableValues();
        if (!in_array($type, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'type', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['type'] = $type;

        return $this;
    }

    /**
     * Gets attribute_name
     *
     * @return string|null
     */
    public function getAttributeName()
    {
        return $this->container['attribute_name'];
    }

    /**
     * Sets attribute_name
     *
     * @param string|null $attribute_name If the type of filter is Attribute, the name of the attribute needs to be specified here.
     *
     * @return $this
     */
    public function setAttributeName($attribute_name)
    {
        $this->container['attribute_name'] = $attribute_name;

        return $this;
    }

    /**
     * Gets value
     *
     * @return object
     */
    public function getValue()
    {
        return $this->container['value'];
    }

    /**
     * Sets value
     *
     * @param object $value The value to filter on.
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->container['value'] = $value;

        return $this;
    }

    /**
     * Gets comparison_mode
     *
     * @return string|null
     */
    public function getComparisonMode()
    {
        return $this->container['comparison_mode'];
    }

    /**
     * Sets comparison_mode
     *
     * @param string|null $comparison_mode Defines the comparison mode to use when filtering.
     *
     * @return $this
     */
    public function setComparisonMode($comparison_mode)
    {
        $allowedValues = $this->getComparisonModeAllowableValues();
        if (!is_null($comparison_mode) && !in_array($comparison_mode, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'comparison_mode', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['comparison_mode'] = $comparison_mode;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }
}



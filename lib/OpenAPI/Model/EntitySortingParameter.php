<?php
/**
 * EntitySortingParameter
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

use \ArrayAccess;
use \Loop54\API\OpenAPI\ObjectSerializer;

/**
 * EntitySortingParameter Class Doc Comment
 *
 * @category Class
 * @description Object determining how a result list should be sorted.
 * @package  Loop54\API\OpenAPI
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class EntitySortingParameter implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'entitySortingParameter';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'type' => 'string',
        'attribute_name' => 'string',
        'order' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'type' => null,
        'attribute_name' => null,
        'order' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
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
        'order' => 'order'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'type' => 'setType',
        'attribute_name' => 'setAttributeName',
        'order' => 'setOrder'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'type' => 'getType',
        'attribute_name' => 'getAttributeName',
        'order' => 'getOrder'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
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
    const TYPE_ID = 'id';
    const TYPE_TYPE = 'type';
    const TYPE_RELEVANCE = 'relevance';
    const TYPE_POPULARITY = 'popularity';
    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';
    

    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getTypeAllowableValues()
    {
        return [
            self::TYPE_ATTRIBUTE,
            self::TYPE_ID,
            self::TYPE_TYPE,
            self::TYPE_RELEVANCE,
            self::TYPE_POPULARITY,
        ];
    }
    
    /**
     * Gets allowable values of the enum
     *
     * @return string[]
     */
    public function getOrderAllowableValues()
    {
        return [
            self::ORDER_ASC,
            self::ORDER_DESC,
        ];
    }
    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(?array $data = null)
    {
        $this->container['type'] = isset($data['type']) ? $data['type'] : 'relevance';
        $this->container['attribute_name'] = isset($data['attribute_name']) ? $data['attribute_name'] : null;
        $this->container['order'] = isset($data['order']) ? $data['order'] : 'desc';
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        $allowedValues = $this->getTypeAllowableValues();
        if (!is_null($this->container['type']) && !in_array($this->container['type'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'type', must be one of '%s'",
                implode("', '", $allowedValues)
            );
        }

        $allowedValues = $this->getOrderAllowableValues();
        if (!is_null($this->container['order']) && !in_array($this->container['order'], $allowedValues, true)) {
            $invalidProperties[] = sprintf(
                "invalid value for 'order', must be one of '%s'",
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
     * @return string|null
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Sets type
     *
     * @param string|null $type How the sorting is done. Defaults to \"relevance\".
     *
     * @return $this
     */
    public function setType($type)
    {
        $allowedValues = $this->getTypeAllowableValues();
        if (!is_null($type) && !in_array($type, $allowedValues, true)) {
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
     * @param string|null $attribute_name If the type is set to \"attribute\" this property decides which attribute on the entity to sort by. When an attribute is missing from an entity, or has a null value, that entity will always be ordered last, no matter whether the sorting parameter is defined as ascending or descending. All entities with null, empty or missing attributes are considered equal.
     *
     * @return $this
     */
    public function setAttributeName($attribute_name)
    {
        $this->container['attribute_name'] = $attribute_name;

        return $this;
    }

    /**
     * Gets order
     *
     * @return string|null
     */
    public function getOrder()
    {
        return $this->container['order'];
    }

    /**
     * Sets order
     *
     * @param string|null $order Whether to sort descending or ascending. Defaults to \"descending\".
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $allowedValues = $this->getOrderAllowableValues();
        if (!is_null($order) && !in_array($order, $allowedValues, true)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Invalid value for 'order', must be one of '%s'",
                    implode("', '", $allowedValues)
                )
            );
        }
        $this->container['order'] = $order;

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



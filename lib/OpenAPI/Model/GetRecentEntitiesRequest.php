<?php
/**
 * GetRecentEntitiesRequest
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
 * GetRecentEntitiesRequest Class Doc Comment
 *
 * @category Class
 * @description Used to perform a request to get entities that a user (or all users) most recently interacted with or navigated to.
 * @package  Loop54\API\OpenAPI
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class GetRecentEntitiesRequest implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'getRecentEntitiesRequest';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'behavior_type' => 'string',
        'entity_type' => 'string[]',
        'for_user_id' => 'string',
        'results_options' => 'EntityCollectionParameters',
        'custom_data' => 'map[string,object]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'behavior_type' => null,
        'entity_type' => null,
        'for_user_id' => null,
        'results_options' => null,
        'custom_data' => null
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
        'behavior_type' => 'behaviorType',
        'entity_type' => 'entityType',
        'for_user_id' => 'forUserId',
        'results_options' => 'resultsOptions',
        'custom_data' => 'customData'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'behavior_type' => 'setBehaviorType',
        'entity_type' => 'setEntityType',
        'for_user_id' => 'setForUserId',
        'results_options' => 'setResultsOptions',
        'custom_data' => 'setCustomData'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'behavior_type' => 'getBehaviorType',
        'entity_type' => 'getEntityType',
        'for_user_id' => 'getForUserId',
        'results_options' => 'getResultsOptions',
        'custom_data' => 'getCustomData'
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
    public function __construct(array $data = null)
    {
        $this->container['behavior_type'] = isset($data['behavior_type']) ? $data['behavior_type'] : null;
        $this->container['entity_type'] = isset($data['entity_type']) ? $data['entity_type'] : null;
        $this->container['for_user_id'] = isset($data['for_user_id']) ? $data['for_user_id'] : null;
        $this->container['results_options'] = isset($data['results_options']) ? $data['results_options'] : null;
        $this->container['custom_data'] = isset($data['custom_data']) ? $data['custom_data'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['behavior_type'] === null) {
            $invalidProperties[] = "'behavior_type' can't be null";
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
     * Gets behavior_type
     *
     * @return string
     */
    public function getBehaviorType()
    {
        return $this->container['behavior_type'];
    }

    /**
     * Sets behavior_type
     *
     * @param string $behavior_type The interaction or navigation type to include (such as \"click\", \"purchase\" or \"search\").
     *
     * @return $this
     */
    public function setBehaviorType($behavior_type)
    {
        $this->container['behavior_type'] = $behavior_type;

        return $this;
    }

    /**
     * Gets entity_type
     *
     * @return string[]|null
     */
    public function getEntityType()
    {
        return $this->container['entity_type'];
    }

    /**
     * Sets entity_type
     *
     * @param string[]|null $entity_type The entity types to include (such as \"Product\" or \"Query\") or null for all.
     *
     * @return $this
     */
    public function setEntityType($entity_type)
    {
        $this->container['entity_type'] = $entity_type;

        return $this;
    }

    /**
     * Gets for_user_id
     *
     * @return string|null
     */
    public function getForUserId()
    {
        return $this->container['for_user_id'];
    }

    /**
     * Sets for_user_id
     *
     * @param string|null $for_user_id A user ID (normally the same as the one in the User-Id header) to retrieve the most common/recent entities for that user or null to retrieve the globally most common/recent entities.
     *
     * @return $this
     */
    public function setForUserId($for_user_id)
    {
        $this->container['for_user_id'] = $for_user_id;

        return $this;
    }

    /**
     * Gets results_options
     *
     * @return EntityCollectionParameters|null
     */
    public function getResultsOptions()
    {
        return $this->container['results_options'];
    }

    /**
     * Sets results_options
     *
     * @param EntityCollectionParameters|null $results_options Parameters for specifying how to filter and sort the results.
     *
     * @return $this
     */
    public function setResultsOptions($results_options)
    {
        $this->container['results_options'] = $results_options;

        return $this;
    }

    /**
     * Gets custom_data
     *
     * @return map[string,object]|null
     */
    public function getCustomData()
    {
        return $this->container['custom_data'];
    }

    /**
     * Sets custom_data
     *
     * @param map[string,object]|null $custom_data Any additional, non-standard, data. Contact support for information about how and when to use this.
     *
     * @return $this
     */
    public function setCustomData($custom_data)
    {
        $this->container['custom_data'] = $custom_data;

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


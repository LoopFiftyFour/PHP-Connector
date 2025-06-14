<?php
/**
 * SearchRequest
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
 * SearchRequest Class Doc Comment
 *
 * @category Class
 * @description Used for performing search requests to the engine.
 * @package  Loop54\API\OpenAPI
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class SearchRequest implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'searchRequest';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'query' => 'string',
        'results_options' => 'EntityCollectionParameters',
        'related_results_options' => 'EntityCollectionParameters',
        'spelling_suggestions_options' => 'QueryCollectionParameters',
        'related_queries_options' => 'QueryCollectionParameters',
        'custom_data' => 'map[string,object]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPIFormats = [
        'query' => null,
        'results_options' => null,
        'related_results_options' => null,
        'spelling_suggestions_options' => null,
        'related_queries_options' => null,
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
        'query' => 'query',
        'results_options' => 'resultsOptions',
        'related_results_options' => 'relatedResultsOptions',
        'spelling_suggestions_options' => 'spellingSuggestionsOptions',
        'related_queries_options' => 'relatedQueriesOptions',
        'custom_data' => 'customData'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'query' => 'setQuery',
        'results_options' => 'setResultsOptions',
        'related_results_options' => 'setRelatedResultsOptions',
        'spelling_suggestions_options' => 'setSpellingSuggestionsOptions',
        'related_queries_options' => 'setRelatedQueriesOptions',
        'custom_data' => 'setCustomData'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'query' => 'getQuery',
        'results_options' => 'getResultsOptions',
        'related_results_options' => 'getRelatedResultsOptions',
        'spelling_suggestions_options' => 'getSpellingSuggestionsOptions',
        'related_queries_options' => 'getRelatedQueriesOptions',
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
    public function __construct(?array $data = null)
    {
        $this->container['query'] = isset($data['query']) ? $data['query'] : null;
        $this->container['results_options'] = isset($data['results_options']) ? $data['results_options'] : null;
        $this->container['related_results_options'] = isset($data['related_results_options']) ? $data['related_results_options'] : null;
        $this->container['spelling_suggestions_options'] = isset($data['spelling_suggestions_options']) ? $data['spelling_suggestions_options'] : null;
        $this->container['related_queries_options'] = isset($data['related_queries_options']) ? $data['related_queries_options'] : null;
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

        if ($this->container['query'] === null) {
            $invalidProperties[] = "'query' can't be null";
        }
        if ((mb_strlen($this->container['query']) > 100)) {
            $invalidProperties[] = "invalid value for 'query', the character length must be smaller than or equal to 100.";
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
     * Gets query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->container['query'];
    }

    /**
     * Sets query
     *
     * @param string $query The query to search for.
     *
     * @return $this
     */
    public function setQuery($query)
    {
        if ((mb_strlen($query) > 100)) {
            throw new \InvalidArgumentException('invalid length for $query when calling SearchRequest., must be smaller than or equal to 100.');
        }

        $this->container['query'] = $query;

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
     * @param EntityCollectionParameters|null $results_options Parameters for specifying which results to retrieve.
     *
     * @return $this
     */
    public function setResultsOptions($results_options)
    {
        $this->container['results_options'] = $results_options;

        return $this;
    }

    /**
     * Gets related_results_options
     *
     * @return EntityCollectionParameters|null
     */
    public function getRelatedResultsOptions()
    {
        return $this->container['related_results_options'];
    }

    /**
     * Sets related_results_options
     *
     * @param EntityCollectionParameters|null $related_results_options Parameters for specifying which related results to retrieve.
     *
     * @return $this
     */
    public function setRelatedResultsOptions($related_results_options)
    {
        $this->container['related_results_options'] = $related_results_options;

        return $this;
    }

    /**
     * Gets spelling_suggestions_options
     *
     * @return QueryCollectionParameters|null
     */
    public function getSpellingSuggestionsOptions()
    {
        return $this->container['spelling_suggestions_options'];
    }

    /**
     * Sets spelling_suggestions_options
     *
     * @param QueryCollectionParameters|null $spelling_suggestions_options Parameters for specifying which spelling suggestions to retrieve.
     *
     * @return $this
     */
    public function setSpellingSuggestionsOptions($spelling_suggestions_options)
    {
        $this->container['spelling_suggestions_options'] = $spelling_suggestions_options;

        return $this;
    }

    /**
     * Gets related_queries_options
     *
     * @return QueryCollectionParameters|null
     */
    public function getRelatedQueriesOptions()
    {
        return $this->container['related_queries_options'];
    }

    /**
     * Sets related_queries_options
     *
     * @param QueryCollectionParameters|null $related_queries_options Parameters for specifying which related queries to retrieve.
     *
     * @return $this
     */
    public function setRelatedQueriesOptions($related_queries_options)
    {
        $this->container['related_queries_options'] = $related_queries_options;

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



<?php
namespace Loop54\API;

class Entity
{
    use OpenAPIWrapper;

    private $attributes;

    public function __construct($entity)
    {
        $this->wraps($entity);
        foreach ($this->getRaw()->getAttributes() as $entityAttribute) {
            $key = $entityAttribute->getName();
            if (!isset($this->attributes[$key])) {
                $this->attributes[$key] = [];
            }
            foreach ($entityAttribute->getValues() as $newValue) {
                /* For some weird reason the PHP code is generated with arrays
                   of arrays for the values. We're only interested in the last
                   value of the inner array here. */
                $this->attributes[$key][] = $newValue[0];
            }
        }
    }

    public static function fromId($type, $id)
    {
        return new Entity(new OpenAPI\Model\Entity([
            'id' => $id,
            'type' => $type,
            'attributes' => []
        ]));
    }

    public function getId()
    {
        return $this->getRaw()->getId();
    }

    public function getType()
    {
        return $this->getRaw()->getType();
    }

    /**
     * Return the value(s) associated with an attribute name.
     *
     * The current implementation of the engine does not support multiple
     * attributes with the same name, so this method should always return a
     * single {@see OpenAPI\Model\EntityAttribute}. However, the API protocol
     * technically allows multiple attributes with the same name, and if that
     * situation occurs, this method will return an array wrapping all
     * attributes.
     *
     * @param $name string Which attribute values to return.
     *
     * @return OpenAPI\Model\EntityAttribute|OpenAPI\Model\EntityAttribute[]
     */
    public function getAttribute($name)
    {
        if (!isset($this->attributes[$name])) {
            throw new AttributeMissingException(
                'Attribute ' . $name . ' missing. Available: ' .
                print_r($this->attributes, 1)
            );
        }

        if (sizeof($this->attributes[$name]) == 1) {
            return $this->attributes[$name][0];
        }

        return $this->attributes[$name];
    }

    /**
     * Determines whether an attribute exists.
     *
     * @param $name string Which attribute to look for.
     *
     * @return bool
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }
}

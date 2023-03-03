<?php
namespace Loop54\API;

class RequestAliasFields
{
    use OpenAPIWrapper;

    public function __construct($requestFields)
    {
        $this->wraps($requestFields);
    }

    /**
     * Specify an alias for this attribute name.
     *
     * @param string $alias
     *    The alias
     *
     * @return $this
     */
    public function name($alias)
    {
        $this->getRaw()->setName($alias);
        return $this;
    }

    /**
     * Specify an alias for this attribute value.
     *
     * @param string $alias
     *    The alias
     *
     * @return $this
     */
    public function value($alias)
    {
        $this->getRaw()->setValue($alias);
        return $this;
    }

    /**
     * Specify an alias for this attribute details.
     *
     * @param string $alias
     *    The alias
     *
     * @return $this
     */
    public function details($alias)
    {
        $this->getRaw()->setDetails($alias);
        return $this;
    }
}

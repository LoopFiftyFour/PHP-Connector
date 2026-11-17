<?php

declare(strict_types=1);

namespace Loop54\API\Request\Parameters;

use Loop54\API\OpenAPI\Model;

final class RequestAliasFields
{
    private readonly Model\RequestAliasFields $raw;

    public function __construct(Model\RequestAliasFields $requestFields)
    {
        $this->raw = $requestFields;
    }

    public function getRaw(): Model\RequestAliasFields
    {
        return $this->raw;
    }

    /**
     * Specify an alias for this attribute name.
     */
    public function name(string $alias): static
    {
        $this->raw->setName($alias);
        return $this;
    }

    /**
     * Specify an alias for this attribute value.
     */
    public function value(string $alias): static
    {
        $this->raw->setValue($alias);
        return $this;
    }

    /**
     * Specify an alias for this attribute details.
     */
    public function details(string $alias): static
    {
        $this->raw->setDetails($alias);
        return $this;
    }
}

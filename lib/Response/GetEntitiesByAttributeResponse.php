<?php

declare(strict_types=1);

namespace Loop54\API\Response;

use Loop54\API\Entity;
use Loop54\API\OpenAPI\Model;

final class GetEntitiesByAttributeResponse implements Response
{
    private readonly Model\GetEntitiesByAttributeResponse $raw;

    public function __construct(Model\GetEntitiesByAttributeResponse $response)
    {
        $this->raw = $response;
    }

    public function getRaw(): Model\GetEntitiesByAttributeResponse
    {
        return $this->raw;
    }

    /**
     * @return array<array<string, mixed>>|null
     */
    public function getFacets(): ?array
    {
        return $this->raw->getResults()->getFacets();
    }

    /**
     * @return Entity[]
     */
    public function getResults(): array
    {
        return array_map(
            fn ($entity) => new Entity($entity),
            $this->raw->getResults()->getItems()
        );
    }
}

<?php

declare(strict_types=1);

namespace Loop54\API\Response;

final class CustomResponse implements Response
{
    private readonly mixed $raw;

    public function __construct(mixed $response)
    {
        $this->raw = $response;
    }

    /**
     * Whatever the engine returned for the custom request. The shape depends
     * entirely on the request, and it may be null.
     */
    public function getRaw(): mixed
    {
        return $this->raw;
    }
}

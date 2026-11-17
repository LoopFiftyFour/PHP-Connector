<?php

declare(strict_types=1);

namespace Loop54\API\Response;

final class CreateEventsResponse implements Response
{
    private readonly mixed $raw;

    public function __construct(mixed $response)
    {
        $this->raw = $response;
    }

    /**
     * The engine does not describe this response in its API, so this is
     * whatever it returned, possibly null.
     */
    public function getRaw(): mixed
    {
        return $this->raw;
    }
}

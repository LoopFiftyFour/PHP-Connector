<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\OpenAPI\ApiException;
use Loop54\API\Response\Response;

interface Request
{
    /**
     * Actually perform the request and get a result. Use
     * {@see \Loop54\API\Client::query()} instead of calling this directly.
     *
     * @internal
     *
     * @throws ApiException
     */
    public function perform(RequestContext $context): Response;
}

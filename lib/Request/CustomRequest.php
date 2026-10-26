<?php
namespace Loop54\API\Request;

use Loop54\API\OpenAPI;
use Loop54\API\OpenAPIWrapper;

class CustomRequest implements Request
{
    use OpenAPIWrapper;

    private $requestName;

    public function __construct($requestName, $customData)
    {
        $this->requestName = $requestName;
        $this->wraps(new OpenAPI\Model\CustomRequest([
            'custom_data' => $customData
        ]));
    }

    public function perform(
        $instance,
        $apiversion,
        $libversion,
        $apikey,
        $userid,
        $userip,
        $useragent,
        $referer
    ) {
        return $instance->requestNamePost(
            $this->requestName,
            $apiversion,
            $apikey,
            $this->getRaw(),
            $libversion
        );
    }
}

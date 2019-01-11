<?php
namespace Loop54\API;

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
            $userid,
            $this->getRaw(),
            $userip,
            $useragent,
            $referer,
            $libversion,
            $apikey
        );
    }
}

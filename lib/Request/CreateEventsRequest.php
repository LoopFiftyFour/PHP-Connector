<?php
namespace Loop54\API\Request;

use Loop54\API\OpenAPI;
use Loop54\API\OpenAPIWrapper;
use Loop54\API\Response\CreateEventsResponse;

class CreateEventsRequest implements Request
{
    use OpenAPIWrapper;

    public function __construct($type)
    {
        $this->wraps(new OpenAPI\Model\CreateEventsRequest([
            'events' => [new OpenAPI\Model\Event([
                'type' => $type
            ])]
        ]));
    }

    public function entity($entity)
    {
        $this->lastEvent()->setEntity($entity->getRaw());
        return $this;
    }

    public function entityIdType($id, $type)
    {
        $this->lastEvent()->setEntity();
        return $this;
    }

    public function revenue($revenue)
    {
        $this->lastEvent()->setRevenue($revenue);
        return $this;
    }

    public function orderId($orderId)
    {
        $this->lastEvent()->setOrderId($orderId);
        return $this;
    }

    public function quantity($quantity)
    {
        $this->lastEvent()->setQuantity($quantity);
        return $this;
    }

    public function lastEvent()
    {
        $events = $this->getRaw()->getEvents();
        return $events[count($events) - 1];
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
        return new CreateEventsResponse($instance->createEventsPost(
            $apiversion,
            $userid,
            $this->getRaw(),
            $userip,
            $useragent,
            $referer,
            $libversion,
            $apikey
        ));
    }
}

<?php

declare(strict_types=1);

namespace Loop54\API\Request;

use Loop54\API\Entity;
use Loop54\API\OpenAPI\Model;
use Loop54\API\Response\CreateEventsResponse;

final class CreateEventsRequest implements Request
{
    private readonly Model\CreateEventsRequest $raw;

    /**
     * @param string $type
     *    Commonly one of 'click', 'addtocart', or 'purchase'.
     */
    public function __construct(string $type)
    {
        $this->raw = new Model\CreateEventsRequest([
            'events' => [new Model\Event(['type' => $type])],
        ]);
    }

    public function getRaw(): Model\CreateEventsRequest
    {
        return $this->raw;
    }

    public function entity(Entity $entity): static
    {
        $this->lastEvent()->setEntity($entity->getRaw());
        return $this;
    }

    public function entityIdType(string|int $id, string $type): static
    {
        $this->lastEvent()->setEntity();
        return $this;
    }

    public function revenue(float $revenue): static
    {
        $this->lastEvent()->setRevenue($revenue);
        return $this;
    }

    public function orderId(string|int $orderId): static
    {
        $this->lastEvent()->setOrderId($orderId);
        return $this;
    }

    public function quantity(int $quantity): static
    {
        $this->lastEvent()->setQuantity($quantity);
        return $this;
    }

    public function lastEvent(): Model\Event
    {
        $events = $this->raw->getEvents();
        return $events[count($events) - 1];
    }

    public function perform(RequestContext $context): CreateEventsResponse
    {
        return new CreateEventsResponse($context->userInitiatedApi->createEventsPost(
            api_version: $context->apiVersion,
            user_id: $context->userId,
            create_events_request: $this->raw,
            user_ip: $context->userIp,
            user_useragent: $context->userAgent,
            user_referer: $context->referer,
            lib_version: $context->libVersion,
            loop54_key: $context->apiKey,
        ));
    }
}

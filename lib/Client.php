<?php

declare(strict_types=1);

namespace Loop54\API;

use GuzzleHttp\ClientInterface;
use Loop54\API\Request\AutoCompleteRequest;
use Loop54\API\Request\CreateEventsRequest;
use Loop54\API\Request\CustomRequest;
use Loop54\API\Request\GetBasketRecommendationsRequest;
use Loop54\API\Request\GetComplementaryEntitiesRequest;
use Loop54\API\Request\GetEntitiesByAttributeRequest;
use Loop54\API\Request\GetEntitiesRequest;
use Loop54\API\Request\GetPopularEntitiesRequest;
use Loop54\API\Request\GetRecentEntitiesRequest;
use Loop54\API\Request\GetRecommendedEntitiesRequest;
use Loop54\API\Request\GetRelatedEntitiesRequest;
use Loop54\API\Request\Request;
use Loop54\API\Request\RequestContext;
use Loop54\API\Request\SearchRequest;
use Loop54\API\Response\CustomResponse;
use Loop54\API\Response\Response;
use Random\RandomException;

class Client
{
    use OpenAPIWrapper;

    public const APIVERSION = 'V3';
    public const LIBVERSION = 'php:V3:4.0.1';
    private string $apikey;
    private RemoteClientInfo\Client $remoteClientInfo;
    private ?ClientInterface $httpClient;
    private OpenAPI\Api\AdministrativeApi $admin;
    private OpenAPI\Api\OtherApi $other;

    /**
     * Initialises a connector instance which targets a Loop54 engine.
     *
     * @param string $host
     *    Which engine to perform requests against.
     *
     * @param string $apikey
     *    Your API key identifying you as a legitimate user of the engine.
     *
     * @param RemoteClientInfo\Client $remoteClientInfo
     *    An instance with methods to get information about the end-user
     *    performing the request. An implementation for a normal PHP web
     *    backend is available as {@see RemoteClientInfo\WebClient}. By
     *    "normal", we mean there is a $_COOKIE cookie jar, and a $_SERVER
     *    variable populated with the usual keys, like HTTP_CLIENT_IP,
     *    HTTP_X_FORWARDED_FOR, HTTP_USER_AGENT and so on.
     *
     * @param ClientInterface|null $client
     *    An optional HTTP client implementation. If none is given, Guzzle
     *    will automatically be used.
     */
    public function __construct(
        string $host,
        string $apikey,
        RemoteClientInfo\Client $remoteClientInfo,
        ?ClientInterface $client = null
    ) {
        $config = new OpenAPI\Configuration();
        $config->setHost(rtrim($host, '/'));

        $this->httpClient = $client;
        $this->wraps(new OpenAPI\Api\UserInitiatedApi($client, $config));
        $this->admin = new OpenAPI\Api\AdministrativeApi($client, $config);
        $this->other = new OpenAPI\Api\OtherApi($client, $config);

        $this->apikey = $apikey;
        $this->remoteClientInfo = $remoteClientInfo;
    }

    /**
     * Return a copy of this connector, but which uses a custom user ID.
     *
     * @param callable $getUserId
     *    A function that, when called, will return the desired user id.
     */
    public function withUserId(callable $getUserId): Client
    {
        $customClientInfo = new RemoteClientInfo\CustomClient(
            $this->remoteClientInfo
        );
        $customClientInfo->customUserId($getUserId);
        return new Client(
            $this->getRaw()->getConfig()->getHost(),
            $this->apikey,
            $customClientInfo,
            $this->httpClient
        );
    }

    /**
     * Configure a search request for a particular query.
     */
    public function search(string $query): SearchRequest
    {
        return new SearchRequest($query);
    }

    /**
     * Configure an autocomplete request for a partial query.
     */
    public function autoComplete(string $query): AutoCompleteRequest
    {
        return new AutoCompleteRequest($query);
    }

    /**
     * Configure a request to get entities with a specific attribute value.
     *
     * @param string $name
     *    The attribute to inspect.
     *
     * @param string|int|float|bool|array<string|int|float|bool> $value
     *    Include only entities with this value (or one of these values) for
     *    the attribute.
     */
    public function getEntitiesByAttribute(
        string $name,
        string|int|float|bool|array $value
    ): GetEntitiesByAttributeRequest {
        return new GetEntitiesByAttributeRequest($name, $value);
    }

    /**
     * Configure a request to get entities.
     */
    public function getEntities(): GetEntitiesRequest
    {
        return new GetEntitiesRequest();
    }

    /**
     * Configure a request to get entities related to the provided entity.
     */
    public function getRelatedEntities(Entity $entity): GetRelatedEntitiesRequest
    {
        return new GetRelatedEntitiesRequest($entity);
    }

    /**
     * Configure a request to get personalized recommendations.
     */
    public function getRecommendedEntities(): GetRecommendedEntitiesRequest
    {
        return new GetRecommendedEntitiesRequest();
    }

    /**
     * Configure a request to get entities complementary to the provided entity.
     */
    public function getComplementaryEntities(Entity $entity): GetComplementaryEntitiesRequest
    {
        return new GetComplementaryEntitiesRequest($entity);
    }

    /**
     * Configure a request to get basket recommendations to the provided entities.
     *
     * @param Entity[] $entities
     *    The set of entities in a basket for which to fetch recommendations for.
     */
    public function getBasketRecommendations(array $entities): GetBasketRecommendationsRequest
    {
        return new GetBasketRecommendationsRequest($entities);
    }

    /**
     * Used to perform a request to get entities that a user (or all users) most commonly interacted with or navigated to.
     *
     * @param string $behaviorType
     *    Interaction or navigation type to include (such as click, purchase or search).
     * @param string|null $forUserId
     *    User ID (normally the same as the one in the User-Id header) to retrieve the most common entities for that user or null to retrieve the globally most common entities.
     * @param string[]|null $entityType
     *    Entity types to include (such as Product or Query) or null for all types.
     */
    public function getPopularEntities(
        string $behaviorType,
        ?string $forUserId = null,
        ?array $entityType = null
    ): GetPopularEntitiesRequest {
        return new GetPopularEntitiesRequest($behaviorType, $entityType, $forUserId);
    }

    /**
     * Used to perform a request to get entities that a user (or all users) most recently interacted with or navigated to.
     *
     * @param string $behaviorType
     *    Interaction or navigation type to include (such as click, purchase or search).
     * @param string|null $forUserId
     *    User ID (normally the same as the one in the User-Id header) to retrieve the most recent entities for that user or null to retrieve the globally most recent entities.
     * @param string[]|null $entityType
     *    Entity types to include (such as Product or Query) or null for all types.
     */
    public function getRecentEntities(
        string $behaviorType,
        ?string $forUserId = null,
        ?array $entityType = null
    ): GetRecentEntitiesRequest {
        return new GetRecentEntitiesRequest($behaviorType, $entityType, $forUserId);
    }

    /**
     * Convenience method to create an {@see Entity} with no attributes but
     * with a particular id and type. This is useful e.g. for creating click
     * events, where you may not possess the full entity being clicked on, but
     * you can trivially find its ID and type.
     */
    public function entity(string $type, string|int $id): Entity
    {
        return Entity::fromId($type, $id);
    }

    /**
     * Create an event indicating an end-user click.
     *
     * @param Entity $entity
     *    The entity being clicked.
     */
    public function clickEvent(Entity $entity): void
    {
        $event = new CreateEventsRequest('click');
        $this->query($event->entity($entity));
    }

    /**
     * Creating an event indicating the end-user expressed a stronger desire
     * to own something.
     *
     * @param Entity $entity
     *    The entity that is now part of the users cart.
     */
    public function addToCartEvent(Entity $entity): void
    {
        $event = new CreateEventsRequest('addtocart');
        $this->query($event->entity($entity));
    }

    /**
     * Creating an event indicating the end-user has showcased the ultimate
     * expression of committed interest in owning something.
     *
     * @param Entity $entity
     *    The entity that is now in possession of the user.
     */
    public function purchaseEvent(Entity $entity): void
    {
        $event = new CreateEventsRequest('purchase');
        $this->query($event->entity($entity));
    }

    /**
     * Get a CreateEvents request that can be configured in greater detail
     * before sending it off.
     *
     * @param string $type
     *    Commonly one of 'click', 'addtocart', or 'purchase'.
     */
    public function createEvent(string $type): CreateEventsRequest
    {
        return new CreateEventsRequest($type);
    }

    /**
     * From one or more create event requests, build a single
     * {@see CreateEventsRequest} containing all events.
     */
    public function concatEvents(CreateEventsRequest ...$events): CreateEventsRequest
    {
        $rawEvents = array_map(
            fn (CreateEventsRequest $event) => $event->getRaw()->getEvents(),
            $events
        );
        $events = new CreateEventsRequest('');
        $events->getRaw()->setEvents(array_merge(...$rawEvents));
        return $events;
    }

    /**
     * Tell the engine to re-sync the contents (the product catalogue).
     */
    public function sync(): mixed
    {
        /* We don't have/need the user context here, so we perform manually. */
        return $this->admin->syncPost(
            api_version: self::APIVERSION,
            loop54_key: $this->apikey,
            sync_request: new OpenAPI\Model\SyncRequest(),
            lib_version: self::LIBVERSION,
        );
    }

    /**
     * @param array<string, mixed>|object $customData
     */
    public function doCustomRequest(string $requestName, array|object $customData): CustomResponse
    {
        return $this->query(new CustomRequest($requestName, $customData));
    }

    /**
     * @deprecated {@see query()} now handles requests from all API categories.
     */
    public function queryOther(Request $request): Response
    {
        trigger_error(
            'queryOther() is deprecated, use query() instead',
            E_USER_DEPRECATED
        );
        return $this->query($request);
    }

    /**
     * Actually perform a request once configured to your liking. The type of
     * response returned is determined by the request performed.
     */
    public function query(Request $request): Response
    {
        try {
            return $request->perform(new RequestContext(
                userInitiatedApi: $this->getRaw(),
                otherApi: $this->other,
                apiVersion: self::APIVERSION,
                libVersion: self::LIBVERSION,
                apiKey: $this->apikey,
                userId: $this->userId(),
                userIp: $this->userIp(),
                userAgent: $this->userAgent(),
                referer: $this->referer(),
            ));
        } catch (OpenAPI\ApiException $e) {
            $errorResponseBody = $e->getResponseBody();
            if (!empty(json_decode($errorResponseBody)->error)) {
                throw ClientException::http(json_decode($errorResponseBody)->error);
            } else {
                throw ClientException::unknown($errorResponseBody);
            }
        }
    }

    /**
     * Generate a random, compact, unique, yet recognizable user id.
     *
     * Just a plain UUID is easily confused with a million other things, so
     * append also "UID" in order to disambiguate and help troubleshooting.
     *
     * @throws RandomException If no secure source of randomness is available
     */
    public static function createUserId(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40); // version 4
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80); // variant 10xx

        return 'UID' . rtrim(base64_encode($bytes), '=');
    }

    private function userId(): string
    {
        $clientId = $this->remoteClientInfo->userId();
        if ($clientId == null) {
            $clientId = $this->remoteClientInfo->userId($this->createUserId());
        }
        return (string) $clientId;
    }

    private function userIp(): ?string
    {
        return self::nullableString($this->remoteClientInfo->userIp());
    }

    private function userAgent(): ?string
    {
        return self::nullableString($this->remoteClientInfo->userAgent());
    }

    private function referer(): ?string
    {
        return self::nullableString($this->remoteClientInfo->referer());
    }

    /**
     * RemoteClientInfo implementations are untyped and may return e.g. ints.
     */
    private static function nullableString(mixed $value): ?string
    {
        return $value === null ? null : (string) $value;
    }
}

<?php
namespace Loop54\API;

use \Ramsey\Uuid\Uuid;

class Client
{
    use OpenAPIWrapper;

    const APIVERSION = 'V3';
    const LIBVERSION = 'php:V3:2.1.1';
    private $apikey;
    private $remoteClientInfo;
    private $httpClient;
    private $admin;  // AdministrativeApi
    private $other;  // OtherApi

    /**
     * Initialises a connector instance which targets a Loop54 engine.
     *
     * @param $host string
     *    Which engine to perform requests against.
     *
     * @param $apikey string
     *    Your API key identifying you as a legitimate user of the engine.
     *
     * @param $remoteClientInfo RemoteClientInfo\Client
     *    An instance with methods to get information about the end-user
     *    performing the request. An implementation for a normal PHP web
     *    backend is available as {@see RemoteClientInfo\WebClient}. By
     *    "normal", we mean there is a $_COOKIE cookie jar, and a $_SERVER
     *    variable populated with the usual keys, like HTTP_CLIENT_IP,
     *    HTTP_X_FORWARDED_FOR, HTTP_USER_AGENT and so on.
     *
     * @param $client null|\GuzzleHttp\ClientInterface
     *    An optional HTTP client implementation. If none is given, Guzzle
     *    will automatically be used.
     */
    public function __construct(
        $host,
        $apikey,
        $remoteClientInfo,
        $client = null
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
     * @param $getUserId callable
     *    A function that, when called, will return the desired user id.
     *
     * @return Client
     */
    public function withUserId($getUserId)
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
     *
     * @param $query string
     *    The search query.
     *
     * @return SearchRequest
     */
    public function search($query)
    {
        return new SearchRequest($query);
    }

    /**
     * Configure an autocomplete request for a partial query.
     *
     * @param $query string
     *    The partial query to find completions for.
     *
     * @return AutoCompleteRequest
     */
    public function autoComplete($query)
    {
        return new AutoCompleteRequest($query);
    }

    /**
     * Configure a request to get entities with a specific attribute value.
     *
     * @param $name string
     *    The attribute to inspect.
     *
     * @param $value string
     *    Include only entities with this value for the attribute.
     *
     * @return GetEntitiesByAttributeRequest
     */
    public function getEntitiesByAttribute($name, $value)
    {
        return new GetEntitiesByAttributeRequest($name, $value);
    }
    
    /**
     * Configure a request to get entities.
     *
     * @return GetEntitiesRequest
     */
    public function getEntities()
    {
        return new GetEntitiesRequest();
    }
    
    /**
     * Configure a request to get entities related to the provided entity.
     *
     * @param $entity Entity
     *    The entity for which to fetch related entities.
     *
     * @return GetRelatedEntitiesRequest
     */
    public function getRelatedEntities($entity)
    {
        return new GetRelatedEntitiesRequest($entity);
    }

    /**
     * Configure a request to get entities complementary to the provided entity.
     *
     * @param $entity Entity
     *    The entity for which to fetch complementary entities.
     *
     * @return GetComplementaryEntitiesRequest
     */
    public function getComplementaryEntities($entity)
    {
        return new GetComplementaryEntitiesRequest($entity);
    }

    /**
     * Configure a request to get basket recommendations to the provided entities.
     *
     * @param $entitiies Entity[]
     *    The set of entities in a basket for which to fetch recommendations for.
     *
     * @return GetBasketRecommendationsRequest
     */
    public function getBasketRecommendations($entities)
    {
        return new GetBasketRecommendationsRequest($entities);
    }

    /**
     * Convenience method to create an {@see Entity} with no attributes but
     * with a particular id and type. This is useful e.g. for creating click
     * events, where you may not possess the full entity being clicked on, but
     * you can trivially find its ID and type.
     *
     * @param $id string
     *    ID for the entity.
     *
     * @param $type string
     *    The type of the entity.
     *
     * @return Entity
     */
    public function entity($type, $id)
    {
        return Entity::fromId($type, $id);
    }

    /**
     * Create an event indicating an end-user click.
     *
     * @param $entity Entity
     *    The entity being clicked.
     */
    public function clickEvent($entity)
    {
        $event = new CreateEventsRequest('click');
        $this->query($event->entity($entity));
    }

    /**
     * Creating an event indicating the end-user expressed a stronger desire
     * to own something.
     *
     * @param $entity Entity
     *    The entity that is now part of the users cart.
     */
    public function addToCartEvent($entity)
    {
        $event = new CreateEventsRequest('addtocart');
        $this->query($event->entity($entity));
    }

    /**
     * Creating an event indicating the end-user has showcased the ultimate
     * expression of committed interest in owning something.
     *
     * @param $entity Entity
     *    The entity that is now in possession of the user.
     */
    public function purchaseEvent($entity)
    {
        $event = new CreateEventsRequest('purchase');
        $this->query($event->entity($entity));
    }

    /**
     * Get a CreateEvents request that can be configured in greater detail
     * before sending it off.
     *
     * @param $type string
     *    Commonly one of 'click', 'addtocart', or 'purchase'.
     *
     * @return CreateEventsRequest
     */
    public function createEvent($type)
    {
        return new CreateEventsRequest($type);
    }

    /**
     * From one or more create event requests, build a single
     * {@see CreateEventsRequest} containing all events.
     *
     * @return CreateEventRequest
     */
    public function concatEvents(...$events)
    {
        $rawEvents = array_map(
            function ($event) {
                return $event->getRaw()->getEvents();
            },
            $events
        );
        $events = new CreateEventsRequest('');
        $events->getRaw()->setEvents(array_merge(...$rawEvents));
        return $events;
    }

    /**
     * Tell the engine to re-sync the contents (the product catalogue).
     */
    public function sync()
    {
        /* We don't have/need the user context here, so we perform manually. */
        return $this->admin->syncPost(
            Client::APIVERSION,               // apiversion
            $this->apikey,                    // apikey
            new OpenAPI\Model\SyncRequest(),  // request
            Client::LIBVERSION                // libversion
        );
    }

    public function doCustomRequest($requestName, $customData)
    {
        return $this->queryOther(
            new CustomRequest($requestName, $customData)
        );
    }

    /**
     * Perform a request from the 'Other' API category.
     *
     * @param $request Request
     *
     * @return object
     */
    public function queryOther($request)
    {
        return $this->queryInstance($request, $this->other);
    }

    /**
     * Actually perform a request once configured to your liking. The type of
     * response returned is determined by the request performed.
     *
     * @param $request Request
     *    Perform this request please.
     *
     * @return Response
     */
    public function query($request)
    {
        return $this->queryInstance($request, $this->getRaw());
    }

    /**
     * Generate a random, compact, unique, yet recogniseable user id.
     *
     * Just a plain UUID is easily confused with a million other things, so
     * append also "UID" in order to disambiguate and help troubleshooting.
     */
    public function createUserId()
    {
        return 'UID' . rtrim(base64_encode(Uuid::uuid4()->getBytes()), '=');
    }

    private function userId()
    {
        $clientId = $this->remoteClientInfo->userId();
        if ($clientId == null) {
            return $this->remoteClientInfo->userId($this->createUserId());
        }
        return $clientId;
    }

    private function userIp()
    {
        return $this->remoteClientInfo->userIp();
    }

    private function userAgent()
    {
        return $this->remoteClientInfo->userAgent();
    }

    private function referer()
    {
        return $this->remoteClientInfo->referer();
    }

    private function queryInstance($request, $instance)
    {
        if ($request == null) {
            throw new \InvalidArgumentException('Request cannot be null');
        }
        if ($instance == null) {
            throw new \InvalidArgumentException('Instance cannot be null');
        }
        try {
            return $request->perform(
                $instance,
                Client::APIVERSION,
                Client::LIBVERSION,
                $this->apikey,
                $this->userId(),
                $this->userIp(),
                $this->userAgent(),
                $this->referer()
            );
        } catch (OpenAPI\ApiException $e) {
            $errorResponseBody = $e->getResponseBody();
            if (!empty(json_decode($errorResponseBody)->error)) {
                throw ClientException::http(json_decode($errorResponseBody)->error);
            } else {
                throw ClientException::unknown($errorResponseBody);
            }
        }
    }
}

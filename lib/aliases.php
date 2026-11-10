<?php
/**
 * Keeps code written against connector v4.0.1 working. Requests and responses used to live
 * directly in the Loop54\API namespace, and have since moved to
 * Loop54\API\Request, Loop54\API\Request\Parameters and Loop54\API\Response.
 *
 * The aliases are registered up front, since with lazily registered aliases, code like
 * `function render(\Loop54\API\SearchResponse $response)` would reject the
 * objects returned by Client::query().
 *
 * @deprecated The old names will be removed in a future major version.
 */
namespace Loop54\API;

class_alias(Request\Request::class, Request::class);
class_alias(Request\AutoCompleteRequest::class, AutoCompleteRequest::class);
class_alias(Request\CreateEventsRequest::class, CreateEventsRequest::class);
class_alias(Request\CustomRequest::class, CustomRequest::class);
class_alias(Request\GetBasketRecommendationsRequest::class, GetBasketRecommendationsRequest::class);
class_alias(Request\GetComplementaryEntitiesRequest::class, GetComplementaryEntitiesRequest::class);
class_alias(Request\GetEntitiesByAttributeRequest::class, GetEntitiesByAttributeRequest::class);
class_alias(Request\GetEntitiesRequest::class, GetEntitiesRequest::class);
class_alias(Request\GetPopularEntitiesRequest::class, GetPopularEntitiesRequest::class);
class_alias(Request\GetRecentEntitiesRequest::class, GetRecentEntitiesRequest::class);
class_alias(Request\GetRecommendedEntitiesRequest::class, GetRecommendedEntitiesRequest::class);
class_alias(Request\GetRelatedEntitiesRequest::class, GetRelatedEntitiesRequest::class);
class_alias(Request\SearchRequest::class, SearchRequest::class);

class_alias(Request\Parameters\FilterParameter::class, FilterParameter::class);
class_alias(Request\Parameters\RequestAliasFields::class, RequestAliasFields::class);
class_alias(Request\Parameters\ResultsOptions::class, ResultsOptions::class);

class_alias(Response\Response::class, Response::class);
class_alias(Response\AutoCompleteResponse::class, AutoCompleteResponse::class);
class_alias(Response\CreateEventsResponse::class, CreateEventsResponse::class);
class_alias(Response\GetBasketRecommendationsResponse::class, GetBasketRecommendationsResponse::class);
class_alias(Response\GetComplementaryEntitiesResponse::class, GetComplementaryEntitiesResponse::class);
class_alias(Response\GetEntitiesByAttributeResponse::class, GetEntitiesByAttributeResponse::class);
class_alias(Response\GetEntitiesResponse::class, GetEntitiesResponse::class);
class_alias(Response\GetPopularEntitiesResponse::class, GetPopularEntitiesResponse::class);
class_alias(Response\GetRecentEntitiesResponse::class, GetRecentEntitiesResponse::class);
class_alias(Response\GetRecommendedEntitiesResponse::class, GetRecommendedEntitiesResponse::class);
class_alias(Response\GetRelatedEntitiesResponse::class, GetRelatedEntitiesResponse::class);
class_alias(Response\SearchResponse::class, SearchResponse::class);

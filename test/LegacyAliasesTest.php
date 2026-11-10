<?php
namespace Loop54\API;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\Attributes\TestDox;

/**
 * Code written against connector v4.0.1 and earlier versions use the old class names directly in the
 * Loop54\API namespace. These tests make sure that code keeps working.
 *
 * Old names are always written fully qualified here, so it is obvious which
 * name a line is using.
 */
class LegacyAliasesTest extends \PHPUnit\Framework\TestCase
{
    public static function legacyNames(): array
    {
        $moved = [
            'Request\\' => [
                'Request', 'AutoCompleteRequest', 'CreateEventsRequest',
                'CustomRequest', 'GetBasketRecommendationsRequest',
                'GetComplementaryEntitiesRequest',
                'GetEntitiesByAttributeRequest', 'GetEntitiesRequest',
                'GetPopularEntitiesRequest', 'GetRecentEntitiesRequest',
                'GetRecommendedEntitiesRequest', 'GetRelatedEntitiesRequest',
                'SearchRequest',
            ],
            'Request\\Parameters\\' => [
                'FilterParameter', 'RequestAliasFields', 'ResultsOptions',
            ],
            'Response\\' => [
                'Response', 'AutoCompleteResponse', 'CreateEventsResponse',
                'GetBasketRecommendationsResponse',
                'GetComplementaryEntitiesResponse',
                'GetEntitiesByAttributeResponse', 'GetEntitiesResponse',
                'GetPopularEntitiesResponse', 'GetRecentEntitiesResponse',
                'GetRecommendedEntitiesResponse', 'GetRelatedEntitiesResponse',
                'SearchResponse',
            ],
        ];

        $cases = [];
        foreach ($moved as $subNamespace => $classes) {
            foreach ($classes as $class) {
                $cases[$class] = [
                    'Loop54\\API\\' . $class,
                    'Loop54\\API\\' . $subNamespace . $class,
                ];
            }
        }
        return $cases;
    }

    #[DataProvider('legacyNames')]
    #[TestDox('$legacyName is an alias of $newName')]
    public function testLegacyNameIsAliasOfNewName(string $legacyName, string $newName)
    {
        $this->assertTrue(
            class_exists($legacyName) || interface_exists($legacyName),
            "Legacy name {$legacyName} should still be usable"
        );
        $this->assertSame(
            $newName,
            (new \ReflectionClass($legacyName))->getName(),
            "Legacy name {$legacyName} should refer to {$newName}"
        );
    }

    #[TestDox('Objects can still be created and configured using the legacy names')]
    public function testLegacyNamesCanBeUsedToBuildRequests()
    {
        $request = new \Loop54\API\SearchRequest('meat');
        $filters = new \Loop54\API\FilterParameter();
        $request->resultsOptions()
            ->sortBy(
                \Loop54\API\ResultsOptions::TYPE_ID,
                \Loop54\API\ResultsOptions::ORDER_ASC
            )
            ->filter($filters->type('Product'));

        $this->assertInstanceOf(Request\SearchRequest::class, $request);
        $this->assertSame('meat', $request->getQuery());
    }

    /**
     * PHP does not autoload for instanceof checks and parameter type checks.
     * This runs in a separate process, so the legacy names have not been
     * touched by any other test: it only passes if the aliases are registered
     * as soon as the Composer autoloader is.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    #[TestDox('Objects of the new classes satisfy type declarations using the legacy names')]
    public function testNewObjectsSatisfyLegacyTypeDeclarations()
    {
        $request = new Request\SearchRequest('meat');
        $response = new Response\SearchResponse(
            new OpenAPI\Model\SearchResponse()
        );

        $this->assertTrue($request instanceof \Loop54\API\SearchRequest);
        $this->assertTrue($request instanceof \Loop54\API\Request);
        $this->assertTrue($response instanceof \Loop54\API\SearchResponse);
        $this->assertTrue($response instanceof \Loop54\API\Response);

        $legacyHandler = function (
            \Loop54\API\SearchRequest $request,
            \Loop54\API\SearchResponse $response
        ) {
            return 'handled';
        };
        $this->assertSame('handled', $legacyHandler($request, $response));
    }
}

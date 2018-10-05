<?php
namespace Loop54\API;

// Do not warn when this is both running a side effect and defining a class.
// We need this since the class will be run sort of stand-alone.
// phpcs:disable
require_once(__DIR__ . '/../vendor/autoload.php');
// phpcs:enable

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Prophecy\Argument;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    private static $resources = __DIR__ . '/resources';

    private static $requiredHeaders = [
        'Host',                                       // Required in HTTP 1.1
        'Api-Version', 'Loop54-key',                  // API meta information
        'User-Useragent', 'User-Referer', 'User-Id',  // End-user information
        'Lib-Version'                                 // Lib meta information
    ];

    private static $noUserInfoRequests = [
        '/sync'
    ];

    private function skipHeader($request, $header)
    {
        return substr($header, 0, 5) == 'User-'
            && in_array(
                $request->getUri()->getPath(),
                self::$noUserInfoRequests
            );
    }

    private function isAPIRequest($request)
    {
        foreach (self::$requiredHeaders as $header) {
            if ($this->skipHeader($request, $header)) {
                continue;
            }
            $this->assertTrue(
                $request->hasHeader($header),
                'Missing required header ' . $header . ' in request'
            );
        }
        $this->assertEquals('V3', $request->getHeader('Api-Version')[0]);
        return true;
    }

    private function assertBodyContains($request, $string)
    {
        $this->assertContains(
            $string,
            $request->getBody()->__toString(),
            'Request body should contain ' . $string
        );
    }

    private function isValidRequest($endpoint, $testName)
    {
        $method = 'POST';
        return function ($request) use ($endpoint, $testName, $method) {
            self::isAPIRequest($request);
            $this->assertEquals(
                'POST',
                $request->getMethod(),
                'Request not made with the expected method'
            );
            $this->assertEquals(
                '/' . $endpoint,
                $request->getUri()->getPath(),
                'Request not made to the expected path'
            );
            if ($endpoint == 'search') {
                $this->assertBodyContains($request, '"query"');
                if ($testName == 'advanced') {
                    $this->assertBodyContains($request, '"skip":1');
                    $this->assertBodyContains($request, '"take":3');
                    $this->assertBodyContains($request, '"order":"asc"');
                    $this->assertBodyContains($request, '"type":"distinct"');
                    $this->assertBodyContains($request, '"type":"range"');
                    $this->assertBodyContains($request, 'uteName":"Pri');
                    $this->assertBodyContains($request, '"selected":{');
                    $this->assertBodyContains($request, '"max":600');
                    $this->assertBodyContains($request, 'ected":["Fruit"]');
                    $this->assertBodyContains($request, 'ilter":{');
                    $this->assertBodyContains($request, '"and":[{');
                    $this->assertBodyContains($request, '"not":{');
                    $this->assertBodyContains($request, '"Food"');
                }
            } elseif ($endpoint == 'autoComplete') {
                $this->assertBodyContains($request, '"query"');
                $this->assertBodyContains($request, '"take":5');
            } elseif ($endpoint == 'getEntitiesByAttribute') {
                $this->assertBodyContains($request, '"attribute"');
                $this->assertBodyContains($request, '"value":"MeatNStuff"');
                $this->assertBodyContains($request, '"skip":1');
                $this->assertBodyContains($request, '"order":"desc"');
                $this->assertBodyContains($request, 'uteName":"Pri');
            } elseif ($endpoint == 'createEvents') {
                $this->assertBodyContains($request, '"events"');
                $this->assertBodyContains($request, '"type":"Product"');
                $this->assertBodyContains($request, '"id":12');
                if ($testName == 'multi') {
                    $this->assertBodyContains($request, '"quantity":5');
                    $this->assertBodyContains($request, '"revenue":249');
                }
            }
            return true;
        };
    }

    private function response($endpoint, $testName)
    {
        $resource = $endpoint . '_response_' . $testName . '.json';
        $responseString =
            file_get_contents(self::$resources . '/' . $resource);

        $responseStream = $this->prophesize(StreamInterface::class);
        $responseStream
            ->getContents()
            ->willReturn($responseString)
            ->shouldBeCalled();

        $response = $this->prophesize(ResponseInterface::class);
        $response
            ->getStatusCode()
            ->willReturn(200)
            ->shouldBeCalled();
        $response
            ->getHeaders()
            ->willReturn([]);
        $response
            ->getBody()
            ->willReturn($responseStream->reveal())
            ->shouldBeCalled();

        return $response;
    }

    private function mockClient($endpoint, $testName)
    {
        $isValidRequest = $this->isValidRequest($endpoint, $testName);
        $response = $this->response($endpoint, $testName);

        $httpClient = $this->prophesize(ClientInterface::class);
        $httpClient
            ->send(Argument::that($isValidRequest), Argument::any())
            ->willReturn($response->reveal())
            ->shouldBeCalled();

        $connector = new Client(
            'https://not-a-real-engine.54proxy.com/',
            'api-key',
            new RemoteClientInfo\SimpleClient(
                '127.0.0.54',
                'simple fake client',
                'no-referer'
            ),
            $httpClient->reveal()
        );

        return $connector;
    }

    public function testSearchMakesSense()
    {
        $connector = $this->mockClient('search', 'makessense');
        $request = $connector->search('oyuwantvievkpyuywvu');
        $response = $connector->query($request);
        $this->assertFalse(
            $response->getMakesSense(),
            'MakesSense should not be true'
        );
        $this->assertEquals(
            'meat',
            $response->getSpellingSuggestions()[0]['query'],
            'Incorrect spelling suggestions'
        );
    }

    public function testSearchMeat()
    {
        $connector = $this->mockClient('search', 'plain');
        $request = $connector->search('search phrase');
        $response = $connector->query($request);
        $this->assertTrue(
            $response->getMakesSense(),
            'MakesSense should be true'
        );
        $this->assertNotEmpty(
            $response->getRelatedQueries(),
            'There should be related queries'
        );
        $this->assertEquals(
            7,
            $response->getRaw()->getResults()->getCount(),
            'Incorrect direct result count'
        );
        $this->assertEmpty(
            $response->getFacets(),
            'There should be no facets defined'
        );
        $this->assertNotEmpty(
            $response->getResults(),
            'There should be results'
        );
        $this->assertNotEmpty(
            $response->getRelatedResults(),
            'There should be related results'
        );
    }

    public function testSearchAdvanced()
    {
        $connector = $this->mockClient('search', 'advanced');
        $request = $connector->search('orange');
        $request->resultsOptions()
            ->skip(1)
            ->take(3)
            ->sortBy(ResultsOptions::TYPE_ID, ResultsOptions::ORDER_ASC)
            ->addDistinctFacet('Category', 'Category', ['Fruit'])
            ->addRangeFacet('Price', 'Price', 3, 600);

        $filters = new FilterParameter();

        $request->relatedResultsOptions()
            ->filter($filters->attribute('Category', 'Food')
                     ->and($filters->type('Product'))
                     ->or($filters->attributeExists('Price')->invert()));

        $response = $connector->query($request);

        $this->assertCount(
            2,
            $response->getFacets(),
            'There should be two facets defined'
        );
        $this->assertEmpty(
            $response->getResults(),
            'There should be no results'
        );
    }

    public function testAutoComplete()
    {
        $connector = $this->mockClient('autoComplete', 'plain');
        $request = $connector->autocomplete('a')->take(5);
        $response = $connector->query($request);
        $this->assertCount(
            4,
            SearchRequest::fromQueryResult($response->getScopedResults()),
            'There should be 4 queries created from the scoped results'
        );
        $this->assertCount(
            4,
            array_map(
                '\Loop54\API\SearchRequest::fromQueryResult',
                $response->getUnScopedResults()
            ),
            'There should be 4 queries created from the unscoped results'
        );
        $this->assertCount(
            8,
            $response->getResultsAsSearchRequests(),
            'There should be a total of 8 suggested queries to complete this'
        );
    }

    public function testGetEntitiesByAttribute()
    {
        $connector = $this->mockClient('getEntitiesByAttribute', 'advanced');
        $request = $connector->getEntitiesByAttribute(
            'Manufacturer',
            'MeatNStuff'
        );
        $request->resultsOptions()
            ->skip(1)
            ->take(3)
            ->sortBy(
                ResultsOptions::TYPE_ATTRIBUTE,
                ResultsOptions::ORDER_DESC,
                'Price'
            );

        $response = $connector->query($request);

        $this->assertEmpty(
            $response->getFacets(),
            "There should be no facets"
        );
        $this->assertCount(
            3,
            $response->getResults(),
            "There should be 3 entities"
        );
        $this->assertEquals(
            'Product',
            $response->getResults()[0]->getType(),
            'The type of this entity should be Product'
        );
    }

    public function testCreateEventsPlain()
    {
        $connector = $this->mockClient('createEvents', 'plain');

        /* Simple events, sent off right away */
        $connector->clickEvent($connector->entity('Product', 12));
        $connector->addToCartEvent($connector->entity('Product', 12));
    }

    public function testCreateEventsMulti()
    {
        $connector = $this->mockClient('createEvents', 'multi');

        $purchase = $connector->concatEvents(
            $connector->createEvent('purchase')
                ->entity($connector->entity('Product', 12))
                ->quantity(5)
                ->revenue(249),
            $connector->createEvent('purchase')
                ->entity($connector->entity('Product', 12)),
            $connector->createEvent('purchase')
                ->entity($connector->entity('Product', 12))
        );

        $connector->query($purchase);
    }

    public function testSync()
    {
        $connector = $this->mockClient('sync', 'plain');
        $connector->sync();
    }
}

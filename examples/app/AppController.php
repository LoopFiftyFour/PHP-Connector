<?php

namespace Loop54\API\Examples\App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Loop54\API\Examples\Config;

class AppController extends AbstractController
{
    public function __construct()
    {
        $remoteClientInfo = new \Loop54\API\RemoteClientInfo\WebClient();
        $this->connector = new \Loop54\API\Client(
            Config\ENDPOINT,
            Config\API_KEY,
            $remoteClientInfo
        );
    }

    private function allRoutes()
    {
        $router = $this->get('kernel')->getContainer()->get('router');
        $router->setOption('debug', true);
        return $router->getRouteCollection()->all();
    }

    private static function populateParameters($source, $parameters)
    {
        foreach ($parameters as $fieldset => $fields) {
            foreach ($fields as $name => $meta) {
                if (isset($source[$name])) {
                    if (!is_callable($meta['validate'])
                        || call_user_func($meta['validate'], $source[$name])
                    ) {
                        $parameters[$fieldset][$name]['value'] =
                            $source[$name];
                    }
                }
            }
        }
        return $parameters;
    }

    public function search()
    {
        $parameters = self::populateParameters($_GET, [
            'Basic' => [
                'query' => [
                    'label' => 'Query',
                    'formtype' => 'text',
                    'validate' => 'Loop54\API\Examples\App\Utils\nonempty',
                    'placeholder' => 'meat'
                ]
            ],
            'Options' => [
                'skip' => [
                    'label' => 'Skip',
                    'formtype' => 'number',
                    'validate' => 'is_numeric',
                    'placeholder' => '0'
                ],
                'take' => [
                    'label' => 'Take',
                    'formtype' => 'number',
                    'validate' => 'is_numeric',
                    'placeholder' => '10'
                ]
            ],
            'Faceting' => [
                'distinct' => [
                    'label' => 'Distinct',
                    'formtype' => 'text',
                    'validate' => 'Loop54\API\Examples\App\Utils\nonempty',
                    'placeholder' => 'Manufacturer,Category'
                ],
                'range' => [
                    'label' => 'Range',
                    'formtype' => 'text',
                    'validate' => 'Loop54\API\Examples\App\Utils\nonempty',
                    'placeholder' => 'OrgPrice'
                ]
            ],
            'Sorting' => [
                'sortby' => [
                    'label' => 'Sort by',
                    'formtype' => 'text',
                    'validate' => 'Loop54\API\Examples\App\Utils\nonempty',
                    'placeholder' => 'relevance'
                ]
            ]
        ]);

        if (isset($parameters['Basic']['query']['value'])) {
            $request = $this->connector
                ->search($parameters['Basic']['query']['value']);
        }
        if (isset($parameters['Options']['skip']['value'])) {
            $request->resultsOptions()
                ->skip((int) $parameters['Options']['skip']['value']);
        }
        if (isset($parameters['Options']['take']['value'])) {
            $request->resultsOptions()
                ->take((int) $parameters['Options']['take']['value']);
        }
        if (isset($parameters['Faceting']['distinct']['value'])) {
            $distinct = $parameters['Faceting']['distinct']['value'];
            foreach (explode(',', $distinct) as $facet) {
                $selected = null;
                if (isset($_GET['distinct_' . $facet])) {
                    $selected = $_GET['distinct_' . $facet];
                }
                $request->resultsOptions()->addDistinctFacet(
                    $facet,
                    $facet,
                    $selected
                );
            }
        }
        if (isset($parameters['Faceting']['range']['value'])) {
            $distinct = $parameters['Faceting']['range']['value'];
            foreach (explode(',', $distinct) as $facet) {
                $request->resultsOptions()->addRangeFacet($facet, $facet);
            }
        }
        if (isset($parameters['Sorting']['sortby']['value'])) {
            $request->sortBy(
                $parameters['Sorting']['sortby']['value'],
                \Loop54\API\Search::ORDER_DESC
            );
        }

        if (isset($request)) {
            $response = $this->connector->query($request);
        }
        else {
            $response = null;
        }
        

        return $this->render('search.html', [
            'routes' => $this->allRoutes(),
            'currentPage' => 'Search',
            'parameters' => $parameters,
            'response' => $response,
            'raw_request' => isset($request)
                ? print_r($request->getRaw(), 1)
                : '',
            'raw_response' => isset($response)
                ? print_r($response->getRaw(), 1)
                : '',
        ]);
    }

    public function autoComplete()
    {
        $parameters = self::populateParameters($_GET, [
            'Basic' => [
                'query' => [
                    'label' => 'Query',
                    'formtype' => 'text',
                    'validate' => 'Loop54\API\Examples\App\Utils\nonempty',
                    'placeholder' => 'm'
                ]
            ],
            'Options' => [
                'take' => [
                    'label' => 'Take',
                    'formtype' => 'number',
                    'validate' => 'is_numeric',
                    'placeholder' => '10'
                ]
            ]
        ]);

        if (isset($parameters['Basic']['query']['value'])) {
            $request = $this->connector
                ->autoComplete($parameters['Basic']['query']['value']);
        }
        if (isset($parameters['Options']['take']['value'])) {
            $request
                ->take((int) $parameters['Options']['take']['value']);
        }

        if (isset($request)) {
            $response = $this->connector->query($request);
        }
        else {
            $response = null;
        }

        return $this->render('autocomplete.html', [
            'routes' => $this->allRoutes(),
            'currentPage' => 'AutoComplete',
            'parameters' => $parameters,
            'response' => $response,
            'raw_request' => isset($request)
                ? print_r($request->getRaw(), 1)
                : '',
            'raw_response' => isset($response)
                ? print_r($response->getRaw(), 1)
                : '',
        ]);
    }

    public function createEvents()
    {
        if (!isset($_GET['entity_type']) || !isset($_GET['entity_id'])) {
            return $this->redirectToRoute('Search');
        }

        $entity = $this->connector
            ->entity($_GET['entity_type'], $_GET['entity_id']);

        $request = $this->connector->clickEvent($entity);

        $returnRoute = $_GET['return'];
        unset($_GET['type']);
        unset($_GET['entity_id']);
        unset($_GET['entity_type']);
        unset($_GET['return']);
        return $this->redirectToRoute($returnRoute, $_GET);
    }
}

<?php
ob_start();
require_once __DIR__ . '/../vendor/autoload.php';

class IntegrationTest extends \PHPUnit\Framework\TestCase
{
    public function testSearchResultCount()
    {
        $request = new Loop54_Request('search');
        $request->setValue('QueryString', 'meat');
        $response = Loop54_RequestHandling::getResponse(
            'https://helloworld.54proxy.com',
            $request
        );

        $resultCount = $response->getValue("DirectResults_TotalItems");
        $this->assertTrue(
            is_integer($resultCount),
            "DirectResults_TotalItems is an integer"
        );
    }
}

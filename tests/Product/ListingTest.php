<?php

namespace Product;

use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Json\Json;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;
use Xigen\Library\OnBuy\Product\Listing;

class ListingTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testHeader()
    {
        $token = 'xyz';
        $client = new Listing($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
        self::assertSame(Constants::CONTENT_TYPE, $client->getClient()->getHeader('Content-Type'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Listing('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

    /**
     * Get details of current product listings
     */
    public function testGetListingParametersCastToString()
    {
        $listing = new Listing('xyz');
        $client = $listing->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($listing->getDomain() . $listing->getVersion() . Constants::LISTINGS);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'sort' => ['last_created' => 'asc'],
            'filter' => ['sku' => 'test'],
            'limit' => Constants::DEFAULT_LIMIT,
            'offset' => Constants::DEFAULT_OFFSET
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, str_replace(
                ['[', ']'],
                ['%5B','%5D'],
                'https://api.onbuy.com/v2/listings?sort[last_created]=asc&filter[sku]=test&limit=50&offset=0'
            ));

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Update listing by product data array
     */
    public function testUpdateListingBySku()
    {
        $listing = new Listing('xyz');
        $client = $listing->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($listing->getDomain() . $listing->getVersion() . Constants::LISTINGS_BY_SKU);
        $client->setMethod(Request::METHOD_PUT);
        $client->setRawBody(Json::encode(
            $this->getMockUpdate()
        ));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_PUT,
                'https://api.onbuy.com/v2/listings/by-sku',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/json',
                    'Content-Length' => 111,
                ],
                '{"site_id":2000,"listings":{"sku":"EXP-143-33S","price":126.34,"stock":125,"boost_marketing_commission":14.83}}'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Delete listing by SKU
     */
    public function testDeleteListingBySku()
    {
        $listing = new Listing('xyz');
        $client = $listing->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($listing->getDomain() . $listing->getVersion() . Constants::LISTINGS_BY_SKU);
        $client->setMethod(Request::METHOD_DELETE);
        $client->setRawBody(Json::encode([
            'site_id' => Constants::SITE_ID,
            'skus' => ['EXP-143-33S', 'EXP-143-33L']
        ]));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_DELETE,
                'https://api.onbuy.com/v2/listings/by-sku',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/json',
                    'Content-Length' => 53,
                ],
                '{"site_id":2000,"skus":["EXP-143-33S","EXP-143-33L"]}'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Create a single product listing from product data array
     */
    public function testCreateListing()
    {
        $listing = new Listing('xyz');
        $client = $listing->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $oPC = 'PN8JV6';
        $client->setAdapter($adapter);
        $client->setUri($listing->getDomain() . $listing->getVersion() . Constants::PRODUCTS . '/' . $oPC . '/' . Constants::LISTINGS);
        $client->setMethod(Request::METHOD_POST);
        $client->setRawBody(Json::encode(
            $this->getMockCreate()
        ));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_POST,
                'https://api.onbuy.com/v2/products/PN8JV6/listings',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/json',
                    'Content-Length' => 118,
                ],
                '{"opc":"PN8JV6","site_id":2000,"listings":[{"sku":"EXP-143-33S","group_sku":"bar","boost_marketing_commission":2.98}]}'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Create a product listing from product data array
     */
    public function testCreateListingByBatch()
    {
        $listing = new Listing('xyz');
        $client = $listing->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $oPC = 'PN8JV6';
        $client->setAdapter($adapter);
        $client->setUri($listing->getDomain() . $listing->getVersion() . Constants::LISTINGS);
        $client->setMethod(Request::METHOD_POST);
        $client->setRawBody(Json::encode(
            $this->getMockCreateByBatch()
        ));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_POST,
                'https://api.onbuy.com/v2/listings',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/json',
                    'Content-Length' => 164,
                ],
                '{"site_id":2000,"listings":[{"opc":"PN8JV6","condition":"poor","price":9.99,"stock":8,"delivery_weight":16,"handling_time":125,"free_returns":"true","warranty":7}]}'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Retrieve the available delivery options set up on your seller account
     */
    public function testGetWinningListing()
    {
        $listing = new Listing('xyz');
        $client = $listing->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($listing->getDomain() . $listing->getVersion() . Constants::LISTINGS_WINNING);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'site_id' => Constants::SITE_ID,
            'skus' => ['EXP-143-33S', 'EXP-143-33L']
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, str_replace(
                ['[', ']'],
                ['%5B','%5D'],
                'https://api.onbuy.com/v2/listings/check-winning?site_id=2000&skus[0]=EXP-143-33S&skus[1]=EXP-143-33L'
            ));

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Invalid create
     * @throws \Exception
     */
    public function testInvalidCreate()
    {
        $this->expectException(\Exception::class);
        $listing = new Listing('xyz');
        $listing->createListing();
    }

    /**
     * Invalid create data
     * @throws \Exception
     */
    public function testInvalidCreateData()
    {
        $this->expectException(\Exception::class);
        $listing = new Listing('xyz');
        $listing->createListing('abc', []);
    }

    /**
     * Invalid create data
     * @throws \Exception
     */
    public function testInvalidCreateByBatchData()
    {
        $this->expectException(\Exception::class);
        $listing = new Listing('xyz');
        $listing->createListingByBatch();
    }

    /**
     * Invalid delete
     * @throws \Exception
     */
    public function testInvalidDelete()
    {
        $this->expectException(\Exception::class);
        $listing = new Listing('xyz');
        $listing->deleteListingBySku();
    }

    /**
     * Mock listing update
     * @return array
     */
    public function getMockCreate()
    {
        return [
            "opc" => "PN8JV6",
            "site_id" => 2000,
            "listings" => [
                [
                    "sku" => "EXP-143-33S",
                    "group_sku" => "bar",
                    "boost_marketing_commission" => 2.98
                ]
            ]
        ];
    }

    public function getMockCreateByBatch()
    {
        return [
            "site_id" => 2000,
            "listings" => [
                [
                    "opc" => "PN8JV6",
                    "condition" => "poor",
                    "price" => 9.99,
                    "stock" => 8,
                    "delivery_weight" => 16,
                    "handling_time" => 125,
                    "free_returns" => "true",
                    "warranty" => 7
                ]
            ]
        ];
    }

    /**
     * Mock listing update
     * @return array
     */
    public function getMockUpdate()
    {
        return [
            "site_id" => 2000,
            "listings" => [
                "sku" => "EXP-143-33S",
                "price" => 126.34,
                "stock" => 125,
                "boost_marketing_commission" => 14.83
            ]
        ];
    }
}

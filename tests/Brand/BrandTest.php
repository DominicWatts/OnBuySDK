<?php

namespace Xigen\Library\OnBuy\Brand;

use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Response;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;

class BrandTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testAuthorizationHeader()
    {
        $token = 'xyz';
        $client = new Brand($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Brand('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

    /**
     * Building of brand search get request
     */
    public function testGetBrandParametersCastToString()
    {
        $brand = new Brand('xyz');
        $client = $brand->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($brand->getDomain() . $brand->getVersion() . Constants::BRAND);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'filter' => [
                'name' => 'test'
            ],
            'sort' => [
                'name' => Constants::DEFAULT_SORT,
            ],
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
                'https://api.onbuy.com/v2/brand?filter[name]=test&sort[name]=asc&limit=50&offset=0'
            ));

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Building of brand search get request
     */
    public function testGetBrandByIdParametersCastToString()
    {
        $brand = new Brand('xyz');
        $client = $brand->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($brand->getDomain() . $brand->getVersion() . Constants::BRAND . '/123');
        $client->setMethod(Request::METHOD_GET);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/brand/123');

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Invalid search
     * @throws \Exception
     */
    public function testInvalidSearch()
    {
        $this->expectException(\Exception::class);
        $brand = new Brand('xyz');
        $brand->getBrand([]);
    }

    /**
     * Invalid search
     * @throws \Exception
     */
    public function testInvalidSearchById()
    {
        $this->expectException(\Exception::class);
        $brand = new Brand('xyz');
        $brand->getBrandById();
    }

    /**
     * Obtain brand information for any brands created on OnBuy
     * @throws \Exception
     */
    public function testGetBrand()
    {
        $brand = new Brand('xyz');
        $result = $brand->getBrand('keyword', 'asc', 10, 0);
        self::assertInstanceOf(Client::class, $result);
    }

    /**
     * Obtain information for a single OnBuy brand
     * @throws \Exception
     */
    public function testGetBrandById()
    {
        $brand = new Brand('xyz');
        $result = $brand->getBrandById(123);
        self::assertInstanceOf(Client::class, $result);
    }
}

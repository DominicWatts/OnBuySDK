<?php

namespace Xigen\Library\OnBuy\Brand;

use PHPUnit\Framework\TestCase;
use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Http\Client\Adapter\AdapterInterface;
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
     * Building of brand search get request
     */
    public function testGetBrandParametersCastToString()
    {
        $connect = new Constants();
        $client = new Client();

        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($connect->getDomain() . $connect->getVersion() . Constants::BRAND);
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
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/brand?filter%5Bname%5D=test&sort%5Bname%5D=asc&limit=50&offset=0');

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
        $connect = new Constants();
        $client = new Client();

        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($connect->getDomain() . $connect->getVersion() . Constants::BRAND. '/123');
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
}

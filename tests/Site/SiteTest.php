<?php

namespace Site;

use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Xigen\Library\OnBuy\Site\Site;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;

class SiteTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testAuthorizationHeader()
    {
        $token = 'xyz';
        $client = new Site($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Site('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

      /**
     * Building of brand search get request
     */
    public function testGetSiteParametersCastToString()
    {
        $site = new Site('xyz');
        $client = $site->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($site->getDomain() . $site->getVersion() . Constants::SITES);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'filter' => [
                'name' => 'bar'
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
                'https://api.onbuy.com/v2/sites?filter[name]=bar&limit=50&offset=0'
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
    public function testGetSiteByIdParametersCastToString()
    {
        $site = new Site('xyz');
        $client = $site->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($site->getDomain() . $site->getVersion() . Constants::SITES . '/123');
        $client->setMethod(Request::METHOD_GET);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/sites/123');

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
    public function testInvalidSearchById()
    {
        $this->expectException(\Exception::class);
        $client = new Site('xyz');
        $client->getSiteById();
    }
}

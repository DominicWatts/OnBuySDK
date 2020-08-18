<?php

namespace Site;

use Laminas\Http\Client;
use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;
use Xigen\Library\OnBuy\Site\Site;

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
     * Obtain site information for any regional variation of OnBuy
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
     * Obtain information for a single OnBuy regional site
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
        $site = new Site('xyz');
        $site->getSiteById();
    }

    public function testGetSite()
    {
        $site = new Site('xyz');
        $result = $site->getSite([
            'name' => 'test'
        ]);
        self::assertInstanceOf(Client::class, $result);
    }

    public function testGetSiteById()
    {
        $site = new Site('xyz');
        $result = $site->getSiteById(123);
        self::assertInstanceOf(Client::class, $result);
    }
}

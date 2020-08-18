<?php

namespace Seller;

use Laminas\Http\Client;
use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;
use Xigen\Library\OnBuy\Seller\Seller;

class SellerTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testHeader()
    {
        $token = 'xyz';
        $client = new Seller($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Seller('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

    /**
     * Obtain information about your seller account
     */
    public function testGetEntityByIdParametersCastToString()
    {
        $seller = new Seller('xyz');
        $client = $seller->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($seller->getDomain() . $seller->getVersion() . Constants::SELLERS . '/123');
        $client->setMethod(Request::METHOD_GET);
        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/sellers/123');

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
        $seller = new Seller('xyz');
        $seller->getSellerById();
    }

    /**
     * Obtain information about your seller account
     * @throws \Exception
     */
    public function testGetSellerById()
    {
        $seller = new Seller('xyz');
        $result = $seller->getSellerById(123);
        self::assertInstanceOf(Client::class, $result);
    }
}

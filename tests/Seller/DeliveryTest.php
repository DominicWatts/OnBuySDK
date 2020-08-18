<?php

namespace Seller;

use Laminas\Http\Client;
use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;
use Xigen\Library\OnBuy\Seller\Delivery;

class DeliveryTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testHeader()
    {
        $token = 'xyz';
        $client = new Delivery($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Delivery('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

    /**
     * Retrieve the available delivery options set up on your seller account
     */
    public function testGetDeliveryParametersCastToString()
    {
        $sellerDelivery = new Delivery('xyz');
        $client = $sellerDelivery->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($sellerDelivery->getDomain() . $sellerDelivery->getVersion() . Constants::DELIVERIES);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'site_id' => Constants::SITE_ID,
            'limit' => Constants::DEFAULT_LIMIT,
            'offset' => Constants::DEFAULT_OFFSET
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/deliveries?site_id=2000&limit=50&offset=0');

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Retrieve the available delivery options set up on your seller account
     * @throws \Exception
     */
    public function testGetDelivery()
    {
        $sellerDelivery = new Delivery('xyz');
        $result = $sellerDelivery->getDelivery();
        self::assertInstanceOf(Client::class, $result);
    }
}

<?php

namespace Seller;

use Xigen\Library\OnBuy\Seller\Delivery;
use PHPUnit\Framework\TestCase;
use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Xigen\Library\OnBuy\Constants;

class DeliveryTest extends TestCase
{
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
}

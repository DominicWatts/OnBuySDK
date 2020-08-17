<?php

namespace Seller;

use Xigen\Library\OnBuy\Seller\Seller;
use PHPUnit\Framework\TestCase;
use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Xigen\Library\OnBuy\Constants;

class SellerTest extends TestCase
{
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
        $brand = new Seller('xyz');
        $brand->getSellerById();
    }
}

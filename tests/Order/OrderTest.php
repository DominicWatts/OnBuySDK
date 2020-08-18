<?php

namespace Order;

use Laminas\Http\Client;
use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Json\Json;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;
use Xigen\Library\OnBuy\Order\Order;

class OrderTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testHeader()
    {
        $token = 'xyz';
        $client = new Order($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Order('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

    /**
     * Browse and search orders
     */
    public function testGetOrderParametersCastToString()
    {
        $order = new Order('xyz');
        $client = $order->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($order->getDomain() . $order->getVersion() . Constants::ORDERS);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'site_id' => Constants::SITE_ID,
            'filter' => [
                'status' => 'dispatched',
                'modified_since' => 'bar',
                'previously_exported' => 0
            ],
            'sort' => [
                'created' => 'asc',
                'modified' => 'desc'
            ]
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, str_replace(
                ['[', ']'],
                ['%5B','%5D'],
                'https://api.onbuy.com/v2/orders?site_id=2000&filter[status]=dispatched&filter[modified_since]=bar&filter[previously_exported]=0&sort[created]=asc&sort[modified]=desc'
            ));

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * View order by ID
     */
    public function testGetOrderByIdParametersCastToString()
    {
        $order = new Order('xyz');
        $client = $order->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($order->getDomain() . $order->getVersion() . Constants::ORDERS . '/123');
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'site_id' => Constants::SITE_ID
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/orders/123?site_id=2000');

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Full or partial dispatched - if only order number is given full dispatch
     */
    public function testDispatchOrderParametersCastToString()
    {
        $order = new Order('xyz');
        $client = $order->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($order->getDomain() . $order->getVersion() . Constants::ORDERS . '/' . Constants::DISPATCH);
        $client->setMethod(Request::METHOD_PUT);
        $client->setRawBody(Json::encode([
            'orders' => $this->getMockUpdate()
        ]));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_PUT,
                'https://api.onbuy.com/v2/orders/dispatch',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Content-Length' => 216,
                ],
                '{"orders":[{"order_id":"T9R7V","products":{"sku":"EXP-143-33S","opc":"PN8JV6","quantity":125},"tracking":{"tracking_id":"bar","supplier_name":"bar","number":"bar","url":"https:\/\/example.com\/path-to-resource\/"}}]}'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Refund a cancelled order
     */
    public function testCancelOrderOrderParametersCastToString()
    {
        $order = new Order('xyz');
        $client = $order->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($order->getDomain() . $order->getVersion() . Constants::ORDERS . '/' . Constants::CANCEL);
        $client->setMethod(Request::METHOD_PUT);
        $client->setRawBody(Json::encode([
            'site_id' => Constants::SITE_ID,
            'orders' => $this->getMockCancel()
        ]));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_PUT,
                'https://api.onbuy.com/v2/orders/cancel',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Content-Length' => 120,
                ],
                '{"site_id":2000,"orders":[{"order_id":"T9R7V","order_cancellation_reason_id":"1","cancel_order_additional_info":"foo"}]}'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Refund a cancelled order
     */
    public function testRefundOrderOrderParametersCastToString()
    {
        $order = new Order('xyz');
        $client = $order->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($order->getDomain() . $order->getVersion() . Constants::ORDERS . '/' . Constants::REFUND);
        $client->setMethod(Request::METHOD_PUT);
        $client->setRawBody(Json::encode([
            'site_id' => Constants::SITE_ID,
            'orders' => $this->getMockRefund()
        ]));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_PUT,
                'https://api.onbuy.com/v2/orders/refund',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Content-Length' => 192,
                ],
                '{"site_id":2000,"orders":[{"order_id":"T9R7V","order_refund_reason_id":"2","delivery":126.34,"seller_note":"foo","customer_note":"foo","items":{"onbuy_internal_reference":7,"amount":126.34}}]}'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Get tracking providers
     */
    public function testGetTrackingProvidersParametersCastToString()
    {
        $order = new Order('xyz');
        $client = $order->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($order->getDomain() . $order->getVersion() . Constants::ORDERS . '/' . Constants::TRACKING_PROVIDERS);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'site_id' => Constants::SITE_ID
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/orders/tracking-providers?site_id=2000');

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * View order by ID
     * @throws \Exception
     */
    public function testGetOrderById()
    {
        $order = new Order('xyz');
        $result = $order->getOrderById('T9R7V');
        self::assertInstanceOf(Client::class, $result);
    }

    /**
     * Full or partial dispatched - if only order number is given full dispatch
     * @throws \Exception
     */
    public function testDispatchOrder()
    {
        $order = new Order('xyz');
        $result = $order->dispatchOrder($this->getMockUpdate());
        self::assertInstanceOf(Client::class, $result);
    }

    /**
     * Refund a cancelled order
     * @throws \Exception
     */
    public function testCancelOrder()
    {
        $order = new Order('xyz');
        $result = $order->cancelOrder($this->getMockCancel());
        self::assertInstanceOf(Client::class, $result);
    }

    /**
     * Refund a cancelled order
     * @throws \Exception
     */
    public function testRefundOrder()
    {
        $order = new Order('xyz');
        $result = $order->refundOrder($this->getMockRefund());
        self::assertInstanceOf(Client::class, $result);
    }

    /**
     * Get tracking providers
     * @throws \Exception
     */
    public function getTrackingProviders()
    {
        $order = new Order('xyz');
        $result = $order->getTrackingProviders();
        self::assertInstanceOf(Client::class, $result);
    }

    /**
     * Mock tracking update
     * @return array
     */
    public function getMockUpdate()
    {
        return [
            [
                "order_id" => "T9R7V",
                "products" => [

                    "sku" => "EXP-143-33S",
                    "opc" => "PN8JV6",
                    "quantity" => 125

                ],
                "tracking" => [
                    "tracking_id" => "bar",
                    "supplier_name" => "bar",
                    "number" => "bar",
                    "url" => "https://example.com/path-to-resource/"
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getMockCancel()
    {
        return [
            [
                "order_id" => "T9R7V",
                "order_cancellation_reason_id" =>  "1",
                "cancel_order_additional_info" => "foo"
            ]
        ];
    }

    /**
     * @return array
     */
    public function getMockRefund()
    {
        return [
            [
                "order_id" => "T9R7V",
                "order_refund_reason_id" => "2",
                "delivery" =>  126.34,
                "seller_note" => "foo",
                "customer_note" =>"foo",
                "items" => [
                    "onbuy_internal_reference" => 7,
                    "amount" => 126.34
                ]
            ]
        ];
    }
}

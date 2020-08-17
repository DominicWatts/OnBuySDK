<?php

namespace Queue;

use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Xigen\Library\OnBuy\Brand\Brand;
use Xigen\Library\OnBuy\Constants;
use Xigen\Library\OnBuy\Queue\Queue;
use PHPUnit\Framework\TestCase;

class QueueTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testAuthorizationHeader()
    {
        $token = 'xyz';
        $client = new Queue($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Queue('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

    /**
     * Check the progress of any actions that use OnBuy queuing system
     */
    public function testGetQueueParametersCastToString()
    {
        $queue = new Queue('xyz');
        $client = $queue->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($queue->getDomain() . $queue->getVersion() . Constants::QUEUES);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'site_id' => Constants::SITE_ID,
            'filter' => [
                'queue_ids' => 123,
                'status' => 'success'
            ],
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, str_replace(
                ['[', ']'],
                ['%5B','%5D'],
                'https://api.onbuy.com/v2/queues?site_id=2000&filter[queue_ids]=123&filter[status]=success'
            ));

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Check the progress of any actions that use OnBuy queuing system
     */
    public function testGetQueueByIdParametersCastToString()
    {
        $queue = new Queue('xyz');
        $client = $queue->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($queue->getDomain() . $queue->getVersion() . Constants::QUEUES . '/123');
        $client->setMethod(Request::METHOD_GET);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/queues/123');

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
        $queue = new Queue('xyz');
        $queue->getQueue([]);
    }

    /**
     * Invalid search
     * @throws \Exception
     */
    public function testInvalidSearchById()
    {
        $this->expectException(\Exception::class);
        $queue = new Queue('xyz');
        $queue->getQueueById();
    }
}

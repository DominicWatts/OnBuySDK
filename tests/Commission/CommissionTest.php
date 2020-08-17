<?php

namespace Commission;

use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Commission\Commission;
use Xigen\Library\OnBuy\Constants;

class CommissionTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testAuthorizationHeader()
    {
        $token = 'xyz';
        $client = new Commission($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Commission('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

    /**
     * Building of commission tier search get request
     */
    public function testGetTierParametersCastToString()
    {
        $commission = new Commission('xyz');
        $client = $commission->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($commission->getDomain() . $commission->getVersion() . Constants::COMMISSION_TIERS);
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
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/commission-tiers?site_id=2000&limit=50&offset=0');

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Building of commission tier search get request
     */
    public function testGetTierByIdParametersCastToString()
    {
        $commission = new Commission('xyz');
        $client = $commission->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($commission->getDomain() . $commission->getVersion() . Constants::CATEGORIES . '/123/' . Constants::VARIANTS);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'site_id' => Constants::SITE_ID,
            'commission_tier_id' => '456',
            'limit' => Constants::DEFAULT_LIMIT,
            'offset' => Constants::DEFAULT_OFFSET
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/categories/123/variants?site_id=2000&commission_tier_id=456&limit=50&offset=0');

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
        $commission = new Commission('xyz');
        $commission->getTierById([]);
        $commission->getResponse();
    }

    /**
     * Invalid token
     * @throws \Exception
     */
    public function testInvalidToken()
    {
        $this->expectException(\Exception::class);
        $commission = new Commission('xyz');
        $commission->getTierById(123, 456);
        $commission->getResponse();
    }
}

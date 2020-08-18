<?php

namespace Category;

use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Category\Variant;
use Xigen\Library\OnBuy\Constants;

class VariantTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testAuthorizationHeader()
    {
        $token = 'xyz';
        $client = new Variant($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Variant('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

    /**
     * Used to access data for Subcategory information
     */
    public function testGetVariantIdParametersCastToString()
    {
        $categoryVariant = new Variant('xyz');
        $client = $categoryVariant->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($categoryVariant->getDomain() . $categoryVariant->getVersion() . Constants::CATEGORIES . '/123/' . Constants::VARIANTS);
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
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/categories/123/variants?site_id=2000&limit=50&offset=0');

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
        $categoryVariant = new Variant('xyz');
        $categoryVariant->getVariantId();
    }
}

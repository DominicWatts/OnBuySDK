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
        $categoryVariant->getResponse();
    }

    /**
     * Invalid token
     * @throws \Exception
     */
    public function testInvalidToken()
    {
        $this->expectException(\Exception::class);
        $categoryVariant = new Variant('xyz');
        $categoryVariant->getVariantId(123);
        $categoryVariant->getResponse();
    }
}

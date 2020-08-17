<?php

namespace Category;

use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Category\Technical;
use Xigen\Library\OnBuy\Constants;

class TechnicalTest extends TestCase
{
    /**
     * Obtain a list of all category groups
     */
    public function testGetTechnicalDetailByIdParametersCastToString()
    {
        $categoryTechnical = new Technical('xyz');
        $client = $categoryTechnical->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($categoryTechnical->getDomain() . $categoryTechnical->getVersion() . Constants::CATEGORIES . '/123/' . Constants::TECHNICAL_DETAILS);
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
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/categories/123/technical-details?site_id=2000&limit=50&offset=0');

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Obtain information for a single OnBuy category
     */
    public function testGetGroupByIdParametersCastToString()
    {
        $categoryTechnical = new Technical('xyz');
        $client = $categoryTechnical->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($categoryTechnical->getDomain() . $categoryTechnical->getVersion() . Constants::CATEGORIES . '/123/' . Constants::TECHNICAL_DETAILS . '/456');
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'site_id' => Constants::SITE_ID,
            'product_detail_group_id' => 125
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/categories/123/technical-details/456?site_id=2000&product_detail_group_id=125');

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
        $categoryTechnical = new Technical('xyz');
        $categoryTechnical->getTechnicalDetailById();
    }
}

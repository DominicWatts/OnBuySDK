<?php

namespace Category;

use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Brand\Brand;
use Xigen\Library\OnBuy\Category\Category;
use Xigen\Library\OnBuy\Constants;

class CategoryTest extends TestCase
{
    /**
     * Building of brand search get request
     */
    public function testGetCategoryParametersCastToString()
    {
        $category = new Category('xyz');
        $client = $category->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($category->getDomain() . $category->getVersion() . Constants::CATEGORIES);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'site_id' => Constants::SITE_ID,
            'limit' => Constants::DEFAULT_LIMIT,
            'offset' => Constants::DEFAULT_OFFSET,
            'filter' => [
                'onbuy_category_id' => 34,
                'category_type_id' => 3,
                'name' => 'bar',
                'can_list_in' => 1
            ],
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, str_replace(
                ['[', ']'],
                ['%5B','%5D'],
                'https://api.onbuy.com/v2/categories?site_id=2000&limit=50&offset=0&filter[onbuy_category_id]=34&filter[category_type_id]=3&filter[name]=bar&filter[can_list_in]=1'
            ));

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Obtain information for a single OnBuy category get request
     */
    public function testGetCategoryByIdParametersCastToString()
    {
        $category = new Category('xyz');
        $client = $category->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);

        $client->setUri($category->getDomain() . $category->getVersion() . Constants::CATEGORIES . '/123');
        $client->setMethod(Request::METHOD_GET);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, 'https://api.onbuy.com/v2/categories/123');

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
        $category = new Category('xyz');
        $category->getCategory([]);
        $category->getResponse();
    }

    /**
     * Invalid token
     * @throws \Exception
     */
    public function testInvalidToken()
    {
        $this->expectException(\Exception::class);
        $category = new Category('xyz');
        $category->getCategoryById(123);
        $category->getResponse();
    }
}

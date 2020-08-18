<?php

namespace Category;

use Laminas\Http\Client;
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
     * Authorization header
     */
    public function testAuthorizationHeader()
    {
        $token = 'xyz';
        $client = new Category($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Category('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

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
    }

    /**
     * Invalid search
     * @throws \Exception
     */
    public function testInvalidSearchById()
    {
        $this->expectException(\Exception::class);
        $category = new Category('xyz');
        $category->getCategoryById();
    }

    /**
     * Obtain category information for any categories created on OnBuy
     * @throws \Exception
     */
    public function testGetCategory()
    {
        $category = new Category('xyz');
        $result = $category->getCategory([
            'name' => 'test',
            'category_type_id' => 1 // department
        ]);
        self::assertInstanceOf(Client::class, $result);
    }

    /**
     * Obtain information for a single OnBuy category
     * @throws \Exception
     */
    public function testGetCategoryById()
    {
        $category = new Category('xyz');
        $result = $category->getCategoryById(13490);
        self::assertInstanceOf(Client::class, $result);
    }
}

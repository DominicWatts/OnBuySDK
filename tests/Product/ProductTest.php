<?php

namespace Product;

use Laminas\Http\Client\Adapter\AdapterInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Json\Json;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;
use Xigen\Library\OnBuy\Product\Product;

class ProductTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testHeader()
    {
        $token = 'xyz';
        $client = new Product($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
        self::assertSame(Constants::CONTENT_TYPE, $client->getClient()->getHeader('Content-Type'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Product('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

    public function testCreateProduct()
    {
        $product = new Product('xyz');
        $client = $product->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($product->getDomain() . $product->getVersion() . Constants::PRODUCTS);
        $client->setMethod(Request::METHOD_POST);
        $client->setRawBody(Json::encode(
            $this->getMockProduct()
        ));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_POST,
                'https://api.onbuy.com/v2/products',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/json',
                    'Content-Length' => 787,
                ],
                '{"site_id":2000,"category_id":125,"published":1,"product_name":"bar","mpn":"EXAMPLE-cdce953d-abbe-4626-ac9d-0806a5611ad9","description":"bar","brand_name":"bar","brand_id":16,"videos":{"label":"foo","url":"https:\/\/example.com\/path-to-resource\/"},"documents":{"label":"foo","url":"https:\/\/example.com\/path-to-resource\/"},"default_image":"foo","rrp":14.83,"product_data":{"label":"bar","value":"foo","group":"foo"},"listings":{"new":{"sku":"EXP-143-33S","group_sku":"bar","price":14.83,"stock":8,"handling_time":125,"return_time":7,"free_returns":"foo","warranty":252,"delivery_template_id":7}},"features":{"option_id":16,"name":"foo","hex":"bar"},"technical_detail":{"detail_id":34,"value":"foo","unit":"bar"},"variant_1":{"name":"bar"},"variant_2":{"name":"foo"},"variants":[[]]}'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Create product from array of product data array
     */
    public function testCreateProductByBatch()
    {
        $product = new Product('xyz');
        $client = $product->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($product->getDomain() . $product->getVersion() . Constants::PRODUCTS);
        $client->setMethod(Request::METHOD_POST);
        $client->setRawBody(Json::encode(
            $this->getMockProductByBatch()
        ));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_POST,
                'https://api.onbuy.com/v2/products',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/json',
                    'Content-Length' => 795,
                ],
                '{"uid":8,"site_id":2000,"category_id":125,"published":1,"product_name":"bar","mpn":"EXAMPLE-cdce953d-abbe-4626-ac9d-0806a5611ad9","description":"bar","brand_name":"bar","brand_id":16,"videos":{"label":"foo","url":"https:\/\/example.com\/path-to-resource\/"},"documents":{"label":"foo","url":"https:\/\/example.com\/path-to-resource\/"},"default_image":"foo","rrp":14.83,"product_data":{"label":"bar","value":"foo","group":"foo"},"listings":{"new":{"sku":"EXP-143-33S","group_sku":"bar","price":14.83,"stock":8,"handling_time":125,"return_time":7,"free_returns":"foo","warranty":252,"delivery_template_id":7}},"features":{"option_id":16,"name":"foo","hex":"bar"},"technical_detail":{"detail_id":34,"value":"foo","unit":"bar"},"variant_1":{"name":"bar"},"variant_2":{"name":"foo"},"variants":[[]]}'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Create product from product data array
     */
    public function testUpdateProduct()
    {
        $product = new Product('xyz');
        $client = $product->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($product->getDomain() . $product->getVersion() . Constants::PRODUCTS . '/xyz');
        $client->setMethod(Request::METHOD_PUT);
        $client->setRawBody(Json::encode([
            $this->getMockProduct()
        ]));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_PUT,
                'https://api.onbuy.com/v2/products/xyz',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/json',
                    'Content-Length' => 789,
                ],
                '[{"site_id":2000,"category_id":125,"published":1,"product_name":"bar","mpn":"EXAMPLE-cdce953d-abbe-4626-ac9d-0806a5611ad9","description":"bar","brand_name":"bar","brand_id":16,"videos":{"label":"foo","url":"https:\/\/example.com\/path-to-resource\/"},"documents":{"label":"foo","url":"https:\/\/example.com\/path-to-resource\/"},"default_image":"foo","rrp":14.83,"product_data":{"label":"bar","value":"foo","group":"foo"},"listings":{"new":{"sku":"EXP-143-33S","group_sku":"bar","price":14.83,"stock":8,"handling_time":125,"return_time":7,"free_returns":"foo","warranty":252,"delivery_template_id":7}},"features":{"option_id":16,"name":"foo","hex":"bar"},"technical_detail":{"detail_id":34,"value":"foo","unit":"bar"},"variant_1":{"name":"bar"},"variant_2":{"name":"foo"},"variants":[[]]}]'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Create product from array of product data array
     */
    public function testUpdateProductByBatch()
    {
        $product = new Product('xyz');
        $client = $product->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($product->getDomain() . $product->getVersion() . Constants::PRODUCTS);
        $client->setMethod(Request::METHOD_PUT);
        $client->setRawBody(Json::encode([
            'products' => [$this->getMockProduct()]
        ]));

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(
                Request::METHOD_PUT,
                'https://api.onbuy.com/v2/products',
                '1.1',
                [
                    'Authorization' => 'xyz',
                    'Host' => 'api.onbuy.com',
                    'Connection' => 'close',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Laminas_Http_Client',
                    'Content-Type' => 'application/json',
                    'Content-Length' => 802,
                ],
                '{"products":[{"site_id":2000,"category_id":125,"published":1,"product_name":"bar","mpn":"EXAMPLE-cdce953d-abbe-4626-ac9d-0806a5611ad9","description":"bar","brand_name":"bar","brand_id":16,"videos":{"label":"foo","url":"https:\/\/example.com\/path-to-resource\/"},"documents":{"label":"foo","url":"https:\/\/example.com\/path-to-resource\/"},"default_image":"foo","rrp":14.83,"product_data":{"label":"bar","value":"foo","group":"foo"},"listings":{"new":{"sku":"EXP-143-33S","group_sku":"bar","price":14.83,"stock":8,"handling_time":125,"return_time":7,"free_returns":"foo","warranty":252,"delivery_template_id":7}},"features":{"option_id":16,"name":"foo","hex":"bar"},"technical_detail":{"detail_id":34,"value":"foo","unit":"bar"},"variant_1":{"name":"bar"},"variant_2":{"name":"foo"},"variants":[[]]}]}'
            );

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Search for a specific product by name or code
     */
    public function testGetProduct()
    {
        $product = new Product('xyz');
        $client = $product->getClient();
        $adapter = $this->createMock(AdapterInterface::class);

        $client->setAdapter($adapter);
        $client->setUri($product->getDomain() . $product->getVersion() . Constants::PRODUCTS);
        $client->setMethod(Request::METHOD_GET);
        $client->setParameterGet([
            'site_id' => Constants::SITE_ID,
            'limit' => Constants::DEFAULT_LIMIT,
            'offset' => Constants::DEFAULT_OFFSET,
            'filter' => [
                'query' => 'test',
                'field' => 'name'
            ]
        ]);

        $response = new Response();

        $adapter
            ->expects($this->once())
            ->method('write')
            ->with(Request::METHOD_GET, str_replace(
                ['[', ']'],
                ['%5B','%5D'],
                'https://api.onbuy.com/v2/products?site_id=2000&limit=50&offset=0&filter[query]=test&filter[field]=name'
            ));

        $adapter
            ->expects($this->any())
            ->method('read')
            ->will($this->returnValue($response->toString()));

        $client->send();
    }

    /**
     * Create product from product data array
     */
    public function testInvalidCreateProduct()
    {
        $this->expectException(\Exception::class);
        $product = new Product('xyz');
        $product->createProduct();
    }

    /**
     * Create product from product data array
     */
    public function testCreateProductMissingRequired()
    {
        $this->expectException(\Exception::class);
        $product = new Product('xyz');
        $product->createProduct([
            'site_id' => Constants::SITE_ID,
            'noproductname' => 'test'
        ]);
    }

    /**
     * Create product from product data array
     */
    public function testInvalidCreateProductByBatch()
    {
        $this->expectException(\Exception::class);
        $product = new Product('xyz');
        $product->createProductByBatch();
    }

    /**
     * Create product from product data array
     */
    public function testCreateProductByBatchMissingReference()
    {
        $this->expectException(\Exception::class);
        $product = new Product('xyz');
        $product->createProductByBatch(['nouid' => 'abc']);
    }

    /**
     * Create product from product data array
     */
    public function testInvalidUpdateProduct()
    {
        $this->expectException(\Exception::class);
        $product = new Product('xyz');
        $product->updateProduct();
    }

    /**
     * Create product from product data array
     */
    public function testInvalidUpdateProductMissingReference()
    {
        $this->expectException(\Exception::class);
        $product = new Product('xyz');
        $product->updateProduct(['noopc' => 'abc']);
    }

    /**
     * Create product from array of product data array
     */
    public function testInvalidUpdateProductByBatch()
    {
        $this->expectException(\Exception::class);
        $product = new Product('xyz');
        $product->updateProductByBatch();
    }

    /**
     * @return array
     */
    public function getMockProduct()
    {
        return [
            "site_id" => 2000,
            "category_id" => 125,
            "published" => 1,
            "product_name" => "bar",
            "mpn" => "EXAMPLE-cdce953d-abbe-4626-ac9d-0806a5611ad9",
            "description" => "bar",
            "brand_name" => "bar",
            "brand_id" => 16,
            "videos" => [
                "label" => "foo",
                "url" => "https://example.com/path-to-resource/"
            ],
            "documents" => [
                "label" => "foo",
                "url" => "https://example.com/path-to-resource/"
            ],
            "default_image" => "foo",
            "rrp" => 14.83,
            "product_data" => [
                "label" => "bar",
                "value" => "foo",
                "group" => "foo"
            ],
            "listings" => [
                "new" => [
                    "sku" => "EXP-143-33S",
                    "group_sku" => "bar",
                    "price" => 14.83,
                    "stock" => 8,
                    "handling_time" => 125,
                    "return_time" => 7,
                    "free_returns" => "foo",
                    "warranty" => 252,
                    "delivery_template_id" => 7
                ]
            ],
            "features" => [
                "option_id" => 16,
                "name" => "foo",
                "hex" => "bar"
            ],
            "technical_detail" => [
                "detail_id" => 34,
                "value" => "foo",
                "unit" => "bar"
            ],
            "variant_1" => [
                "name" => "bar"
            ],
            "variant_2" => [
                "name" => "foo"
            ],
            "variants" => [
                []
            ]
        ];
    }

    /**
     * @return array
     */
    public function getMockProductByBatch()
    {
        $array = [
            'uid' => 8
        ];

        return array_merge($array, $this->getMockProduct());
    }
}

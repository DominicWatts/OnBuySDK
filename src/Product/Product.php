<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Product;

use Laminas\Http\Request;
use Laminas\Json\Json;

class Product extends Base
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var \Laminas\Http\Headers
     */
    protected $headers;

    /**
     * @var \Laminas\Http\Client;
     */
    protected $client;

    /**
     * @var \Laminas\Http\Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $default;

    /**
     * Listing constructor.
     * @param $token
     */
    public function __construct($token)
    {
        parent::__construct($token);
        $this->default = [
            'site_id' => self::SITE_ID,
        ];
    }

    /**
     * Create product from product data array
     * @param array $insertArray
     * @return Client
     * @throws \Exception
     */
    public function createProduct($insertArray = [])
    {
        if (empty($insertArray)) {
            throw new \Exception("Product data required");
        }

        $required = [
            'site_id',
            'category_id',
            'product_name',
            'product_codes',
            'brand_name',
            'brand_id'
        ];

        foreach ($required as $require) {
            if (!isset($insertArray[$require])) {
                throw new \Exception($require . ' is required');
            }
        }

        $this->client->setUri($this->domain . $this->version . self::PRODUCTS);
        $this->client->setMethod(Request::METHOD_POST);
        $insertArray = array_merge($this->default, $insertArray);
        $this->client->setRawBody(Json::encode($insertArray));
        return $this->client;
    }

    /**
     * Create product from array of product data array
     * @param array $insertArray
     * @return Client
     * @throws \Exception
     */
    public function createProductByBatch($insertArray = [])
    {
        if (empty($insertArray)) {
            throw new \Exception("Product data required");
        }

        if (!isset($insertArray['uid'])) {
            throw new \Exception('Batch ID not set');
        }

        $this->client->setUri($this->domain . $this->version . self::PRODUCTS);
        $this->client->setMethod(Request::METHOD_POST);
        $this->client->setRawBody(Json::encode(
            $insertArray
        ));
        return $this->client;
    }

    /**
     * Update products
     * @param array $updateArray
     * @return Client
     * @throws \Exception
     */
    public function updateProduct($updateArray = [])
    {
        if (empty($updateArray)) {
            throw new \Exception("Product data required");
        }

        if (!isset($updateArray['opc'])) {
            throw new \Exception('OnBuy Product Code required');
        }

        $this->client->setUri($this->domain . $this->version . self::PRODUCTS . '/' . $updateArray['opc']);
        $this->client->setMethod(Request::METHOD_PUT);
        $updateArray = array_merge($this->default, $updateArray);
        $this->client->setRawBody(Json::encode($updateArray));
        return $this->client;
    }

    /**
     * Update multiple products in a single request
     * @param array $updateArray
     * @return Client
     * @throws \Exception
     */
    public function updateProductByBatch($updateArray = [])
    {
        if (empty($updateArray)) {
            throw new \Exception("Product data required");
        }

        $this->client->setUri($this->domain . $this->version . self::PRODUCTS);
        $this->client->setMethod(Request::METHOD_PUT);
        $this->client->setRawBody(Json::encode([
            'products' => $updateArray
        ]));
        return $this->client;
    }

    /**
     * Search for a specific product by name or code
     * @param array $searchArray query|field[name|product_code|opc|mpn]|category_id
     * @param null $limit
     * @param null $offset
     * @return Client
     * @throws \Exception
     */
    public function getProduct($searchArray = [], $limit = null, $offset = null)
    {
        $this->client->setUri($this->domain . $this->version . self::PRODUCTS);
        $this->client->setMethod(Request::METHOD_GET);

        $params = [
            'site_id' => self::SITE_ID,
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET,
        ];

        // optional
        if (!empty($searchArray)) {
            $params['filter'] = $searchArray;
        }

        $this->client->setParameterGet($params);

        return $this->client;
    }
}

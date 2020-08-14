<?php

namespace Xigen\Library\OnBuy\Product;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Xigen\Library\OnBuy\Constants;

class Product extends Constants
{
    /**
     * @var string
     */
    private $token;

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
        $this->headers = new Headers();
        $this->client = new Client();
        $this->client->setOptions([
            'maxredirects' => 10,
            'timeout'      => 30,
        ]);
        $this->token = $token;
        $this->headers->addHeaderLine('Authorization', $this->token);
        $this->headers->addHeaderLine('Content-Type', self::CONTENT_TYPE);
        $this->client->setHeaders($this->headers);
        $this->default = [
            'site_id' => self::SITE_ID,
        ];
    }

    /**
     * Create product from product data array
     * @param array $insertArray
     * @return mixed
     * @throws \Exception
     */
    public function createProduct($insertArray = [])
    {
        $this->client->setUri($this->domain . $this->version . self::PRODUCTS);
        $this->client->setMethod(Request::METHOD_POST);
        $insertArray = array_merge($this->default, $insertArray);
        $this->client->setRawBody(Json::encode($insertArray));
        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }

    /**
     * Create product from product data array
     * @param array $updateArray
     * @return mixed
     * @throws \Exception
     */
    public function updateProduct($updateArray = [])
    {
        if (!isset($updateArray['opc'])) {
            throw new \Exception('OnBuy Product Code required');
        }
        $this->client->setUri($this->domain . $this->version . self::PRODUCTS . '/' . $updateArray['opc']);
        $this->client->setMethod(Request::METHOD_PUT);
        $updateArray = array_merge($this->default, $updateArray);
        $this->client->setRawBody(Json::encode($updateArray));
        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }

    /**
     * Create product from array of product data array
     * @param array $updateArray
     * @return mixed
     * @throws \Exception
     */
    public function updateProductByBatch($updateArray = [])
    {
        $this->client->setUri($this->domain . $this->version . self::PRODUCTS);
        $this->client->setMethod(Request::METHOD_PUT);
        $this->client->setRawBody(Json::encode([
            'products' => $updateArray
        ]));
        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }

    /**
     * Search for a specific product by name or code
     * @param array $searchArray query|field[name|product_code|opc|mpn]|category_id
     * @param null $limit
     * @param null $offset
     */
    public function getProduct($searchArray = [], $limit = null, $offset = null)
    {
        $this->client->setUri($this->domain . $this->version . self::PRODUCTS);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'limit' => $limit ?: self::LISTING_DEFAULT_LIMIT,
            'offset' => $offset ?: self::LISTING_DEFAULT_OFFSET,
            'filter' => $searchArray
        ]);
        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }
}

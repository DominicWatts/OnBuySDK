<?php

namespace Xigen\Library\OnBuy\Product;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Xigen\Library\OnBuy\Constants;

class Listing extends Constants
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
    }

    /**
     * Get details of current product listings
     * @param null $limit
     * @param null $offset
     * @return mixed
     * @throws \Exception
     */
    public function getListing($limit = null, $offset = null)
    {
        $this->client->setUri($this->domain . $this->version . self::LISTINGS);
        $this->client->setMethod(Request::METHOD_GET);

        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'limit' => $limit ?: self::LISTING_DEFAULT_LIMIT,
            'offset' => $offset ?: self::LISTING_DEFAULT_OFFSET
        ]);

        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }

    /**
     * Update listing by product data array
     * @param array $updateArray sku|price|stock|boost_marketing_commission
     * @return mixed
     * @throws \Exception
     */
    public function updateListingBySku($updateArray = [])
    {
        $this->client->setUri($this->domain . $this->version . self::LISTINGS_BY_SKU);
        $this->client->setMethod(Request::METHOD_PUT);
        $this->client->setRawBody(Json::encode([
            'site_id' => self::SITE_ID,
            'listings' => $updateArray
        ]));

        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }

    /**
     * Delete listing by SKU
     * @param array $deleteArray
     * @return mixed
     */
    public function deleteListingBySku($deleteArray = [])
    {
        $this->client->setUri($this->domain . $this->version . self::LISTINGS_BY_SKU);
        $this->client->setMethod(Request::METHOD_DELETE);

        $this->client->setRawBody(Json::encode([
            'site_id' => self::SITE_ID,
            'skus' => $deleteArray
        ]));

        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }

    /**
     * Create a single product listing from product data array
     * @param $oPc OnBuy Product Code
     * @param array $insertArray sku|group_sku|boost_marketing_commission
     * @return mixed
     * @throws \Exception
     */
    public function createListing($oPc, $insertArray = [])
    {
        $this->client->setUri($this->domain . $this->version . self::PRODUCTS . '/' . $oPc . '/' . self::LISTINGS);
        $this->client->setMethod(Request::METHOD_POST);
        $this->client->setRawBody(Json::encode([
            'opc' => $oPc,
            'site_id' => self::SITE_ID,
            'listings' => $insertArray
        ]));
        
        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }

    /**
     * Create a product listing from product data array
     * @param array $insertArray opc|condition|price|stock|delivery_weight|handling_time|free_returns|warranty|condition_notes
     * @return mixed
     * @throws \Exception
     */
    public function createListingByBatch($insertArray = [])
    {
        $this->client->setUri($this->domain . $this->version . self::LISTINGS);
        $this->client->setMethod(Request::METHOD_POST);
        $this->client->setParameterPost([
            'site_id' => self::SITE_ID,
            'listings' => Json::encode($insertArray),
        ]);
        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }

    /**
     * Seller's listings, referenced by SKUs, are 'winning'
     * @param array $skusArray
     * @return mixed
     * @throws \Exception
     */
    public function getWinningListing($skusArray = [])
    {
        $this->client->setUri($this->domain . $this->version . self::LISTINGS_WINNING);
        $this->client->setMethod(Request::METHOD_GET);

        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'skus' => $skusArray
        ]);

        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }
}

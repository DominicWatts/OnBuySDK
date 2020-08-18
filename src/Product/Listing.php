<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Product;

use Laminas\Http\Request;
use Laminas\Json\Json;

class Listing extends Base
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
        parent::__construct($token);
    }

    /**
     * Get details of current product listings
     * @param array $sortArray last_created[asc|desc]
     * @param array $filterArray condition|sku|opc|in_stock[0|1]
     * @param null $limit
     * @param null $offset
     * @return mixed
     * @throws \Exception
     */
    public function getListing($sortArray = [], $filterArray = [], $limit = null, $offset = null)
    {
        $this->client->setUri($this->domain . $this->version . self::LISTINGS);
        $this->client->setMethod(Request::METHOD_GET);

        $param = [
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ];

        // optional
        if (!empty($sortArray)) {
            $param['sort'] = $sortArray;
        }
        if (!empty($filterArray)) {
            $param['filter'] = $filterArray;
        }

        $this->client->setParameterGet($param);

        $this->getResponse();
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

        $this->getResponse();
    }

    /**
     * Delete listing by SKU
     * @param array $deleteArray
     * @return mixed
     */
    public function deleteListingBySku($deleteArray = [])
    {
        if (empty($deleteArray)) {
            throw new \Exception("SKUs are required");
        }

        $this->client->setUri($this->domain . $this->version . self::LISTINGS_BY_SKU);
        $this->client->setMethod(Request::METHOD_DELETE);

        $this->client->setRawBody(Json::encode([
            'site_id' => self::SITE_ID,
            'skus' => $deleteArray
        ]));

        $this->getResponse();
    }

    /**
     * Create a single product listing from product data array
     * @param $oPC OnBuy Product Code
     * @param array $insertArray sku|group_sku|boost_marketing_commission
     * @return mixed
     * @throws \Exception
     */
    public function createListing($oPC = null, $insertArray = [])
    {
        if (empty($oPC)) {
            throw new \Exception("OnBuy Product Code is required");
        }

        if (empty($insertArray)) {
            throw new \Exception("Product data required");
        }

        $this->client->setUri($this->domain . $this->version . self::PRODUCTS . '/' . $oPC . '/' . self::LISTINGS);
        $this->client->setMethod(Request::METHOD_POST);
        $this->client->setRawBody(Json::encode([
            'opc' => $oPC,
            'site_id' => self::SITE_ID,
            'listings' => $insertArray
        ]));

        $this->getResponse();
    }

    /**
     * Create a product listing from product data array
     * @param array $insertArray opc|condition|price|stock|delivery_weight|handling_time|free_returns|warranty|condition_notes
     * @return mixed
     * @throws \Exception
     */
    public function createListingByBatch($insertArray = [])
    {
        if (empty($insertArray)) {
            throw new \Exception("Product data required");
        }

        $this->client->setUri($this->domain . $this->version . self::LISTINGS);
        $this->client->setMethod(Request::METHOD_POST);
        $this->client->setParameterPost([
            'site_id' => self::SITE_ID,
            'listings' => Json::encode($insertArray),
        ]);
        $this->getResponse();
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

        $this->getResponse();
    }
}

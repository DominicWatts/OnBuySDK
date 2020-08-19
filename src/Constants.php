<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy;

use Laminas\Http\Client;
use Laminas\Http\Headers;
use Laminas\Http\Response;
use Laminas\Json\Json;

class Constants
{
    const TOKEN_CONTENT_TYPE = 'application/x-www-form-urlencoded';
    const CONTENT_TYPE = 'application/json';
    const MAXREDIRECTS = 10;
    const TIMEOUT = 30;

    /**
     * The OnBuy Regional site id e.g. 2000 for the UK
     */
    const SITE_ID = 2000;

    const BRAND = 'brand';
    const CANCEL = 'cancel';
    const CATEGORIES = 'categories';
    const COMMISSION_TIERS = 'commission-tiers';
    const CONDITIONS = 'conditions';
    const DELIVERIES = 'deliveries';
    const DISPATCH = 'dispatch';
    const ENTITIES = 'entities';
    const FEATURES = 'features';
    const LISTINGS = 'listings';
    const LISTINGS_BY_SKU = 'listings/by-sku';
    const LISTINGS_WINNING = 'listings/check-winning';
    const ORDERS = 'orders';
    const PRODUCTS = 'products';
    const QUEUES = 'queues';
    const REFUND = 'refund';
    const REQUEST_TOKEN = 'auth/request-token';
    const SELLERS = 'sellers';
    const SITES = 'sites';
    const TECHNICAL_DETAILS = 'technical-details';
    const TRACKING_PROVIDERS = 'tracking-providers';
    const VARIANTS = 'variants';

    const DEFAULT_SORT = 'asc';
    const DEFAULT_LIMIT = 50;
    const DEFAULT_OFFSET = 0;

    /**
     * @var string
     */
    public $domain = 'https://api.onbuy.com/';

    /**
     * @var string
     */
    public $version = 'v2/';

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $consumerKey;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $expires;

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
    protected $responseArray;

    /**
     * Constants constructor.
     * @param $token
     */
    public function __construct($token)
    {
        $this->headers = new Headers();
        $this->client = new Client();
        $this->client->setOptions([
            'maxredirects' => self::MAXREDIRECTS,
            'timeout' => self::TIMEOUT,
        ]);
        $this->token = $token;
        $this->headers->addHeaderLine('Authorization', $this->token);
        $this->client->setHeaders($this->headers);
    }

    /**
     * @param \Laminas\Http\Response $response
     * @throws \Exception
     */
    public function catchError(\Laminas\Http\Response $response)
    {
        if ($response->isServerError()) {
            throw new \Exception('Server error');
        }

        $decode = Json::decode($response->getBody(), Json::TYPE_ARRAY);

        if (!empty($decode) && isset($decode['error'])) {
            throw new \Exception(sprintf(
                'Error code : %s Error Message: %s',
                $decode['error']['errorCode'],
                $decode['error']['message']
            ));
        }
    }

    /**
     * Make call, parse response
     * @return array|mixed
     * @throws \Exception
     */
    public function getResponse()
    {
        $this->response = $this->client->send();
        $this->catchError($this->response);
        $this->responseArray = Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
        return $this->responseArray;
    }

    /**
     * Set token
     * @param string $token
     * @return void
     */
    public function setToken($token)
    {
        $this->token = $token;
        $this->expires = time() + 900; // 15 minutes
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     */
    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return \Laminas\Http\Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @return array
     */
    public function getResponseArray(): array
    {
        return $this->responseArray;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param array $responseArray
     */
    public function setResponseArray(array $responseArray): void
    {
        $this->responseArray = $responseArray;
    }
}

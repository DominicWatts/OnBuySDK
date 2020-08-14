<?php

namespace Xigen\Library\OnBuy;

use Laminas\Json\Json;

class Constants
{
    const TOKEN_CONTENT_TYPE = 'application/x-www-form-urlencoded';
    const CONTENT_TYPE = 'application/json';
    
    const SITE_ID = 2000;
    
    const REQUEST_TOKEN = 'auth/request-token';
    const LISTINGS = 'listings';
    const LISTINGS_BY_SKU = 'listings/by-sku';
    const LISTINGS_WINNING = 'listings/check-winning';
    const PRODUCTS = 'products';
    

    const LISTING_DEFAULT_LIMIT = 50;
    const LISTING_DEFAULT_OFFSET = 0;

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
     * Set token
     * @param string $token
     * @return void
     */
    public function setToken($token)
    {
        $this->token = $token;
    }
}

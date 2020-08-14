<?php

namespace Xigen\Library\OnBuy\Category;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Xigen\Library\OnBuy\Constants;

class Feature extends Constants
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
     * Category constructor.
     * @param $token
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }

    /**
     * Obtain information for a single OnBuy category
     * @param int $categoryId
     * @param $limit
     * @param $offset
     * @return mixed
     * @throws \Exception
     */
    public function getFeatureById($categoryId = null, $limit = null, $offset = null)
    {
        if (empty($categoryId)) {
            throw new \Exception('Category ID required');
        }
        
        $this->client->setUri($this->domain . $this->version . self::CATEGORIES . '/' . $categoryId . '/' . self::FEATURES);
        $this->client->setMethod(Request::METHOD_GET);

        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);

        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }
}

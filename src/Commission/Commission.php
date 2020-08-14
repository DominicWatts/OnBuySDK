<?php

namespace Xigen\Library\OnBuy\Commission;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Xigen\Library\OnBuy\Constants;

class Commission extends Constants
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
     * Brand constructor.
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
     * Obtain commission tier information for any regional variation of OnBuy
     * @param $limit int
     * @param $offset int
     * @return mixed
     * @throws \Exception
     */
    public function getTier($limit = null, $offset = null)
    {
        $this->client->setUri($this->domain . $this->version . self::COMMISSION_TIERS);
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

    /**
     * Obtain commission tier information for a single OnBuy regional site
     * @param $categoryId int
     * @param $tierId int
     * @return mixed
     * @throws \Exception
     */
    public function getTierById($categoryId = null, $tierId = null, $limit = null, $offset = null)
    {
        if (empty($categoryId) || empty($tierId)) {
            throw new \Exception('ID filters required required');
        }
        $this->client->setUri($this->domain . $this->version . self::CATEGORIES . '/' . $categoryId . '/' . self::VARIANTS);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'commission_tier_id' => $tierId,
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);
        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }
}

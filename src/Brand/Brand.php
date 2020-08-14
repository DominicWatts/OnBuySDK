<?php

namespace Xigen\Library\OnBuy\Brand;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Xigen\Library\OnBuy\Constants;

class Brand extends Constants 
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
            'maxredirects' => 10,
            'timeout'      => 30,
        ]);
        $this->token = $token;
        $this->headers->addHeaderLine('Authorization', $this->token);
        $this->client->setHeaders($this->headers);
    }

    /**
     * Obtain brand information for any brands created on OnBuy
     * @param $filter string
     * @param $sort string asc|desc
     * @param $limit int
     * @param $offset int
     * @return mixed
     * @throws \Exception
     */
    public function getBrand($filter, $sort, $limit, $offset)
    {
        if (empty($filter)) {
            throw new \Exception('Brand filter keyword required');
        }
        
        $this->client->setUri($this->domain . $this->version . self::BRAND);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'filter' => [
                'name' => $filter
            ],
            'sort' => [
                'name' => $sort ?: self::DEFAULT_SORT,
            ],
            'limit' => $limit ?: self::LISTING_DEFAULT_LIMIT,
            'offset' => $offset ?: self::LISTING_DEFAULT_OFFSET
        ]);

        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }

    /**
     * Obtain information for a single OnBuy brand.
     * @param $brandId
     * @return mixed
     * @throws \Exception
     */
    public function viewBrand($brandId)
    {
        if (empty($brandId)) {
            throw new \Exception('Brand ID required');
        }
        $this->client->setUri($this->domain . $this->version . self::BRAND . '/' . $brandId);
        $this->client->setMethod(Request::METHOD_GET);
        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }
}
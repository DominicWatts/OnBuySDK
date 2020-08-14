<?php

namespace Xigen\Library\OnBuy\Category;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Xigen\Library\OnBuy\Constants;

class Technical extends Base
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
     * Obtain a list of all category groups
     * @param int $categoryId
     * @param $limit int
     * @param $offset int
     * @return mixed
     * @throws \Exception
     */
    public function getTechnicalDetailById($categoryId = null, $limit = null, $offset = null)
    {
        if (empty($categoryId)) {
            throw new \Exception('Category filter parameters required');
        }
        
        $this->client->setUri($this->domain . $this->version . self::CATEGORIES . '/' . $categoryId . '/' . self::TECHNICAL_DETAILS);
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
     * Obtain information for a single OnBuy category
     * @param int $categoryId
     * @param int $groupId
     * @return mixed
     * @throws \Exception
     */
    public function getGroupById($categoryId = null, $groupId = null)
    {
        if (empty($categoryId) || empty($groupId)) {
            throw new \Exception('Category filter parameters required');
        }

        $this->client->setUri($this->domain . $this->version . self::CATEGORIES . '/' . $categoryId . '/' . self::TECHNICAL_DETAILS . '/' . $groupId);
        $this->client->setMethod(Request::METHOD_GET);

        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'product_detail_group_id' => $groupId
        ]);

        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }
}

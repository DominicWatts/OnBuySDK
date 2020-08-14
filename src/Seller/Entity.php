<?php

namespace Xigen\Library\OnBuy\Seller;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Xigen\Library\OnBuy\Constants;

class Entity extends Base
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
     * Obtain details of all of your trading entities
     * @param $limit int
     * @param $offset int
     * @return mixed
     * @throws \Exception
     */
    public function getEntity($limit = null, $offset = null)
    {
        $this->client->setUri($this->domain . $this->version . self::ENTITIES);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);
        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }

    /**
     * Obtain details of a specific one of your trading entities
     * @param $entityId int
     * @param $limit int
     * @param $offset int
     * @return mixed
     * @throws \Exception
     */
    public function getEntityById($entityId = null, $limit = null, $offset = null)
    {
        if (empty($entityId)) {
            throw new \Exception('Entity ID required');
        }
        $this->client->setUri($this->domain . $this->version . self::ENTITIES . '/' . $entityId);
        $this->client->setMethod(Request::METHOD_GET);

        $this->client->setParameterGet([
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);

        $this->response = $this->client->send();
        $this->catchError($this->response);
        return Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
    }
}

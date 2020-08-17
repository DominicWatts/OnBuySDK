<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Seller;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Json\Json;

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
        $this->getResponse();
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

        $this->getResponse();
    }
}

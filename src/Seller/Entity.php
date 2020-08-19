<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Seller;

use Laminas\Http\Request;

class Entity extends Base
{
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
     * @return Client
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
        return $this->client;
    }

    /**
     * Obtain details of a specific one of your trading entities
     * @param $entityId int
     * @param $limit int
     * @param $offset int
     * @return Client
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

        return $this->client;
    }
}

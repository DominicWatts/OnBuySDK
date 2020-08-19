<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Seller;

use Laminas\Http\Request;

class Delivery extends Base
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
     * Retrieve the available delivery options set up on your seller account
     * @param $limit
     * @param $offset
     * @return Client
     * @throws \Exception
     */
    public function getDelivery($limit = null, $offset = null)
    {
        $this->client->setUri($this->domain . $this->version . self::DELIVERIES);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);
        return $this->client;
    }
}

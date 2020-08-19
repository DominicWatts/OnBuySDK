<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Seller;

use Laminas\Http\Request;

class Seller extends Base
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
     * Obtain information about your seller account
     * @param $sellerId int
     * @return Client
     * @throws \Exception
     */
    public function getSellerById($sellerId = null)
    {
        if (empty($sellerId)) {
            throw new \Exception('Seller ID required');
        }
        $this->client->setUri($this->domain . $this->version . self::SELLERS . '/' . $sellerId);
        $this->client->setMethod(Request::METHOD_GET);
        return $this->client;
    }
}

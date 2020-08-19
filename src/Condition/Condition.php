<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Condition;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Xigen\Library\OnBuy\Constants;

class Condition extends Constants
{
    /**
     * Brand constructor.
     * @param $token
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }

    /**
     * Valid conditions for use in various endpoints, most notably for listings
     * @return Client
     * @throws \Exception
     */
    public function getCondition()
    {
        $this->client->setUri($this->domain . $this->version . self::CONDITIONS);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'site_id' => self::SITE_ID
        ]);

        return $this->client;
    }
}

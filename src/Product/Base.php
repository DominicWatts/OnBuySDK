<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Product;

use Laminas\Http\Headers;
use Xigen\Library\OnBuy\Constants;

class Base extends Constants
{
    /**
     * Base constructor - product requests have different header
     * @param $token
     */
    public function __construct($token)
    {
        parent::__construct($token);
        $this->headers = new Headers();
        $this->headers->addHeaderLine('Authorization', $this->token);
        $this->headers->addHeaderLine('Content-Type', self::CONTENT_TYPE);
        $this->client->setHeaders($this->headers);
    }
}

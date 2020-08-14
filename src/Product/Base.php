<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Product;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Xigen\Library\OnBuy\Constants;

class Base extends Constants
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
     * Listing constructor.
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
        $this->headers->addHeaderLine('Content-Type', self::CONTENT_TYPE);
        $this->client->setHeaders($this->headers);
    }
}

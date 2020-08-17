<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Site;

use Laminas\Http\Client;
use Laminas\Http\Headers;
use Laminas\Http\Request;
use Xigen\Library\OnBuy\Constants;

class Site extends Constants
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
     * Site constructor.
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
        $this->client->setHeaders($this->headers);
    }

    /**
     * Obtain site information for any regional variation of OnBuy
     * @param array $filterArray name
     * @param $limit int
     * @param $offset int
     * @return mixed
     * @throws \Exception
     */
    public function getSite($filterArray = [], $limit = null, $offset = null)
    {
        $this->client->setUri($this->domain . $this->version . self::SITES);
        $this->client->setMethod(Request::METHOD_GET);

        $params = [
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ];

        // optional
        if (!empty($filterArray)) {
            $params['filter'] = $filterArray;
        }

        $this->client->setParameterGet($params);

        $this->getResponse();
    }

    /**
     * Obtain information for a single OnBuy regional site
     * @param $siteId int
     * @return mixed
     * @throws \Exception
     */
    public function getSiteById($siteId = null)
    {
        if (empty($siteId)) {
            throw new \Exception('Site ID required');
        }
        $this->client->setUri($this->domain . $this->version . self::SITES . '/' . $siteId);
        $this->client->setMethod(Request::METHOD_GET);
        $this->getResponse();
    }
}

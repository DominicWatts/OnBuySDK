<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Site;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Xigen\Library\OnBuy\Constants;

class Site extends Constants
{
    /**
     * Site constructor.
     * @param $token
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }

    /**
     * Obtain site information for any regional variation of OnBuy
     * @param array $filterArray name
     * @param $limit int
     * @param $offset int
     * @return Client
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

        return $this->client;
    }

    /**
     * Obtain information for a single OnBuy regional site
     * @param $siteId int
     * @return Client
     * @throws \Exception
     */
    public function getSiteById($siteId = null)
    {
        if (empty($siteId)) {
            throw new \Exception('Site ID required');
        }
        $this->client->setUri($this->domain . $this->version . self::SITES . '/' . $siteId);
        $this->client->setMethod(Request::METHOD_GET);
        return $this->client;
    }
}

<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Commission;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Xigen\Library\OnBuy\Constants;

class Commission extends Constants
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
     * Obtain commission tier information for any regional variation of OnBuy
     * @param $limit int
     * @param $offset int
     * @return Client
     * @throws \Exception
     */
    public function getTier($limit = null, $offset = null)
    {
        $this->client->setUri($this->domain . $this->version . self::COMMISSION_TIERS);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);

        return $this->client;
    }

    /**
     * Obtain commission tier information for a single OnBuy regional site
     * @param $categoryId int
     * @param $tierId int
     * @return Client
     * @throws \Exception
     */
    public function getTierById($categoryId = null, $tierId = null, $limit = null, $offset = null)
    {
        if (empty($categoryId) || empty($tierId)) {
            throw new \Exception('ID filters required required');
        }
        $this->client->setUri($this->domain . $this->version . self::CATEGORIES . '/' . $categoryId . '/' . self::VARIANTS);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'commission_tier_id' => $tierId,
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);
        return $this->client;
    }
}

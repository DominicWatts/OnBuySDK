<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Brand;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Xigen\Library\OnBuy\Constants;

class Brand extends Constants
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
     * Obtain brand information for any brands created on OnBuy
     * @param $filter string
     * @param $sort string asc|desc
     * @param $limit int
     * @param $offset int
     * @return Client
     * @throws \Exception
     */
    public function getBrand($filter, $sort = null, $limit = null, $offset = null)
    {
        if (empty($filter)) {
            throw new \Exception('Brand filter keyword required');
        }

        $this->client->setUri($this->domain . $this->version . self::BRAND);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'filter' => [
                'name' => $filter
            ],
            'sort' => [
                'name' => $sort ?: self::DEFAULT_SORT,
            ],
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);

        return $this->client;
    }

    /**
     * Obtain information for a single OnBuy brand
     * @param $brandId
     * @return Client
     * @throws \Exception
     */
    public function getBrandById($brandId = null)
    {
        if (empty($brandId)) {
            throw new \Exception('Brand ID required');
        }
        $this->client->setUri($this->domain . $this->version . self::BRAND . '/' . $brandId);
        $this->client->setMethod(Request::METHOD_GET);
        return $this->client;
    }
}

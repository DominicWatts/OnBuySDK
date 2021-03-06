<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Category;

use Laminas\Http\Request;

class Feature extends Base
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
     * Obtain information for a single OnBuy category
     * @param int $categoryId
     * @param $limit int
     * @param $offset int
     * @return Client
     * @throws \Exception
     */
    public function getFeatureById($categoryId = null, $limit = null, $offset = null)
    {
        if (empty($categoryId)) {
            throw new \Exception('Category ID required');
        }

        $this->client->setUri($this->domain . $this->version . self::CATEGORIES . '/' . $categoryId . '/' . self::FEATURES);
        $this->client->setMethod(Request::METHOD_GET);

        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);

        return $this->client;
    }
}

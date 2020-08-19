<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Category;

use Laminas\Http\Request;

class Technical extends Base
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
     * Obtain a list of all category groups
     * @param int $categoryId
     * @param $limit int
     * @param $offset int
     * @return Client
     * @throws \Exception
     */
    public function getTechnicalDetailById($categoryId = null, $limit = null, $offset = null)
    {
        if (empty($categoryId)) {
            throw new \Exception('Category filter parameters required');
        }

        $this->client->setUri($this->domain . $this->version . self::CATEGORIES . '/' . $categoryId . '/' . self::TECHNICAL_DETAILS);
        $this->client->setMethod(Request::METHOD_GET);

        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);

        return $this->client;
    }

    /**
     * Obtain information for a single OnBuy category
     * @param int $categoryId
     * @param int $groupId
     * @return Client
     * @throws \Exception
     */
    public function getGroupById($categoryId = null, $groupId = null)
    {
        if (empty($categoryId) || empty($groupId)) {
            throw new \Exception('Category filter parameters required');
        }

        $this->client->setUri($this->domain . $this->version . self::CATEGORIES . '/' . $categoryId . '/' . self::TECHNICAL_DETAILS . '/' . $groupId);
        $this->client->setMethod(Request::METHOD_GET);

        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'product_detail_group_id' => $groupId
        ]);

        return $this->client;
    }
}

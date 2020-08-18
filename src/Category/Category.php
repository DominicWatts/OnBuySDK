<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Category;

use Laminas\Http\Request;

class Category extends Base
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
     * Category constructor.
     * @param $token
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }

    /**
     * Obtain category information for any categories created on OnBuy
     * @param array $filterArray onbuy_category_id|category_type_id|name|can_list_in
     * @param $limit int
     * @param $offset int
     * @return mixed
     * @throws \Exception
     */
    public function getCategory($filterArray = [], $limit = null, $offset = null)
    {
        if (empty($filterArray)) {
            throw new \Exception('Category filter parameters required');
        }

        $this->client->setUri($this->domain . $this->version . self::CATEGORIES);
        $this->client->setMethod(Request::METHOD_GET);

        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'filter' => $filterArray,
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ]);

        $this->getResponse();
    }

    /**
     * Obtain information for a single OnBuy category
     * @param $categoryId int
     * @return mixed
     * @throws \Exception
     */
    public function getCategoryById($categoryId = null)
    {
        if (empty($categoryId)) {
            throw new \Exception('Category ID required');
        }
        $this->client->setUri($this->domain . $this->version . self::CATEGORIES . '/' . $categoryId);
        $this->client->setMethod(Request::METHOD_GET);

        $this->client->setParameterGet([
            'site_id' => self::SITE_ID
        ]);

        $this->getResponse();
    }
}

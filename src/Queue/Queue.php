<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Queue;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Xigen\Library\OnBuy\Constants;

class Queue extends Constants
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
     * Check the progress of any actions that use OnBuy queuing system
     * @param $filterArray array queue_ids|status[success|failed|pending]
     * @return Client
     * @throws \Exception
     */
    public function getQueue($filterArray = [])
    {
        if (empty($filterArray)) {
            throw new \Exception('Filter parameters required');
        }

        $this->client->setUri($this->domain . $this->version . self::QUEUES);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'site_id' => self::SITE_ID,
            'filter' => $filterArray
        ]);

        return $this->client;
    }

    /**
     * Check the progress of any actions that use OnBuy queuing system
     * @param $queueId
     * @return Client
     * @throws \Exception
     */
    public function getQueueById($queueId = null)
    {
        if (empty($queueId)) {
            throw new \Exception('Queue ID required');
        }
        $this->client->setUri($this->domain . $this->version . self::QUEUES . '/' . $queueId);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'site_id' => self::SITE_ID
        ]);
        return $this->client;
    }
}

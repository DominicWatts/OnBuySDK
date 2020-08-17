<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Queue;

use Laminas\Http\Client;
use Laminas\Http\Headers;
use Laminas\Http\Request;
use Laminas\Json\Json;
use Xigen\Library\OnBuy\Constants;

class Queue extends Constants
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
     * Brand constructor.
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
     * Check the progress of any actions that use OnBuy queuing system
     * @param $filterArray array queue_ids|status[success|failed|pending]
     * @return mixed
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

        $this->getResponse();
    }

    /**
     * Check the progress of any actions that use OnBuy queuing system
     * @param $queueId
     * @return mixed
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
        $this->getResponse();
    }
}

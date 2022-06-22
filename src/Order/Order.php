<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy\Order;

use Laminas\Http\Request;
use Laminas\Json\Json;
use Xigen\Library\OnBuy\Constants;

class Order extends Base
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
     * Browse and search orders
     * @param array $filterArray status[awaiting_dispatch|dispatched|complete|cancelled|cancelled_by_seller|cancelled_by_buyer|partially_dispatched|partially_refunded|refunded|all]|order_ids|modified_since|previously_exported
     * @param array $sortArray string created[asc|desc]|modified[asc|desc]
     * @param null $limit
     * @param null $offset
     * @return mixed
     * @throws \Exception
     */
    public function getOrder($filterArray = [], $sortArray = [], $limit = null, $offset = null)
    {
        $this->client->setUri($this->domain . $this->version . self::ORDERS);
        $this->client->setMethod(Request::METHOD_GET);

        // required
        $params = [
            'site_id' => self::SITE_ID,
            'limit' => $limit ?: self::DEFAULT_LIMIT,
            'offset' => $offset ?: self::DEFAULT_OFFSET
        ];

        // optional
        if (!empty($filterArray)) {
            $params['filter'] = $filterArray;
        }
        if (!empty($sortArray)) {
            $params['sort'] = $sortArray;
        }

        $this->client->setParameterGet($params);
        return $this->client;
    }

    /**
     * View order by ID
     * @param $orderId int
     * @return mixed
     * @throws \Exception
     */
    public function getOrderById($orderId = null)
    {
        if (empty($orderId)) {
            throw new \Exception('Order ID required');
        }
        $this->client->setUri($this->domain . $this->version . self::ORDERS . '/' . $orderId);
        $this->client->setMethod(Request::METHOD_GET);
        return $this->client;
    }

    /**
     * Full or partial dispatched - if only order number is given full dispatch
     * @param array $updateArray order_id|products[sku|opc|quantity]|tracking[tracking_id|supplier_name|number|url]
     * @return mixed
     * @throws \Exception
     */
    public function dispatchOrder($updateArray = [])
    {
        $this->client->setUri($this->domain . $this->version . self::ORDERS . '/' . self::DISPATCH);
        $this->client->setMethod(Request::METHOD_PUT);
        $this->client->setRawBody(Json::encode([
            'site_id' => self::SITE_ID,
            'orders' => $updateArray
        ]));
        return $this->client;
    }

    /**
     * Refund a cancelled order
     * @param array $cancelArray order_id|order_cancellation_reason_id[1|2|3|4|5]|delivery|seller_note|customer_note|items[onbuy_internal_reference|amount]
     * @return mixed
     * @throws \Exception
     */
    public function cancelOrder($cancelArray = [])
    {
        $this->client->setUri($this->domain . $this->version . self::ORDERS . '/' . self::CANCEL);
        $this->client->setMethod(Request::METHOD_PUT);
        $this->client->setRawBody(Json::encode([
            'site_id' => self::SITE_ID,
            'orders' => $cancelArray
        ]));
        return $this->client;
    }

    /**
     * Refund a cancelled order
     * @param array $refundArray order_id|order_refund_reason_id[1|2|3|4|5|6]|cancel_order_additional_info
     * @return mixed
     * @throws \Exception
     */
    public function refundOrder($refundArray = [])
    {
        $this->client->setUri($this->domain . $this->version . self::ORDERS . '/' . self::REFUND);
        $this->client->setMethod(Request::METHOD_PUT);
        $this->client->setRawBody(Json::encode([
            'site_id' => self::SITE_ID,
            'orders' => $refundArray
        ]));
        return $this->client;
    }

    /**
     * Get tracking providers
     * @return mixed
     * @throws \Exception
     */
    public function getTrackingProviders()
    {
        $this->client->setUri($this->domain . $this->version . self::ORDERS . '/' . self::TRACKING_PROVIDERS);
        $this->client->setMethod(Request::METHOD_GET);
        $this->client->setParameterGet([
            'site_id' => self::SITE_ID
        ]);
        return $this->client;
    }
}

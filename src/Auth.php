<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\Library\OnBuy;

use Laminas\Http\Client;
use Laminas\Http\Headers;
use Laminas\Http\Request;
use Laminas\Json\Json;

class Auth extends Constants
{
    /**
     * @var \Laminas\Http\Client
     */
    public $client;

    /**
     * @var \Laminas\Http\Response
     */
    public $response;

    /**
     * @var array
     */
    public $responseArray;

    /**
     * @var \Laminas\Http\Headers
     */
    public $headers;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $expires;

    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $consumerKey;

    /**
     * Auth constructor.
     * @param array $customConfig
     * @throws \Exception
     */
    public function __construct($customConfig = [])
    {
        if (empty($customConfig)) {
            throw new \Exception('Secret and Consumer key required');
        }

        $this->secretKey = $customConfig['secret_key'] ?? null;
        $this->consumerKey = $customConfig['consumer_key'] ?? null;

        if (!$this->secretKey || !$this->consumerKey) {
            throw new \Exception('Secret and Consumer key required');
        }

        $this->headers = new Headers();

        $this->client = new Client();
        $this->client->setUri($this->domain . $this->version . self::REQUEST_TOKEN);
        $this->client->setMethod(Request::METHOD_POST);
        $this->client->setOptions([
            'maxredirects' => self::MAXREDIRECTS,
            'timeout' => self::TIMEOUT,
        ]);

        $this->client->setParameterPost([
            'secret_key' => $this->secretKey,
            'consumer_key' => $this->consumerKey
        ]);

        $this->headers->addHeaderLine('Content-Type', self::TOKEN_CONTENT_TYPE);
        $this->client->setHeaders($this->headers);
    }

    /**
     * Perform request and return token
     * @return string
     * @throws \Exception
     */
    public function getToken(): string
    {
        // if token exists and has valid expiry return token
        if ($this->token && $this->expires < time()) {
            return $this->token;
        }
        
        $this->response = $this->client->send();

        if ($this->response->isServerError()) {
            throw new \Exception('Server error');
        }

        $this->catchError($this->response);

        $response = Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
        $this->responseArray = $response;

        if (isset($response['access_token'])) {
            $this->token = $response['access_token'];
            $this->expires = $response['expires_at'];
        }
        return $this->token;
    }

    /**
     * @return string
     */
    public function getExpires(): string
    {
        return $this->expires;
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @param string $consumerKey
     */
    public function setConsumerKey(string $consumerKey): void
    {
        $this->consumerKey = $consumerKey;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * @return string
     */
    public function getConsumerKey(): string
    {
        return $this->consumerKey;
    }

    /**
     * @return \Laminas\Http\Response
     */
    public function getResponse(): \Laminas\Http\Response
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getResponseArray(): array
    {
        return $this->responseArray;
    }
}

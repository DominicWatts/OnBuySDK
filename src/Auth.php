<?php

namespace Xigen\Library\OnBuy;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Headers;
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
        $this->secretKey = $customConfig['secret_key'];
        $this->consumerKey = $customConfig['consumer_key'];

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
        $this->response = $this->client->send();

        if ($this->response->isServerError()) {
            throw new \Exception('Server error');
        }

        $this->catchError($this->response);

        $response = Json::decode($this->response->getBody(), Json::TYPE_ARRAY);
      
        if (isset($response['access_token'])) {
            $this->token = $response['access_token'];
            $this->expires = $response['access_token'];
        }
    }

    /**
     * @return mixed|string
     */
    public function getToken(): string
    {
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
}

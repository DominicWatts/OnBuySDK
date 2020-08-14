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
    private $token;

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
            'maxredirects' => 10,
            'timeout'      => 30,
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
        }
    }

    /**
     * @return mixed|string
     */
    public function getToken()
    {
        return $this->token;
    }
}

<?php

namespace Xigen\Library\OnBuy;

use Laminas\Http\Client;
use Laminas\Http\Request;
use Laminas\Http\Headers;
use Symfony\Component\VarDumper\VarDumper;

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

        $this->headers->addHeaderLine('Content-Type', self::CONTENT_TYPE);
        $this->client->setHeaders($this->headers);
        $this->response = $this->client->send();

        if ($this->response->isServerError()) {
            throw new \Exception('Server error');
        }

        $decode = json_decode($this->response->getBody(), true);
      
        if (isset($decode['success'])) {
            return $decode;
        }
        
        if (isset($decode['error'])) {
            throw new \Exception(sprintf(
                'Error code : %s Error Message: %s',
                $decode['error']['errorCode'],
                $decode['error']['message'],
            ));
        }
    }
}

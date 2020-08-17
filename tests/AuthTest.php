<?php

use Xigen\Library\OnBuy\Auth;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;

class AuthTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testHeader()
    {
        $client = new Auth([
            'consumer_key' => '123',
            'secret_key' => '456'
        ]);
        self::assertSame(Constants::TOKEN_CONTENT_TYPE, $client->getClient()->getHeader('Content-Type'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Auth([
            'consumer_key' => '123',
            'secret_key' => '456'
        ]);
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }

    /**
     * Keys
     */
    public function testKeys()
    {
        $consumerKey = '123';
        $secretKey = '456';
        $client = new Auth([
            'consumer_key' => $consumerKey,
            'secret_key' => $secretKey
        ]);
        self::assertSame($consumerKey, $client->getConsumerKey());
        self::assertSame($secretKey, $client->getSecretKey());
    }

    /**
     * Incorrect token
     */
    public function testInvalidParameters()
    {
        $this->expectException(\Exception::class);
        $auth = new Auth(['123', '456']);
        $auth->getToken();
    }

    /**
     * Invalid token
     * @throws \Exception
     */
    public function testNoParameters()
    {
        $this->expectException(\Exception::class);
        $auth = new Auth([]);
        $auth->getToken();
    }
}

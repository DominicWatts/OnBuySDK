<?php

use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Auth;
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

    /**
     * Consumer key override
     */
    public function testSetConsumerKey()
    {
        $key = 'secret_key';
        $notThisKey = 'not_this_secret_key';
        $client = new Auth([
            'consumer_key' => $notThisKey,
            'secret_key' => 'xyz'
        ]);
        $client->setConsumerKey($key);
        self::assertSame($key, $client->getConsumerKey());
        self::assertNotSame($notThisKey, $client->getConsumerKey());
    }

    /**
     * Secret key override
     */
    public function testSetSecretKey()
    {
        $key = 'secret_key';
        $notThisKey = 'not_this_secret_key';
        $client = new Auth([
            'consumer_key' => 'xyz',
            'secret_key' => $notThisKey
        ]);
        $client->setSecretKey($key);
        self::assertSame($key, $client->getSecretKey());
        self::assertNotSame($notThisKey, $client->getSecretKey());
    }
}

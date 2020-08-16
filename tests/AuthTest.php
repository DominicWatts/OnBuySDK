<?php


use Xigen\Library\OnBuy\Auth;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;

class AuthTest extends TestCase
{
    /**
     * Authorization header
     * @deprecated
     */
    public function Header()
    {
        $client = new Auth([
            'consumer_key' => '123',
            'secret_key' => '456'
        ]);
        self::assertSame(Constants::TOKEN_CONTENT_TYPE, $client->getClient()->getHeader('Content-Type'));
    }

    /**
     * Options
     * @deprecated
     */
    public function Options()
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
     * @deprecated
     */
    public function Keys()
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
     * @throws \Exception
     */
    public function testInvalidParameters()
    {
        $this->expectException(\Exception::class);
        $queue = new Auth(['123', '456']);
    }

    /**
     * Invalid token
     * @throws \Exception
     */
    public function testNoParameters()
    {
        $this->expectException(\Exception::class);
        $queue = new Auth([]);
    }
}

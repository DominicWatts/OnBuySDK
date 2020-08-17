<?php

namespace Seller;

use Xigen\Library\OnBuy\Seller\Seller;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;

class BaseTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testHeader()
    {
        $token = 'xyz';
        $client = new Seller($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Seller('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }
}

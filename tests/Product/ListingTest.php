<?php

namespace Product;

use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;
use Xigen\Library\OnBuy\Product\Listing;

class ListingTest extends TestCase
{
    /**
     * Authorization header
     */
    public function testHeader()
    {
        $token = 'xyz';
        $client = new Listing($token);
        self::assertSame($token, $client->getClient()->getHeader('Authorization'));
        self::assertSame(Constants::CONTENT_TYPE, $client->getClient()->getHeader('Content-Type'));
    }

    /**
     * Options
     */
    public function testOptions()
    {
        $client = new Listing('xyz');
        self::assertSame(Constants::TIMEOUT, $client->getClient()->getAdapter()->getConfig()['timeout']);
        self::assertSame(Constants::MAXREDIRECTS, $client->getClient()->getAdapter()->getConfig()['maxredirects']);
    }
}

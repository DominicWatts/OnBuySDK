<?php

use Laminas\Http\Response\Stream;
use Laminas\Json\Json;
use PHPUnit\Framework\TestCase;
use Xigen\Library\OnBuy\Constants;

class ConstantsTest extends TestCase
{
    /**
     * Invalid keys
     */
    public function testCatchError()
    {
        $string = 'HTTP/1.0 200 OK' . "\r\n\r\n" . '{"error":{"message":"Invalid secret key","errorCode":"invalidRequest","responseCode":400}}' . "\r\n";
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $string);
        $this->expectException(\Exception::class);
        $response = Stream::fromStream($string, $stream);
        $constant = new Constants('xyz');
        $constant->catchError($response);
    }

    /**
     * Invalid server response
     */
    public function testCatchServerError()
    {
        $this->expectException(\Exception::class);
        $string = 'HTTP/1.0 500 OK' . "\r\n\r\n" . '' . "\r\n";
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $string);
        $response = Stream::fromStream($string, $stream);
        $constant = new Constants('xyz');
        $constant->catchError($response);
    }

    /**
     * Valid keys
     */
    public function testCatchResponse()
    {
        $string = 'HTTP/1.0 200 OK' . "\r\n\r\n" . '{"access_token":"ABCDEFGH-ABCD-ABCD-ABCD-ABCDEFGHIJKL","expires_at":"1234567890"}' . "\r\n";
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $string);
        $response = Stream::fromStream($string, $stream);
        $constant = new Constants('xyz');
        $constant->catchError($response);
        $responseArray = Json::decode($response->getBody(), Json::TYPE_ARRAY);
        self::assertSame('ABCDEFGH-ABCD-ABCD-ABCD-ABCDEFGHIJKL', $responseArray['access_token']);
        self::assertSame('1234567890', $responseArray['expires_at']);
    }

    /**
     * Domain override
     */
    public function testSetDomain()
    {
        $constant = new Constants('xyz');
        $domain = 'https://anotherapi.onbuy.com/';
        $constant->setDomain($domain);
        self::assertSame($domain, $constant->getDomain());
    }

    /**
     * Token override
     */
    public function testSetToken()
    {
        $constant = new Constants('xyz');
        $token = 'ABCDEFGH-ABCD-ABCD-ABCD-ABCDEFGHIJKL';
        $constant->setToken($token);
        self::assertSame($token, $constant->getToken());
    }

    /**
     * Version override
     */
    public function testSetVersion()
    {
        $constant = new Constants('xyz');
        $version = 'v3/';
        $constant->setVersion($version);
        self::assertSame($version, $constant->getVersion());
    }

    /**
     * Response as array
     * @throws Exception
     */
    public function testGetResponseArray()
    {
        $string = 'HTTP/1.0 200 OK' . "\r\n\r\n" . '{"access_token":"ABCDEFGH-ABCD-ABCD-ABCD-ABCDEFGHIJKL","expires_at":"1234567890"}' . "\r\n";
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $string);
        $response = Stream::fromStream($string, $stream);
        $constant = new Constants('xyz');
        $constant->catchError($response);
        $responseArray = Json::decode($response->getBody(), Json::TYPE_ARRAY);
        self::assertIsArray($responseArray);
    }
}

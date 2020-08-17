<?php

use Xigen\Library\OnBuy\Constants;
use PHPUnit\Framework\TestCase;
use Laminas\Http\Response\Stream;
use Laminas\Json\Json;

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
        $constant = new Constants();
        $constant->catchError($response);
    }

    /**
     * Invalid server response
     */
    public function testCatchServerError()
    {
        $string = 'HTTP/1.0 500 OK' . "\r\n\r\n" . '' . "\r\n";
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $string);
        $this->expectException(\Exception::class);
        $response = Stream::fromStream($string, $stream);
        $constant = new Constants();
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
        $constant = new Constants();
        $constant->catchError($response);
        $responseArray = Json::decode($response->getBody(), Json::TYPE_ARRAY);
        self::assertSame('ABCDEFGH-ABCD-ABCD-ABCD-ABCDEFGHIJKL', $responseArray['access_token']);
        self::assertSame('1234567890', $responseArray['expires_at']);
    }
}
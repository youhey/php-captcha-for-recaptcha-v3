<?php

/**
 * Google reCAPTCHA v3 for PHP scripts
 */

declare(strict_types=1);

namespace PiCaptcha\Tests;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PiCaptcha\Captcha;
use ReCaptcha\RequestMethod;

#[CoversMethod(Captcha::class, 'verify')]
class VerificationTest extends TestCase
{
    #[Test]
    public function testVerify(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true}');

        $result = $captcha->verify(token: 'token', requestMethod: $method);

        self::assertTrue($result->isSuccess());
    }

    #[Test]
    public function testAboveScoreThreshold(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "score": "0.9"}');

        $result = $captcha->verify(token: 'token', score: 0.5, requestMethod: $method);

        self::assertTrue($result->isSuccess());
        self::assertEquals(0.9, $result->getScore());
    }

    #[Test]
    public function testBelowScoreThreshold(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "score": "0.1"}');
        $result = $captcha->verify(token: 'token', score: 0.5, requestMethod: $method);

        self::assertFalse($result->isSuccess());
        self::assertEquals(0.1, $result->getScore());
    }

    #[Test]
    public function testActionMatch(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "action": "action/hoge"}');
        $result = $captcha->verify(token: 'token', action:'action/hoge', requestMethod: $method);

        self::assertTrue($result->isSuccess());
        self::assertEquals('action/hoge', $result->getAction());
    }

    #[Test]
    public function testActionMismatch(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "action": "action/foobar"}');
        $result = $captcha->verify(token: 'token', action: 'action/hoge', requestMethod: $method);

        self::assertFalse($result->isSuccess());
        self::assertEquals('action/foobar', $result->getAction());
    }

    #[Test]
    public function testHostnameMatch(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "hostname": "hostname.hoge"}');
        $result = $captcha->verify(token: 'token', hostname:'hostname.hoge', requestMethod: $method);

        self::assertTrue($result->isSuccess());
        self::assertEquals('hostname.hoge', $result->getHostname());
    }

    #[Test]
    public function testHostnameMismatch(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "hostname": "hostname.foobar"}');
        $result = $captcha->verify(token: 'token', hostname: 'hostname.hoge', requestMethod: $method);

        self::assertFalse($result->isSuccess());
        self::assertEquals('hostname.foobar', $result->getHostname());
    }

    /**
     * Mock RequestMethod
     *
     * @param string $json Response JSON
     *
     * @return RequestMethod
     */
    private function getMockRequestMethod(string $json): RequestMethod
    {
        $method = $this->getMockBuilder(RequestMethod::class)->disableOriginalConstructor()->getMock();
        $method->method('submit')->willReturn($json);

        assert($method instanceof RequestMethod);

        return $method;
    }
}

<?php

/**
 * Google reCAPTCHA v3 for PHP scripts
 */

declare(strict_types=1);

namespace PiCaptcha\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PiCaptcha\Captcha;
use ReCaptcha\RequestMethod;

#[CoversClass(Captcha::class)]
class VerificationTest extends TestCase
{
    #[Test]
    public function testVerify(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true}');

        $result = $captcha->verify(token: 'token', requestMethod: $method);

        $this->assertTrue($result);
    }

    #[Test]
    public function testAboveScoreThreshold(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "score": "0.9"}');

        $result = $captcha->verify(token: 'token', threshold: 0.5, requestMethod: $method);

        $this->assertTrue($result);
    }

    #[Test]
    public function testBelowScoreThreshold(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "score": "0.1"}');
        $result = $captcha->verify(token: 'token', threshold: 0.5, requestMethod: $method);

        $this->assertFalse($result);
    }

    #[Test]
    public function testActionMatch(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "action": "action/hoge"}');
        $result = $captcha->verify(token: 'token', action:'action/hoge', requestMethod: $method);

        $this->assertTrue($result);
    }

    #[Test]
    public function testActionMismatch(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "action": "action/hoge"}');
        $result = $captcha->verify(token: 'token', action: 'action/foobar', requestMethod: $method);

        $this->assertFalse($result);
    }

    #[Test]
    public function testHostnameMatch(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "hostname": "hostname.hoge"}');
        $result = $captcha->verify(token: 'token', hostname:'hostname.hoge', requestMethod: $method);

        $this->assertTrue($result);
    }

    #[Test]
    public function testHostnameMismatch(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "hostname": "hostname.hoge"}');
        $result = $captcha->verify(token: 'token', hostname: 'hostname.foobar', requestMethod: $method);

        $this->assertFalse($result);
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

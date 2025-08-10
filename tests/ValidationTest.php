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
use PiCaptcha\ValidationException;
use ReCaptcha\RequestMethod;

#[CoversMethod(Captcha::class, 'validate')]
class ValidationTest extends TestCase
{
    #[Test]
    public function testValidate(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true}');

        $captcha->validate(token: 'token', requestMethod: $method);
        self::assertTrue(true);
    }

    #[Test]
    public function testAboveScoreThreshold(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "score": "0.9"}');

        $captcha->validate(token: 'token', score: 0.5, requestMethod: $method);
        self::assertTrue(true);
    }

    #[Test]
    public function testBelowScoreThreshold(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The user failed the CAPTCHA test. Error codes (score-threshold-not-met)');

        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "score": "0.1"}');
        $captcha->validate(token: 'token', score: 0.5, requestMethod: $method);
    }

    #[Test]
    public function testActionMatch(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "action": "action/hoge"}');
        $captcha->validate(token: 'token', action:'action/hoge', requestMethod: $method);
        self::assertTrue(true);
    }

    #[Test]
    public function testActionMismatch(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The user failed the CAPTCHA test. Error codes (action-mismatch)');

        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "action": "action/hoge"}');
        $captcha->validate(token: 'token', action: 'action/foobar', requestMethod: $method);
    }

    #[Test]
    public function testHostnameMatch(): void
    {
        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "hostname": "hostname.hoge"}');
        $captcha->validate(token: 'token', hostname:'hostname.hoge', requestMethod: $method);
        self::assertTrue(true);
    }

    #[Test]
    public function testHostnameMismatch(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The user failed the CAPTCHA test. Error codes (hostname-mismatch)');

        $captcha = new Captcha('secret');

        $method = $this->getMockRequestMethod('{"success": true, "hostname": "hostname.hoge"}');
        $captcha->validate(token: 'token', hostname: 'hostname.foobar', requestMethod: $method);
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

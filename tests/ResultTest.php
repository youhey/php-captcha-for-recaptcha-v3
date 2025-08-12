<?php

/**
 * Google reCAPTCHA v3 for PHP scripts
 */

declare(strict_types=1);

namespace PiCaptcha\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PiCaptcha\Result;
use ReCaptcha\ReCaptcha;

#[CoversClass(ValidationException::class)]
class ResultTest extends TestCase
{
    #[Test]
    public function testSuccessfulResult(): void
    {
        $exception = new Result(success: true, errorCodes: [], hostname: 'example.com', timestamp: '2025-01-23T12:34:56 +09:00', score: 0.9, action: 'action.name');

        self::assertTrue($exception->isSuccess());
        self::assertEquals([], $exception->getErrorCodes());
        self::assertEquals('example.com', $exception->getHostname());
        self::assertEquals(1737603296, $exception->getTimestamp());
        self::assertEquals(0.9, $exception->getScore());
        self::assertEquals('action.name', $exception->getAction());
    }

    #[Test]
    public function testResultOfFailure(): void
    {
        $exception = new Result(success: false, errorCodes: [
            ReCaptcha::E_INVALID_JSON,
            ReCaptcha::E_CONNECTION_FAILED,
            ReCaptcha::E_BAD_RESPONSE,
            ReCaptcha::E_UNKNOWN_ERROR,
            ReCaptcha::E_MISSING_INPUT_RESPONSE,
            ReCaptcha::E_HOSTNAME_MISMATCH,
            ReCaptcha::E_APK_PACKAGE_NAME_MISMATCH,
            ReCaptcha::E_ACTION_MISMATCH,
            ReCaptcha::E_SCORE_THRESHOLD_NOT_MET,
            ReCaptcha::E_CHALLENGE_TIMEOUT,
        ], hostname: 'example.com', timestamp: '2025-01-23T12:34:56 +09:00', score: 0.1, action: 'action.name');

        self::assertFalse($exception->isSuccess());
        self::assertEquals([
            ReCaptcha::E_INVALID_JSON,
            ReCaptcha::E_CONNECTION_FAILED,
            ReCaptcha::E_BAD_RESPONSE,
            ReCaptcha::E_UNKNOWN_ERROR,
            ReCaptcha::E_MISSING_INPUT_RESPONSE,
            ReCaptcha::E_HOSTNAME_MISMATCH,
            ReCaptcha::E_APK_PACKAGE_NAME_MISMATCH,
            ReCaptcha::E_ACTION_MISMATCH,
            ReCaptcha::E_SCORE_THRESHOLD_NOT_MET,
            ReCaptcha::E_CHALLENGE_TIMEOUT,
        ], $exception->getErrorCodes());
        self::assertEquals('example.com', $exception->getHostname());
        self::assertEquals(1737603296, $exception->getTimestamp());
        self::assertEquals(0.1, $exception->getScore());
        self::assertEquals('action.name', $exception->getAction());
    }

    #[Test]
    public function testSimpleResult(): void
    {
        $exception = new Result(success: true);

        self::assertEquals([], $exception->getErrorCodes());
        self::assertNull($exception->getHostname());
        self::assertNull($exception->getTimestamp());
        self::assertNull($exception->getScore());
        self::assertNull($exception->getAction());
    }
}

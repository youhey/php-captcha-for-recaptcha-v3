<?php

/**
 * Google reCAPTCHA v3 for PHP scripts
 */

declare(strict_types=1);

namespace PiCaptcha\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use PiCaptcha\ValidationException;
use ReCaptcha\ReCaptcha;

#[CoversClass(ValidationException::class)]
class ValidationExceptionTest extends TestCase
{
    #[Test]
    public function testValidate(): void
    {
        $exception = new ValidationException(errorCodes: [
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
        ], hostname: 'example.com', timestamp: '2025-01-23T12:34:56 +09:00', score: 0.9, action: 'action.name');

        self::assertEquals('The user failed the CAPTCHA test. Error codes (invalid-json, connection-failed, bad-response, unknown-error, missing-input-response, hostname-mismatch, apk_package_name-mismatch, action-mismatch, score-threshold-not-met, challenge-timeout)', $exception->getMessage());
        self::assertEquals(0, $exception->getCode());
        self::assertNull($exception->getPrevious());
        self::assertStringContainsString('PiCaptcha\ValidationException: The user failed the CAPTCHA test.', (string) $exception);
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
        self::assertEquals(0.9, $exception->getScore());
        self::assertEquals('action.name', $exception->getAction());
    }
}

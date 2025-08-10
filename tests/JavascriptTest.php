<?php

/**
 * Google reCAPTCHA v3 for PHP scripts
 */

declare(strict_types=1);

namespace PiCaptcha\Tests;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PiCaptcha\Captcha;
use PHPUnit\Framework\TestCase;

#[CoversMethod(Captcha::class, 'js')]
class JavascriptTest extends TestCase
{
    #[Test]
    public function testGenerationOfScriptTag(): void
    {
        $result = Captcha::js('SITE_KEY');

        self::assertEquals('<script src="https://www.google.com/recaptcha/api.js?render=SITE_KEY"></script>', $result);
    }

    #[Test]
    public function testGenerationOfScriptTagWithAsyncAttribute(): void
    {
        $result = Captcha::js(siteKey: 'SITE_KEY', async: true);

        self::assertEquals('<script async src="https://www.google.com/recaptcha/api.js?render=SITE_KEY"></script>', $result);
    }

    #[Test]
    public function testGenerationOfScriptTagWithDeferAttribute(): void
    {
        $result = Captcha::js(siteKey: 'SITE_KEY', defer: true);

        self::assertEquals('<script defer src="https://www.google.com/recaptcha/api.js?render=SITE_KEY"></script>', $result);
    }

    #[Test]
    public function testAsyncDeferScriptTagGeneration(): void
    {
        $result = Captcha::js(siteKey: 'SITE_KEY', async: true, defer: true);

        self::assertEquals('<script async defer src="https://www.google.com/recaptcha/api.js?render=SITE_KEY"></script>', $result);
    }

    #[Test]
    public function testGenerationOfJsModuleScriptTag(): void
    {
        $result = Captcha::js(siteKey: 'SITE_KEY', module: true);

        self::assertEquals('<script type="module" src="https://www.google.com/recaptcha/api.js?render=SITE_KEY"></script>', $result);
    }
}

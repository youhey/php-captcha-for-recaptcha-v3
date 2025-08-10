<?php

/**
 * Google reCAPTCHA v3 for PHP scripts
 */

declare(strict_types=1);

namespace PiCaptcha;

class Captcha
{
    private const string CLIENT_API_URL = 'https://www.google.com/recaptcha/api.js';

    /**
     * Get the JS for Google reCAPTCHA v3.
     *
     * @param string|null $siteKey Google reCAPTCHA v3 site key
     * @param bool $async
     * @param bool $defer
     * @param bool $module
     *
     * @return string
     *
     * @see https://developers.google.com/recaptcha/docs/v3?hl=ja
     */
    public static function js(?string $siteKey = null, bool $async = false, bool $defer = false, bool $module = false): string
    {
        $url = self::CLIENT_API_URL;

        $param = [];

        if (! is_null($siteKey)) {
            $param['render'] = $siteKey;
        }

        if ($param !== []) {
            $url .= '?' . http_build_query($param);
        }

        $attribute = '';

        if ($module) {
            $attribute .= ' type="module"';
        }
        if ($async) {
            $attribute .= ' async';
        }
        if ($defer) {
            $attribute .= ' defer';
        }

        return <<<HTML
            <script{$attribute} src="{$url}"></script>
            HTML;
    }
}

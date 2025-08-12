<?php

/**
 * Google reCAPTCHA v3 for PHP scripts
 */

declare(strict_types=1);

namespace PiCaptcha;

use ReCaptcha\ReCaptcha;
use ReCaptcha\RequestMethod;
use ReCaptcha\RequestMethod\Post;

class Captcha
{
    private const string CLIENT_API_URL = 'https://www.google.com/recaptcha/api.js';

    /** @var string reCAPTCHA secret for the site */
    private string $secret;

    /**
     * Constructor
     *
     * @param string $secret reCAPTCHA secret
     */
    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * reCAPTCHA site Validation
     *
     * @param string $token The user response token
     * @param string|null $action Expected action
     * @param string|null $clientIp Expected end user's IP address
     * @param float|null $score Expected score threshold
     * @param string|null $hostname Expected hostname
     * @param RequestMethod $requestMethod Expected method used to send the request
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function validate(string $token, ?string $clientIp = null, ?string $action = null, ?float $score = null, ?string $hostname = null, RequestMethod $requestMethod = new Post()): void
    {
        $result = $this->exec(token: $token, clientIp: $clientIp, action: $action, score: $score, hostname: $hostname, requestMethod: $requestMethod);

        if ($result->isSuccess()) {
            return;
        }

        throw new ValidationException($result);
    }

    /**
     * reCAPTCHA site Verification
     *
     * @param string $token The user response token
     * @param string|null $action Expected action
     * @param string|null $clientIp Expected end user's IP address
     * @param float|null $score Expected score threshold
     * @param string|null $hostname Expected hostname
     * @param RequestMethod $requestMethod Expected method used to send the request
     *
     * @return Result
     */
    public function verify(string $token, ?string $clientIp = null, ?string $action = null, ?float $score= null, ?string $hostname = null, RequestMethod $requestMethod = new Post()): Result
    {
        return $this->exec(token: $token, clientIp: $clientIp, action: $action, score: $score, hostname: $hostname, requestMethod: $requestMethod);
    }

    /**
     * Calls the reCAPTCHA site-verify API
     *
     * @param string $token The user response token
     * @param string|null $action Expected action
     * @param string|null $clientIp Expected end user's IP address
     * @param float|null $score Expected score threshold
     * @param string|null $hostname Expected hostname
     * @param RequestMethod $requestMethod  Expected method used to send the request
     *
     * @return Result
     */
    private function exec(string $token, ?string $clientIp, ?string $action, ?float $score, ?string $hostname, RequestMethod $requestMethod): Result
    {
        $recaptcha = new ReCaptcha($this->secret, $requestMethod);

        if (! is_null($hostname)) {
            $recaptcha->setExpectedHostname($hostname);
        }
        if (! is_null($action)) {
            $recaptcha->setExpectedAction($action);
        }
        if (! is_null($score)) {
            $recaptcha->setScoreThreshold($score);
        }

        $response = $recaptcha->verify($token, $clientIp);

        return new Result($response->isSuccess(), $response->getErrorCodes(), $response->getHostname(), $response->getChallengeTs(), $response->getScore(), $response->getAction());
    }

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

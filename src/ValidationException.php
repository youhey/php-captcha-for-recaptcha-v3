<?php

/**
 * KITERETSU: Main Site for Cross-Platform Game
 */

declare(strict_types=1);

namespace PiCaptcha;

use Exception;

class ValidationException extends Exception
{
    /**
     * The captcha result
     *
     * @var Result
     */
    private Result $result;

    /**
     * Constructor
     *
     * @param Result $result
     */
    public function __construct(Result $result)
    {
        $this->result = $result;

        $message = 'The user failed the CAPTCHA test.';

        if (count($this->getErrorCodes()) > 0) {
            $message .= ' Error codes (' . implode(', ', $this->getErrorCodes()) . ')';
        }

        parent::__construct($message);
    }

    /**
     * Returns the error codes.
     *
     * @return list<string>
     */
    public function getErrorCodes(): array
    {
        return $this->result->getErrorCodes();
    }

    /**
     * Returns the hostname.
     *
     * @return string|null
     */
    public function getHostname(): ?string
    {
        return $this->result->getHostname();
    }

    /**
     * Returns the challenge timestamp.
     *
     * @return float|null
     */
    public function getTimestamp(): ?float
    {
        return $this->result->getTimestamp();
    }

    /**
     * Returns the score.
     *
     * @return float|null
     */
    public function getScore(): ?float
    {
        return $this->result->getScore();
    }

    /**
     * Returns the action.
     *
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->result->getAction();
    }
}

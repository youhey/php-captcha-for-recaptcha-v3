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
     * Error code strings.
     *
     * @var list<string>
     */
    private array $errorCodes;

    /**
     * The hostname of the site where the reCAPTCHA was solved.
     *
     * @var string|null
     */
    private ?string $hostname;

    /**
     * Timestamp of the challenge load
     *
     * @var int|null
     */
    private ?int $timestamp;

    /**
     * Score assigned to the request
     *
     * @var float|null
     */
    private ?float $score;

    /**
     * Action as specified by the page
     *
     * @var string|null
     */
    private ?string $action;

    /**
     * Constructor
     *
     * @param list<string> $errorCodes
     * @param string|null $hostname
     * @param string|null $timestamp
     * @param float|null $score
     * @param string|null $action
     */
    public function __construct(array $errorCodes, ?string $hostname, ?string $timestamp, ?float $score, ?string $action)
    {
        $this->errorCodes = array_map(static fn (string $errorCode): string => $errorCode, $errorCodes);


        $message = 'The user failed the CAPTCHA test.';

        if (count($this->errorCodes) > 0) {
            $message .= ' Error codes (' . implode(', ', $this->errorCodes) . ')';
        }

        parent::__construct($message);

        if (! is_null($hostname) && ($hostname !== '')) {
            $this->hostname = $hostname;
        }

        if (! is_null($timestamp) && ($timestamp !== '')) {
            $this->timestamp = strtotime($timestamp);
        }

        if (! is_null($score)) {
            $this->score = $score;
        }

        if (! is_null($action) && ($action !== '')) {
            $this->action = $action;
        }
    }

    /**
     * Returns the error codes.
     *
     * @return list<string>
     */
    public function getErrorCodes(): array
    {
        return $this->errorCodes;
    }

    /**
     * Returns the hostname.
     *
     * @return string|null
     */
    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    /**
     * Returns the challenge timestamp.
     *
     * @return float|null
     */
    public function getTimestamp(): ?float
    {
        return $this->timestamp;
    }

    /**
     * Returns the score.
     *
     * @return float|null
     */
    public function getScore(): ?float
    {
        return $this->score;
    }

    /**
     * Returns the action.
     *
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }
}

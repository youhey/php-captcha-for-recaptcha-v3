<?php

/**
 * KITERETSU: Main Site for Cross-Platform Game
 */

declare(strict_types=1);

namespace PiCaptcha;

class Result
{
    /**
     * Success or failure.
     *
     * @var bool
     */
    private bool $success;

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
    private ?string $hostname = null;

    /**
     * Timestamp of the challenge load
     *
     * @var int|null
     */
    private ?int $timestamp = null;

    /**
     * Score assigned to the request
     *
     * @var float|null
     */
    private ?float $score = null;

    /**
     * Action as specified by the page
     *
     * @var string|null
     */
    private ?string $action = null;

    /**
     * Constructor
     *
     * @param bool $success
     * @param list<string> $errorCodes
     * @param string|null $hostname
     * @param string|null $timestamp
     * @param float|null $score
     * @param string|null $action
     */
    public function __construct(bool $success, array $errorCodes = [], ?string $hostname = null, ?string $timestamp = null, ?float $score = null, ?string $action = null)
    {
        $this->success = $success;

        $this->errorCodes = array_map(static fn (string $errorCode): string => $errorCode, $errorCodes);

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
     * Returns the Success or failure.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
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

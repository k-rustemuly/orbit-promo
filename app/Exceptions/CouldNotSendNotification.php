<?php

namespace App\Exceptions;

use DomainException;
use Exception;

class CouldNotSendNotification extends Exception
{
    /**
     * Thrown when content length is greater than 1000 characters.
     *
     * @return static
     */
    public static function contentLengthLimitExceeded(): self
    {
        return new static(
            'Notification was not sent. Content length may not be greater than 1000 characters.'
        );
    }

    /**
     * Thrown when we're unable to communicate with isms.center
     *
     * @param  DomainException  $exception
     *
     * @return static
     */
    public static function smscRespondedWithAnError(DomainException $exception): self
    {
        return new static(
            "isms.center responded with an error '{$exception->getCode()}: {$exception->getMessage()}'",
            $exception->getCode(),
            $exception
        );
    }

    /**
     * Thrown when we're unable to communicate with isms.center
     *
     * @param  Exception  $exception
     *
     * @return static
     */
    public static function couldNotCommunicateWithSmsc(Exception $exception): self
    {
        return new static(
            "The communication with isms.center failed. Reason: {$exception->getMessage()}",
            $exception->getCode(),
            $exception
        );
    }
}

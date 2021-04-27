<?php

namespace App;

use Throwable;
use Exception as BaseException;

class Exception extends BaseException
{

    /**
     * Create instance of Exception
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     *
     * @return static
     */
    public static function create($message = "", $code = 0, Throwable $previous = null)
    {
        return new static($message, $code, $previous);
    }
}
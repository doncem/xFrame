<?php

namespace Xframe\Exception;

use ErrorException;

/**
 * Replaces the default error handler and generates ErrorExceptions instead.
 * This is error handler obeys the @ operator.
 *
 * @package exception
 */
class ErrorHandler
{
    /**
     * Register this as the error handler. Errors will raise ErrorExceptions.
     */
    public function register()
    {
        \set_error_handler([$this, 'handle']);
    }

    /**
     * Handles the error and throws an ErrorException if it is beyond the
     * error_reporting() threshold and the @ operator was not set.
     *
     * @param int    $errno
     * @param int    $errstr
     * @param string $errfile
     * @param int    $errline
     */
    public function handle($errno, $errstr, $errfile, $errline)
    {
        // This error code is not included in error_reporting
        if (!(\error_reporting() & $errno)) {
            return false;
        }

        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}

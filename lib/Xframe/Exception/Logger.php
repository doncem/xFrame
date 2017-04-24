<?php

namespace Xframe\Exception;

use SplObserver;
use SplSubject;

/**
 * Uses the observer pattern to listen for exceptions.
 *
 * @see http://devzone.zend.com/article/12229
 *
 * @package exception
 */
class Logger implements SplObserver
{
    /**
     * Log the exception.
     *
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject)
    {
        \error_log($subject->getLastException()->getMessage());
    }
}

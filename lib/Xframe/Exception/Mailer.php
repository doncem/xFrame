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
class Mailer implements SplObserver
{
    /**
     * @var string
     */
    private $recipients;

    /**
     * @param string $recipients
     */
    public function __construct($recipients)
    {
        $this->recipients = $recipients;
    }

    /**
     * Mail the exception.
     *
     * @param SplSubject $subject
     */
    public function update(SplSubject $subject)
    {
        \error_log($subject->getLastException()->getMessage());

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: "' . \filter_input(INPUT_SERVER, 'SERVER_NAME')
                              . \filter_input(INPUT_SERVER, 'REQUEST_URI')
                              . '" <xframe@' . \filter_input(INPUT_SERVER, 'SERVER_NAME') . ">\r\n";
        $headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";

        \mail($this->recipients,
              $subject->getLastException()->getMessage(),
              $subject->getLastException()->__toString(),
              $headers);
    }
}

<?php

namespace Xframe\Exception;

use SplObserver;
use SplSubject;
use Throwable;

/**
 * Uses the observer pattern to handle exceptions. You may add a listener
 * by calling the attach method and passing an SplObserver.
 *
 * @see http://devzone.zend.com/article/12229
 *
 * @package exception
 */
class ExceptionHandler implements SplSubject
{
    /**
     * List of observers to notify.
     *
     * @var array
     */
    private $observers;

    /**
     * @var array
     */
    private $exceptions;

    /**
     * Set the initial state.
     */
    public function __construct()
    {
        $this->observers = [];
        $this->exceptions = [];
    }

    /**
     * Attaches the given observer to the list of observers to be notified
     * when an exception occurs.
     *
     * @param SplObserver $observer
     */
    public function attach(SplObserver $observer)
    {
        $id = \spl_object_hash($observer);

        $this->observers[$id] = $observer;
    }

    /**
     * Detaches the given observer.
     *
     * @param SplObserver $observer
     */
    public function detach(SplObserver $observer)
    {
        $id = \spl_object_hash($observer);

        unset($this->observers[$id]);
    }

    /**
     * Notify the observers that the event as occurred.
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * Register this exception handler with the SPL internals.
     */
    public function register()
    {
        \set_exception_handler([$this, 'handle']);
    }

    /**
     * Exception handler. Notifies all observers that an exception has occurred.
     *
     * @param Throwable $e
     */
    public function handle(Throwable $e)
    {
        $this->exceptions[] = $e;
        $this->notify();
    }

    /**
     * Return the exceptions that has occurred so far.
     *
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * Returns the last exception that was thrown.
     *
     * @return Exception
     */
    public function getLastException()
    {
        return \end($this->exceptions);
    }

    /**
     * @return array
     */
    public function getObservers()
    {
        return $this->observers;
    }
}

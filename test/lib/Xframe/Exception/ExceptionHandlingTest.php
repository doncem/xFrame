<?php

namespace Xframe\Exception;

use ErrorException;
use PHPUnit\Framework\TestCase;
use SplObserver;

class ExceptionHandlingTest extends TestCase
{
    /**
     * @var ExceptionHandler
     */
    private $handler;

    /**
     * @var SplObserver
     */
    private $observer;

    protected function setUp()
    {
        $this->handler = new ExceptionHandler();
        $this->observer = new ExceptionOutputter();

        $this->handler->register();
        $this->handler->attach($this->observer);
    }

    protected function tearDown()
    {
        \set_exception_handler(null);
    }

    public function testExceptionHandle()
    {
        $exception = new ErrorException('Test error');
        $this->handler->handle($exception);

        $this->expectOutputRegex('/^ErrorException: Test error/');

        $this->assertSame($exception, $this->handler->getLastException());
        $this->assertEquals(1, \count($this->handler->getExceptions()));
        $this->assertEquals(1, \count($this->handler->getObservers()));
    }

    public function testExceptionHandleWith2Observers()
    {
        $exception = new ErrorException('Test error');
        $this->handler->attach(new Logger());
        $this->handler->handle($exception);

        $this->expectOutputRegex('/^ErrorException: Test error/');

        $this->assertSame($exception, $this->handler->getLastException());
        $this->assertEquals(1, \count($this->handler->getExceptions()));
        $this->assertEquals(2, \count($this->handler->getObservers()));
    }

    public function testExceptionHandleExceptionTwice()
    {
        $exception = new ErrorException('Test error');
        $this->handler->handle($exception);
        $this->handler->handle($exception);

        $this->expectOutputRegex('/^ErrorException: Test error/');

        $this->assertSame($exception, $this->handler->getLastException());
        $this->assertEquals(2, \count($this->handler->getExceptions()));
        $this->assertEquals(1, \count($this->handler->getObservers()));
    }

    public function testExceptionHandleWithoutObservers()
    {
        $exception = new ErrorException('Test error');
        $this->handler->detach($this->observer);
        $this->handler->handle($exception);

        $this->expectOutputString('');

        $this->assertSame($exception, $this->handler->getLastException());
        $this->assertEquals(1, \count($this->handler->getExceptions()));
        $this->assertEquals(0, \count($this->handler->getObservers()));
    }
}

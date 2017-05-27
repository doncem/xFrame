<?php

namespace Xframe\Exception;

use PHPUnit\Framework\TestCase;

class ErrorHandlerTest extends TestCase
{
    /**
     * @var ErrorHandler
     */
    private $handler;

    protected function setUp()
    {
        $this->handler = new ErrorHandler();
        $this->handler->register();
    }

    protected function tearDown()
    {
        \set_error_handler(null);
    }

    public function testErrorHandler()
    {
        $this->expectException('ErrorException');
        1 / 0;
    }

    public function testErrorHandlerWithLooseErrorReporting()
    {
        $oldLevel = \error_reporting();
        \error_reporting(E_ERROR);

        1 / 0;

        $this->assertTrue(true);

        \error_reporting($oldLevel);
    }
}

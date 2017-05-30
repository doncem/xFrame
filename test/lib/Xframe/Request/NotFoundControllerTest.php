<?php

namespace Xframe\Request;

use PHPUnit\Framework\TestCase;

class NotFoundControllerTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testHandle()
    {
        $controller = new NotFoundController($this->getRequestMock($this));

        $this->expectOutputString('Resource: test not found.' . PHP_EOL);

        $controller->handleRequest();
    }
}

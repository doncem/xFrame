<?php

namespace Demo\Controller;

use PHPUnit\Framework\TestCase;
use Xframe\Request\Controller;
use Xframe\View\JsonView;

class IndexTest extends TestCase
{
    use \Xframe\Fixtures;

    /**
     * @var Controller
     */
    public $controller;

    protected function setUp()
    {
        $this->controller = new Index(
            $this->getDependencyInjectionMock($this),
            $this->getRequestMock($this),
            'run',
            new JsonView()
        );
    }

    public function testRun()
    {
        $this->expectOutputString('[]');

        $this->controller->handleRequest();
    }
}

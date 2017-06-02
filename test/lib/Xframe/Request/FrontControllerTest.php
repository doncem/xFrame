<?php

namespace Xframe\Request;

use PHPUnit\Framework\TestCase;

class FrontControllerMock extends FrontController
{
    protected function rebuildRequestMap()
    {
    }
}

class FrontControllerTest extends TestCase
{
    use \Xframe\Fixtures;

    /**
     * @var FrontController
     */
    private $front;

    /**
     * @var Request
     */
    private $request;

    protected function setUp()
    {
        $this->front = new FrontControllerMock($this->getDependencyInjectionMock($this));
    }

    public function testDispatch()
    {
        $this->expectOutputString('[]');

        $this->front->dispatch($this->getRequestMock($this));
    }

    public function testFileNotFoundAfterRebuild()
    {
        $this->expectOutputString('Resource: index not found.' . PHP_EOL);

        $this->request = $this->getMock($this, 'Xframe\Request\Request');
        $this->request->method('getRequestedResource')->willReturn('index');

        $this->front->dispatch($this->request);
    }

    public function testFileFoundAfterRebuild()
    {
        $this->expectOutputString('[]');

        $this->request = $this->getMock($this, 'Xframe\Request\Request');
        $this->request->method('getRequestedResource')->willReturn('index', 'test');

        $this->front->dispatch($this->request);
    }
}

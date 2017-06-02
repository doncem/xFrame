<?php

namespace Xframe\Request;

use PHPUnit\Framework\TestCase;
use Xframe\View\JsonView;

class ControllerMock extends Controller
{
    public function runPrefilters()
    {
        return parent::runPrefilters();
    }
}

class ControllerTest extends TestCase
{
    use \Xframe\Fixtures;

    /**
     * @var ControllerMock
     */
    private $controller;

    protected function setUp()
    {
        $this->controller = new ControllerMock(
            $this->getDependencyInjectionMock($this),
            $this->getRequestMock($this),
            'test',
            new JsonView(),
            [],
            [
                $this->getPrefilterMock($this)
            ]
        );
    }

    public function testRedirectFailure()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Could not redirect to index, headers already sent');

        $this->controller->redirect('index');
    }

    /**
     * @runInSeparateProcess
     */
    public function testRedirectSuccess()
    {
        $this->controller->redirect('index');

        $this->assertTrue(true);
    }

    public function testForbiddenFailure()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Error sending 403, headers already sent');

        $this->controller->forbidden();
    }

    /**
     * @runInSeparateProcess
     */
    public function testForbiddenSuccess()
    {
        $this->controller->forbidden();

        $this->assertTrue(true);
    }

    public function testPrefilterFail()
    {
        $this->assertFalse($this->controller->runPrefilters());
    }
}

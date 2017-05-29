<?php

namespace Demo\Controller;

use PHPUnit\Framework\TestCase;
use Xframe\Core\DependencyInjectionContainer;
use Xframe\Registry\Registry;
use Xframe\Request\Controller;
use Xframe\Request\Request;
use Xframe\View\JsonView;

class IndexTest extends TestCase
{
    use \Xframe\Fixtures;

    /**
     * @var Controller
     */
    public $controller;

    /**
     * @var DependencyInjectionContainer
     */
    public $dic;

    /**
     * @var Registry
     */
    public $registry;

    /**
     * @var Request
     */
    public $request;

    public function chooseDicLambda(string $lambda)
    {
        switch ($lambda) {
            case 'registry':
                $return = $this->registry;
                break;
            default:
                $return = null;
                break;
        }

        return $return;
    }

    protected function setUp()
    {
        $this->dic = $this->createMock('Xframe\Core\DependencyInjectionContainer');
        $this->registry = $this->getRegistryMock($this);
        $this->request = $this->createMock('Xframe\Request\Request');

        $this->dic->method('__get')->will($this->returnCallback([$this, 'chooseDicLambda']));

        $this->controller = new Index($this->dic, $this->request, 'run', new JsonView());
    }

    public function testRun()
    {
        $this->expectOutputString('[]');

        $this->controller->handleRequest();
    }
}

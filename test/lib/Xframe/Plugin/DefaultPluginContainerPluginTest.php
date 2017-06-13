<?php

namespace Xframe\Plugin;

use PHPUnit\Framework\TestCase;
use Xframe\Container;

class DefaultPluginContainerPluginTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    protected function setUp()
    {
        $this->container = new Container();
    }

    use \Xframe\Fixtures;

    public function getRegistryMock(TestCase $case)
    {
        $registry = $this->getMock($case, 'Xframe\Registry');

        $registry->method('__get')->willReturn($this->container);

        return $registry;
    }

    public function testPlugin()
    {
        $plugin = new DefaultPluginContainerPlugin($this->getDependencyInjectionMock($this));
        $container = $plugin->init();

        $this->assertInstanceOf('Xframe\Container', $container);
        $this->assertEquals(0, $container->getIterator()->count());
    }

    public function testSomePlugins()
    {
        $this->container->dummy = 'Xframe\Plugin\DummyPlugin';

        $plugin = new DefaultPluginContainerPlugin($this->getDependencyInjectionMock($this));
        $container = $plugin->init();

        $this->assertInstanceOf('Xframe\Container', $container);
        $this->assertEquals(0, $container->getIterator()->count());
        $this->assertEquals('Dummy', $container->dummy);
        $this->assertEquals(1, $container->getIterator()->count());
    }
}

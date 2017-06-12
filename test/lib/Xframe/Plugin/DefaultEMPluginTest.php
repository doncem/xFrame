<?php

namespace Xframe\Plugin;

use PHPUnit\Framework\TestCase;

class DefaultEMPluginTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testPlugin()
    {
        $plugin = new DefaultEMPlugin($this->getDependencyInjectionMock($this));

        $this->assertInstanceOf('Doctrine\ORM\EntityManager', $plugin->init());
    }
}

<?php

namespace Xframe\Plugin;

use PHPUnit\Framework\TestCase;

class DefaultEvMPluginTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testPlugin()
    {
        $plugin = new DefaultEvMPlugin($this->getDependencyInjectionMock($this));

        $this->assertInstanceOf('Doctrine\Common\EventManager', $plugin->init());
    }
}

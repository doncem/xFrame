<?php

namespace Xframe\Plugin;

use PHPUnit\Framework\TestCase;

class DefaultDoctrineCachePluginTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testPlugin()
    {
        $plugin = new DefaultDoctrineCachePlugin($this->getDependencyInjectionMock($this));

        $this->assertInstanceOf('Doctrine\Common\Cache\Cache', $plugin->init());
    }
}

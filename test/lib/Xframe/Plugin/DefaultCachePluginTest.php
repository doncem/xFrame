<?php

namespace Xframe\Plugin;

use PHPUnit\Framework\TestCase;

class DefaultCachePluginTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testPlugin()
    {
        if (!\class_exists('Memcache')) {
            $this->markTestSkipped('Memcache class required');
        }

        $plugin = new DefaultCachePlugin($this->getDependencyInjectionMock($this));

        $this->assertInstanceOf('Memcache', $plugin->init());
    }
}

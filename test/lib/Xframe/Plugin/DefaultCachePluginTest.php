<?php

namespace Xframe\Plugin;

use PHPUnit\Framework\TestCase;

class DefaultCachePluginTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testPlugin()
    {
        $dic = $this->getDependencyInjectionMock($this);

        if (!\class_exists($dic->registry->cache->CACHE_CLASS)) {
            $this->markTestSkipped($dic->registry->cache->CACHE_CLASS . ' class required');
        }

        $plugin = new DefaultCachePlugin($dic);

        $this->assertInstanceOf($dic->registry->cache->CACHE_CLASS, $plugin->init());
    }
}

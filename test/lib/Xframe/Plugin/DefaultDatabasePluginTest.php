<?php

namespace Xframe\Plugin;

use PHPUnit\Framework\TestCase;

class DefaultDatabasePluginTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testPlugin()
    {
        $plugin = new DefaultDatabasePlugin($this->getDependencyInjectionMock($this));

        $this->assertInstanceOf('PDO', $plugin->init());
    }
}

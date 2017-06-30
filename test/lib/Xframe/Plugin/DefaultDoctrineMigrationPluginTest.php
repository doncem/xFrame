<?php

namespace Xframe\Plugin;

use PHPUnit\Framework\TestCase;

class DefaultDoctrineMigrationPluginTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testPlugin()
    {
        $plugin = new DefaultDoctrineMigrationPlugin($this->getDependencyInjectionMock($this));

        $this->assertInstanceOf('Symfony\Component\Console\Application', $plugin->init());
    }
}

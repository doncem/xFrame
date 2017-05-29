<?php

namespace Xframe\View;

use PHPUnit\Framework\TestCase;

class PhptalViewTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testView()
    {
        if (!class_exists('PHPTAL')) {
            $this->markTestSkipped('PHPTAL class required');
        }

        $view = new PhptalView(
            $this->getRegistryMock($this),
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
            '',
            'test'
        );

        $this->assertEquals('<h1>Test</h1>' . PHP_EOL, $view->execute());
    }
}

<?php

namespace Xframe\View;

use PHPUnit\Framework\TestCase;

class PhpViewTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testView()
    {
        $view = new PhpView(
            $this->getRegistryMock($this),
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
            '',
            'test'
        );

        $this->assertEquals('<h1>Test</h1>' . PHP_EOL, $view->execute());
    }
}

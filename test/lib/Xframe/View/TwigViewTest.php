<?php

namespace Xframe\View;

use PHPUnit\Framework\TestCase;

class TwigViewMock extends TwigView
{
    public function disableCache()
    {
        $this->twig->setCache(false);
    }
}

class TwigViewTest extends TestCase
{
    use \Xframe\Fixtures;

    public function testView()
    {
        $view = new TwigViewMock(
            $this->getRegistryMock($this),
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
            '',
            'test',
            true
        );
        $view->disableCache();

        $this->assertEquals('<h1>Test</h1>' . PHP_EOL, $view->execute());
    }
}

<?php

namespace Xframe;

use PHPUnit\Framework\TestCase;
use Xframe\Registry\RequestRegistry;

class RegistryTest extends TestCase
{
    private $root;

    protected function setUp()
    {
        $this->root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
    }

    public function testLoadIniFile()
    {
        Registry::load($this->root . 'config' . DIRECTORY_SEPARATOR . 'valid.ini');
        Registry::load('valid.ini', 'test' . DIRECTORY_SEPARATOR . 'config');

        $this->assertTrue(true);
    }

    public function testFileNotFoundLoadFailure()
    {
        $context = 'test' . DIRECTORY_SEPARATOR . 'config';
        $registry = Registry::load('valid', $context);

        $this->assertEquals(RequestRegistry::AUTO_REBUILD, $registry->request->AUTO_REBUILD);
    }

    public function testLoadFailure()
    {
        $registry = Registry::load('invalid.ini', 'test' . DIRECTORY_SEPARATOR . 'config');

        $this->assertEquals(RequestRegistry::AUTO_REBUILD, $registry->request->AUTO_REBUILD);
    }
}

<?php

namespace Xframe;

use PHPUnit\Framework\TestCase;

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

        $this->expectException('Exception');
        $this->expectExceptionMessage('File "' . $context . DIRECTORY_SEPARATOR . 'valid" could not be found.');

        Registry::load('valid', $context);
    }

    public function testLoadFailure()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Could not process ini file');

        Registry::load('invalid.ini', 'test' . DIRECTORY_SEPARATOR . 'config');
    }
}

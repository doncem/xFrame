<?php

namespace Xframe\Registry;

use PHPUnit\Framework\TestCase;

class RegistryTest extends TestCase
{
    /**
     * @var Registry
     */
    private $registry;

    private $root;

    protected function setUp()
    {
        $this->registry = new Registry();
        $this->root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
    }

    public function testLoadIniFile()
    {
        $this->registry->load($this->root . 'config' . DIRECTORY_SEPARATOR . 'valid.ini');
        $this->registry->load('valid.ini', 'test' . DIRECTORY_SEPARATOR . 'config');
        $this->assertTrue(true);
    }

    public function testFileNotFoundLoadFailure()
    {
        $context = 'test' . DIRECTORY_SEPARATOR . 'config';

        $this->expectException('Exception');
        $this->expectExceptionMessage('File "' . $context . DIRECTORY_SEPARATOR . 'valid" could not be found.');
        $this->registry->load('valid', $context);
    }

    public function testLoadFailure()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessage('Could not process ini file');
        $this->registry->load('invalid.ini', 'test' . DIRECTORY_SEPARATOR . 'config');
    }

    public function testSingleValue()
    {
        $this->registry->set('key', 'value');
        $this->assertEquals('value', $this->registry->get('key'));
    }
}

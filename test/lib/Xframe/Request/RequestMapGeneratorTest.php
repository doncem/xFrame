<?php

namespace Xframe\Request;

use Minime\Annotations\Cache\ArrayCache;
use PHPUnit\Framework\TestCase;
use Xframe\Core\DependencyInjectionContainer;

class RequestMapGeneratorMock extends RequestMapGenerator
{
    protected function getAnnotationReader()
    {
        $reader = parent::getAnnotationReader();

        $reader->setCache(new ArrayCache());

        return $reader;
    }
}

class RequestMapGeneratorTest extends TestCase
{
    use \Xframe\Fixtures;

    /**
     * @var DependencyInjectionContainer;
     */
    private $dic;

    /**
     * @var RequestMapGenerator
     */
    private $generator;

    protected function setUp()
    {
        $this->dic = $this->getDependencyInjectionMock($this);
        $this->generator = new RequestMapGeneratorMock($this->dic);
    }

    public function testStopImmediatelly()
    {
        $this->assertEmpty($this->generator->scan(__FILE__));
    }

    public function testVeryWrongDirectory()
    {
        //$this->expectOutputRegex("/lib\\Xframe\\Request\\..\\Fixtures does not exist/m");

        $this->assertEmpty($this->generator->scan(__DIR__ . DIRECTORY_SEPARATOR . '..'));
    }

    public function testWrongDirectory()
    {
        $this->assertEmpty($this->generator->scan($this->dic->root . 'lib'));
    }
}

<?php

namespace Xframe\Registry;

use PHPUnit\Framework\TestCase;

class AbstractRegistryTest extends TestCase
{
    public function testAbstractRegistry()
    {
        $registry = new CacheRegistry();

        $this->assertNull($registry->SMTH);
        $this->assertFalse($registry->ENABLED);

        $registry->ENABLED = true;

        $this->assertTrue($registry->ENABLED);
    }
}

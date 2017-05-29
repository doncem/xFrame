<?php

namespace Xframe;

use PHPUnit\Framework\TestCase;

trait Fixtures
{
    private function getMock(TestCase $case, string $classname)
    {
        $mock = new \PHPUnit_Framework_MockObject_MockBuilder($case, $classname);

        return $mock->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
    }

    public function chooseRegistry(string $key)
    {
        switch ($key) {
            case 'AUTO_REBUILD_TWIG':
                $value = true;
                break;
            case 'CACHE_ENABLED':
                $value = false;
                break;
            default:
                $value = null;
                break;
        }

        return $value;
    }

    public function getRegistryMock(TestCase $case)
    {
        $registry = $this->getMock($case, 'Xframe\Registry\Registry');

        $registry->method('get')->will($this->returnCallback([$this, 'chooseRegistry']));

        return $registry;
    }
}

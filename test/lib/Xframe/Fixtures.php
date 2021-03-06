<?php

namespace Xframe;

use PHPUnit\Framework\TestCase;
use Xframe\Request\Controller;
use Xframe\Request\Request;

trait Fixtures
{
    /**
     * @var TestCase
     */
    private $testCase;

    private function getMock(TestCase $case, string $classname)
    {
        return $case->getMockBuilder($classname)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();
    }

    public function chooseDicLambda(string $lambda)
    {
        switch ($lambda) {
            case 'database':
                $return = $this->getMock($this->testCase, 'PDO');
                $return->method('getAttribute')->willReturn('sqlite');

                break;
            case 'doctrineCache':
                $return = $this->getMock($this->testCase, 'Doctrine\Common\Cache\Cache');

                break;
            case 'em':
                $return = $this->getMock($this->testCase, 'Doctrine\ORM\EntityManager');
                $return->method('getConnection')->willReturn($this->getMock($this->testCase, 'Doctrine\DBAL\Connection'));

                break;

            case 'isDev':
                $return = false;

                break;
            case 'registry':
                $return = $this->getRegistryMock($this->testCase);

                break;
            case 'root':
                $return = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

                break;
            case 'tmp':
                $return = $this->chooseDicLambda('root') . 'tmp' . DIRECTORY_SEPARATOR;

                break;
            default:
                $return = null;

                break;
        }

        return $return;
    }

    public function chooseRegistry(string $key)
    {
        switch ($key) {
            case 'AUTO_REBUILD':
                $value = true;

                break;
            case 'CACHE_CLASS':
                $value = '\\Memcached';

                break;
            case 'ENABLED':
                $value = false;

                break;
            case 'DEFAULT_VIEW':
                $value = 'Xframe\View\JsonView';

                break;
            case 'ENGINE':
                $value = 'memory';

                break;
            case 'PREFIX':
                $value = 'test';

                break;
            default:
                $value = null;

                break;
        }

        return $value;
    }

    public function runPrefilter(Request $request, Controller $controller)
    {
        return false;
    }

    public function validate($value)
    {
        switch ($value) {
            case 'pass':
                $return = true;

                break;
            case 'fail':
            default:
                $return = false;

                break;
        }

        return $return;
    }

    public function getDependencyInjectionMock(TestCase $case)
    {
        $this->testCase = $case;

        $dic = $this->createMock('Xframe\Core\DependencyInjectionContainer');

        $dic->method('__get')->will($this->returnCallback([$this, 'chooseDicLambda']));

        return $dic;
    }

    public function getPrefilterMock(TestCase $case)
    {
        $prefilter = $this->getMock($case, 'Xframe\Request\Prefilter');
        $prefilter->method('run')->will($this->returnCallback([$this, 'runPrefilter']));

        return $prefilter;
    }

    public function getRegistryMock(TestCase $case)
    {
        $registry = $this->getMock($case, 'Xframe\Registry');
        $container = $this->getMock($case, 'Xframe\Container');

        $registry->method('__get')->willReturn($container);

        $container->method('__get')->will($this->returnCallback([$this, 'chooseRegistry']));

        return $registry;
    }

    public function getRequestMock(TestCase $case)
    {
        $request = $this->getMock($case, 'Xframe\Request\Request');

        $request->method('getRequestedResource')->willReturn('test');

        return $request;
    }

    public function getValidatorMock(TestCase $case)
    {
        $validator = $this->getMock($case, 'Xframe\Validation\Validator');

        $validator->method('validate')->will($this->returnCallback([$this, 'validate']));

        return $validator;
    }
}

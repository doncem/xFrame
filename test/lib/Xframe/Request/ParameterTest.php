<?php

namespace Xframe\Request;

use PHPUnit\Framework\TestCase;

class ParameterTest extends TestCase
{
    use \Xframe\Fixtures;

    /**
     * @var Parameter
     */
    private $parameter;

    /**
     * @var Parameter
     */
    private $parameterWithValidator;

    protected function setUp()
    {
        $this->parameter = new Parameter('name', null, false, 'test');
        $this->parameterWithValidator = new Parameter('name', $this->getValidatorMock($this), false, 'test');
    }

    public function testObject()
    {
        $this->assertEquals('name', $this->parameter->getName());
        $this->assertEquals('test', $this->parameter->getDefault());
        $this->assertFalse($this->parameter->isRequired());
    }

    public function testValidityWithNoValidator()
    {
        $this->assertTrue($this->parameter->validate('value'));
    }

    public function testValidityWithValidator()
    {
        $this->assertTrue($this->parameterWithValidator->validate('pass'));
    }

    public function testFailedValidity()
    {
        $this->expectException('Exception');
        $this->expectExceptionMessageRegExp('/^Value \'fail\' is not valid for parameter \'name\' using validator/');

        $this->parameterWithValidator->validate('fail');
    }
}

<?php

namespace Xframe\Validation;

/**
 * Test class for Digit.
 */
class DigitTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $validator = new Digit(1, 100);

        $this->assertTrue($validator->validate(50));
        $this->assertTrue($validator->validate('50'));
        $this->assertFalse($validator->validate(0));
        $this->assertFalse($validator->validate(101));
        $this->assertFalse($validator->validate('A'));

        $validator = new Digit();

        $this->assertTrue($validator->validate('1'));
        $this->assertFalse($validator->validate('A'));
    }
}

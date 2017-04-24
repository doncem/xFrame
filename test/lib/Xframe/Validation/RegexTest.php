<?php

namespace Xframe\Validation;

/**
 * Test class for Regex.
 */
class RegexTest extends \PHPUnit_Framework_TestCase
{
    public function testValidate()
    {
        $validator = new RegEx('/u[0-9]{3}[a-z]/i');

        $this->assertTrue($validator->validate('U123a'));
        $this->assertFalse($validator->validate('U1234'));
    }
}

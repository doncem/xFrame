<?php

namespace Xframe\Validation;

use PHPUnit\Framework\TestCase;

/**
 * Test class for Regex.
 */
class RegexTest extends TestCase
{
    public function testValidate()
    {
        $validator = new Regex('/u[0-9]{3}[a-z]/i');

        $this->assertTrue($validator->validate('U123a'));
        $this->assertFalse($validator->validate('U1234'));
    }
}

<?php

namespace Xframe\Authentication;

use PHPUnit\Framework\TestCase;

class ResultTest extends TestCase
{
    public function testMessageAddition()
    {
        $result = new Result();

        $this->assertEmpty($result->getMessages());

        $result->addMessage('test');

        $this->assertContains('test', $result->getMessages());
    }

    public function testIsValidFlag()
    {
        $result = new Result();
        $this->assertFalse($result->isValid());

        $result->setCode(Result::GENERAL_FAILURE);
        $this->assertFalse($result->isValid());

        $result->setCode(Result::IDENTITY_NOT_FOUND);
        $this->assertFalse($result->isValid());

        $result->setCode(Result::INVALID_CREDENTIAL);
        $this->assertFalse($result->isValid());

        $result->setCode(Result::AMBIGUOUS_IDENTITY);
        $this->assertFalse($result->isValid());

        $result->setCode(Result::SUCCESS);
        $this->assertTrue($result->isValid());
    }
}

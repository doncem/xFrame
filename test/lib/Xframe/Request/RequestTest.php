<?php

namespace Xframe\Request;

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /**
     * @var Request
     */
    private $request;

    protected function setUp()
    {
        $this->request = new Request('/request?q=asd');
    }

    public function testGetMappedParameters()
    {
        $this->assertEquals([], $this->request->getMappedParameters());
    }

    public function testSetMappedParameters()
    {
        $params = ['manual' => 'test'];

        $this->request->setMappedParameters($params);

        $this->assertEquals($params, $this->request->getMappedParameters());
    }

    public function testGetRequestedResource()
    {
        $this->assertEquals('request', $this->request->getRequestedResource());
    }

    public function testHash()
    {
        $this->assertEquals('10573b873d2fa5a365d558a45e328e47', $this->request->hash());
    }
}

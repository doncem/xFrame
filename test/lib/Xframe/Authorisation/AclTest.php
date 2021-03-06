<?php

namespace Xframe\Authorisation;

use PHPUnit\Framework\TestCase;

/**
 * Test class for Acl.
 */
class AclTest extends TestCase
{
    /**
     * @var Acl
     */
    protected $acl;

    protected function setUp()
    {
        $this->acl = new Acl();
        $this->acl->addResource('public')
                  ->addResource('su panel')
                  ->addResource('control panel', 'su panel')
                  ->addRole('anon')
                  ->addRole('user', 'anon')
                  ->addRole('admin', 'user');
    }

    public function testIsAllowed()
    {
        $this->assertFalse($this->acl->isAllowed('anon', 'public'));
        $this->acl->allow('anon', 'public');
        $this->assertTrue($this->acl->isAllowed('anon', 'public'));
        $this->assertTrue($this->acl->isAllowed('user', 'public'));
        $this->assertTrue($this->acl->isAllowed('admin', 'public'));

        $this->acl->deny('anon', 'public');
        $this->assertFalse($this->acl->isAllowed('anon', 'public'));
        $this->assertFalse($this->acl->isAllowed('user', 'public'));
        $this->assertFalse($this->acl->isAllowed('admin', 'public'));

        $this->acl->allowAll();
        $this->assertTrue($this->acl->isAllowed('anon', 'control panel'));
        $this->assertTrue($this->acl->isAllowed('user', 'control panel'));
        $this->assertTrue($this->acl->isAllowed('admin', 'control panel'));

        $this->acl->denyRole('user');
        $this->assertTrue($this->acl->isAllowed('anon', 'public'));
        $this->assertFalse($this->acl->isAllowed('user', 'public'));
        $this->assertFalse($this->acl->isAllowed('admin', 'public'));

        $this->acl->denyAll();
        $this->assertFalse($this->acl->isAllowed('anon', 'su panel'));
        $this->assertFalse($this->acl->isAllowed('user', 'su panel'));
        $this->assertFalse($this->acl->isAllowed('admin', 'su panel'));

        $this->acl->allowAll();
        $this->acl->denyResource('su panel');
        $this->assertTrue($this->acl->isAllowed('anon', 'public'));
        $this->assertTrue($this->acl->isAllowed('user', 'public'));
        $this->assertTrue($this->acl->isAllowed('admin', 'public'));
        $this->assertFalse($this->acl->isAllowed('anon', 'control panel'));
        $this->assertFalse($this->acl->isAllowed('user', 'control panel'));
        $this->assertFalse($this->acl->isAllowed('admin', 'control panel'));
        $this->assertFalse($this->acl->isAllowed('anon', 'su panel'));
        $this->assertFalse($this->acl->isAllowed('user', 'su panel'));
        $this->assertFalse($this->acl->isAllowed('admin', 'su panel'));

        $this->acl->denyAll();
        $this->acl->allowRole('user');
        $this->assertFalse($this->acl->isAllowed('anon', 'su panel'));
        $this->assertTrue($this->acl->isAllowed('user', 'su panel'));
        $this->assertTrue($this->acl->isAllowed('admin', 'su panel'));

        $this->acl->denyAll();
        $this->acl->allowResource('su panel');
        $this->assertTrue($this->acl->isAllowed('anon', 'su panel'));
        $this->assertTrue($this->acl->isAllowed('user', 'control panel'));
        $this->assertFalse($this->acl->isAllowed('admin', 'public'));

        $this->acl->denyAll();
        $this->acl->allow('user', 'su panel');
        $this->assertFalse($this->acl->isAllowed('anon', 'su panel'));
        $this->assertFalse($this->acl->isAllowed('anon', 'su panel'));
        $this->assertTrue($this->acl->isAllowed('user', 'su panel'));
        $this->assertTrue($this->acl->isAllowed('user', 'control panel'));
        $this->assertTrue($this->acl->isAllowed('admin', 'su panel'));
        $this->assertTrue($this->acl->isAllowed('admin', 'control panel'));
    }
}

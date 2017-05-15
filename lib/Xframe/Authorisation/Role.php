<?php

namespace Xframe\Authorisation;

/**
 * Represent a role in the Acl. Children and parents can be attached to form a hierarchy.
 *
 * @package authorisation
 */
class Role
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var \stdClass
     */
    protected $children;

    /**
     * @param string         $name
     * @param \stdClass|null $children
     */
    public function __construct($name, \stdClass $children = null)
    {
        $this->name = $name;
        $this->children = null === $children ? new \stdClass() : $children;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \stdClass
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Adds a child role to this role.
     *
     * @param Role $role
     *
     * @return Role
     */
    public function addChild(Role $role)
    {
        $this->children->{$role->getName()} = $role;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}

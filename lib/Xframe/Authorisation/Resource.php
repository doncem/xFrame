<?php

namespace Xframe\Authorisation;

/**
 * Represent a resource in the Acl. Children and parents can be attached to form a hierarchy.
 *
 * @package authorisation
 */
class Resource
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
     * Adds a child resource to this resource.
     *
     * @param Xframe\Authorisation\Resource $resource
     *
     * @return Xframe\Authorisation\Resource
     */
    public function addChild(Resource $resource)
    {
        $this->children->{$resource->getName()} = $resource;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}

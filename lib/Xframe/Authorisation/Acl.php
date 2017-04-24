<?php

namespace Xframe\Authorisation;

/**
 * Class to manage and query an Access Control List.
 *
 * @package authorisation
 */
class Acl
{
    protected $role = [];
    protected $resource = [];
    protected $rule = [];

    /**
     * Add a role.
     *
     * @param Role|string $role
     * @param Role|string $parent
     *
     * @return Acl
     */
    public function addRole($role, $parent = null)
    {
        if (\is_string($role)) {
            $role = new Role($role);
        }

        if (\is_string($parent)) {
            $parent = new Role($parent);
        }

        if ($parent) {
            $this->role[(string) $parent]->addChild($role);
        }

        $this->role[(string) $role] = $role;

        return $this;
    }

    /**
     * Add a resource.
     *
     * @param Xframe\Authorisation\Resource|string $resource
     * @param Xframe\Authorisation\Resource|string $parent
     *
     * @return Acl
     */
    public function addResource($resource, $parent = null)
    {
        if (\is_string($resource)) {
            $resource = new Resource($resource);
        }

        if (\is_string($parent)) {
            $parent = new Resource($parent);
        }

        if ($parent) {
            $this->resource[(string) $parent]->addChild($resource);
        }

        $this->resource[(string) $resource] = $resource;

        return $this;
    }

    /**
     * Allow a role access to a resource.
     *
     * @param string $role
     * @param string $resource
     *
     * @return Acl
     */
    public function allow($role, $resource)
    {
        $this->rule[(string) $resource][(string) $role] = true;

        foreach ($this->role[(string) $role]->getChildren() as $cRole) {
            $this->allow($cRole, $resource);
        }
        foreach ($this->resource[(string) $resource]->getChildren() as $cResource) {
            $this->allow($role, $cResource);
        }

        return $this;
    }

    /**
     * Remove access to a resource for a role.
     *
     * @param string $role
     * @param string $resource
     *
     * @return Acl
     */
    public function deny($role, $resource)
    {
        unset($this->rule[(string) $resource][(string) $role]);

        foreach ($this->role[(string) $role]->getChildren() as $cRole) {
            $this->deny($cRole, $resource);
        }

        return $this;
    }

    /**
     * Check if a role is allowed access to a resource.
     *
     * @param string $role
     * @param string $resource
     */
    public function isAllowed($role, $resource)
    {
        return isset($this->rule[(string) $resource][(string) $role]);
    }

    /**
     * Allow access to all roles for a resource.
     *
     * @param string $resource
     *
     * @return Acl
     */
    public function allowResource($resource)
    {
        foreach ($this->role as $role) {
            $this->rule[(string) $resource][(string) $role] = true;
        }

        foreach ($this->resource[(string) $resource]->getChildren() as $cResource) {
            $this->allowResource($cResource);
        }

        return $this;
    }

    /**
     * Allow role to access all resources.
     *
     * @param string $role
     *
     * @return Acl
     */
    public function allowRole($role)
    {
        foreach ($this->resource as $resource) {
            $this->rule[(string) $resource][(string) $role] = true;
        }

        foreach ($this->role[(string) $role]->getChildren() as $cRole) {
            $this->allowRole($cRole);
        }

        return $this;
    }

    /**
     * Allows access to all resources for all roles.
     *
     * @return Acl
     */
    public function allowAll()
    {
        foreach ($this->role as $role) {
            foreach ($this->resource as $resource) {
                $this->rule[(string) $resource][(string) $role] = true;
            }
        }

        return $this;
    }

    /**
     * Denies access to all resources for all roles.
     *
     * @return Acl
     */
    public function denyAll()
    {
        $this->rule = [];

        return $this;
    }

    /**
     * Denys all access to a resource.
     *
     * @param string $resource
     *
     * @return Acl
     */
    public function denyResource($resource)
    {
        unset($this->rule[(string) $resource]);

        foreach ($this->resource[(string) $resource]->getChildren() as $cResource) {
            $this->denyResource($cResource);
        }

        return $this;
    }

    /**
     * Denys to all resources for a role.
     *
     * @param mixed $role
     *
     * @return Acl
     */
    public function denyRole($role)
    {
        foreach ($this->rule as &$res) {
            unset($res[(string) $role]);
        }

        foreach ($this->role[(string) $role]->getChildren() as $cRole) {
            $this->denyRole($cRole);
        }

        return $this;
    }
}

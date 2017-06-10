<?php

namespace Xframe;

/**
 * Container for arbitrary data, provides __get and __set methods can easily
 * be used as a dependency injection container or generic model/data container.
 *
 * @package util
 */
class Container
{
    /**
     * Associative array storing container attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * @param array $defaults
     */
    public function __construct(array $defaults = [])
    {
        $this->attributes = &$defaults;
    }

    /**
     * Return the given property.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (\array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
    }

    /**
     * Set the given property.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Returns true of the given property is set.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Unset the given variable.
     *
     * @param mixed $key
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * Provides support for getters and setters.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        $action = \mb_substr($method, 0, 3);
        $property = \lcfirst(\mb_substr($method, 3));

        switch ($action) {
            case 'get':
                return $this->__get($property);
            case 'set':
                $this->__set($property, \current($arguments));
        }
    }
}

<?php

namespace Xframe\Core;

use Xframe\Util\Container;

/**
 * Used to store the applications dependencies.
 *
 * @package core
 */
class DependencyInjectionContainer extends Container
{
    /**
     * Stores the lambda functions that get the dependencies.
     *
     * @var array
     */
    protected $builders;

    /**
     * Constructor.
     *
     * @param array $attributes
     * @param array $builders
     */
    public function __construct(array $attributes = [], array $builders = [])
    {
        parent::__construct($attributes);

        $this->builders = $builders;
    }

    /**
     * Add a lambda function that returns a dependency.
     *
     * @param string    $name
     * @param \Callback $lambda
     */
    public function add($name, $lambda)
    {
        $this->builders[$name] = $lambda;
    }

    /**
     * If the requested dependency has not been set, if we have a lambda
     * to create it do so then return the dependency.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (!isset($this->attributes[$name]) && isset($this->builders[$name])) {
            $this->attributes[$name] = $this->builders[$name]($this);
        }

        return $this->attributes[$name];
    }
}

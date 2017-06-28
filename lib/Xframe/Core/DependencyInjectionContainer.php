<?php

namespace Xframe\Core;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Memcache;
use PDO;
use Xframe\Container;
use Xframe\Exception\ErrorHandler;
use Xframe\Exception\ExceptionHandler;
use Xframe\Registry;
use Xframe\Request\FrontController;

/**
 * Used to store the applications dependencies.
 *
 * @property ErrorHandler $errorHandler
 * @property ExceptionHandler $exceptionHandler
 * @property FrontController $frontController
 * @property Registry $registry
 * @property PDO $database
 * @property Container $plugin
 * @property Memcache $cache
 * @property EntityManager $em
 * @property EventManager $evm
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

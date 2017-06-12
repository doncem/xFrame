<?php

namespace Xframe\Plugin;

use Xframe\Core\DependencyInjectionContainer;

/**
 * Abstract class to allow developers to create classes accessible via the.
 *
 * @package plugin
 */
abstract class AbstractPlugin
{
    /**
     * @var DependencyInjectionContainer
     */
    protected $dic;

    public function __construct(DependencyInjectionContainer $dic)
    {
        $this->dic = $dic;
    }

    /**
     * Abstract function to be implemented by each plugin.
     */
    abstract public function init();
}

<?php

namespace Xframe\Request;

use Xframe\Core\DependencyInjectionContainer;

/**
 * The prefilter runs before a request is executed, it can used to provide authentication and other goodies.
 *
 * @package request
 */
abstract class Prefilter
{
    /**
     * @var DependencyInjectionContainer
     */
    protected $dic;

    /**
     * @param DependencyInjectionContainer $dic
     */
    public function __construct(DependencyInjectionContainer $dic)
    {
        $this->dic = $dic;
    }

    abstract public function run(Request $request, Controller $controller);
}

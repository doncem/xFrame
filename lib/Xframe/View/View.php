<?php

namespace Xframe\View;

use Xframe\Util\Container;

/**
 * This abstract class specifies the requirements for a view.
 *
 * @package view
 */
abstract class View extends Container
{
    protected $parameters;
    protected $exceptions;

    /**
     * Initialise the view.
     */
    public function __construct()
    {
        parent::__construct();

        $this->parameters = [];
        $this->exceptions = [];
    }

    /**
     * Add a parameter to the view for the template.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    /**
     * Adds the given exception to the page.
     *
     * @param FrameEx $ex exception to add to the view
     */
    public function addException(FrameEx $ex)
    {
        $this->exceptions[] = $ex;
    }

    /**
     * Clears the exceptions on the page.
     */
    public function clearExceptions()
    {
        $this->exceptions = [];
    }

    /**
     * Generate the contents of the page response.
     */
    abstract public function execute();
}

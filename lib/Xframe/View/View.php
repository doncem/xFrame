<?php

namespace Xframe\View;

use Xframe\Container;

/**
 * This abstract class specifies the requirements for a view.
 *
 * @package view
 */
abstract class View extends Container
{
    /**
     * Generate the contents of the page response.
     */
    abstract public function execute();
}

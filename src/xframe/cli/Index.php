<?php

namespace xframe\cli;

use Xframe\Request\Controller;

/**
 * Endpoint for the xFrame CLI, displays help.
 */
class Index extends Controller
{
    /**
     * @Request("cli-index")
     */
    public function run()
    {
    }
}

<?php

namespace Xframe\Request\Prefilter;

use Exception;
use Xframe\Request\Controller;
use Xframe\Request\Prefilter;
use Xframe\Request\Request;

/**
 * Forces a request to be performed on the CLI.
 *
 * @package request/prefilter
 */
class ForceCLI extends Prefilter
{
    /**
     * Checks if the current request is being made on the cli and throws an Exception if not.
     *
     * @param Request    $request
     * @param Controller $controller
     *
     * @throws Exception
     */
    public function run(Request $request, Controller $controller)
    {
        if (!$request->cli) {
            \trigger_error('This request must be performed on the CLI.', E_USER_ERROR);
        }
    }
}

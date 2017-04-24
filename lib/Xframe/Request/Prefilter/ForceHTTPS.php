<?php

namespace Xframe\Request\Prefilter;

use Xframe\Request\Controller;
use Xframe\Request\Prefilter;
use Xframe\Request\Request;

/**
 * Forces a web request to be over https.
 *
 * @package request/prefilter
 */
class ForceHTTPS extends Prefilter
{
    /**
     * Checks if the current request is secure and redirects to a secure protocol if not.
     *
     * @param Request    $request
     * @param Controller $controller
     */
    public function run(Request $request, Controller $controller)
    {
        // if its not a HTTPS or CLI request, redirect
        if (!$request->https && !$request->cli) {
            $controller->redirect('https://' . $request->server['SERVER_NAME'] . $request->server['REQUEST_URI']);
        }
    }
}

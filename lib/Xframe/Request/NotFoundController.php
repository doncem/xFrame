<?php

namespace Xframe\Request;

/**
 * Handles 404 requests.
 *
 * @package request
 */
class NotFoundController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Send back a 404 response.
     */
    public function handleRequest()
    {
        if (PHP_SAPI !== 'cli') {
            \header(\filter_input(INPUT_SERVER, 'SERVER_PROTOCOL') . ' 404 Not Found');
        }

        die('Resource: ' . $this->request->getRequestedResource() . ' not found.' . PHP_EOL);
    }
}

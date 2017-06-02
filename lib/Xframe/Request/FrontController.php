<?php

namespace Xframe\Request;

use Xframe\Core\DependencyInjectionContainer;

/**
 * This encapsulates a given request. Usually this object will be routed
 * through the front controller and handled by a request controller.
 *
 * @package request
 */
class FrontController
{
    /**
     * Stores the root directory and provides access to the database handle.
     *
     * @var DependencyInjectionContainer
     */
    private $dic;

    /**
     * Default handler for 404 requests.
     *
     * @var Controller
     */
    private $notFoundController;

    /**
     * Setup the initial state.
     *
     * @param DependencyInjectionContainer $dic
     * @param Controller|null              $notFoundController
     */
    public function __construct(DependencyInjectionContainer $dic,
                                Controller $notFoundController = null)
    {
        $this->dic = $dic;
        $this->notFoundController = $notFoundController;
    }

    /**
     * Dispatches the given request to it's controller.
     *
     * @param Request $request
     */
    public function dispatch(Request $request)
    {
        $filename = $this->dic->tmp . $request->getRequestedResource() . '.php';

        //if we have a mapping for the request
        if (\file_exists($filename)) {
            //return the response from the controller
            $controller = require $filename;
        } elseif ($this->dic->registry->get('AUTO_REBUILD_REQUEST_MAP')) {
            $this->rebuildRequestMap();
            $filename = $this->dic->tmp . $request->getRequestedResource() . '.php';

            //try again, in case it has just been added
            if (\file_exists($filename)) {
                $controller = require $filename;
            }
        }

        // if we still don't have a controller 404 it
        if (!isset($controller)) {
            $controller = $this->get404Controller($request);
        }

        $controller->handleRequest($request);
    }

    /**
     * @param Request $request
     *
     * @return Controller
     */
    public function get404Controller(Request $request)
    {
        if (null === $this->notFoundController) {
            $this->notFoundController = new NotFoundController($request);
        }

        return $this->notFoundController;
    }

    protected function rebuildRequestMap()
    {
        $mapper = new RequestMapGenerator($this->dic);

        $mapper->scan($this->dic->root . 'src');
    }
}

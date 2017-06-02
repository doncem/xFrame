<?php

namespace Xframe\Request;

use Exception;
use Xframe\Util\Container;

/**
 * This encapsulates a given request. Usually this object will be routed
 * through the front controller and handled by a request controller.
 *
 * @package request
 */
class Request extends Container
{
    private $requestedResource;
    private $mappedParameters;

    public $server;
    public $https;
    public $cli;
    public $files;
    public $cookie;

    /**
     * @param string $requestURI
     * @param array  $parameters
     */
    public function __construct($requestURI, array $parameters = [])
    {
        $request = $requestURI;
        //see if there is a query attached
        $end = \mb_strpos($request, '?');
        //remove the query from the request
        $request = false === $end ? \mb_substr($request, 1) : \mb_substr($request, 1, $end - 1);
        //check for blank request
        $request = '' === $request ? 'index' : $request;
        //support for urls with request/param/param
        $request = \explode('/', $request);
        //get the request name
        $this->requestedResource = $request[0];
        //get the parameters out of the request URI
        $this->mappedParameters = \array_slice($request, 1);
        //store the other params (usually from $_REQUEST)
        parent::__construct($parameters);

        //get the $_FILES array
        $this->files = &$_FILES;
        $this->cookie = \filter_input_array(INPUT_COOKIE);
        $this->server = \filter_input_array(INPUT_SERVER);
        $this->https = isset($this->server['HTTPS']) || isset($this->server['HTTP_X_SECURE']);
        $this->cli = 'cli' === PHP_SAPI;
    }

    /**
     * Apply the given parameter map to this request.
     * Loop over the parameters from the request URI and inject them into the
     * parameters given in the constructor (usually these come from $_REQUEST).
     *
     * @param array $map
     */
    public function applyParameterMap(array &$map)
    {
        // use the given map to put the parameters in an associative array
        foreach ($map as $i => $parameter) {
            // if there is no key value for this param, throw an exception
            if ($parameter->isRequired() && !isset($this->mappedParameters[$i])) {
                throw new Exception("Parameter #{$i}({$parameter->getName()}) has not been provided and is required");
            }

            // if there is no value, try to get the default value
            if (!isset($this->mappedParameters[$i])) {
                $this->mappedParameters[$i] = $parameter->getDefault();
            }

            // pass the parameter through the request validation
            $parameter->validate($this->mappedParameters[$i]);

            // add the parameter
            $this->attributes[$parameter->getName()] = $this->mappedParameters[$i];
        }
    }

    /**
     * @return array
     */
    public function getMappedParameters()
    {
        return $this->mappedParameters;
    }

    /**
     * @param array $parameters
     */
    public function setMappedParameters(array &$parameters)
    {
        $this->mappedParameters = &$parameters;
    }

    /**
     * @return string
     */
    public function getRequestedResource()
    {
        return $this->requestedResource;
    }

    /**
     * Add the given parameters to the request.
     *
     * @param array $parameters
     */
    public function addParameters(array $parameters)
    {
        $this->attributes = \array_merge($this->attributes, $parameters);
    }

    /**
     * Return a hash of the Request.
     */
    public function hash()
    {
        return \md5($this->requestedResource .
            \implode($this->mappedParameters) .
            \implode(\array_keys($this->mappedParameters)));
    }
}

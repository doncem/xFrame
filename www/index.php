<?php

/*
 * Welcome to xFrame. This file is the entry point for the front controller.
 * It registers the autoloader, boots the framework and dispatches the request.
 */

$root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
$loader = require $root . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$loader->addPsr4('Xframe\\', $root . 'lib' . DIRECTORY_SEPARATOR . 'Xframe');

use Xframe\Autoloader\Autoloader;
use Xframe\Core\System;
use Xframe\Request\Request;

$autoloader = new Autoloader($root);
$autoloader->register();

$system = new System($root, $_SERVER['CONFIG']);
$system->boot();

$request = new Request($_SERVER['REQUEST_URI'], $_REQUEST);
$system->getFrontController()->dispatch($request);

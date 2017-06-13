<?php

/*
 * Welcome to xFrame. This file is the entry point for the front controller.
 * It registers the autoloader, boots the framework and dispatches the request.
 */

$root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
$loader = require $root . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$loader->addPsr4('Demo\\', $root . 'src' . DIRECTORY_SEPARATOR . 'Demo');
$loader->addPsr4('Xframe\\', $root . 'lib' . DIRECTORY_SEPARATOR . 'Xframe');

use Xframe\Core\System;
use Xframe\Request\Request;

$system = new System($root, \filter_input(INPUT_SERVER, 'CONFIG'));
$system->boot();

$request = new Request(\filter_input(INPUT_SERVER, 'REQUEST_URI'), $_REQUEST);
$system->getFrontController()->dispatch($request);

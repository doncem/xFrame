<?php

use Xframe\Autoloader\Autoloader;
use Xframe\Core\System;
use Xframe\Request\Request;

/*
 * Welcome to xFrame. This file is the entry point for the cli. It takes the
 * command line parameters and hacks them into the $_SERVER and $_REQUEST
 * variables so the standard front controller can use them.
 */

$_SERVER['REQUEST_URI'] = isset($argv[1]) ? '/' . \str_replace('--', '', $argv[1]) : '/cli-index';
$_REQUEST = [];
$params = [];

// process the cli arguments
for ($i = 2; $i < \count($argv); ++$i) {
    if (false === \mb_strpos($argv[$i], '=')) {
        $params[] = $argv[$i];
    } else {
        $parts = \explode('=', $argv[$i]);
        $key = \str_replace('--', '', $parts[0]);
        $_REQUEST[$key] = $parts[1];
    }
}

$_SERVER['CONFIG'] = $_REQUEST['config'] ?? 'dev';

unset($_REQUEST['config']);

$root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

require $root . 'lib/Xframe/Autoloader/Autoloader.php';

$autoloader = new Autoloader($root);
$autoloader->register();

$system = new System($root, $_SERVER['CONFIG']);
$system->boot();

$request = new Request($_SERVER['REQUEST_URI'], $_REQUEST);
$request->setMappedParameters($params);
$system->getFrontController()->dispatch($request);

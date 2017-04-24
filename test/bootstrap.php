<?php

$root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

$autoloader = new Xframe\Autoloader\Autoloader($root);
$autoloader->register();

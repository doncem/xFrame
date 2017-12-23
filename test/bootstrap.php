<?php

$root = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

$loader = require $root . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$loader->addPsr4('Demo\\', $root . 'src' . DIRECTORY_SEPARATOR . 'Demo');

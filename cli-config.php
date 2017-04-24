<?php

use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Xframe\Autoloader\Autoloader;
use Xframe\Core\System;

$root = __DIR__ . DIRECTORY_SEPARATOR;

require_once $root . 'lib/xframe/autoloader/Autoloader.php';

//include addendum
require_once $root . 'lib/addendum/annotations.php';

$autoloader = new Autoloader($root);
$autoloader->register();

$system = new System($root, 'dev');
$system->boot();

$helperSet = new HelperSet([
    'em' => new EntityManagerHelper($system->em)
]);

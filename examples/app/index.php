<?php

namespace Loop54\API\Examples\App;

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../Config.php";

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$kernel = new AppKernel('dev', true);
$kernel->handle($request)->send();

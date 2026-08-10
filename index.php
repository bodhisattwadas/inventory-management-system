<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$basePath = __DIR__;

if (! file_exists($basePath.'/bootstrap/app.php') && file_exists($basePath.'/../bootstrap/app.php')) {
    $basePath = dirname(__DIR__);
}

if (! file_exists($basePath.'/bootstrap/app.php')) {
    http_response_code(500);
    exit('Laravel bootstrap file not found. Update $basePath in index.php to point to the application directory.');
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $basePath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $basePath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $basePath.'/bootstrap/app.php';

$app->handleRequest(Request::capture());

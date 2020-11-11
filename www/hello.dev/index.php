<?php

use App\App;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__.'/vendor/autoload.php';

$app = new App();
$app->boot();
$response = $app->handle();

$response->send();

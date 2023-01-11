<?php declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ERROR);

require './vendor/autoload.php';

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: *');

\App\Bootstrap::start();
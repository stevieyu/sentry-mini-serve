<?php

require './vendor/autoload.php';

$host = $_SERVER['HTTP_HOST'];

$request_protocol = 'http' . (($_SERVER['HTTPS'] ?? '') === 'on' ||
    ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https' ||
    ($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '') === 'on' ? 's' : '');

\Sentry\init(['dsn' => "$request_protocol://serve@$host/serve"]);

\App\Bootstrap::start();
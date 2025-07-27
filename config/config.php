<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__  . "/../");
$dotenv->load();

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USERNAME', $_ENV['DB_USERNAME']);
define('DB_DATABASE', $_ENV['DB_NAME']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

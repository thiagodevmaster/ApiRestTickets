<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('DB_DRIVE', DB_DRIVE);
define('DB_HOST', DB_HOST);
define('DB_USERNAME', DB_USERNAME);
define('DB_DATABASE', DB_DATABASE);
define('DB_PASSWORD', DB_PASSWORD);
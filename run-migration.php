<?php

require_once __DIR__ . '/vendor/autoload.php';

$host = 'mysql'; 
$dbname = 'cupons';
$user = 'thiago';
$pass = '123456';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
    exit(1);
}

$migrations = glob(__DIR__ . '/src/Database/Migrations/*.php');

foreach ($migrations as $file) {
    $migration = require $file;
    $migration($pdo);
    echo "Executed migration: " . basename($file) . PHP_EOL;
}

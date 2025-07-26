<?php

namespace App\Infra;

use PDO;
use PDOException;

require_once __DIR__ . "/config/config.php";

final class ConnectionFactory
{
    public static function CreateConnection(): PDO {
        try{
            $dbDrive = DB_DRIVE;
            $dbHost = DB_HOST;
            $dbName = DB_DATABASE;
            $dbUsername = DB_USERNAME;
            $dbPassword = DB_PASSWORD;

            $pdo = new PDO("$dbDrive:dbname=$dbName;host=$dbHost", $dbUsername, $dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $erro) {
            echo "ERRO => " . $erro->getMessage() . PHP_EOL;
            exit;
        }

        return $pdo;
    }
}
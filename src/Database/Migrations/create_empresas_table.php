<?php

return function (PDO $pdo) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS empresas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            razao_social VARCHAR(255) NOT NULL,
            nome_fantasia VARCHAR(255) NOT NULL,
            cnpj VARCHAR(18) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            last_access TIMESTAMP NULL DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
};


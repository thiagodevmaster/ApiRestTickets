<?php

return function(PDO $pdo) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cupons_usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cupom_id INT NOT NULL,
            usuario_id INT NOT NULL,
            quantidade_uso INT DEFAULT 1,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (cupom_id, usuario_id),
            FOREIGN KEY (cupom_id) REFERENCES cupons(id) ON DELETE CASCADE
        )
    ");
};
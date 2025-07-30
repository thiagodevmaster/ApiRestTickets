<?php

return function (PDO $pdo) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cupons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            empresa_id INT NOT NULL,
            codigo VARCHAR(50) NOT NULL UNIQUE,
            descricao TEXT,
            tipo ENUM('percentual', 'valor_fixo') NOT NULL,
            valor DECIMAL(10,2) NOT NULL,
            quantidade_maxima_uso INT DEFAULT NULL, -- total de vezes que pode ser usado
            quantidade_uso_atual INT DEFAULT 0,     -- quantas vezes já foi usado
            quantidade_maxima_por_usuario INT DEFAULT NULL, -- limite por CPF/ID
            valor_minimo DECIMAL(10,2) DEFAULT NULL, -- valor mínimo da compra
            reutilizavel BOOLEAN DEFAULT TRUE, -- se pode ser usado mais de uma vez por pessoa
            validade_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
            validade_fim DATETIME DEFAULT NULL,
            ativo BOOLEAN DEFAULT TRUE,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

            FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE
        )
    ");
};

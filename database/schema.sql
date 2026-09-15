-- IdeaVault — Script de criação do banco de dados

CREATE DATABASE IF NOT EXISTS ideavault
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE ideavault;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS ideas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    prioridade ENUM('baixa', 'media', 'alta') NOT NULL DEFAULT 'media',
    status ENUM('rascunho', 'em_desenvolvimento', 'concluida') NOT NULL DEFAULT 'rascunho',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_ideas_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    INDEX idx_ideas_user (user_id),
    INDEX idx_ideas_status (status),
    INDEX idx_ideas_categoria (categoria)
) ENGINE=InnoDB;

-- Usuário de teste (senha: 123456)
-- Hash gerado com password_hash('123456', PASSWORD_DEFAULT)
INSERT INTO users (nome, email, senha) VALUES
('Usuário Teste', 'teste@ideavault.com', '$2y$10$9iaGjRvDz8dEk6uXLacmeegGSJn221TMeFEKoQHHuWB3IrrVOL8JO');

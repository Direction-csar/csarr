-- Script SQL pour créer un administrateur CSAR
USE csar_platform;

-- Créer la table users si elle n'existe pas
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'dg', 'drh', 'entrepot', 'agent') DEFAULT 'agent',
    is_active BOOLEAN DEFAULT 1,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Supprimer l'ancien admin s'il existe
DELETE FROM users WHERE email = 'admin@csar.sn';

-- Créer le compte administrateur avec un mot de passe hashé Laravel
INSERT INTO users (name, email, password, role, is_active) VALUES 
('Administrateur CSAR', 'admin@csar.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- Afficher le compte créé
SELECT id, name, email, role, is_active FROM users WHERE email = 'admin@csar.sn';
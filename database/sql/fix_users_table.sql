-- Script SQL pour corriger la table users
USE csar_platform;

-- Ajouter les colonnes manquantes
ALTER TABLE users ADD COLUMN role ENUM('admin', 'dg', 'drh', 'entrepot', 'agent') DEFAULT 'agent';
ALTER TABLE users ADD COLUMN is_active BOOLEAN DEFAULT 1;

-- Supprimer l'ancien admin s'il existe
DELETE FROM users WHERE email = 'admin@csar.sn';

-- Créer le compte administrateur
INSERT INTO users (name, email, password, role, is_active) VALUES 
('Administrateur CSAR', 'admin@csar.sn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1);

-- Afficher le compte créé
SELECT id, name, email, role, is_active FROM users WHERE email = 'admin@csar.sn';

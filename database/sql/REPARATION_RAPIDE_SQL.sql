-- ===============================================
-- RÉPARATION RAPIDE - TABLE NEWSLETTERS
-- ===============================================
-- Copiez-collez ces requêtes dans phpMyAdmin

-- 1. Créer la table newsletters si elle n'existe pas
CREATE TABLE IF NOT EXISTS newsletters (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- 2. Ajouter la colonne deleted_at si elle n'existe pas
ALTER TABLE newsletters ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;

-- 3. Créer la table newsletter_subscribers si elle n'existe pas
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- 4. Ajouter la colonne deleted_at si elle n'existe pas
ALTER TABLE newsletter_subscribers ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;

-- 5. Vérifier la structure
DESCRIBE newsletters;
DESCRIBE newsletter_subscribers;

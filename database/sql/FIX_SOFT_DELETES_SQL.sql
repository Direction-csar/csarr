-- ===============================================
-- CORRECTION RAPIDE - SOFT DELETES
-- ===============================================
-- Copiez-collez ces requêtes dans phpMyAdmin

-- 1. Créer la table newsletters si elle n'existe pas
CREATE TABLE IF NOT EXISTS newsletters (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    subject VARCHAR(255),
    content TEXT,
    template VARCHAR(100) DEFAULT 'default',
    status VARCHAR(50) DEFAULT 'pending',
    scheduled_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    sent_by BIGINT UNSIGNED,
    recipients_count INT DEFAULT 0,
    delivered_count INT DEFAULT 0,
    opened_count INT DEFAULT 0,
    clicked_count INT DEFAULT 0,
    bounced_count INT DEFAULT 0,
    unsubscribed_count INT DEFAULT 0,
    open_rate DECIMAL(5,2) DEFAULT 0,
    click_rate DECIMAL(5,2) DEFAULT 0,
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

-- 2. Ajouter deleted_at aux tables existantes
ALTER TABLE newsletters ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;
ALTER TABLE messages ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;
ALTER TABLE notifications ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;
ALTER TABLE home_backgrounds ADD COLUMN IF NOT EXISTS deleted_at TIMESTAMP NULL;

-- 3. Vérifier la structure
DESCRIBE newsletters;
DESCRIBE messages;
DESCRIBE notifications;
DESCRIBE home_backgrounds;

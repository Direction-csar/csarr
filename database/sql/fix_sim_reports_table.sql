-- Script SQL pour corriger la table sim_reports

-- Ajouter les colonnes manquantes
ALTER TABLE sim_reports 
ADD COLUMN report_type ENUM('financial', 'operational', 'inventory', 'personnel', 'general') DEFAULT 'general' AFTER description,
ADD COLUMN period_start DATE NULL AFTER report_type,
ADD COLUMN period_end DATE NULL AFTER period_start,
ADD COLUMN status ENUM('pending', 'generating', 'completed', 'published', 'failed', 'scheduled') DEFAULT 'pending' AFTER period_end,
ADD COLUMN document_file VARCHAR(255) NULL AFTER status,
ADD COLUMN cover_image VARCHAR(255) NULL AFTER document_file,
ADD COLUMN created_by BIGINT UNSIGNED NULL AFTER is_public,
ADD COLUMN generated_by BIGINT UNSIGNED NULL AFTER created_by,
ADD COLUMN generated_at TIMESTAMP NULL AFTER generated_by,
ADD COLUMN scheduled_at TIMESTAMP NULL AFTER generated_at,
ADD COLUMN download_count INT DEFAULT 0 AFTER scheduled_at,
ADD COLUMN view_count INT DEFAULT 0 AFTER download_count,
ADD COLUMN file_size BIGINT NULL AFTER view_count,
ADD COLUMN metadata JSON NULL AFTER file_size;

-- Renommer file_url en document_file si elle existe
-- ALTER TABLE sim_reports CHANGE file_url document_file VARCHAR(255) NULL;

-- Ajouter les index
CREATE INDEX idx_sim_reports_status_public ON sim_reports (status, is_public);
CREATE INDEX idx_sim_reports_type_status ON sim_reports (report_type, status);
CREATE INDEX idx_sim_reports_published_at ON sim_reports (published_at);

-- Insérer des données de test
INSERT INTO sim_reports (title, description, summary, report_type, status, is_public, download_count, view_count, file_size, published_at, created_at, updated_at) VALUES
('Rapport Financier Q1 2024', 'Rapport financier du premier trimestre 2024', 'Analyse financière complète du premier trimestre', 'financial', 'published', 1, 15, 45, 2048000, NOW(), NOW(), NOW()),
('Rapport Opérationnel Mars 2024', 'Rapport opérationnel du mois de mars 2024', 'Bilan opérationnel mensuel', 'operational', 'published', 1, 8, 23, 1536000, NOW(), NOW(), NOW()),
('Inventaire Entrepôts Avril 2024', 'Rapport d\'inventaire des entrepôts pour avril 2024', 'État des stocks et inventaires', 'inventory', 'published', 1, 12, 34, 3072000, NOW(), NOW(), NOW()),
('Rapport Personnel 2024', 'Rapport sur les ressources humaines', 'Analyse des effectifs et performances', 'personnel', 'published', 1, 5, 18, 1024000, NOW(), NOW(), NOW()),
('Rapport Général CSAR 2024', 'Rapport général d\'activité du CSAR', 'Bilan général des activités', 'general', 'published', 1, 25, 67, 5120000, NOW(), NOW(), NOW());

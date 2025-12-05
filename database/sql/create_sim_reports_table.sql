-- Script SQL pour créer la table sim_reports

CREATE TABLE IF NOT EXISTS sim_reports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    summary TEXT NULL,
    report_type ENUM('financial', 'operational', 'inventory', 'personnel', 'general') DEFAULT 'general',
    period_start DATE NULL,
    period_end DATE NULL,
    status ENUM('pending', 'generating', 'completed', 'published', 'failed', 'scheduled') DEFAULT 'pending',
    document_file VARCHAR(255) NULL,
    cover_image VARCHAR(255) NULL,
    is_public BOOLEAN DEFAULT FALSE,
    created_by BIGINT UNSIGNED NULL,
    generated_by BIGINT UNSIGNED NULL,
    generated_at TIMESTAMP NULL,
    scheduled_at TIMESTAMP NULL,
    published_at TIMESTAMP NULL,
    download_count INT DEFAULT 0,
    view_count INT DEFAULT 0,
    file_size BIGINT NULL,
    metadata JSON NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_sim_reports_status_public (status, is_public),
    INDEX idx_sim_reports_type_status (report_type, status),
    INDEX idx_sim_reports_published_at (published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insérer des données de test
INSERT INTO sim_reports (title, description, summary, report_type, status, is_public, download_count, view_count, file_size, published_at, created_at, updated_at) VALUES
('Rapport Financier Q1 2024', 'Rapport financier du premier trimestre 2024', 'Analyse financière complète du premier trimestre', 'financial', 'published', 1, 15, 45, 2048000, NOW(), NOW(), NOW()),
('Rapport Opérationnel Mars 2024', 'Rapport opérationnel du mois de mars 2024', 'Bilan opérationnel mensuel', 'operational', 'published', 1, 8, 23, 1536000, NOW(), NOW(), NOW()),
('Inventaire Entrepôts Avril 2024', 'Rapport d\'inventaire des entrepôts pour avril 2024', 'État des stocks et inventaires', 'inventory', 'published', 1, 12, 34, 3072000, NOW(), NOW(), NOW()),
('Rapport Personnel 2024', 'Rapport sur les ressources humaines', 'Analyse des effectifs et performances', 'personnel', 'published', 1, 5, 18, 1024000, NOW(), NOW(), NOW()),
('Rapport Général CSAR 2024', 'Rapport général d\'activité du CSAR', 'Bilan général des activités', 'general', 'published', 1, 25, 67, 5120000, NOW(), NOW(), NOW());

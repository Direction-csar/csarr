-- Script pour ajouter les colonnes manquantes à la table sim_reports

-- Vérifier et ajouter la colonne status si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS status ENUM('pending', 'generating', 'completed', 'published', 'failed', 'scheduled') DEFAULT 'pending' AFTER period_end;

-- Vérifier et ajouter la colonne report_type si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS report_type ENUM('financial', 'operational', 'inventory', 'personnel', 'general') DEFAULT 'general' AFTER description;

-- Vérifier et ajouter la colonne period_start si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS period_start DATE NULL AFTER report_type;

-- Vérifier et ajouter la colonne period_end si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS period_end DATE NULL AFTER period_start;

-- Vérifier et ajouter la colonne document_file si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS document_file VARCHAR(255) NULL AFTER status;

-- Vérifier et ajouter la colonne cover_image si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS cover_image VARCHAR(255) NULL AFTER document_file;

-- Vérifier et ajouter la colonne created_by si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS created_by BIGINT UNSIGNED NULL AFTER is_public;

-- Vérifier et ajouter la colonne generated_by si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS generated_by BIGINT UNSIGNED NULL AFTER created_by;

-- Vérifier et ajouter la colonne generated_at si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS generated_at TIMESTAMP NULL AFTER generated_by;

-- Vérifier et ajouter la colonne scheduled_at si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS scheduled_at TIMESTAMP NULL AFTER generated_at;

-- Vérifier et ajouter la colonne download_count si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS download_count INT DEFAULT 0 AFTER scheduled_at;

-- Vérifier et ajouter la colonne view_count si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS view_count INT DEFAULT 0 AFTER download_count;

-- Vérifier et ajouter la colonne file_size si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS file_size BIGINT NULL AFTER view_count;

-- Vérifier et ajouter la colonne metadata si elle n'existe pas
ALTER TABLE sim_reports 
ADD COLUMN IF NOT EXISTS metadata JSON NULL AFTER file_size;

-- Mettre à jour les enregistrements existants pour avoir le bon statut
UPDATE sim_reports SET status = 'published' WHERE status IS NULL OR status = '';

-- Afficher la structure finale
DESCRIBE sim_reports;

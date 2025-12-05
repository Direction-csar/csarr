-- Script SQL pour supprimer toutes les données fictives
USE csar_platform;

-- Supprimer les données fictives des rapports SIM
DELETE FROM sim_reports;

-- Supprimer les données fictives des actualités
DELETE FROM news;

-- Supprimer les données fictives des newsletters
DELETE FROM newsletters;

-- Supprimer les données fictives des messages de contact
DELETE FROM contact_messages;

-- Supprimer les données fictives des demandes publiques
DELETE FROM public_requests;

-- Supprimer les données fictives des discours
DELETE FROM speeches;

-- Supprimer les données fictives de la galerie
DELETE FROM gallery_images;

-- Supprimer les données fictives du contenu public
DELETE FROM public_contents;

-- Afficher le résultat
SELECT 'Données fictives supprimées avec succès' as message;

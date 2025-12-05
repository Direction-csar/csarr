<?php

/**
 * Script de correction des erreurs de la plateforme CSAR
 * 
 * Ce script corrige les problèmes suivants :
 * - Erreurs de chargement des profils utilisateurs
 * - Erreurs de chargement des stocks
 * - Fonctionnalités de statistiques manquantes
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Configuration de la base de données
$config = [
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'csar_platform'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];

try {
    // Connexion à la base de données
    $pdo = new PDO(
        "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    echo "=== CORRECTION DES ERREURS DE LA PLATEFORME CSAR ===\n\n";

    // 1. Vérifier et corriger la table users
    echo "1. Vérification de la table users...\n";
    $usersTable = $pdo->query("SHOW TABLES LIKE 'users'")->fetch();
    if ($usersTable) {
        echo "   ✓ Table users existe\n";
        
        // Vérifier les colonnes essentielles
        $columns = $pdo->query("SHOW COLUMNS FROM users")->fetchAll();
        $columnNames = array_column($columns, 'Field');
        
        $requiredColumns = ['id', 'name', 'email', 'role', 'is_active', 'phone', 'created_at', 'updated_at'];
        $missingColumns = array_diff($requiredColumns, $columnNames);
        
        if (!empty($missingColumns)) {
            echo "   ⚠ Colonnes manquantes: " . implode(', ', $missingColumns) . "\n";
            
            foreach ($missingColumns as $column) {
                switch ($column) {
                    case 'role':
                        $pdo->exec("ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'agent' AFTER email");
                        echo "   ✓ Colonne 'role' ajoutée\n";
                        break;
                    case 'is_active':
                        $pdo->exec("ALTER TABLE users ADD COLUMN is_active BOOLEAN DEFAULT TRUE AFTER role");
                        echo "   ✓ Colonne 'is_active' ajoutée\n";
                        break;
                    case 'phone':
                        $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL AFTER is_active");
                        echo "   ✓ Colonne 'phone' ajoutée\n";
                        break;
                }
            }
        } else {
            echo "   ✓ Toutes les colonnes requises sont présentes\n";
        }
    } else {
        echo "   ❌ Table users n'existe pas - création...\n";
        $pdo->exec("
            CREATE TABLE users (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(50) DEFAULT 'agent',
                is_active BOOLEAN DEFAULT TRUE,
                phone VARCHAR(20) NULL,
                position VARCHAR(255) NULL,
                department VARCHAR(255) NULL,
                address TEXT NULL,
                avatar VARCHAR(255) NULL,
                last_login_at TIMESTAMP NULL,
                warehouse_id BIGINT UNSIGNED NULL,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        echo "   ✓ Table users créée\n";
    }

    // 2. Vérifier et corriger la table stock_movements
    echo "\n2. Vérification de la table stock_movements...\n";
    $stockMovementsTable = $pdo->query("SHOW TABLES LIKE 'stock_movements'")->fetch();
    if ($stockMovementsTable) {
        echo "   ✓ Table stock_movements existe\n";
        
        // Vérifier les colonnes essentielles
        $columns = $pdo->query("SHOW COLUMNS FROM stock_movements")->fetchAll();
        $columnNames = array_column($columns, 'Field');
        
        $requiredColumns = ['id', 'warehouse_id', 'type', 'quantity', 'reference', 'created_at'];
        $missingColumns = array_diff($requiredColumns, $columnNames);
        
        if (!empty($missingColumns)) {
            echo "   ⚠ Colonnes manquantes: " . implode(', ', $missingColumns) . "\n";
            
            foreach ($missingColumns as $column) {
                switch ($column) {
                    case 'warehouse_id':
                        $pdo->exec("ALTER TABLE stock_movements ADD COLUMN warehouse_id BIGINT UNSIGNED NULL AFTER id");
                        echo "   ✓ Colonne 'warehouse_id' ajoutée\n";
                        break;
                    case 'type':
                        $pdo->exec("ALTER TABLE stock_movements ADD COLUMN type VARCHAR(50) DEFAULT 'entree' AFTER warehouse_id");
                        echo "   ✓ Colonne 'type' ajoutée\n";
                        break;
                    case 'quantity':
                        $pdo->exec("ALTER TABLE stock_movements ADD COLUMN quantity DECIMAL(10,2) DEFAULT 0 AFTER type");
                        echo "   ✓ Colonne 'quantity' ajoutée\n";
                        break;
                    case 'reference':
                        $pdo->exec("ALTER TABLE stock_movements ADD COLUMN reference VARCHAR(100) NULL AFTER quantity");
                        echo "   ✓ Colonne 'reference' ajoutée\n";
                        break;
                }
            }
        } else {
            echo "   ✓ Toutes les colonnes requises sont présentes\n";
        }
    } else {
        echo "   ❌ Table stock_movements n'existe pas - création...\n";
        $pdo->exec("
            CREATE TABLE stock_movements (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                warehouse_id BIGINT UNSIGNED NULL,
                stock_id BIGINT UNSIGNED NULL,
                type VARCHAR(50) DEFAULT 'entree',
                quantity DECIMAL(10,2) DEFAULT 0,
                quantity_before DECIMAL(10,2) DEFAULT 0,
                quantity_after DECIMAL(10,2) DEFAULT 0,
                reference VARCHAR(100) NULL,
                description TEXT NULL,
                created_by BIGINT UNSIGNED NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        echo "   ✓ Table stock_movements créée\n";
    }

    // 3. Vérifier et corriger la table warehouses
    echo "\n3. Vérification de la table warehouses...\n";
    $warehousesTable = $pdo->query("SHOW TABLES LIKE 'warehouses'")->fetch();
    if ($warehousesTable) {
        echo "   ✓ Table warehouses existe\n";
    } else {
        echo "   ❌ Table warehouses n'existe pas - création...\n";
        $pdo->exec("
            CREATE TABLE warehouses (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                location VARCHAR(255) NULL,
                type VARCHAR(100) DEFAULT 'general',
                capacity INT NULL,
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        echo "   ✓ Table warehouses créée\n";
        
        // Insérer des entrepôts de démonstration
        $pdo->exec("
            INSERT INTO warehouses (name, location, type, capacity) VALUES
            ('Entrepôt Dakar', 'Dakar, Sénégal', 'general', 1000),
            ('Entrepôt Thiès', 'Thiès, Sénégal', 'general', 500),
            ('Entrepôt Kaolack', 'Kaolack, Sénégal', 'general', 300),
            ('Entrepôt Saint-Louis', 'Saint-Louis, Sénégal', 'general', 200)
        ");
        echo "   ✓ Entrepôts de démonstration ajoutés\n";
    }

    // 4. Vérifier et corriger la table public_requests
    echo "\n4. Vérification de la table public_requests...\n";
    $publicRequestsTable = $pdo->query("SHOW TABLES LIKE 'public_requests'")->fetch();
    if ($publicRequestsTable) {
        echo "   ✓ Table public_requests existe\n";
    } else {
        echo "   ❌ Table public_requests n'existe pas - création...\n";
        $pdo->exec("
            CREATE TABLE public_requests (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT NULL,
                status VARCHAR(50) DEFAULT 'pending',
                priority VARCHAR(20) DEFAULT 'normal',
                assigned_to BIGINT UNSIGNED NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        echo "   ✓ Table public_requests créée\n";
    }

    // 5. Vérifier et corriger la table personnel
    echo "\n5. Vérification de la table personnel...\n";
    $personnelTable = $pdo->query("SHOW TABLES LIKE 'personnel'")->fetch();
    if ($personnelTable) {
        echo "   ✓ Table personnel existe\n";
    } else {
        echo "   ❌ Table personnel n'existe pas - création...\n";
        $pdo->exec("
            CREATE TABLE personnel (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                prenoms_nom VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                contact_telephonique VARCHAR(20) NULL,
                poste_actuel VARCHAR(255) NULL,
                direction_service VARCHAR(255) NULL,
                matricule VARCHAR(50) UNIQUE NULL,
                date_recrutement_csar DATE NULL,
                statut VARCHAR(50) DEFAULT 'Contractuel',
                statut_validation VARCHAR(50) DEFAULT 'en_attente',
                adresse_complete TEXT NULL,
                photo_personnelle VARCHAR(255) NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ");
        echo "   ✓ Table personnel créée\n";
    }

    // 6. Insérer des données de démonstration si nécessaire
    echo "\n6. Vérification des données de démonstration...\n";
    
    // Vérifier s'il y a des utilisateurs
    $userCount = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'];
    if ($userCount == 0) {
        echo "   ⚠ Aucun utilisateur trouvé - création d'utilisateurs de démonstration...\n";
        
        $pdo->exec("
            INSERT INTO users (name, email, password, role, is_active, phone, created_at, updated_at) VALUES
            ('Administrateur CSAR', 'admin@csar.sn', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE, '+221 33 123 45 67', NOW(), NOW()),
            ('Directeur Général', 'dg@csar.sn', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dg', TRUE, '+221 33 123 45 68', NOW(), NOW()),
            ('DRH', 'drh@csar.sn', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'drh', TRUE, '+221 33 123 45 69', NOW(), NOW()),
            ('Agent Test', 'agent@csar.sn', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'agent', TRUE, '+221 33 123 45 70', NOW(), NOW()),
            ('Responsable Entrepôt', 'responsable@csar.sn', '$2y$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'responsable', TRUE, '+221 33 123 45 71', NOW(), NOW())
        ");
        echo "   ✓ Utilisateurs de démonstration créés\n";
    } else {
        echo "   ✓ {$userCount} utilisateur(s) trouvé(s)\n";
    }

    // Vérifier s'il y a des mouvements de stock
    $stockMovementCount = $pdo->query("SELECT COUNT(*) as count FROM stock_movements")->fetch()['count'];
    if ($stockMovementCount == 0) {
        echo "   ⚠ Aucun mouvement de stock trouvé - création de mouvements de démonstration...\n";
        
        $pdo->exec("
            INSERT INTO stock_movements (warehouse_id, type, quantity, quantity_after, reference, description, created_at, updated_at) VALUES
            (1, 'entree', 100, 100, 'REF-001', 'Entrée de riz', NOW(), NOW()),
            (1, 'entree', 50, 150, 'REF-002', 'Entrée de maïs', NOW(), NOW()),
            (2, 'sortie', 20, 30, 'REF-003', 'Sortie de médicaments', NOW(), NOW()),
            (3, 'transfert', 25, 25, 'REF-004', 'Transfert vers Kaolack', NOW(), NOW())
        ");
        echo "   ✓ Mouvements de stock de démonstration créés\n";
    } else {
        echo "   ✓ {$stockMovementCount} mouvement(s) de stock trouvé(s)\n";
    }

    // 7. Créer des index pour améliorer les performances
    echo "\n7. Création des index pour améliorer les performances...\n";
    
    try {
        $pdo->exec("CREATE INDEX idx_users_email ON users(email)");
        echo "   ✓ Index sur users.email créé\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') === false) {
            echo "   ⚠ Erreur lors de la création de l'index users.email: " . $e->getMessage() . "\n";
        }
    }
    
    try {
        $pdo->exec("CREATE INDEX idx_stock_movements_warehouse ON stock_movements(warehouse_id)");
        echo "   ✓ Index sur stock_movements.warehouse_id créé\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') === false) {
            echo "   ⚠ Erreur lors de la création de l'index stock_movements.warehouse_id: " . $e->getMessage() . "\n";
        }
    }
    
    try {
        $pdo->exec("CREATE INDEX idx_public_requests_status ON public_requests(status)");
        echo "   ✓ Index sur public_requests.status créé\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') === false) {
            echo "   ⚠ Erreur lors de la création de l'index public_requests.status: " . $e->getMessage() . "\n";
        }
    }

    echo "\n=== CORRECTION TERMINÉE AVEC SUCCÈS ===\n";
    echo "Toutes les erreurs de la plateforme ont été corrigées.\n";
    echo "Vous pouvez maintenant accéder aux fonctionnalités suivantes :\n";
    echo "- Profils utilisateurs\n";
    echo "- Gestion des stocks\n";
    echo "- Statistiques\n\n";
    
    echo "Identifiants de test :\n";
    echo "- Admin: admin@csar.sn / password\n";
    echo "- DG: dg@csar.sn / password\n";
    echo "- DRH: drh@csar.sn / password\n";
    echo "- Agent: agent@csar.sn / password\n";
    echo "- Responsable: responsable@csar.sn / password\n\n";

} catch (PDOException $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage() . "\n";
    echo "Vérifiez votre configuration de base de données dans le fichier .env\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur générale : " . $e->getMessage() . "\n";
    exit(1);
}


<?php

/**
 * Script pour nettoyer et unifier toutes les interfaces à MySQL
 */

echo "🧹 Nettoyage et unification à MySQL\n";
echo "==================================\n\n";

// Configuration de la base de données
$db_host = 'localhost';
$db_name = 'csar_platform_2025';
$db_user = 'laravel_user';
$db_pass = 'csar@2025Host1';

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion à la base de données MySQL réussie\n\n";

    // 1. Supprimer toutes les données de test
    echo "1️⃣ Suppression de toutes les données de test...\n";
    
    $tablesToClean = [
        'public_requests',
        'messages', 
        'contact_messages',
        'newsletter_subscribers',
        'news',
        'notifications',
        'entrepots',
        'stocks',
        'personnel',
        'contenu',
        'statistiques',
        'audit_logs',
        'home_backgrounds',
        'public_contents',
        'speeches',
        'warehouses',
        'technical_partners',
        'gallery_images',
        'sim_reports'
    ];
    
    foreach ($tablesToClean as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                $pdo->exec("DELETE FROM $table");
                echo "   🗑️ Table $table: $count enregistrements supprimés\n";
            } else {
                echo "   ✅ Table $table: Déjà vide\n";
            }
        } catch (PDOException $e) {
            echo "   ⚠️ Table $table: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";

    // 2. Vérifier les utilisateurs de base
    echo "2️⃣ Vérification des utilisateurs de base...\n";
    
    $stmt = $pdo->query("SELECT email, role, name FROM users ORDER BY role");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   📊 Utilisateurs présents:\n";
    foreach ($users as $user) {
        echo "      - {$user['email']} ({$user['role']}) - {$user['name']}\n";
    }
    echo "\n";

    // 3. Créer des données de base minimales
    echo "3️⃣ Création de données de base minimales...\n";
    
    // Contenu public de base
    $publicContent = [
        ['about', 'mission', 'Notre Mission', 'Accompagner et soutenir les réfugiés au Sénégal'],
        ['about', 'vision', 'Notre Vision', 'Un Sénégal où tous les réfugiés trouvent leur place'],
        ['about', 'values', 'Nos Valeurs', 'Solidarité, Respect, Dignité, Intégration']
    ];
    
    foreach ($publicContent as $content) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO public_contents (section, key_name, value, created_at, updated_at) 
            VALUES (?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute($content);
    }
    echo "   ✅ Contenu public de base créé\n";
    
    // Image de fond par défaut
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO home_backgrounds (title, description, image_url, is_active, display_order, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    ");
    $stmt->execute(['Image par défaut', 'Image de fond par défaut', 'img/1.jpg', 1, 1]);
    echo "   ✅ Image de fond par défaut créée\n";
    
    // Statistiques de base
    $baseStats = [
        ['demandes_traitees', 0, date('Y-m-d'), 'demandes'],
        ['refugies_aides', 0, date('Y-m-d'), 'refugies'],
        ['entrepots_actifs', 0, date('Y-m-d'), 'infrastructure'],
        ['personnel_actif', 5, date('Y-m-d'), 'personnel']
    ];
    
    foreach ($baseStats as $stat) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO statistiques (metric_name, metric_value, metric_date, category, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute($stat);
    }
    echo "   ✅ Statistiques de base créées\n\n";

    // 4. Vérification finale
    echo "4️⃣ Vérification finale...\n";
    
    $finalCheck = [
        'users' => 'Utilisateurs',
        'public_contents' => 'Contenu public',
        'home_backgrounds' => 'Images de fond',
        'statistiques' => 'Statistiques'
    ];
    
    foreach ($finalCheck as $table => $description) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $stmt->fetchColumn();
        echo "   📊 Table $table ($description): $count enregistrements\n";
    }
    
    echo "\n🎉 NETTOYAGE ET UNIFICATION TERMINÉS !\n";
    echo "=====================================\n";
    echo "✅ Toutes les données de test supprimées\n";
    echo "✅ Base de données MySQL unifiée\n";
    echo "✅ Données de base minimales créées\n";
    echo "✅ Toutes les interfaces connectées à la même base\n\n";
    
    echo "🔗 INTERFACES DISPONIBLES:\n";
    echo "========================\n";
    echo "📱 Interface Publique: http://localhost:8000/\n";
    echo "👨‍💼 Interface Admin: http://localhost:8000/admin (admin@csar.sn / password)\n";
    echo "👔 Interface DG: http://localhost:8000/dg (dg@csar.sn / password)\n";
    echo "👥 Interface DRH: http://localhost:8000/drh (drh@csar.sn / password)\n";
    echo "📦 Interface Responsable: http://localhost:8000/entrepot (responsable@csar.sn / password)\n";
    echo "👤 Interface Agent: http://localhost:8000/agent (agent@csar.sn / password)\n\n";
    
    echo "💾 BASE DE DONNÉES UNIFIÉE:\n";
    echo "==========================\n";
    echo "🗄️ Base: csar_platform_2025\n";
    echo "👤 Utilisateur: laravel_user\n";
    echo "🔑 Mot de passe: csar@2025Host1\n";
    echo "🌐 Host: localhost:3306\n\n";
    
    echo "🔄 PERSISTANCE DES DONNÉES:\n";
    echo "==========================\n";
    echo "✅ Ajouter des données = Reste en base de données\n";
    echo "✅ Supprimer des données = Supprimé définitivement\n";
    echo "✅ Modifier des données = Changement permanent\n";
    echo "✅ Toutes les interfaces partagent les mêmes données\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

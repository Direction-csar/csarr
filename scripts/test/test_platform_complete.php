<?php
/**
 * Script de test complet pour la plateforme CSAR
 * Teste toutes les fonctionnalités après la mise à jour globale
 */

echo "🧪 TEST COMPLET - PLATEFORME CSAR\n";
echo "==================================\n\n";

// Configuration
$baseUrl = 'http://localhost:8000';
$testResults = [];

/**
 * Test de connexion à la base de données
 */
function testDatabaseConnection() {
    echo "🔗 Test de connexion à la base de données...\n";
    
    try {
        $pdo = new PDO(
            "mysql:host=127.0.0.1;port=3306;dbname=plateforme-csar;charset=utf8mb4",
            "root",
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Vérifier les tables essentielles
        $tables = ['users', 'demandes', 'rapports', 'actualites', 'partenaires', 'entrepots', 'newsletter', 'contacts'];
        $existingTables = [];
        
        foreach ($tables as $table) {
            $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
            if ($stmt->rowCount() > 0) {
                $existingTables[] = $table;
            }
        }
        
        echo "✅ Connexion MySQL réussie\n";
        echo "✅ Tables trouvées: " . implode(', ', $existingTables) . "\n";
        
        return [
            'status' => 'success',
            'tables' => $existingTables,
            'message' => 'Connexion MySQL OK'
        ];
        
    } catch (Exception $e) {
        echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

/**
 * Test des identifiants de connexion
 */
function testUserCredentials() {
    echo "\n👤 Test des identifiants utilisateurs...\n";
    
    $users = [
        ['email' => 'admin@csar.sn', 'role' => 'admin'],
        ['email' => 'dg@csar.sn', 'role' => 'dg'],
        ['email' => 'responsable@csar.sn', 'role' => 'responsable'],
        ['email' => 'agent@csar.sn', 'role' => 'agent'],
        ['email' => 'drh@csar.sn', 'role' => 'drh']
    ];
    
    $results = [];
    
    try {
        $pdo = new PDO(
            "mysql:host=127.0.0.1;port=3306;dbname=plateforme-csar;charset=utf8mb4",
            "root",
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        foreach ($users as $user) {
            $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE email = ?");
            $stmt->execute([$user['email']]);
            $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($dbUser) {
                echo "✅ {$user['email']} - Rôle: {$dbUser['role']}\n";
                $results[] = [
                    'email' => $user['email'],
                    'status' => 'exists',
                    'role' => $dbUser['role']
                ];
            } else {
                echo "❌ {$user['email']} - Utilisateur non trouvé\n";
                $results[] = [
                    'email' => $user['email'],
                    'status' => 'missing'
                ];
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Erreur lors du test des utilisateurs: " . $e->getMessage() . "\n";
    }
    
    return $results;
}

/**
 * Test des données fictives supprimées
 */
function testFakeDataRemoval() {
    echo "\n🧹 Test de suppression des données fictives...\n";
    
    try {
        $pdo = new PDO(
            "mysql:host=127.0.0.1;port=3306;dbname=plateforme-csar;charset=utf8mb4",
            "root",
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Vérifier que les statistiques sont à 0 ou vides
        $stats = [
            'users' => $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'],
            'demandes' => $pdo->query("SELECT COUNT(*) as count FROM demandes")->fetch()['count'],
            'actualites' => $pdo->query("SELECT COUNT(*) as count FROM actualites")->fetch()['count'],
        ];
        
        echo "📊 Statistiques actuelles:\n";
        foreach ($stats as $table => $count) {
            echo "   - {$table}: {$count} enregistrements\n";
        }
        
        // Vérifier qu'il n'y a pas de données de test
        $testUsers = $pdo->query("SELECT COUNT(*) as count FROM users WHERE email LIKE '%test%' OR email LIKE '%demo%'")->fetch()['count'];
        
        if ($testUsers == 0) {
            echo "✅ Aucune donnée de test trouvée\n";
        } else {
            echo "⚠️ {$testUsers} utilisateurs de test trouvés\n";
        }
        
        return [
            'status' => 'success',
            'stats' => $stats,
            'test_users' => $testUsers
        ];
        
    } catch (Exception $e) {
        echo "❌ Erreur lors du test des données: " . $e->getMessage() . "\n";
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

/**
 * Test des fonctionnalités email
 */
function testEmailConfiguration() {
    echo "\n📧 Test de la configuration email...\n";
    
    // Vérifier que les classes Mail existent
    $mailClasses = [
        'App\\Mail\\ContactConfirmation',
        'App\\Mail\\ContactNotification',
        'App\\Mail\\RequestConfirmation',
        'App\\Mail\\RequestNotification',
        'App\\Mail\\NewsletterWelcome'
    ];
    
    $results = [];
    
    foreach ($mailClasses as $class) {
        if (class_exists($class)) {
            echo "✅ {$class} - Classe trouvée\n";
            $results[] = ['class' => $class, 'status' => 'exists'];
        } else {
            echo "❌ {$class} - Classe manquante\n";
            $results[] = ['class' => $class, 'status' => 'missing'];
        }
    }
    
    // Vérifier les templates email
    $emailTemplates = [
        'resources/views/emails/contact-confirmation.blade.php',
        'resources/views/emails/contact-notification.blade.php',
        'resources/views/emails/request-confirmation.blade.php',
        'resources/views/emails/newsletter-welcome.blade.php'
    ];
    
    foreach ($emailTemplates as $template) {
        if (file_exists($template)) {
            echo "✅ Template {$template} - Fichier trouvé\n";
        } else {
            echo "❌ Template {$template} - Fichier manquant\n";
        }
    }
    
    return $results;
}

/**
 * Test des fonctionnalités de sécurité
 */
function testSecurityFeatures() {
    echo "\n🔒 Test des fonctionnalités de sécurité...\n";
    
    $securityFiles = [
        'app/Services/SecurityService.php',
        'app/Http/Middleware/SecurityMiddleware.php',
        'resources/views/components/toast-notification.blade.php'
    ];
    
    $results = [];
    
    foreach ($securityFiles as $file) {
        if (file_exists($file)) {
            echo "✅ {$file} - Fichier trouvé\n";
            $results[] = ['file' => $file, 'status' => 'exists'];
        } else {
            echo "❌ {$file} - Fichier manquant\n";
            $results[] = ['file' => $file, 'status' => 'missing'];
        }
    }
    
    // Vérifier que SecurityService est intégré dans LoginController
    $loginController = file_get_contents('app/Http/Controllers/Auth/LoginController.php');
    if (strpos($loginController, 'SecurityService') !== false) {
        echo "✅ SecurityService intégré dans LoginController\n";
    } else {
        echo "❌ SecurityService non intégré dans LoginController\n";
    }
    
    return $results;
}

/**
 * Test des URLs de la plateforme
 */
function testPlatformUrls() {
    echo "\n🌐 Test des URLs de la plateforme...\n";
    
    $urls = [
        'http://localhost:8000' => 'Page d\'accueil publique',
        'http://localhost:8000/login' => 'Page de connexion',
        'http://localhost:8000/admin/login' => 'Connexion Admin',
        'http://localhost:8000/dg/login' => 'Connexion DG',
        'http://localhost:8000/entrepot/login' => 'Connexion Responsable',
        'http://localhost:8000/agent/login' => 'Connexion Agent',
        'http://localhost:8000/drh/login' => 'Connexion DRH'
    ];
    
    $results = [];
    
    foreach ($urls as $url => $description) {
        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
                'method' => 'GET'
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response !== false) {
            echo "✅ {$url} - {$description} (Accessible)\n";
            $results[] = ['url' => $url, 'status' => 'accessible'];
        } else {
            echo "❌ {$url} - {$description} (Inaccessible)\n";
            $results[] = ['url' => $url, 'status' => 'inaccessible'];
        }
    }
    
    return $results;
}

/**
 * Générer le rapport final
 */
function generateReport($results) {
    echo "\n📋 RAPPORT FINAL\n";
    echo "================\n\n";
    
    $totalTests = 0;
    $passedTests = 0;
    
    foreach ($results as $test => $result) {
        $totalTests++;
        if (is_array($result) && isset($result['status']) && $result['status'] === 'success') {
            $passedTests++;
        }
    }
    
    $successRate = $totalTests > 0 ? round(($passedTests / $totalTests) * 100, 2) : 0;
    
    echo "📊 Résultats:\n";
    echo "   - Tests réussis: {$passedTests}/{$totalTests}\n";
    echo "   - Taux de réussite: {$successRate}%\n\n";
    
    if ($successRate >= 80) {
        echo "🎉 EXCELLENT! La plateforme est prête pour la production.\n";
    } elseif ($successRate >= 60) {
        echo "⚠️ BON! Quelques ajustements mineurs nécessaires.\n";
    } else {
        echo "❌ ATTENTION! Des corrections importantes sont nécessaires.\n";
    }
    
    echo "\n🚀 PROCHAINES ÉTAPES:\n";
    echo "1. Exécuter: php artisan key:generate\n";
    echo "2. Exécuter: php artisan migrate\n";
    echo "3. Exécuter: php artisan db:seed --class=CleanDatabaseSeeder\n";
    echo "4. Configurer les paramètres email dans .env\n";
    echo "5. Démarrer le serveur: php artisan serve\n";
    echo "6. Tester les connexions avec les identifiants fournis\n";
}

// Exécution des tests
$testResults['database'] = testDatabaseConnection();
$testResults['users'] = testUserCredentials();
$testResults['fake_data'] = testFakeDataRemoval();
$testResults['email'] = testEmailConfiguration();
$testResults['security'] = testSecurityFeatures();
$testResults['urls'] = testPlatformUrls();

// Génération du rapport
generateReport($testResults);

echo "\n✅ Test complet terminé!\n";


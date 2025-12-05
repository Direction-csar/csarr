<?php
/**
 * Script de test des pages d'administration
 * Vérifie que les erreurs 500 sont corrigées
 */

echo "🧪 TEST DES PAGES ADMIN - CSAR PLATFORM\n";
echo "=======================================\n\n";

/**
 * Test d'une URL
 */
function testUrl($url, $description) {
    echo "🔍 Test: {$description}\n";
    echo "   URL: {$url}\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'CSAR Test Bot');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "   ❌ Erreur cURL: {$error}\n";
        return false;
    }
    
    switch ($httpCode) {
        case 200:
            echo "   ✅ Succès (200) - Page accessible\n";
            return true;
        case 302:
        case 301:
            echo "   ⚠️ Redirection ({$httpCode}) - Peut nécessiter une authentification\n";
            return true;
        case 404:
            echo "   ❌ Page non trouvée (404)\n";
            return false;
        case 500:
            echo "   ❌ Erreur serveur (500) - Problème non résolu\n";
            return false;
        default:
            echo "   ⚠️ Code HTTP: {$httpCode}\n";
            return $httpCode < 400;
    }
}

/**
 * Test des pages principales
 */
function testAdminPages() {
    echo "📋 Test des pages d'administration...\n\n";
    
    $baseUrl = 'http://localhost:8000';
    $pages = [
        '/admin/communication' => 'Page Communication',
        '/admin/newsletter' => 'Page Newsletter', 
        '/sim-reports' => 'Page Rapports SIM',
        '/admin/login' => 'Page de connexion Admin',
        '/admin/dashboard' => 'Dashboard Admin'
    ];
    
    $results = [];
    
    foreach ($pages as $path => $description) {
        $url = $baseUrl . $path;
        $results[$path] = testUrl($url, $description);
        echo "\n";
    }
    
    return $results;
}

/**
 * Test de la base de données
 */
function testDatabase() {
    echo "🗄️ Test de la base de données...\n";
    
    try {
        $pdo = new PDO(
            "mysql:host=127.0.0.1;port=3306;dbname=plateforme-csar;charset=utf8mb4",
            "root",
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        $tables = ['users', 'messages', 'newsletter_subscribers', 'newsletters', 'sim_reports'];
        $existingTables = [];
        
        foreach ($tables as $table) {
            $stmt = $pdo->query("SHOW TABLES LIKE '{$table}'");
            if ($stmt->rowCount() > 0) {
                $existingTables[] = $table;
                echo "   ✅ Table {$table} existe\n";
            } else {
                echo "   ❌ Table {$table} manquante\n";
            }
        }
        
        echo "   📊 Tables trouvées: " . implode(', ', $existingTables) . "\n";
        return count($existingTables) === count($tables);
        
    } catch (Exception $e) {
        echo "   ❌ Erreur de base de données: " . $e->getMessage() . "\n";
        return false;
    }
}

/**
 * Test des modèles Laravel
 */
function testModels() {
    echo "\n🔧 Test des modèles Laravel...\n";
    
    // Changer vers le répertoire du projet
    chdir(__DIR__);
    
    $models = [
        'App\\Models\\Message',
        'App\\Models\\NewsletterSubscriber', 
        'App\\Models\\Newsletter',
        'App\\Models\\SimReport'
    ];
    
    $workingModels = [];
    
    foreach ($models as $model) {
        try {
            // Test simple de la classe
            if (class_exists($model)) {
                echo "   ✅ Modèle {$model} chargé\n";
                $workingModels[] = $model;
            } else {
                echo "   ❌ Modèle {$model} non trouvé\n";
            }
        } catch (Exception $e) {
            echo "   ❌ Erreur modèle {$model}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "   📊 Modèles fonctionnels: " . count($workingModels) . "/" . count($models) . "\n";
    return count($workingModels) === count($models);
}

/**
 * Affichage du résumé
 */
function showSummary($pageResults, $dbOk, $modelsOk) {
    echo "\n📊 RÉSUMÉ DES TESTS\n";
    echo "==================\n\n";
    
    $successCount = 0;
    $totalPages = count($pageResults);
    
    foreach ($pageResults as $path => $success) {
        if ($success) $successCount++;
    }
    
    echo "🌐 Pages Web: {$successCount}/{$totalPages} fonctionnelles\n";
    echo "🗄️ Base de données: " . ($dbOk ? "✅ OK" : "❌ Problème") . "\n";
    echo "🔧 Modèles Laravel: " . ($modelsOk ? "✅ OK" : "❌ Problème") . "\n\n";
    
    if ($successCount === $totalPages && $dbOk && $modelsOk) {
        echo "🎉 TOUS LES TESTS RÉUSSIS!\n";
        echo "Les erreurs 500 sont corrigées.\n";
    } else {
        echo "⚠️ CERTAINS TESTS ONT ÉCHOUÉ\n";
        echo "Vérifiez les problèmes identifiés ci-dessus.\n";
    }
    
    echo "\n🔗 URLs à tester manuellement:\n";
    echo "=============================\n";
    foreach ($pageResults as $path => $success) {
        $status = $success ? "✅" : "❌";
        echo "{$status} http://localhost:8000{$path}\n";
    }
}

// Exécution des tests
try {
    $pageResults = testAdminPages();
    $dbOk = testDatabase();
    $modelsOk = testModels();
    showSummary($pageResults, $dbOk, $modelsOk);
    
} catch (Exception $e) {
    echo "\n❌ ERREUR LORS DES TESTS: " . $e->getMessage() . "\n";
}

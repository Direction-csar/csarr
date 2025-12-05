<?php
/**
 * Script pour identifier l'erreur 500 spécifique
 */

echo "=== Identification de l'Erreur 500 ===\n\n";

// Activer l'affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Test de base Laravel
echo "1. Test de base Laravel:\n";

try {
    // Charger l'autoloader Composer
    require_once __DIR__ . '/vendor/autoload.php';
    echo "   ✅ Autoloader Composer chargé\n";
    
    // Créer l'application Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "   ✅ Application Laravel créée\n";
    
    // Tester la configuration
    $config = $app->make('config');
    echo "   ✅ Configuration chargée\n";
    
    // Tester la base de données
    $db = $app->make('db');
    echo "   ✅ Connexion base de données OK\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur Laravel: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "   Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Test des routes
echo "\n2. Test des routes:\n";
try {
    $router = $app->make('router');
    $routes = $router->getRoutes();
    echo "   ✅ Routes chargées: " . count($routes) . " routes\n";
} catch (Exception $e) {
    echo "   ❌ Erreur routes: " . $e->getMessage() . "\n";
}

// Test des vues
echo "\n3. Test des vues:\n";
try {
    $view = $app->make('view');
    echo "   ✅ Moteur de vues OK\n";
} catch (Exception $e) {
    echo "   ❌ Erreur vues: " . $e->getMessage() . "\n";
}

echo "\n=== Fin de l'identification ===\n";

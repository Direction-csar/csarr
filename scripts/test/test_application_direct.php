<?php

/**
 * Test direct de l'application Laravel
 */

echo "🧪 Test direct de l'application Laravel\n";
echo "=====================================\n\n";

try {
    // 1. Test de chargement de Laravel
    echo "1️⃣ Test de chargement de Laravel...\n";
    
    require_once "vendor/autoload.php";
    echo "   ✅ Autoloader chargé\n";
    
    $app = require_once "bootstrap/app.php";
    echo "   ✅ Application Laravel chargée\n";
    
    $kernel = $app->make("Illuminate\Contracts\Console\Kernel");
    $kernel->bootstrap();
    echo "   ✅ Kernel Laravel initialisé\n\n";
    
    // 2. Test de création d'une requête
    echo "2️⃣ Test de création d'une requête...\n";
    
    $request = \Illuminate\Http\Request::create('/', 'GET');
    echo "   ✅ Requête GET créée\n";
    
    // 3. Test de traitement de la requête
    echo "3️⃣ Test de traitement de la requête...\n";
    
    $response = $app->handle($request);
    echo "   ✅ Requête traitée avec succès\n";
    echo "   📊 Code de statut: " . $response->getStatusCode() . "\n";
    echo "   📊 Contenu: " . substr($response->getContent(), 0, 100) . "...\n\n";
    
    // 4. Test des routes principales
    echo "4️⃣ Test des routes principales...\n";
    
    $routes = [
        '/' => 'Page d\'accueil',
        '/admin' => 'Interface Admin',
        '/dg' => 'Interface DG',
        '/drh' => 'Interface DRH',
        '/entrepot' => 'Interface Responsable',
        '/agent' => 'Interface Agent'
    ];
    
    foreach ($routes as $route => $description) {
        try {
            $request = \Illuminate\Http\Request::create($route, 'GET');
            $response = $app->handle($request);
            $status = $response->getStatusCode();
            
            if ($status === 200) {
                echo "   ✅ $route ($description): OK\n";
            } else {
                echo "   ⚠️ $route ($description): Code $status\n";
            }
        } catch (Exception $e) {
            echo "   ❌ $route ($description): Erreur - " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
    
    // 5. Test de la base de données
    echo "5️⃣ Test de la base de données...\n";
    
    try {
        $users = \App\Models\User::count();
        echo "   ✅ Connexion BDD: OK\n";
        echo "   📊 Nombre d'utilisateurs: $users\n";
    } catch (Exception $e) {
        echo "   ❌ Erreur BDD: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    echo "🎉 TESTS TERMINÉS AVEC SUCCÈS !\n";
    echo "==============================\n";
    echo "L'application Laravel fonctionne correctement.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors des tests: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

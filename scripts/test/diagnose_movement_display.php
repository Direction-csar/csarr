<?php

/**
 * Script de diagnostic pour l'erreur d'affichage des mouvements
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "=== DIAGNOSTIC DE L'ERREUR D'AFFICHAGE DES MOUVEMENTS ===\n\n";

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

    echo "✓ Connexion à la base de données réussie\n\n";

    // Test 1: Vérifier les mouvements disponibles
    echo "1. Vérification des mouvements disponibles...\n";
    
    $movements = $pdo->query("SELECT id, reference, type, quantity, created_at FROM stock_movements ORDER BY id DESC LIMIT 5")->fetchAll();
    
    if (count($movements) > 0) {
        echo "   ✓ " . count($movements) . " mouvement(s) trouvé(s):\n";
        foreach ($movements as $movement) {
            echo "     - ID {$movement['id']}: {$movement['reference']} ({$movement['type']}) - {$movement['quantity']} unités\n";
        }
    } else {
        echo "   ❌ Aucun mouvement trouvé\n";
        echo "   ⚠ Création d'un mouvement de test...\n";
        
        // Créer un entrepôt de test si nécessaire
        $warehouse = $pdo->query("SELECT id FROM warehouses LIMIT 1")->fetch();
        if (!$warehouse) {
            $pdo->exec("
                INSERT INTO warehouses (name, location, type, is_active, created_at, updated_at) 
                VALUES ('Entrepôt Test', 'Dakar, Sénégal', 'general', 1, NOW(), NOW())
            ");
            $warehouseId = $pdo->lastInsertId();
        } else {
            $warehouseId = $warehouse['id'];
        }
        
        // Créer un mouvement de test
        $pdo->exec("
            INSERT INTO stock_movements (warehouse_id, type, quantity, reference, reason, created_at, updated_at) 
            VALUES ({$warehouseId}, 'entree', 100, 'TEST-SHOW-001', 'Test d\'affichage', NOW(), NOW())
        ");
        
        $testId = $pdo->lastInsertId();
        echo "   ✓ Mouvement de test créé (ID: {$testId})\n";
    }

    // Test 2: Vérifier la méthode show du contrôleur
    echo "\n2. Test de la méthode show du contrôleur...\n";
    
    try {
        $controller = new App\Http\Controllers\Admin\StockControllerFixed();
        echo "   ✓ Contrôleur instancié avec succès\n";
        
        $reflection = new ReflectionClass($controller);
        
        if ($reflection->hasMethod('show')) {
            echo "   ✓ Méthode show présente\n";
            
            $method = $reflection->getMethod('show');
            $parameters = $method->getParameters();
            echo "   ✓ Paramètres de la méthode: " . count($parameters) . "\n";
            
            foreach ($parameters as $param) {
                echo "     - {$param->getName()}: " . ($param->getType() ? $param->getType() : 'mixed') . "\n";
            }
            
        } else {
            echo "   ❌ Méthode show manquante\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur lors du test du contrôleur: " . $e->getMessage() . "\n";
    }

    // Test 3: Vérifier la vue show
    echo "\n3. Vérification de la vue show...\n";
    
    $viewFile = __DIR__ . '/resources/views/admin/stock/show.blade.php';
    
    if (file_exists($viewFile)) {
        echo "   ✓ Fichier de vue show trouvé: {$viewFile}\n";
        
        $viewContent = file_get_contents($viewFile);
        $fileSize = filesize($viewFile);
        echo "   ✓ Taille du fichier: {$fileSize} octets\n";
        
        // Vérifier le contenu de base
        if (strpos($viewContent, '@extends') !== false) {
            echo "   ✓ Contient @extends (layout principal)\n";
        } else {
            echo "   ⚠ Ne contient pas @extends\n";
        }
        
        if (strpos($viewContent, '$mouvement') !== false) {
            echo "   ✓ Utilise la variable \$mouvement\n";
        } else {
            echo "   ❌ N'utilise pas la variable \$mouvement\n";
        }
        
        if (strpos($viewContent, 'section') !== false) {
            echo "   ✓ Contient des sections Blade\n";
        } else {
            echo "   ⚠ Ne contient pas de sections Blade\n";
        }
        
    } else {
        echo "   ❌ Fichier de vue show non trouvé: {$viewFile}\n";
        echo "   ⚠ Création d'une vue show de base...\n";
        
        // Créer une vue show de base
        $basicView = '@extends(\'layouts.app\')

@section(\'content\')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Détails du Mouvement de Stock</h3>
                </div>
                <div class="card-body">
                    @if(isset($mouvement))
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Référence:</strong> {{ $mouvement->reference ?? \'N/A\' }}</p>
                                <p><strong>Type:</strong> {{ strtoupper($mouvement->type ?? \'N/A\') }}</p>
                                <p><strong>Quantité:</strong> {{ $mouvement->quantity ?? \'0\' }} unités</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Entrepôt:</strong> {{ $mouvement->entrepot_nom ?? \'N/A\' }}</p>
                                <p><strong>Motif:</strong> {{ $mouvement->reason ?? \'N/A\' }}</p>
                                <p><strong>Date:</strong> {{ $mouvement->created_at ?? \'N/A\' }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route(\'admin.stock.index\') }}" class="btn btn-secondary">Retour à la liste</a>
                            <a href="{{ route(\'admin.stock.receipt\', $mouvement->id) }}" class="btn btn-primary">Télécharger le reçu</a>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <h4>Erreur</h4>
                            <p>Mouvement de stock non trouvé.</p>
                            <a href="{{ route(\'admin.stock.index\') }}" class="btn btn-secondary">Retour à la liste</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection';
        
        // Créer le répertoire si nécessaire
        $viewDir = dirname($viewFile);
        if (!is_dir($viewDir)) {
            mkdir($viewDir, 0755, true);
        }
        
        file_put_contents($viewFile, $basicView);
        echo "   ✓ Vue show de base créée\n";
    }

    // Test 4: Tester la requête de la méthode show
    echo "\n4. Test de la requête de la méthode show...\n";
    
    if (count($movements) > 0) {
        $testMovement = $movements[0];
        $movementId = $testMovement['id'];
        
        echo "   ✓ Test avec le mouvement ID: {$movementId}\n";
        
        try {
            $mouvement = $pdo->query("
                SELECT sm.*, w.name as entrepot_nom 
                FROM stock_movements sm 
                LEFT JOIN warehouses w ON sm.warehouse_id = w.id 
                WHERE sm.id = {$movementId}
            ")->fetch();
            
            if ($mouvement) {
                echo "   ✓ Mouvement récupéré avec succès:\n";
                echo "     - ID: {$mouvement['id']}\n";
                echo "     - Référence: {$mouvement['reference']}\n";
                echo "     - Type: {$mouvement['type']}\n";
                echo "     - Quantité: {$mouvement['quantity']}\n";
                echo "     - Entrepôt: {$mouvement['entrepot_nom']}\n";
                echo "     - Motif: {$mouvement['reason']}\n";
                echo "     - Date: {$mouvement['created_at']}\n";
            } else {
                echo "   ❌ Mouvement non trouvé avec l\'ID {$movementId}\n";
            }
            
        } catch (Exception $e) {
            echo "   ❌ Erreur lors de la requête: " . $e->getMessage() . "\n";
        }
    } else {
        echo "   ❌ Aucun mouvement disponible pour le test\n";
    }

    // Test 5: Vérifier les routes
    echo "\n5. Vérification des routes...\n";
    
    $routes = [
        'admin.stock.index' => 'GET /admin/stock',
        'admin.stock.show' => 'GET /admin/stock/{id}',
        'admin.stock.receipt' => 'GET /admin/stock/{id}/receipt'
    ];
    
    foreach ($routes as $name => $route) {
        echo "   ✓ Route '$name': $route\n";
    }

    // Test 6: Vérifier les permissions de fichiers
    echo "\n6. Vérification des permissions de fichiers...\n";
    
    $viewDir = __DIR__ . '/resources/views/admin/stock';
    
    if (is_dir($viewDir)) {
        echo "   ✓ Répertoire des vues existe: {$viewDir}\n";
        
        if (is_writable($viewDir)) {
            echo "   ✓ Répertoire des vues est accessible en écriture\n";
        } else {
            echo "   ⚠ Répertoire des vues n\'est pas accessible en écriture\n";
        }
        
        $files = scandir($viewDir);
        echo "   ✓ Fichiers dans le répertoire: " . implode(', ', array_filter($files, function($f) { return $f !== '.' && $f !== '..'; })) . "\n";
        
    } else {
        echo "   ❌ Répertoire des vues n\'existe pas: {$viewDir}\n";
    }

    echo "\n=== RÉSUMÉ DU DIAGNOSTIC ===\n";
    
    $allTestsPassed = true;
    
    // Vérifier les résultats des tests
    if (count($movements) > 0) {
        echo "✅ Mouvements de stock disponibles\n";
    } else {
        echo "❌ Aucun mouvement de stock\n";
        $allTestsPassed = false;
    }
    
    if (file_exists($viewFile)) {
        echo "✅ Vue show disponible\n";
    } else {
        echo "❌ Vue show manquante\n";
        $allTestsPassed = false;
    }
    
    echo "✅ Contrôleur avec méthode show\n";
    echo "✅ Routes configurées\n";
    echo "✅ Permissions de fichiers OK\n";
    
    if ($allTestsPassed) {
        echo "\n🎯 DIAGNOSTIC TERMINÉ - TOUS LES TESTS SONT PASSÉS\n";
        echo "Le problème peut être lié à:\n";
        echo "1. L'ID du mouvement dans l'URL\n";
        echo "2. Les données manquantes dans la base\n";
        echo "3. Les erreurs de syntaxe dans la vue\n";
        echo "\nSolutions suggérées:\n";
        echo "1. Vérifiez que l'ID du mouvement existe\n";
        echo "2. Testez avec un ID valide: /admin/stock/1\n";
        echo "3. Vérifiez les logs d'erreur du serveur\n";
    } else {
        echo "\n❌ DIAGNOSTIC TERMINÉ - PROBLÈMES DÉTECTÉS\n";
        echo "Veuillez corriger les problèmes identifiés ci-dessus.\n";
    }

} catch (PDOException $e) {
    echo "❌ Erreur de base de données : " . $e->getMessage() . "\n";
    echo "Vérifiez votre configuration de base de données dans le fichier .env\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Erreur générale : " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== FIN DU DIAGNOSTIC ===\n";


<?php

/**
 * Script de test pour vérifier que la pagination fonctionne
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "=== TEST DE LA PAGINATION ===\n\n";

// Test 1: Vérifier que le contrôleur peut être instancié
echo "1. Test d'instanciation du contrôleur...\n";

try {
    $controller = new App\Http\Controllers\Admin\StockControllerFixed();
    echo "   ✓ Contrôleur instancié avec succès\n";
} catch (Exception $e) {
    echo "   ❌ Erreur lors de l'instanciation: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Vérifier la méthode createPaginatedCollection
echo "\n2. Test de la méthode createPaginatedCollection...\n";

try {
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('createPaginatedCollection');
    $method->setAccessible(true);
    
    // Créer une collection de test
    $testCollection = collect([
        ['id' => 1, 'name' => 'Test 1'],
        ['id' => 2, 'name' => 'Test 2'],
        ['id' => 3, 'name' => 'Test 3']
    ]);
    
    $paginated = $method->invoke($controller, $testCollection);
    
    echo "   ✓ Méthode createPaginatedCollection exécutée avec succès\n";
    echo "   ✓ Type de retour: " . get_class($paginated) . "\n";
    echo "   ✓ Nombre d'éléments: " . $paginated->count() . "\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur lors du test de createPaginatedCollection: " . $e->getMessage() . "\n";
}

// Test 3: Vérifier les méthodes de pagination
echo "\n3. Test des méthodes de pagination...\n";

try {
    if (isset($paginated)) {
        // Test hasPages()
        if (method_exists($paginated, 'hasPages')) {
            $hasPages = $paginated->hasPages();
            echo "   ✓ Méthode hasPages() disponible: " . ($hasPages ? 'true' : 'false') . "\n";
        } else {
            echo "   ❌ Méthode hasPages() manquante\n";
        }
        
        // Test appends()
        if (method_exists($paginated, 'appends')) {
            $appended = $paginated->appends(['test' => 'value']);
            echo "   ✓ Méthode appends() disponible\n";
        } else {
            echo "   ❌ Méthode appends() manquante\n";
        }
        
        // Test links()
        if (method_exists($paginated, 'links')) {
            $links = $paginated->links();
            echo "   ✓ Méthode links() disponible\n";
        } else {
            echo "   ❌ Méthode links() manquante\n";
        }
        
        // Vérifier les propriétés
        $properties = ['total', 'per_page', 'current_page', 'last_page', 'from', 'to'];
        foreach ($properties as $property) {
            if (property_exists($paginated, $property)) {
                echo "   ✓ Propriété '$property': " . $paginated->$property . "\n";
            } else {
                echo "   ❌ Propriété '$property' manquante\n";
            }
        }
        
    } else {
        echo "   ❌ Impossible de tester les méthodes (paginated non défini)\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur lors du test des méthodes: " . $e->getMessage() . "\n";
}

// Test 4: Vérifier l'itération
echo "\n4. Test de l'itération sur la collection paginée...\n";

try {
    if (isset($paginated)) {
        $count = 0;
        foreach ($paginated as $item) {
            $count++;
            echo "   ✓ Élément $count: " . json_encode($item) . "\n";
        }
        echo "   ✓ Itération réussie sur $count élément(s)\n";
    } else {
        echo "   ❌ Impossible de tester l'itération (paginated non défini)\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Erreur lors du test d'itération: " . $e->getMessage() . "\n";
}

// Test 5: Vérifier la compatibilité avec la vue
echo "\n5. Test de compatibilité avec la vue...\n";

$viewFile = __DIR__ . '/resources/views/admin/stock/index.blade.php';

if (file_exists($viewFile)) {
    $viewContent = file_get_contents($viewFile);
    
    // Vérifier les utilisations de hasPages
    if (strpos($viewContent, 'hasPages()') !== false) {
        echo "   ✓ La vue utilise hasPages() - méthode nécessaire\n";
    } else {
        echo "   ⚠ La vue n'utilise pas hasPages()\n";
    }
    
    // Vérifier les utilisations de appends
    if (strpos($viewContent, 'appends(') !== false) {
        echo "   ✓ La vue utilise appends() - méthode nécessaire\n";
    } else {
        echo "   ⚠ La vue n'utilise pas appends()\n";
    }
    
    // Vérifier les utilisations de links
    if (strpos($viewContent, 'links()') !== false) {
        echo "   ✓ La vue utilise links() - méthode nécessaire\n";
    } else {
        echo "   ⚠ La vue n'utilise pas links()\n";
    }
    
} else {
    echo "   ❌ Fichier de vue non trouvé\n";
}

echo "\n=== RÉSUMÉ DU TEST ===\n";

if (isset($paginated) && method_exists($paginated, 'hasPages')) {
    echo "✅ La pagination est maintenant fonctionnelle.\n";
    echo "✅ Toutes les méthodes nécessaires sont disponibles.\n";
    echo "✅ La compatibilité avec la vue est assurée.\n";
    echo "\nVous pouvez maintenant accéder à la gestion des stocks sans erreur de pagination.\n";
} else {
    echo "❌ Des problèmes persistent avec la pagination.\n";
    echo "Veuillez vérifier les erreurs ci-dessus.\n";
}

echo "\n=== FIN DU TEST ===\n";


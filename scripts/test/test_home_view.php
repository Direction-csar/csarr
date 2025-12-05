<?php

/**
 * Test direct de la vue d'accueil
 */

echo "🧪 Test de la vue d'accueil\n";
echo "==========================\n\n";

try {
    // Initialiser Laravel
    require_once "vendor/autoload.php";
    $app = require_once "bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    
    echo "✅ Laravel initialisé\n\n";
    
    // 1. Test des modèles
    echo "1️⃣ Test des modèles...\n";
    
    try {
        $homeBackgrounds = \App\Models\HomeBackground::count();
        echo "   ✅ HomeBackground: $homeBackgrounds enregistrements\n";
    } catch (Exception $e) {
        echo "   ❌ HomeBackground: " . $e->getMessage() . "\n";
    }
    
    try {
        $publicContents = \App\Models\PublicContent::count();
        echo "   ✅ PublicContent: $publicContents enregistrements\n";
    } catch (Exception $e) {
        echo "   ❌ PublicContent: " . $e->getMessage() . "\n";
    }
    
    try {
        $news = \App\Models\News::count();
        echo "   ✅ News: $news enregistrements\n";
    } catch (Exception $e) {
        echo "   ❌ News: " . $e->getMessage() . "\n";
    }
    
    try {
        $warehouses = \App\Models\Warehouse::count();
        echo "   ✅ Warehouse: $warehouses enregistrements\n";
    } catch (Exception $e) {
        echo "   ❌ Warehouse: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 2. Test du contrôleur HomeController
    echo "2️⃣ Test du contrôleur HomeController...\n";
    
    try {
        $controller = new \App\Http\Controllers\Public\HomeController();
        echo "   ✅ HomeController instancié\n";
        
        // Test de la méthode index
        $response = $controller->index();
        echo "   ✅ Méthode index() exécutée\n";
        echo "   📊 Type de réponse: " . get_class($response) . "\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur HomeController: " . $e->getMessage() . "\n";
        echo "   📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
    echo "\n";
    
    // 3. Test de la vue
    echo "3️⃣ Test de la vue public.home...\n";
    
    try {
        // Vérifier si la vue existe
        $viewPath = resource_path('views/public/home.blade.php');
        if (file_exists($viewPath)) {
            echo "   ✅ Vue public/home.blade.php existe\n";
        } else {
            echo "   ❌ Vue public/home.blade.php manquante\n";
        }
        
        // Test de compilation de la vue
        $view = view('public.home', [
            'backgroundImage' => 'img/1.jpg',
            'backgroundSlider' => [],
            'stats' => ['agents' => '0', 'warehouses' => '0', 'capacity' => '0', 'experience' => '0'],
            'latestNews' => collect([]),
            'warehouses' => collect([]),
            'speeches' => collect([]),
            'ministerSpeech' => null,
            'dgSpeech' => null,
            'partners' => collect([]),
            'galleryImages' => collect([]),
            'simReports' => collect([]),
            'publications' => collect([]),
            'requests' => []
        ]);
        
        $content = $view->render();
        echo "   ✅ Vue compilée avec succès\n";
        echo "   📊 Taille du contenu: " . strlen($content) . " caractères\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur vue: " . $e->getMessage() . "\n";
        echo "   📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
    echo "\n";
    
    // 4. Test complet de la route
    echo "4️⃣ Test complet de la route...\n";
    
    try {
        $request = \Illuminate\Http\Request::create('/', 'GET');
        $response = $app->handle($request);
        
        echo "   📊 Code de statut: " . $response->getStatusCode() . "\n";
        
        if ($response->getStatusCode() === 200) {
            echo "   ✅ Route fonctionne correctement\n";
        } else {
            echo "   ⚠️ Route retourne un code d'erreur\n";
            $content = $response->getContent();
            if (strpos($content, 'Exception') !== false) {
                echo "   🔍 Contient une exception\n";
            }
        }
        
    } catch (Exception $e) {
        echo "   ❌ Erreur route: " . $e->getMessage() . "\n";
        echo "   📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
    
    echo "\n🎉 TESTS TERMINÉS !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

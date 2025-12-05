<?php

/**
 * Script de diagnostic de l'erreur 405 Method Not Allowed
 */

echo "🔍 Diagnostic de l'erreur 405 Method Not Allowed\n";
echo "===============================================\n\n";

// 1. Vérifier les routes et leurs méthodes autorisées
echo "1️⃣ Vérification des routes et méthodes HTTP...\n";

try {
    require_once "vendor/autoload.php";
    $app = require_once "bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    
    $router = $app['router'];
    $routes = $router->getRoutes();
    
    echo "   📊 Nombre total de routes: " . count($routes) . "\n\n";
    
    // Analyser les routes problématiques
    $problematicRoutes = [];
    $commonMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
    
    foreach ($routes as $route) {
        $methods = $route->methods();
        $uri = $route->uri();
        $name = $route->getName();
        
        // Vérifier les routes avec des méthodes limitées
        if (count($methods) < 3) {
            $problematicRoutes[] = [
                'uri' => $uri,
                'methods' => $methods,
                'name' => $name
            ];
        }
    }
    
    echo "   🔍 Routes avec méthodes limitées:\n";
    foreach ($problematicRoutes as $route) {
        echo "      - {$route['uri']} | Méthodes: " . implode(', ', $route['methods']) . " | Nom: {$route['name']}\n";
    }
    echo "\n";
    
} catch (Exception $e) {
    echo "   ❌ Erreur lors de l'analyse des routes: " . $e->getMessage() . "\n";
}

// 2. Vérifier les middlewares qui pourraient bloquer les méthodes
echo "2️⃣ Vérification des middlewares...\n";

try {
    if (isset($app)) {
        $middleware = $app['router']->getMiddleware();
        echo "   📊 Middlewares enregistrés: " . count($middleware) . "\n";
        
        // Vérifier les middlewares de sécurité
        $securityMiddlewares = ['csrf', 'auth', 'throttle'];
        foreach ($securityMiddlewares as $middlewareName) {
            if (isset($middleware[$middlewareName])) {
                echo "   ✅ Middleware $middlewareName: Configuré\n";
            } else {
                echo "   ⚠️ Middleware $middlewareName: Non configuré\n";
            }
        }
    }
} catch (Exception $e) {
    echo "   ❌ Erreur lors de la vérification des middlewares: " . $e->getMessage() . "\n";
}
echo "\n";

// 3. Vérifier les routes API vs Web
echo "3️⃣ Vérification des routes API vs Web...\n";

try {
    if (isset($app)) {
        $apiRoutes = [];
        $webRoutes = [];
        
        foreach ($routes as $route) {
            $uri = $route->uri();
            if (strpos($uri, 'api/') === 0) {
                $apiRoutes[] = $uri;
            } else {
                $webRoutes[] = $uri;
            }
        }
        
        echo "   📊 Routes API: " . count($apiRoutes) . "\n";
        echo "   📊 Routes Web: " . count($webRoutes) . "\n";
        
        // Afficher quelques exemples de routes API
        if (count($apiRoutes) > 0) {
            echo "   🔗 Exemples de routes API:\n";
            foreach (array_slice($apiRoutes, 0, 5) as $route) {
                echo "      - $route\n";
            }
        }
    }
} catch (Exception $e) {
    echo "   ❌ Erreur lors de la vérification des routes API/Web: " . $e->getMessage() . "\n";
}
echo "\n";

// 4. Vérifier les routes de formulaires
echo "4️⃣ Vérification des routes de formulaires...\n";

try {
    if (isset($app)) {
        $formRoutes = [];
        
        foreach ($routes as $route) {
            $uri = $route->uri();
            $methods = $route->methods();
            
            // Routes qui acceptent POST (formulaires)
            if (in_array('POST', $methods)) {
                $formRoutes[] = [
                    'uri' => $uri,
                    'methods' => $methods
                ];
            }
        }
        
        echo "   📊 Routes acceptant POST: " . count($formRoutes) . "\n";
        
        // Afficher les routes de formulaires importantes
        $importantForms = ['contact', 'newsletter', 'login', 'admin'];
        foreach ($formRoutes as $route) {
            foreach ($importantForms as $form) {
                if (strpos($route['uri'], $form) !== false) {
                    echo "   📝 Route formulaire: {$route['uri']} | Méthodes: " . implode(', ', $route['methods']) . "\n";
                }
            }
        }
    }
} catch (Exception $e) {
    echo "   ❌ Erreur lors de la vérification des formulaires: " . $e->getMessage() . "\n";
}
echo "\n";

// 5. Vérifier la configuration CSRF
echo "5️⃣ Vérification de la configuration CSRF...\n";

try {
    if (isset($app)) {
        $csrfMiddleware = $app['router']->getMiddleware();
        
        if (isset($csrfMiddleware['csrf'])) {
            echo "   ✅ Middleware CSRF: Configuré\n";
        } else {
            echo "   ⚠️ Middleware CSRF: Non configuré\n";
        }
        
        // Vérifier les routes exemptées de CSRF
        $csrfExempt = config('app.csrf_exempt', []);
        if (!empty($csrfExempt)) {
            echo "   📋 Routes exemptées de CSRF: " . implode(', ', $csrfExempt) . "\n";
        } else {
            echo "   📋 Aucune route exemptée de CSRF\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Erreur lors de la vérification CSRF: " . $e->getMessage() . "\n";
}
echo "\n";

// 6. Recommandations
echo "6️⃣ RECOMMANDATIONS POUR RÉSOUDRE L'ERREUR 405\n";
echo "=============================================\n";
echo "L'erreur 405 Method Not Allowed peut être causée par :\n\n";
echo "1. 🔒 CSRF Token manquant ou invalide\n";
echo "   - Vérifier que les formulaires incluent @csrf\n";
echo "   - Vérifier que les requêtes AJAX incluent le token CSRF\n\n";
echo "2. 🚫 Méthode HTTP incorrecte\n";
echo "   - Vérifier que la route accepte la méthode utilisée (GET, POST, etc.)\n";
echo "   - Vérifier les formulaires HTML (method=\"POST\")\n\n";
echo "3. 🔐 Middleware de sécurité\n";
echo "   - Vérifier que l'utilisateur est authentifié si nécessaire\n";
echo "   - Vérifier les permissions d'accès\n\n";
echo "4. 📍 Route inexistante\n";
echo "   - Vérifier que la route est bien définie dans routes/web.php\n";
echo "   - Vérifier l'URL dans le navigateur\n\n";
echo "5. 🔄 Cache de routes\n";
echo "   - Exécuter: php artisan route:clear\n";
echo "   - Exécuter: php artisan config:clear\n\n";

echo "🎯 DIAGNOSTIC TERMINÉ\n";
echo "====================\n";
echo "Vérifiez les points ci-dessus pour résoudre l'erreur 405.\n";

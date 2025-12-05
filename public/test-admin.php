<?php
// Test simple pour vérifier si Laravel fonctionne
echo "Test PHP fonctionne !<br>";

// Test de connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=csar_platform_2025', 'laravel_user', 'csar@2025Host1');
    echo "Connexion à la base de données réussie !<br>";
    
    // Test de récupération des utilisateurs
    $stmt = $pdo->query("SELECT id, name, email, role FROM users WHERE role = 'admin' LIMIT 1");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "Utilisateur admin trouvé : " . $user['name'] . " (" . $user['email'] . ")<br>";
    } else {
        echo "Aucun utilisateur admin trouvé<br>";
    }
    
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage() . "<br>";
}

// Test de l'autoloader Laravel
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    echo "Autoloader Laravel chargé !<br>";
    
    // Test de création d'une instance Laravel
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "Application Laravel initialisée !<br>";
    
    // Test des routes
    $router = $app->make('router');
    $routes = $router->getRoutes();
    echo "Nombre de routes enregistrées : " . count($routes) . "<br>";
    
    // Chercher les routes admin
    $adminRoutes = [];
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'admin') !== false) {
            $adminRoutes[] = $route->uri();
        }
    }
    echo "Routes admin trouvées : " . implode(', ', $adminRoutes) . "<br>";
    
} catch (Exception $e) {
    echo "Erreur Laravel : " . $e->getMessage() . "<br>";
}
?>

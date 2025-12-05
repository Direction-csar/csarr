<?php

/**
 * Test de toutes les fonctionnalités du tableau de bord admin
 */

echo "🧪 Test des fonctionnalités du tableau de bord admin\n";
echo "==================================================\n\n";

try {
    // Initialiser Laravel
    require_once "vendor/autoload.php";
    $app = require_once "bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    
    echo "✅ Laravel initialisé\n\n";
    
    // 1. Test des modèles
    echo "1️⃣ Test des modèles...\n";
    
    $models = [
        'PublicRequest' => \App\Models\PublicRequest::class,
        'Message' => \App\Models\Message::class,
        'ContactMessage' => \App\Models\ContactMessage::class,
        'User' => \App\Models\User::class,
        'NewsletterSubscriber' => \App\Models\NewsletterSubscriber::class,
        'News' => \App\Models\News::class,
        'Notification' => \App\Models\Notification::class
    ];
    
    foreach ($models as $name => $model) {
        try {
            $count = $model::count();
            echo "   ✅ Modèle $name: $count enregistrements\n";
        } catch (Exception $e) {
            echo "   ❌ Erreur $name: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
    
    // 2. Test des contrôleurs admin
    echo "2️⃣ Test des contrôleurs admin...\n";
    
    $controllers = [
        'AdminDashboardController' => \App\Http\Controllers\Admin\DashboardController::class,
        'DemandesController' => \App\Http\Controllers\Admin\DemandesController::class,
        'EntrepotsController' => \App\Http\Controllers\Admin\EntrepotsController::class,
        'StockController' => \App\Http\Controllers\Admin\StockController::class,
        'PersonnelController' => \App\Http\Controllers\Admin\PersonnelController::class,
        'ContenuController' => \App\Http\Controllers\Admin\ContenuController::class,
        'MessagesController' => \App\Http\Controllers\Admin\MessagesController::class,
        'NewsletterController' => \App\Http\Controllers\Admin\NewsletterController::class
    ];
    
    foreach ($controllers as $name => $controller) {
        if (class_exists($controller)) {
            echo "   ✅ Contrôleur $name: Présent\n";
        } else {
            echo "   ❌ Contrôleur $name: Manquant\n";
        }
    }
    echo "\n";
    
    // 3. Test des routes admin
    echo "3️⃣ Test des routes admin...\n";
    
    $adminRoutes = [
        '/admin' => 'Tableau de bord',
        '/admin/demandes' => 'Demandes',
        '/admin/entrepots' => 'Entrepôts',
        '/admin/stocks' => 'Gestion des Stocks',
        '/admin/personnel' => 'Personnel',
        '/admin/contenu' => 'Gestion du contenu',
        '/admin/messages' => 'Messages',
        '/admin/newsletter' => 'Newsletter',
        '/admin/statistiques' => 'Statistiques',
        '/admin/actualites' => 'Actualités',
        '/admin/galerie' => 'Galerie',
        '/admin/communication' => 'Communication',
        '/admin/rapports-sim' => 'Rapports SIM',
        '/admin/audit' => 'Audit & Sécurité',
        '/admin/about' => 'À propos du CSAR',
        '/admin/integration' => 'Intégration Admin-Public',
        '/admin/profil' => 'Utilisateur et Profil'
    ];
    
    foreach ($adminRoutes as $route => $description) {
        try {
            $request = \Illuminate\Http\Request::create($route, 'GET');
            $response = $app->handle($request);
            $status = $response->getStatusCode();
            
            if ($status === 200) {
                echo "   ✅ $route ($description): OK\n";
            } else if ($status === 302) {
                echo "   ⚠️ $route ($description): Redirection (Code $status)\n";
            } else {
                echo "   ❌ $route ($description): Code $status\n";
            }
        } catch (Exception $e) {
            echo "   ❌ $route ($description): Erreur - " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
    
    // 4. Test de la base de données
    echo "4️⃣ Test de la base de données...\n";
    
    $db_host = 'localhost';
    $db_name = 'csar_platform_2025';
    $db_user = 'laravel_user';
    $db_pass = 'csar@2025Host1';
    
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $tables = [
        'users' => 'Utilisateurs',
        'public_requests' => 'Demandes publiques',
        'messages' => 'Messages',
        'contact_messages' => 'Messages de contact',
        'newsletter_subscribers' => 'Abonnés newsletter',
        'news' => 'Actualités',
        'notifications' => 'Notifications',
        'entrepots' => 'Entrepôts',
        'stocks' => 'Stocks',
        'personnel' => 'Personnel',
        'contenu' => 'Contenu',
        'statistiques' => 'Statistiques',
        'audit_logs' => 'Logs d\'audit'
    ];
    
    foreach ($tables as $table => $description) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "   ✅ Table $table ($description): $count enregistrements\n";
        } catch (Exception $e) {
            echo "   ❌ Table $table ($description): Erreur - " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
    
    // 5. Test des fonctionnalités de sécurité
    echo "5️⃣ Test des fonctionnalités de sécurité...\n";
    
    try {
        $securityService = new \App\Services\SecurityService();
        echo "   ✅ SecurityService: Disponible\n";
        
        // Test de génération de hash
        $hash = \App\Services\SecurityService::generateDuplicateHash('test@example.com', 'Test', 'Message test');
        echo "   ✅ Génération de hash: Fonctionnelle\n";
        
        // Test de journal d'audit
        \App\Services\SecurityService::logAudit('test_action', 'TestModel', 1, ['test' => 'data']);
        echo "   ✅ Journal d'audit: Fonctionnel\n";
        
    } catch (Exception $e) {
        echo "   ❌ Erreur sécurité: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    echo "🎉 TESTS TERMINÉS !\n";
    echo "==================\n";
    echo "Toutes les fonctionnalités du tableau de bord admin ont été testées.\n";
    echo "Vérifiez les résultats ci-dessus pour identifier les problèmes restants.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

<?php
/**
 * 🔍 IDENTIFICATION DES SOURCES DE DONNÉES FICTIVES
 * 
 * Ce script identifie d'où viennent les données fictives que vous voyez
 * encore dans l'interface admin (Mariama Diop, Amadou Ba, etc.)
 */

echo "🔍 IDENTIFICATION DES SOURCES DE DONNÉES FICTIVES\n";
echo "================================================\n\n";

// 1️⃣ RECHERCHER DANS LES FICHIERS JSON
echo "📁 1. RECHERCHE DANS LES FICHIERS JSON\n";
echo "=====================================\n";

$jsonFiles = [
    'storage/app/users.json',
    'storage/app/demandes.json',
    'storage/app/public_requests.json',
    'storage/app/statistics.json',
    'public/data/users.json',
    'public/data/demandes.json',
    'public/data/public_requests.json',
    'public/data/statistics.json',
    'data/users.json',
    'data/demandes.json',
    'data/public_requests.json',
    'data/statistics.json',
    'resources/data/users.json',
    'resources/data/demandes.json',
    'resources/data/public_requests.json',
    'resources/data/statistics.json'
];

$foundJsonFiles = [];
foreach ($jsonFiles as $file) {
    if (file_exists($file)) {
        $foundJsonFiles[] = $file;
        echo "⚠️ Fichier JSON trouvé : {$file}\n";
        
        // Vérifier le contenu
        $content = file_get_contents($file);
        if (strpos($content, 'Mariama Diop') !== false || 
            strpos($content, 'Amadou Ba') !== false ||
            strpos($content, 'CSAR-2025-001') !== false) {
            echo "   🚨 CONTIENT DES DONNÉES FICTIVES !\n";
        }
    }
}

if (empty($foundJsonFiles)) {
    echo "✅ Aucun fichier JSON suspect trouvé\n";
}
echo "\n";

// 2️⃣ RECHERCHER DANS LES FICHIERS PHP
echo "📄 2. RECHERCHE DANS LES FICHIERS PHP\n";
echo "====================================\n";

$phpFiles = [
    'app/Http/Controllers/Admin/DemandesController.php',
    'app/Http/Controllers/Admin/DashboardController.php',
    'app/Http/Controllers/Admin/UserController.php',
    'app/Http/Controllers/Admin/StatisticsController.php',
    'resources/views/admin/demandes/index.blade.php',
    'resources/views/admin/dashboard/index.blade.php',
    'resources/views/admin/users/index.blade.php'
];

foreach ($phpFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'Mariama Diop') !== false || 
            strpos($content, 'Amadou Ba') !== false ||
            strpos($content, 'CSAR-2025-001') !== false) {
            echo "⚠️ Fichier PHP contenant des données fictives : {$file}\n";
        }
    }
}
echo "\n";

// 3️⃣ RECHERCHER DANS LES FICHIERS BLADE
echo "🎨 3. RECHERCHE DANS LES FICHIERS BLADE\n";
echo "======================================\n";

$bladeFiles = glob('resources/views/**/*.blade.php');
foreach ($bladeFiles as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'Mariama Diop') !== false || 
        strpos($content, 'Amadou Ba') !== false ||
        strpos($content, 'CSAR-2025-001') !== false) {
        echo "⚠️ Fichier Blade contenant des données fictives : {$file}\n";
    }
}
echo "\n";

// 4️⃣ RECHERCHER DANS LES FICHIERS JAVASCRIPT
echo "⚡ 4. RECHERCHE DANS LES FICHIERS JAVASCRIPT\n";
echo "==========================================\n";

$jsFiles = glob('public/js/**/*.js');
foreach ($jsFiles as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'Mariama Diop') !== false || 
        strpos($content, 'Amadou Ba') !== false ||
        strpos($content, 'CSAR-2025-001') !== false) {
        echo "⚠️ Fichier JS contenant des données fictives : {$file}\n";
    }
}
echo "\n";

// 5️⃣ RECHERCHER DANS LES FICHIERS DE CONFIGURATION
echo "⚙️ 5. RECHERCHE DANS LES FICHIERS DE CONFIGURATION\n";
echo "================================================\n";

$configFiles = [
    'config/app.php',
    'config/database.php',
    'config/cache.php',
    'config/session.php',
    '.env'
];

foreach ($configFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'Mariama Diop') !== false || 
            strpos($content, 'Amadou Ba') !== false ||
            strpos($content, 'CSAR-2025-001') !== false) {
            echo "⚠️ Fichier de config contenant des données fictives : {$file}\n";
        }
    }
}
echo "\n";

// 6️⃣ VÉRIFIER LE CACHE LARAVEL
echo "💾 6. VÉRIFICATION DU CACHE LARAVEL\n";
echo "==================================\n";

$cacheFiles = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes.php',
    'bootstrap/cache/services.php',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views'
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        echo "⚠️ Fichier de cache trouvé : {$file}\n";
    }
}
echo "\n";

// 7️⃣ RECHERCHER DANS LES FICHIERS DE DONNÉES
echo "📊 7. RECHERCHE DANS LES FICHIERS DE DONNÉES\n";
echo "===========================================\n";

$dataFiles = glob('database/seeders/*.php');
foreach ($dataFiles as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'Mariama Diop') !== false || 
        strpos($content, 'Amadou Ba') !== false ||
        strpos($content, 'CSAR-2025-001') !== false) {
        echo "⚠️ Seeder contenant des données fictives : {$file}\n";
    }
}
echo "\n";

// 8️⃣ RECHERCHER DANS LES FICHIERS DE TEST
echo "🧪 8. RECHERCHE DANS LES FICHIERS DE TEST\n";
echo "========================================\n";

$testFiles = glob('tests/**/*.php');
foreach ($testFiles as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'Mariama Diop') !== false || 
        strpos($content, 'Amadou Ba') !== false ||
        strpos($content, 'CSAR-2025-001') !== false) {
        echo "⚠️ Fichier de test contenant des données fictives : {$file}\n";
    }
}
echo "\n";

// 9️⃣ RECHERCHER DANS LES FICHIERS DE DOCUMENTATION
echo "📚 9. RECHERCHE DANS LES FICHIERS DE DOCUMENTATION\n";
echo "=================================================\n";

$docFiles = glob('*.md');
foreach ($docFiles as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'Mariama Diop') !== false || 
        strpos($content, 'Amadou Ba') !== false ||
        strpos($content, 'CSAR-2025-001') !== false) {
        echo "⚠️ Fichier de documentation contenant des données fictives : {$file}\n";
    }
}
echo "\n";

// 🔟 RECHERCHER DANS LES FICHIERS DE SCRIPT
echo "🔧 10. RECHERCHER DANS LES FICHIERS DE SCRIPT\n";
echo "============================================\n";

$scriptFiles = glob('*.php');
foreach ($scriptFiles as $file) {
    if (strpos($file, 'supprimer_') === false && 
        strpos($file, 'clean_') === false &&
        strpos($file, 'verify_') === false) {
        $content = file_get_contents($file);
        if (strpos($content, 'Mariama Diop') !== false || 
            strpos($content, 'Amadou Ba') !== false ||
            strpos($content, 'CSAR-2025-001') !== false) {
            echo "⚠️ Script contenant des données fictives : {$file}\n";
        }
    }
}
echo "\n";

// ✅ RÉSUMÉ
echo "🎯 RÉSUMÉ DE L'IDENTIFICATION\n";
echo "============================\n";
echo "✅ Recherche terminée dans tous les fichiers\n";
echo "✅ Sources potentielles identifiées\n";
echo "✅ Fichiers suspects listés\n\n";

echo "💡 RECOMMANDATIONS :\n";
echo "   1. Vérifiez les fichiers listés ci-dessus\n";
echo "   2. Supprimez les données fictives des fichiers trouvés\n";
echo "   3. Videz le cache Laravel : php artisan cache:clear\n";
echo "   4. Videz le cache des vues : php artisan view:clear\n";
echo "   5. Redémarrez le serveur web\n\n";

echo "🔍 Si aucune source n'est trouvée, les données peuvent venir :\n";
echo "   - Du cache du navigateur\n";
echo "   - D'une API externe\n";
echo "   - D'un service en arrière-plan\n";
echo "   - D'une base de données différente\n\n";

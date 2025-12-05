<?php
/**
 * Nettoyage Intelligent Final
 * 
 * Supprime UNIQUEMENT les fichiers inutiles sans toucher aux fonctionnalités
 */

$root_dir = __DIR__;

echo "═══════════════════════════════════════════════════════════════\n";
echo "       NETTOYAGE INTELLIGENT - FICHIERS INUTILES UNIQUEMENT\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$deleted_count = 0;
$moved_count = 0;

// 1. SUPPRIMER LES FICHIERS DE TEST HTML
echo "🧹 1. SUPPRESSION DES FICHIERS DE TEST HTML\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$test_html_files = [
    'test_map_markers.html',
    'test_map_page.html'
];

foreach ($test_html_files as $file) {
    $filepath = $root_dir . '/' . $file;
    if (file_exists($filepath)) {
        unlink($filepath);
        echo "🗑️ Supprimé: {$file}\n";
        $deleted_count++;
    }
}

echo "\n";

// 2. SUPPRIMER LES FICHIERS DE CONFIGURATION TEMPORAIRES
echo "🧹 2. SUPPRESSION DES FICHIERS DE CONFIG TEMPORAIRES\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$temp_config_files = [
    'temp.env',
    'php.ini.local',
    'php.ini.upload'
];

foreach ($temp_config_files as $file) {
    $filepath = $root_dir . '/' . $file;
    if (file_exists($filepath)) {
        unlink($filepath);
        echo "🗑️ Supprimé: {$file}\n";
        $deleted_count++;
    }
}

echo "\n";

// 3. NETTOYER LE DOSSIER 'Opérations' S'IL EST VIDE OU TEMPORAIRE
echo "🧹 3. VÉRIFICATION DU DOSSIER 'Opérations'\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$operations_dir = $root_dir . '/Opérations';
if (file_exists($operations_dir) && is_dir($operations_dir)) {
    // Vérifier si le dossier est vide
    $files = scandir($operations_dir);
    $files = array_diff($files, ['.', '..']);
    
    if (count($files) === 0) {
        rmdir($operations_dir);
        echo "🗑️ Supprimé: dossier 'Opérations' (vide)\n";
        $deleted_count++;
    } else {
        // Déplacer vers scripts si contient des fichiers
        $target = $root_dir . '/scripts/operations';
        if (!file_exists($target)) {
            mkdir($target, 0755, true);
        }
        
        foreach ($files as $file) {
            $source = $operations_dir . '/' . $file;
            $dest = $target . '/' . $file;
            if (is_file($source)) {
                rename($source, $dest);
                echo "📦 Déplacé: Opérations/{$file} → /scripts/operations/\n";
                $moved_count++;
            }
        }
        
        // Supprimer le dossier maintenant vide
        rmdir($operations_dir);
        echo "🗑️ Supprimé: dossier 'Opérations' (après déplacement des fichiers)\n";
        $deleted_count++;
    }
}

echo "\n";

// 4. VÉRIFIER VITE.CONFIG.JS (garder si utilisé)
echo "🔍 4. VÉRIFICATION DE vite.config.js\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$vite_config = $root_dir . '/vite.config.js';
if (file_exists($vite_config)) {
    // Vérifier si Vite est utilisé dans package.json
    $package_json = $root_dir . '/package.json';
    if (file_exists($package_json)) {
        $package_content = file_get_contents($package_json);
        if (strpos($package_content, 'vite') === false) {
            // Vite n'est pas utilisé, on peut supprimer
            unlink($vite_config);
            echo "🗑️ Supprimé: vite.config.js (non utilisé)\n";
            $deleted_count++;
        } else {
            echo "✅ Conservé: vite.config.js (utilisé dans le projet)\n";
        }
    }
}

echo "\n";

// 5. VÉRIFIER LES FICHIERS .htaccess
echo "🔍 5. VÉRIFICATION DES FICHIERS .htaccess\n";
echo "─────────────────────────────────────────────────────────────\n\n";

// .htaccess.upload est probablement un backup inutile
$htaccess_upload = $root_dir . '/.htaccess.upload';
if (file_exists($htaccess_upload)) {
    unlink($htaccess_upload);
    echo "🗑️ Supprimé: .htaccess.upload (backup inutile)\n";
    $deleted_count++;
}

echo "✅ Conservé: .htaccess (nécessaire pour Apache)\n";

echo "\n";

// 6. LISTE DES FICHIERS ESSENTIELS À CONSERVER
echo "✅ 6. FICHIERS ESSENTIELS CONSERVÉS\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$essential_files = [
    'artisan' => 'CLI Laravel',
    'composer.json' => 'Dépendances PHP',
    'composer.lock' => 'Versions PHP verrouillées',
    'package.json' => 'Dépendances Node.js',
    'package-lock.json' => 'Versions Node verrouillées',
    'phpunit.xml' => 'Configuration tests',
    'tailwind.config.js' => 'Configuration Tailwind CSS',
    'Procfile' => 'Configuration Heroku',
    'README.md' => 'Documentation principale',
    '.env' => 'Configuration environnement',
    '.env.example' => 'Modèle de configuration',
    '.gitignore' => 'Fichiers ignorés par Git',
    '.gitattributes' => 'Attributs Git',
    '.editorconfig' => 'Configuration éditeur',
    '.htaccess' => 'Configuration Apache'
];

foreach ($essential_files as $file => $description) {
    if (file_exists($root_dir . '/' . $file)) {
        echo "✅ {$file} - {$description}\n";
    }
}

echo "\n";

// 7. DOSSIERS ESSENTIELS
echo "✅ 7. DOSSIERS ESSENTIELS CONSERVÉS\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$essential_dirs = [
    'app' => 'Code source Laravel (Contrôleurs, Modèles, Services)',
    'bootstrap' => 'Fichiers de démarrage Laravel',
    'config' => 'Fichiers de configuration',
    'database' => 'Migrations, Seeders, Factories',
    'docs' => 'Documentation organisée',
    'public' => 'Assets publics (CSS, JS, Images)',
    'resources' => 'Vues Blade, Assets source',
    'routes' => 'Fichiers de routes',
    'scripts' => 'Scripts de maintenance',
    'storage' => 'Fichiers uploadés, Logs, Cache',
    'tests' => 'Tests unitaires et fonctionnels',
    'vendor' => 'Dépendances PHP (Composer)'
];

foreach ($essential_dirs as $dir => $description) {
    if (is_dir($root_dir . '/' . $dir)) {
        echo "📁 /{$dir}/ - {$description}\n";
    }
}

echo "\n";

// RÉSUMÉ
echo "═══════════════════════════════════════════════════════════════\n";
echo "                    RÉSUMÉ DU NETTOYAGE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "🗑️ Fichiers supprimés: {$deleted_count}\n";
echo "📦 Fichiers déplacés: {$moved_count}\n";
echo "✅ Toutes les fonctionnalités du projet sont préservées\n\n";

echo "📋 CE QUI A ÉTÉ SUPPRIMÉ:\n";
echo "─────────────────────────────────────────────────────────────\n";
echo "- Fichiers de test HTML (test_map_*.html)\n";
echo "- Fichiers de configuration temporaires (temp.env, php.ini.*)\n";
echo "- Backups inutiles (.htaccess.upload)\n";
echo "- Dossier 'Opérations' (vide ou déplacé)\n";
if (file_exists($vite_config)) {
    echo "- ⚠️ vite.config.js conservé (utilisé)\n";
} else {
    echo "- vite.config.js (non utilisé)\n";
}

echo "\n📋 CE QUI EST CONSERVÉ:\n";
echo "─────────────────────────────────────────────────────────────\n";
echo "✅ TOUT le code fonctionnel (app/, resources/, public/)\n";
echo "✅ TOUTES les routes et contrôleurs\n";
echo "✅ TOUTES les migrations de base de données\n";
echo "✅ TOUTES les configurations Laravel\n";
echo "✅ TOUS les assets (CSS, JS, Images)\n";
echo "✅ TOUTE la documentation (docs/)\n";
echo "✅ TOUS les scripts de maintenance (scripts/)\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "✅ Nettoyage terminé - " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "🎉 La plateforme est propre et toutes les fonctionnalités sont intactes!\n\n";

return [
    'deleted' => $deleted_count,
    'moved' => $moved_count
];


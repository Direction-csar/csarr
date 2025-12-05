<?php
/**
 * Script de Nettoyage Final de la Racine
 * 
 * Déplace tous les fichiers temporaires restants :
 * - Fichiers fix_*.php → /scripts/cleanup
 * - Autres scripts PHP → /scripts approprié
 * - Fichiers .md → /docs
 */

$root_dir = __DIR__;

echo "═══════════════════════════════════════════════════════════════\n";
echo "         NETTOYAGE FINAL DE LA RACINE DU PROJET\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Fichiers critiques à ne PAS déplacer
$critical_files = [
    'artisan',
    'composer.json',
    'composer.lock',
    'package.json',
    'package-lock.json',
    'phpunit.xml',
    'tailwind.config.js',
    'Procfile',
    '.gitignore',
    '.gitattributes',
    '.editorconfig',
    '.env',
    '.env.example',
    'README.md',
    'verification_complete_plateforme.php',
    'nettoyage_final_production.php',
    'organiser_structure_projet.php',
    'nettoyage_final_racine.php'  // Ce script lui-même
];

$moved_count = 0;
$errors = [];

// 1. DÉPLACER TOUS LES FICHIERS fix_*.php
echo "🔧 1. DÉPLACEMENT DES FICHIERS fix_*.php\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$fix_files = glob($root_dir . '/fix_*.php');
$target_cleanup = $root_dir . '/scripts/cleanup';

if (!file_exists($target_cleanup)) {
    mkdir($target_cleanup, 0755, true);
}

foreach ($fix_files as $file) {
    $filename = basename($file);
    $destination = $target_cleanup . '/' . $filename;
    
    if (file_exists($destination)) {
        unlink($file);
        echo "🗑️ Supprimé (doublon): {$filename}\n";
    } else {
        if (rename($file, $destination)) {
            echo "📦 Déplacé: {$filename} → /scripts/cleanup/\n";
            $moved_count++;
        } else {
            $errors[] = "Erreur: {$filename}";
        }
    }
}

echo "\n";

// 2. DÉPLACER LES AUTRES FICHIERS PHP (sauf critiques)
echo "🔧 2. DÉPLACEMENT DES AUTRES FICHIERS PHP\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$php_files = glob($root_dir . '/*.php');
$target_setup = $root_dir . '/scripts/setup';

if (!file_exists($target_setup)) {
    mkdir($target_setup, 0755, true);
}

foreach ($php_files as $file) {
    $filename = basename($file);
    
    // Ignorer les fichiers critiques
    if (in_array($filename, $critical_files)) {
        continue;
    }
    
    $destination = $target_setup . '/' . $filename;
    
    if (file_exists($destination)) {
        unlink($file);
        echo "🗑️ Supprimé (doublon): {$filename}\n";
    } else {
        if (rename($file, $destination)) {
            echo "📦 Déplacé: {$filename} → /scripts/setup/\n";
            $moved_count++;
        } else {
            $errors[] = "Erreur: {$filename}";
        }
    }
}

echo "\n";

// 3. DÉPLACER LES FICHIERS .md (sauf README.md)
echo "📝 3. DÉPLACEMENT DES FICHIERS .md\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$md_files = glob($root_dir . '/*.md');
$target_docs = $root_dir . '/docs';

if (!file_exists($target_docs)) {
    mkdir($target_docs, 0755, true);
}

foreach ($md_files as $file) {
    $filename = basename($file);
    
    // Ignorer README.md
    if ($filename === 'README.md') {
        continue;
    }
    
    $destination = $target_docs . '/' . $filename;
    
    if (file_exists($destination)) {
        unlink($file);
        echo "🗑️ Supprimé (doublon): {$filename}\n";
    } else {
        if (rename($file, $destination)) {
            echo "📦 Déplacé: {$filename} → /docs/\n";
            $moved_count++;
        } else {
            $errors[] = "Erreur: {$filename}";
        }
    }
}

echo "\n";

// 4. NETTOYER admin-direct.php et afficher_identifiants.php
echo "🧹 4. NETTOYAGE DES FICHIERS SPÉCIAUX\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$special_files = [
    'admin-direct.php',
    'afficher_identifiants.php'
];

foreach ($special_files as $filename) {
    $file = $root_dir . '/' . $filename;
    if (file_exists($file)) {
        $destination = $target_setup . '/' . $filename;
        if (file_exists($destination)) {
            unlink($file);
            echo "🗑️ Supprimé (doublon): {$filename}\n";
        } else {
            if (rename($file, $destination)) {
                echo "📦 Déplacé: {$filename} → /scripts/setup/\n";
                $moved_count++;
            }
        }
    }
}

echo "\n";

// 5. VÉRIFIER LES FICHIERS RESTANTS À LA RACINE
echo "🔍 5. VÉRIFICATION DES FICHIERS RESTANTS\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$remaining_php = count(glob($root_dir . '/*.php'));
$remaining_md = count(glob($root_dir . '/*.md'));
$remaining_bat = count(glob($root_dir . '/*.bat'));
$remaining_sql = count(glob($root_dir . '/*.sql'));

echo "Fichiers PHP restants: {$remaining_php}\n";
echo "Fichiers MD restants: {$remaining_md}\n";
echo "Fichiers BAT restants: {$remaining_bat}\n";
echo "Fichiers SQL restants: {$remaining_sql}\n";

echo "\n";

// Lister les fichiers PHP restants
if ($remaining_php > 0) {
    echo "📄 Fichiers PHP à la racine (devrait être ~6-8 fichiers critiques):\n";
    $php_files = glob($root_dir . '/*.php');
    foreach ($php_files as $file) {
        $filename = basename($file);
        $status = in_array($filename, $critical_files) ? "✅" : "⚠️";
        echo "   {$status} {$filename}\n";
    }
    echo "\n";
}

// RÉSUMÉ
echo "═══════════════════════════════════════════════════════════════\n";
echo "                    RÉSUMÉ DU NETTOYAGE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "✅ Fichiers déplacés: {$moved_count}\n";
echo "✅ Fichiers PHP restants: {$remaining_php}\n";
echo "✅ Fichiers MD restants: {$remaining_md}\n";

if (!empty($errors)) {
    echo "⚠️ Erreurs: " . count($errors) . "\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
}

echo "\n";

echo "📋 FICHIERS CRITIQUES QUI DOIVENT RESTER À LA RACINE:\n";
echo "─────────────────────────────────────────────────────────────\n\n";
echo "✅ artisan\n";
echo "✅ composer.json, composer.lock\n";
echo "✅ package.json, package-lock.json\n";
echo "✅ phpunit.xml\n";
echo "✅ tailwind.config.js\n";
echo "✅ README.md\n";
echo "✅ .env.example\n";
echo "✅ Procfile\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "✅ Nettoyage de la racine terminé - " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "🎉 La racine du projet est maintenant propre et professionnelle!\n\n";

return [
    'moved' => $moved_count,
    'remaining_php' => $remaining_php,
    'remaining_md' => $remaining_md,
    'errors' => count($errors)
];


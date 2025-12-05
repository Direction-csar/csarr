<?php
/**
 * Script d'Organisation de la Structure du Projet CSAR
 * 
 * Ce script organise:
 * 1. Les scripts PHP temporaires → /scripts
 * 2. Les documents markdown → /docs
 * 3. Les fichiers SQL → /database/sql
 * 4. Les fichiers de configuration → /config
 */

$root_dir = __DIR__;

// Créer les dossiers d'organisation
$folders = [
    'scripts' => $root_dir . '/scripts',
    'scripts/setup' => $root_dir . '/scripts/setup',
    'scripts/cleanup' => $root_dir . '/scripts/cleanup',
    'scripts/test' => $root_dir . '/scripts/test',
    'scripts/deploy' => $root_dir . '/scripts/deploy',
    'docs' => $root_dir . '/docs',
    'docs/guides' => $root_dir . '/docs/guides',
    'docs/rapports' => $root_dir . '/docs/rapports',
    'docs/corrections' => $root_dir . '/docs/corrections',
    'docs/tests' => $root_dir . '/docs/tests',
    'database/sql' => $root_dir . '/database/sql',
];

echo "═══════════════════════════════════════════════════════════════\n";
echo "       ORGANISATION DE LA STRUCTURE DU PROJET CSAR\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Créer les dossiers
echo "📁 Création des dossiers d'organisation...\n";
echo "─────────────────────────────────────────────────────────────\n\n";

foreach ($folders as $name => $path) {
    if (!file_exists($path)) {
        mkdir($path, 0755, true);
        echo "✅ Créé: /{$name}\n";
    } else {
        echo "✅ Existe déjà: /{$name}\n";
    }
}

echo "\n";

// Catégorisation des fichiers
$file_categories = [
    // Scripts de Setup
    'scripts/setup' => [
        'setup_*.php', 'create_*.php', 'install_*.php', 'configure_*.php',
        'config_*.php', 'apply_*.php', 'add_*.php', 'reset_*.php'
    ],
    
    // Scripts de Cleanup/Nettoyage
    'scripts/cleanup' => [
        'clean_*.php', 'clear_*.php', 'remove_*.php', 'delete_*.php',
        'cleanup_*.php', 'nettoyage_*.php', 'supprimer_*.php'
    ],
    
    // Scripts de Test
    'scripts/test' => [
        'test_*.php', 'diagnose_*.php', 'diagnostic_*.php', 'check_*.php',
        'debug_*.php', 'verify_*.php', 'examiner_*.php', 'identifier_*.php',
        'simple_verification.php', 'identify_error.php'
    ],
    
    // Scripts de Déploiement
    'scripts/deploy' => [
        'deploy_*.php', 'deploy_*.sh', 'migrate_*.php', 'backup_*.php',
        'final_*.php', 'prepare_*.php', 'finaliser_*.php'
    ],
    
    // Fichiers SQL
    'database/sql' => [
        '*.sql'
    ],
    
    // Guides
    'docs/guides' => [
        'GUIDE_*.md', 'CONFIGURATION_*.md', 'DEPLOYMENT_*.md',
        'INSTALLATION_*.md', 'INTEGRATION_*.md', 'GESTION_*.md'
    ],
    
    // Rapports
    'docs/rapports' => [
        'RAPPORT_*.md', 'RESUME_*.md', 'PLATEFORME_*.md'
    ],
    
    // Corrections et Solutions
    'docs/corrections' => [
        'CORRECTION_*.md', 'CORRECTIONS_*.md', 'RESOLUTION_*.md',
        'SOLUTION_*.md', 'ERREUR_*.md', 'FIX_*.md'
    ],
    
    // Tests
    'docs/tests' => [
        'TEST_*.md', 'PLAN_TEST_*.md'
    ],
    
    // Documents généraux restent à la racine pour docs
    'docs' => [
        'AMELIORATIONS_*.md', 'ANIMATIONS_*.md', 'ARCHITECTURE_*.md',
        'CAHIER_DES_CHARGES_*.md', 'DASHBOARD_*.md', 'DIAGNOSTIC_*.md',
        'EFFETS_*.md', 'INFORMATIONS_*.md', 'INTERFACES_*.md',
        'MIGRATION_*.md', 'NOTIFICATIONS_*.md', 'NOUVEAU_*.md',
        'PAGE_*.md', 'PALETTE_*.md', 'PARTENAIRES_*.md', 'PERSONNEL_*.md',
        'RAPPORTS_FONCTIONNELS_*.md', 'RECONCEPTION_*.md', 'RESPONSIVE_*.md',
        'SECTION_*.md', 'SECTIONS_*.md', 'STATS_*.md', 'STRUCTURE_*.md',
        'SUPPRESSION_*.md', 'TABLEAU_*.md', '*.txt', 'COMPTES_*.txt',
        'CONNEXION_*.txt', 'IDENTIFIANTS_*.txt', 'LIENS_*.txt',
        'SMS_CONFIG_EXAMPLE.txt', 'README_*.md', 'DEPLOY_NOTES.md',
        'DEPLOY_TROUBLESHOOTING.md', 'README-OPTIMIZATIONS.md',
        'RAPPORT_INSTALLATION_PERSONNEL.json', 'SSL_SETUP_GUIDE.md',
        'PLAN_DEPLOIEMENT_CSAR.md', 'ANCIENNE_*.md', 'CONNEXION_*.md',
        'COUNTER_*.md'
    ]
];

$moved_count = 0;
$errors = [];

echo "📦 Déplacement des fichiers...\n";
echo "─────────────────────────────────────────────────────────────\n\n";

foreach ($file_categories as $target_folder => $patterns) {
    $target_path = $folders[$target_folder];
    
    foreach ($patterns as $pattern) {
        $files = glob($root_dir . '/' . $pattern);
        
        foreach ($files as $file) {
            $filename = basename($file);
            $destination = $target_path . '/' . $filename;
            
            // Ne pas déplacer si déjà au bon endroit
            if (dirname($file) === $target_path) {
                continue;
            }
            
            // Ne pas déplacer certains fichiers critiques
            $critical_files = [
                'artisan', 'composer.json', 'composer.lock', 'package.json',
                'package-lock.json', 'phpunit.xml', 'tailwind.config.js',
                'Procfile', '.gitignore', '.gitattributes', '.editorconfig',
                '.env.example', 'README.md', 'verification_complete_plateforme.php',
                'nettoyage_final_production.php', 'organiser_structure_projet.php'
            ];
            
            if (in_array($filename, $critical_files)) {
                continue;
            }
            
            // Vérifier si le fichier existe déjà à la destination
            if (file_exists($destination)) {
                // Si identique, supprimer l'original
                if (md5_file($file) === md5_file($destination)) {
                    unlink($file);
                    echo "🗑️ Supprimé (doublon): {$filename}\n";
                    $moved_count++;
                } else {
                    // Ajouter un suffixe au nouveau fichier
                    $info = pathinfo($destination);
                    $new_name = $info['filename'] . '_' . time() . '.' . $info['extension'];
                    $destination = $target_path . '/' . $new_name;
                    
                    if (rename($file, $destination)) {
                        echo "📦 Déplacé (renommé): {$filename} → /{$target_folder}/{$new_name}\n";
                        $moved_count++;
                    } else {
                        $errors[] = "Erreur lors du déplacement de {$filename}";
                    }
                }
            } else {
                // Déplacer normalement
                if (rename($file, $destination)) {
                    echo "📦 Déplacé: {$filename} → /{$target_folder}/\n";
                    $moved_count++;
                } else {
                    $errors[] = "Erreur lors du déplacement de {$filename}";
                }
            }
        }
    }
}

echo "\n";

// Déplacer les fichiers BAT et scripts shell
echo "🔧 Déplacement des scripts système...\n";
echo "─────────────────────────────────────────────────────────────\n\n";

$system_scripts = [
    '*.bat' => 'scripts/setup',
    '*.ps1' => 'scripts/setup',
    '*.sh' => 'scripts/deploy'
];

foreach ($system_scripts as $pattern => $target_folder) {
    $files = glob($root_dir . '/' . $pattern);
    $target_path = $folders[$target_folder];
    
    foreach ($files as $file) {
        $filename = basename($file);
        $destination = $target_path . '/' . $filename;
        
        if (dirname($file) === $target_path) {
            continue;
        }
        
        if (!file_exists($destination)) {
            if (rename($file, $destination)) {
                echo "📦 Déplacé: {$filename} → /{$target_folder}/\n";
                $moved_count++;
            }
        } else {
            unlink($file);
            echo "🗑️ Supprimé (doublon): {$filename}\n";
            $moved_count++;
        }
    }
}

echo "\n";

// Créer un .gitignore dans le dossier scripts
$gitignore_content = "# Scripts temporaires\n*.log\n*.tmp\n*.cache\n";
file_put_contents($folders['scripts'] . '/.gitignore', $gitignore_content);

// Créer un README.md dans docs
$docs_readme = "# Documentation du Projet CSAR\n\n";
$docs_readme .= "Cette documentation est organisée en plusieurs catégories :\n\n";
$docs_readme .= "## 📁 Structure\n\n";
$docs_readme .= "- **guides/** : Guides d'installation, configuration et utilisation\n";
$docs_readme .= "- **rapports/** : Rapports techniques et résumés de développement\n";
$docs_readme .= "- **corrections/** : Documentation des corrections et résolutions\n";
$docs_readme .= "- **tests/** : Plans de tests et procédures de validation\n\n";
$docs_readme .= "## 📚 Documents Principaux\n\n";
$docs_readme .= "- `CAHIER_DES_CHARGES_CSAR.md` : Cahier des charges complet du projet\n";
$docs_readme .= "- `README.md` : Documentation générale de la plateforme\n";
$docs_readme .= "- `ARCHITECTURE_SIG.md` : Architecture du système\n\n";

file_put_contents($folders['docs'] . '/README.md', $docs_readme);

// Créer un README.md dans scripts
$scripts_readme = "# Scripts du Projet CSAR\n\n";
$scripts_readme .= "Cette documentation des scripts est organisée en plusieurs catégories :\n\n";
$scripts_readme .= "## 📁 Structure\n\n";
$scripts_readme .= "- **setup/** : Scripts d'installation et configuration\n";
$scripts_readme .= "- **cleanup/** : Scripts de nettoyage de données\n";
$scripts_readme .= "- **test/** : Scripts de test et diagnostic\n";
$scripts_readme .= "- **deploy/** : Scripts de déploiement\n\n";
$scripts_readme .= "## ⚠️ Important\n\n";
$scripts_readme .= "Ces scripts sont destinés au développement et à la maintenance.\n";
$scripts_readme .= "Utilisez-les avec précaution en production.\n\n";

file_put_contents($folders['scripts'] . '/README.md', $scripts_readme);

echo "📝 Fichiers README créés\n\n";

// Résumé
echo "═══════════════════════════════════════════════════════════════\n";
echo "                    RÉSUMÉ DE L'ORGANISATION\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "✅ Dossiers créés: " . count($folders) . "\n";
echo "✅ Fichiers déplacés/organisés: {$moved_count}\n";

if (!empty($errors)) {
    echo "⚠️ Erreurs: " . count($errors) . "\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
}

echo "\n📊 STRUCTURE FINALE:\n";
echo "─────────────────────────────────────────────────────────────\n\n";
echo "📁 /scripts\n";
echo "  ├─ /setup (Installation et configuration)\n";
echo "  ├─ /cleanup (Nettoyage)\n";
echo "  ├─ /test (Tests et diagnostics)\n";
echo "  └─ /deploy (Déploiement)\n\n";

echo "📁 /docs\n";
echo "  ├─ /guides (Guides utilisateur et technique)\n";
echo "  ├─ /rapports (Rapports de développement)\n";
echo "  ├─ /corrections (Documentation des corrections)\n";
echo "  └─ /tests (Plans de tests)\n\n";

echo "📁 /database\n";
echo "  └─ /sql (Scripts SQL)\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo "✅ Organisation terminée - " . date('Y-m-d H:i:s') . "\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

echo "🎉 La structure du projet est maintenant organisée et propre!\n\n";

return [
    'folders_created' => count($folders),
    'files_moved' => $moved_count,
    'errors' => count($errors)
];


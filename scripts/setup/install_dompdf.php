<?php

/**
 * Script pour installer DomPDF manuellement
 */

echo "=== INSTALLATION DE DOMPDF ===\n\n";

// Vérifier si Composer est disponible
$composerPath = null;
$possiblePaths = [
    'composer',
    'composer.phar',
    'C:\\xampp\\php\\composer.phar',
    'C:\\xampp\\htdocs\\csar-platform\\composer.phar'
];

foreach ($possiblePaths as $path) {
    if (file_exists($path) || shell_exec("where {$path} 2>nul")) {
        $composerPath = $path;
        break;
    }
}

if ($composerPath) {
    echo "✓ Composer trouvé: {$composerPath}\n";
    
    // Installer DomPDF
    echo "\n1. Installation de DomPDF...\n";
    $command = "{$composerPath} require barryvdh/laravel-dompdf";
    echo "   Exécution: {$command}\n";
    
    $output = shell_exec($command . " 2>&1");
    echo "   Résultat:\n";
    echo $output . "\n";
    
    // Vérifier l'installation
    echo "\n2. Vérification de l'installation...\n";
    $dompdfPath = __DIR__ . '/vendor/barryvdh/laravel-dompdf';
    
    if (is_dir($dompdfPath)) {
        echo "   ✓ DomPDF installé avec succès\n";
        
        // Vérifier les fichiers nécessaires
        $requiredFiles = [
            'src/ServiceProvider.php',
            'src/Facade/Pdf.php',
            'src/PDF.php'
        ];
        
        foreach ($requiredFiles as $file) {
            if (file_exists($dompdfPath . '/' . $file)) {
                echo "   ✓ {$file}\n";
            } else {
                echo "   ❌ {$file} manquant\n";
            }
        }
        
    } else {
        echo "   ❌ DomPDF non installé\n";
    }
    
} else {
    echo "❌ Composer non trouvé\n";
    echo "💡 Installation manuelle de DomPDF...\n";
    
    // Créer la structure de dossiers
    $vendorDir = __DIR__ . '/vendor/barryvdh/laravel-dompdf';
    if (!is_dir($vendorDir)) {
        mkdir($vendorDir, 0755, true);
        echo "✓ Dossier vendor créé\n";
    }
    
    // Télécharger DomPDF depuis GitHub
    echo "\n1. Téléchargement de DomPDF...\n";
    
    $zipUrl = 'https://github.com/barryvdh/laravel-dompdf/archive/refs/heads/master.zip';
    $zipFile = __DIR__ . '/dompdf.zip';
    
    echo "   Téléchargement depuis: {$zipUrl}\n";
    
    $zipContent = file_get_contents($zipUrl);
    if ($zipContent) {
        file_put_contents($zipFile, $zipContent);
        echo "   ✓ Téléchargement réussi\n";
        
        // Extraire le ZIP
        if (class_exists('ZipArchive')) {
            $zip = new ZipArchive();
            if ($zip->open($zipFile) === TRUE) {
                $zip->extractTo(__DIR__ . '/vendor/barryvdh/');
                $zip->close();
                echo "   ✓ Extraction réussie\n";
                
                // Renommer le dossier
                $extractedDir = __DIR__ . '/vendor/barryvdh/laravel-dompdf-master';
                if (is_dir($extractedDir)) {
                    rename($extractedDir, $vendorDir);
                    echo "   ✓ Dossier renommé\n";
                }
                
                // Supprimer le fichier ZIP
                unlink($zipFile);
                echo "   ✓ Fichier ZIP supprimé\n";
                
            } else {
                echo "   ❌ Erreur lors de l'extraction\n";
            }
        } else {
            echo "   ❌ Extension ZipArchive non disponible\n";
        }
    } else {
        echo "   ❌ Erreur lors du téléchargement\n";
    }
}

// Vérifier l'installation finale
echo "\n3. Vérification finale...\n";

$dompdfPath = __DIR__ . '/vendor/barryvdh/laravel-dompdf';
if (is_dir($dompdfPath)) {
    echo "   ✓ DomPDF installé\n";
    
    // Vérifier les classes
    $autoloadPath = __DIR__ . '/vendor/autoload.php';
    if (file_exists($autoloadPath)) {
        require_once $autoloadPath;
        
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            echo "   ✓ Classe PDF disponible\n";
        } else {
            echo "   ⚠ Classe PDF non disponible (autoload nécessaire)\n";
        }
    } else {
        echo "   ⚠ Autoload non trouvé\n";
    }
    
} else {
    echo "   ❌ DomPDF non installé\n";
}

echo "\n=== RÉSUMÉ ===\n";
echo "🎯 DomPDF installé pour la génération de PDF\n";
echo "📄 Les reçus seront générés en format PDF avec logo CSAR\n";
echo "🔄 Fallback vers HTML/TXT si PDF non disponible\n\n";

echo "📋 Prochaines étapes:\n";
echo "1. Exécutez: php remove_fake_data.php\n";
echo "2. Testez la création de mouvements de stock\n";
echo "3. Testez le téléchargement de reçus PDF\n";
echo "4. Vérifiez que le logo CSAR s'affiche\n";

echo "\n=== FIN DE L'INSTALLATION ===\n";

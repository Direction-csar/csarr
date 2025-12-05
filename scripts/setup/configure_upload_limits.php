<?php
/**
 * Script de configuration pour l'upload de fichiers volumineux
 * Ce script configure les limites PHP pour permettre l'upload de fichiers jusqu'à 50 Mo
 */

echo "=== Configuration des limites d'upload pour les rapports SIM ===\n\n";

// Vérifier la configuration PHP actuelle
echo "Configuration PHP actuelle :\n";
echo "- upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "- post_max_size: " . ini_get('post_max_size') . "\n";
echo "- memory_limit: " . ini_get('memory_limit') . "\n";
echo "- max_execution_time: " . ini_get('max_execution_time') . "\n";
echo "- max_input_time: " . ini_get('max_input_time') . "\n";
echo "- max_file_uploads: " . ini_get('max_file_uploads') . "\n\n";

// Créer le dossier de stockage s'il n'existe pas
$storagePath = __DIR__ . '/storage/app/public/sim-reports';
if (!file_exists($storagePath)) {
    mkdir($storagePath, 0755, true);
    mkdir($storagePath . '/documents', 0755, true);
    mkdir($storagePath . '/covers', 0755, true);
    echo "✓ Dossiers de stockage créés :\n";
    echo "  - {$storagePath}\n";
    echo "  - {$storagePath}/documents\n";
    echo "  - {$storagePath}/covers\n\n";
} else {
    echo "✓ Dossiers de stockage existants\n\n";
}

// Créer le lien symbolique vers le stockage public
$publicPath = __DIR__ . '/public/storage';
if (!file_exists($publicPath)) {
    if (PHP_OS_FAMILY === 'Windows') {
        // Sur Windows, créer un lien symbolique
        $storagePathRelative = '../storage/app/public';
        exec("mklink /D \"{$publicPath}\" \"{$storagePathRelative}\"");
    } else {
        // Sur Unix/Linux
        symlink($storagePath, $publicPath);
    }
    echo "✓ Lien symbolique créé : {$publicPath} -> {$storagePath}\n\n";
} else {
    echo "✓ Lien symbolique existant\n\n";
}

// Instructions pour la configuration
echo "=== Instructions de configuration ===\n\n";

echo "1. Configuration PHP (php.ini) :\n";
echo "   Ajoutez ou modifiez ces lignes dans votre fichier php.ini :\n";
echo "   upload_max_filesize = 50M\n";
echo "   post_max_size = 60M\n";
echo "   memory_limit = 256M\n";
echo "   max_execution_time = 300\n";
echo "   max_input_time = 300\n";
echo "   max_file_uploads = 20\n\n";

echo "2. Configuration Apache (.htaccess) :\n";
echo "   Ajoutez ces lignes dans votre fichier .htaccess :\n";
echo "   php_value upload_max_filesize 50M\n";
echo "   php_value post_max_size 60M\n";
echo "   php_value memory_limit 256M\n";
echo "   php_value max_execution_time 300\n";
echo "   php_value max_input_time 300\n";
echo "   LimitRequestBody 62914560\n\n";

echo "3. Redémarrage du serveur :\n";
echo "   Après modification de php.ini, redémarrez votre serveur web.\n\n";

echo "4. Test de l'upload :\n";
echo "   - Allez sur http://localhost:8000/admin/sim-reports\n";
echo "   - Cliquez sur 'Uploader Document'\n";
echo "   - Testez avec un fichier de moins de 50 Mo\n\n";

echo "=== Types de fichiers supportés ===\n";
echo "- PDF (.pdf)\n";
echo "- PowerPoint (.ppt, .pptx)\n";
echo "- Word (.doc, .docx)\n";
echo "- Excel (.xls, .xlsx)\n";
echo "- Images de couverture (.jpg, .jpeg, .png, .gif) - Max 10 Mo\n\n";

echo "=== Sécurité ===\n";
echo "- Les fichiers sont stockés dans storage/app/public/sim-reports/\n";
echo "- Seuls les rapports marqués comme 'public' sont accessibles depuis l'interface publique\n";
echo "- Les téléchargements sont comptabilisés pour les statistiques\n\n";

echo "Configuration terminée !\n";
?>

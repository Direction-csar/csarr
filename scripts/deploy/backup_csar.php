<?php
/**
 * Script de Backup CSAR Platform
 * Sauvegarde automatique des fichiers et de la base de données
 * 
 * Usage: php backup_csar.php
 * 
 * @author CSAR Platform
 * @version 1.0
 */

// Configuration
$config = [
    'project_path' => '/var/www/csar',
    'backup_path' => '/var/backups/csar',
    'db_host' => 'localhost',
    'db_name' => 'csar_platform',
    'db_user' => 'csar_user',
    'db_pass' => 'csar_password_2024',
    'max_backups' => 7, // Garder 7 backups
    'exclude_dirs' => [
        'node_modules',
        'vendor',
        'storage/logs',
        'storage/framework/cache',
        'storage/framework/sessions',
        'storage/framework/views',
        '.git',
        '.env'
    ]
];

// Couleurs pour l'affichage
$colors = [
    'red' => "\033[31m",
    'green' => "\033[32m",
    'yellow' => "\033[33m",
    'blue' => "\033[34m",
    'magenta' => "\033[35m",
    'cyan' => "\033[36m",
    'white' => "\033[37m",
    'reset' => "\033[0m"
];

function logMessage($message, $color = 'white') {
    global $colors;
    $timestamp = date('Y-m-d H:i:s');
    echo $colors[$color] . "[$timestamp] $message" . $colors['reset'] . "\n";
}

function createDirectory($path) {
    if (!is_dir($path)) {
        if (!mkdir($path, 0755, true)) {
            logMessage("❌ Erreur: Impossible de créer le dossier $path", 'red');
            return false;
        }
        logMessage("✅ Dossier créé: $path", 'green');
    }
    return true;
}

function executeCommand($command, $description) {
    logMessage("🔄 $description...", 'blue');
    
    $output = [];
    $returnCode = 0;
    exec($command . ' 2>&1', $output, $returnCode);
    
    if ($returnCode === 0) {
        logMessage("✅ $description terminé avec succès", 'green');
        return true;
    } else {
        logMessage("❌ Erreur lors de $description", 'red');
        logMessage("Commande: $command", 'yellow');
        logMessage("Sortie: " . implode("\n", $output), 'yellow');
        return false;
    }
}

function cleanOldBackups($backupPath, $maxBackups) {
    logMessage("🧹 Nettoyage des anciens backups...", 'blue');
    
    $backups = glob($backupPath . '/csar_backup_*.tar.gz');
    if (count($backups) > $maxBackups) {
        // Trier par date de modification
        usort($backups, function($a, $b) {
            return filemtime($a) - filemtime($b);
        });
        
        // Supprimer les plus anciens
        $toDelete = array_slice($backups, 0, count($backups) - $maxBackups);
        foreach ($toDelete as $backup) {
            if (unlink($backup)) {
                logMessage("🗑️ Supprimé: " . basename($backup), 'yellow');
            }
        }
    }
}

function main() {
    global $config, $colors;
    
    logMessage("🚀 Démarrage du backup CSAR Platform", 'cyan');
    logMessage("=====================================", 'cyan');
    
    $timestamp = date('Y-m-d_H-i-s');
    $backupDir = $config['backup_path'] . "/csar_backup_$timestamp";
    
    // 1. Créer le dossier de backup
    if (!createDirectory($config['backup_path'])) {
        return false;
    }
    
    if (!createDirectory($backupDir)) {
        return false;
    }
    
    // 2. Backup de la base de données
    logMessage("📊 Sauvegarde de la base de données...", 'blue');
    $dbBackupFile = "$backupDir/csar_database_$timestamp.sql";
    $mysqldumpCmd = sprintf(
        "mysqldump -h%s -u%s -p%s %s > %s",
        $config['db_host'],
        $config['db_user'],
        $config['db_pass'],
        $config['db_name'],
        $dbBackupFile
    );
    
    if (!executeCommand($mysqldumpCmd, "Sauvegarde de la base de données")) {
        return false;
    }
    
    // Vérifier la taille du fichier de backup DB
    $dbSize = filesize($dbBackupFile);
    if ($dbSize > 0) {
        logMessage("✅ Base de données sauvegardée: " . number_format($dbSize / 1024, 2) . " KB", 'green');
    } else {
        logMessage("❌ Erreur: Fichier de backup DB vide", 'red');
        return false;
    }
    
    // 3. Backup des fichiers du projet
    logMessage("📁 Sauvegarde des fichiers du projet...", 'blue');
    
    // Créer la commande tar avec exclusions
    $excludeArgs = '';
    foreach ($config['exclude_dirs'] as $dir) {
        $excludeArgs .= " --exclude='$dir'";
    }
    
    $tarCmd = "cd " . dirname($config['project_path']) . " && tar -czf $backupDir/csar_files_$timestamp.tar.gz $excludeArgs " . basename($config['project_path']);
    
    if (!executeCommand($tarCmd, "Sauvegarde des fichiers")) {
        return false;
    }
    
    // Vérifier la taille du fichier de backup
    $filesBackup = "$backupDir/csar_files_$timestamp.tar.gz";
    $filesSize = filesize($filesBackup);
    if ($filesSize > 0) {
        logMessage("✅ Fichiers sauvegardés: " . number_format($filesSize / 1024 / 1024, 2) . " MB", 'green');
    } else {
        logMessage("❌ Erreur: Fichier de backup vide", 'red');
        return false;
    }
    
    // 4. Créer un fichier d'information
    $infoFile = "$backupDir/backup_info.txt";
    $info = [
        "Date de backup: " . date('Y-m-d H:i:s'),
        "Version PHP: " . PHP_VERSION,
        "Serveur: " . gethostname(),
        "Taille DB: " . number_format($dbSize / 1024, 2) . " KB",
        "Taille fichiers: " . number_format($filesSize / 1024 / 1024, 2) . " MB",
        "Total: " . number_format(($dbSize + $filesSize) / 1024 / 1024, 2) . " MB"
    ];
    
    file_put_contents($infoFile, implode("\n", $info));
    logMessage("📋 Fichier d'information créé", 'green');
    
    // 5. Créer un script de restauration
    $restoreScript = "$backupDir/restore.sh";
    $restoreContent = "#!/bin/bash\n";
    $restoreContent .= "# Script de restauration CSAR Platform\n";
    $restoreContent .= "# Date: " . date('Y-m-d H:i:s') . "\n\n";
    $restoreContent .= "echo '🔄 Restauration CSAR Platform...'\n\n";
    $restoreContent .= "# Restaurer les fichiers\n";
    $restoreContent .= "echo '📁 Restauration des fichiers...'\n";
    $restoreContent .= "cd " . dirname($config['project_path']) . "\n";
    $restoreContent .= "tar -xzf csar_files_$timestamp.tar.gz\n\n";
    $restoreContent .= "# Restaurer la base de données\n";
    $restoreContent .= "echo '📊 Restauration de la base de données...'\n";
    $restoreContent .= "mysql -h{$config['db_host']} -u{$config['db_user']} -p{$config['db_pass']} {$config['db_name']} < csar_database_$timestamp.sql\n\n";
    $restoreContent .= "echo '✅ Restauration terminée!'\n";
    
    file_put_contents($restoreScript, $restoreContent);
    chmod($restoreScript, 0755);
    logMessage("🔧 Script de restauration créé", 'green');
    
    // 6. Nettoyer les anciens backups
    cleanOldBackups($config['backup_path'], $config['max_backups']);
    
    // 7. Résumé final
    logMessage("", 'white');
    logMessage("🎉 BACKUP TERMINÉ AVEC SUCCÈS!", 'green');
    logMessage("=====================================", 'green');
    logMessage("📁 Dossier de backup: $backupDir", 'white');
    logMessage("📊 Base de données: csar_database_$timestamp.sql", 'white');
    logMessage("📁 Fichiers: csar_files_$timestamp.tar.gz", 'white');
    logMessage("📋 Informations: backup_info.txt", 'white');
    logMessage("🔧 Restauration: restore.sh", 'white');
    logMessage("", 'white');
    logMessage("💾 Taille totale: " . number_format(($dbSize + $filesSize) / 1024 / 1024, 2) . " MB", 'cyan');
    
    return true;
}

// Vérifier si on est en ligne de commande
if (php_sapi_name() !== 'cli') {
    die("Ce script doit être exécuté en ligne de commande.\n");
}

// Exécuter le backup
if (main()) {
    exit(0);
} else {
    logMessage("❌ Backup échoué!", 'red');
    exit(1);
}
?>


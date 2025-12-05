#!/usr/bin/env php
<?php
/**
 * Script de sauvegarde automatique de la base de données MySQL
 * 
 * Usage: php database_backup.php
 * Cron: 0 2 * * * php /path/to/database_backup.php
 */

// Charger les variables d'environnement
require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

class DatabaseBackup
{
    private $backupPath;
    private $maxBackups = 30; // Garder 30 jours
    private $logFile;
    
    public function __construct()
    {
        $this->backupPath = storage_path('backups');
        $this->logFile = storage_path('logs/backup.log');
        
        // Créer le dossier de backup s'il n'existe pas
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }
    
    /**
     * Exécuter la sauvegarde complète
     */
    public function execute()
    {
        $this->log('=== Début de la sauvegarde automatique ===');
        $startTime = microtime(true);
        
        try {
            // 1. Sauvegarde de la base de données
            $dbBackupFile = $this->backupDatabase();
            $this->log("✅ Base de données sauvegardée: {$dbBackupFile}");
            
            // 2. Sauvegarde des fichiers uploads
            $filesBackupFile = $this->backupFiles();
            $this->log("✅ Fichiers sauvegardés: {$filesBackupFile}");
            
            // 3. Compression des sauvegardes
            $archiveFile = $this->createArchive($dbBackupFile, $filesBackupFile);
            $this->log("✅ Archive créée: {$archiveFile}");
            
            // 4. Upload vers stockage distant (si configuré)
            if ($this->isCloudStorageConfigured()) {
                $this->uploadToCloud($archiveFile);
                $this->log("✅ Sauvegarde uploadée vers le cloud");
            }
            
            // 5. Nettoyage des anciennes sauvegardes
            $this->cleanOldBackups();
            $this->log("✅ Anciennes sauvegardes nettoyées");
            
            $duration = round(microtime(true) - $startTime, 2);
            $this->log("=== Sauvegarde terminée avec succès en {$duration}s ===");
            
            // Envoyer notification de succès
            $this->notifySuccess($archiveFile, $duration);
            
            return true;
            
        } catch (\Exception $e) {
            $this->log("❌ ERREUR: " . $e->getMessage());
            $this->log("Stack trace: " . $e->getTraceAsString());
            
            // Envoyer notification d'erreur
            $this->notifyError($e);
            
            return false;
        }
    }
    
    /**
     * Sauvegarde de la base de données MySQL
     */
    private function backupDatabase()
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "database_backup_{$timestamp}.sql";
        $filepath = $this->backupPath . '/' . $filename;
        
        $dbConfig = config('database.connections.mysql');
        
        $host = $dbConfig['host'];
        $database = $dbConfig['database'];
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];
        
        // Commande mysqldump
        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s --single-transaction --routines --triggers %s > %s 2>&1',
            escapeshellarg($host),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($filepath)
        );
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception("Échec de mysqldump: " . implode("\n", $output));
        }
        
        // Vérifier que le fichier a été créé et n'est pas vide
        if (!file_exists($filepath) || filesize($filepath) < 1000) {
            throw new \Exception("Le fichier de sauvegarde est vide ou invalide");
        }
        
        return $filepath;
    }
    
    /**
     * Sauvegarde des fichiers uploads
     */
    private function backupFiles()
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "files_backup_{$timestamp}.tar.gz";
        $filepath = $this->backupPath . '/' . $filename;
        
        $storagePath = storage_path('app');
        $publicUploads = public_path('images');
        
        // Créer une archive tar.gz des fichiers
        $command = sprintf(
            'tar -czf %s -C %s . -C %s . 2>&1',
            escapeshellarg($filepath),
            escapeshellarg($storagePath),
            escapeshellarg($publicUploads)
        );
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            $this->log("⚠️ Avertissement lors de la sauvegarde des fichiers: " . implode("\n", $output));
        }
        
        return $filepath;
    }
    
    /**
     * Créer une archive complète
     */
    private function createArchive($dbFile, $filesFile)
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $archiveName = "csar_backup_{$timestamp}.tar.gz";
        $archivePath = $this->backupPath . '/' . $archiveName;
        
        $command = sprintf(
            'tar -czf %s -C %s %s %s 2>&1',
            escapeshellarg($archivePath),
            escapeshellarg($this->backupPath),
            escapeshellarg(basename($dbFile)),
            escapeshellarg(basename($filesFile))
        );
        
        exec($command, $output, $returnCode);
        
        if ($returnCode !== 0) {
            throw new \Exception("Échec de la création de l'archive: " . implode("\n", $output));
        }
        
        // Supprimer les fichiers temporaires
        @unlink($dbFile);
        @unlink($filesFile);
        
        return $archivePath;
    }
    
    /**
     * Vérifier si le stockage cloud est configuré
     */
    private function isCloudStorageConfigured()
    {
        return config('filesystems.disks.s3.key') || 
               config('filesystems.disks.google.key_file');
    }
    
    /**
     * Upload vers le cloud (AWS S3, Google Cloud, etc.)
     */
    private function uploadToCloud($filepath)
    {
        $disk = config('backup.cloud_disk', 's3'); // s3, google, etc.
        
        if (!config("filesystems.disks.{$disk}")) {
            $this->log("⚠️ Stockage cloud non configuré");
            return;
        }
        
        $filename = basename($filepath);
        $cloudPath = 'backups/csar/' . $filename;
        
        Storage::disk($disk)->put(
            $cloudPath,
            file_get_contents($filepath)
        );
        
        $this->log("☁️ Uploadé vers {$disk}: {$cloudPath}");
    }
    
    /**
     * Nettoyer les anciennes sauvegardes (garder les 30 derniers jours)
     */
    private function cleanOldBackups()
    {
        $files = glob($this->backupPath . '/csar_backup_*.tar.gz');
        
        // Trier par date (les plus anciens en premier)
        usort($files, function($a, $b) {
            return filemtime($a) - filemtime($b);
        });
        
        $deleted = 0;
        $totalFiles = count($files);
        
        // Garder seulement les N derniers
        if ($totalFiles > $this->maxBackups) {
            $filesToDelete = array_slice($files, 0, $totalFiles - $this->maxBackups);
            
            foreach ($filesToDelete as $file) {
                if (unlink($file)) {
                    $deleted++;
                }
            }
        }
        
        $this->log("🗑️ {$deleted} ancienne(s) sauvegarde(s) supprimée(s)");
    }
    
    /**
     * Envoyer une notification de succès
     */
    private function notifySuccess($archiveFile, $duration)
    {
        $fileSize = $this->formatBytes(filesize($archiveFile));
        
        $message = "✅ Sauvegarde automatique réussie\n\n";
        $message .= "📁 Fichier: " . basename($archiveFile) . "\n";
        $message .= "📊 Taille: {$fileSize}\n";
        $message .= "⏱️ Durée: {$duration}s\n";
        $message .= "📅 Date: " . Carbon::now()->format('d/m/Y H:i:s');
        
        // Créer une notification dans le système
        \App\Models\Notification::create([
            'title' => 'Sauvegarde automatique réussie',
            'message' => $message,
            'type' => 'success',
            'read' => false
        ]);
        
        // Envoyer un email aux administrateurs (optionnel)
        $this->sendEmailNotification('Sauvegarde réussie', $message);
    }
    
    /**
     * Envoyer une notification d'erreur
     */
    private function notifyError($exception)
    {
        $message = "❌ Échec de la sauvegarde automatique\n\n";
        $message .= "Erreur: " . $exception->getMessage() . "\n";
        $message .= "Fichier: " . $exception->getFile() . ":" . $exception->getLine();
        
        // Créer une notification dans le système
        \App\Models\Notification::create([
            'title' => 'ERREUR - Sauvegarde automatique',
            'message' => $message,
            'type' => 'error',
            'read' => false
        ]);
        
        // Envoyer un email urgent aux administrateurs
        $this->sendEmailNotification('URGENT - Échec sauvegarde', $message, true);
    }
    
    /**
     * Envoyer un email de notification
     */
    private function sendEmailNotification($subject, $message, $urgent = false)
    {
        try {
            $adminEmails = \App\Models\User::where('role', 'admin')
                ->pluck('email')
                ->toArray();
            
            foreach ($adminEmails as $email) {
                \Illuminate\Support\Facades\Mail::raw($message, function($mail) use ($email, $subject, $urgent) {
                    $mail->to($email)
                        ->subject($subject . ' - CSAR Platform')
                        ->priority($urgent ? 1 : 3);
                });
            }
        } catch (\Exception $e) {
            $this->log("⚠️ Impossible d'envoyer l'email: " . $e->getMessage());
        }
    }
    
    /**
     * Logger les messages
     */
    private function log($message)
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$message}\n";
        
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        echo $logMessage;
    }
    
    /**
     * Formater la taille en octets
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

// Exécuter la sauvegarde
$backup = new DatabaseBackup();
$success = $backup->execute();

exit($success ? 0 : 1);























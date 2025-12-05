<?php

/**
 * Script d'optimisation des images pour CSAR
 * Compresse et redimensionne les images pour améliorer les performances
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizer
{
    private $manager;
    private $optimizedCount = 0;
    private $savedSpace = 0;
    
    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }
    
    /**
     * Optimiser toutes les images
     */
    public function optimizeAll()
    {
        echo "🚀 Début de l'optimisation des images...\n\n";
        
        // Optimiser les images du dossier public/images
        $this->optimizeDirectory(public_path('images'));
        
        // Optimiser les images du storage
        $this->optimizeDirectory(storage_path('app/public'));
        
        echo "\n✅ Optimisation terminée !\n";
        echo "📊 Images optimisées : {$this->optimizedCount}\n";
        echo "💾 Espace économisé : " . $this->formatBytes($this->savedSpace) . "\n";
    }
    
    /**
     * Optimiser un répertoire
     */
    private function optimizeDirectory(string $directory)
    {
        if (!is_dir($directory)) {
            return;
        }
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $this->isImage($file->getPathname())) {
                $this->optimizeImage($file->getPathname());
            }
        }
    }
    
    /**
     * Vérifier si le fichier est une image
     */
    private function isImage(string $filepath): bool
    {
        $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }
    
    /**
     * Optimiser une image
     */
    private function optimizeImage(string $filepath)
    {
        try {
            $originalSize = filesize($filepath);
            
            // Charger l'image
            $image = $this->manager->read($filepath);
            
            // Redimensionner si trop grande (max 1920px de largeur)
            if ($image->width() > 1920) {
                $image->scaleDown(width: 1920);
            }
            
            // Optimiser selon le type
            $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
            
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image->toJpeg(85); // Qualité 85%
                    break;
                case 'png':
                    $image->toPng(8); // Compression 8
                    break;
                case 'gif':
                    $image->toGif();
                    break;
                case 'webp':
                    $image->toWebp(85); // Qualité 85%
                    break;
            }
            
            // Sauvegarder
            $image->save($filepath);
            
            $newSize = filesize($filepath);
            $saved = $originalSize - $newSize;
            
            if ($saved > 0) {
                $this->optimizedCount++;
                $this->savedSpace += $saved;
                
                echo "✅ " . basename($filepath) . " - Économisé: " . $this->formatBytes($saved) . "\n";
            }
            
        } catch (Exception $e) {
            echo "❌ Erreur avec " . basename($filepath) . ": " . $e->getMessage() . "\n";
        }
    }
    
    /**
     * Formater les bytes en unités lisibles
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Créer des versions WebP des images
     */
    public function createWebPVersions()
    {
        echo "🔄 Création des versions WebP...\n\n";
        
        $this->createWebPForDirectory(public_path('images'));
        $this->createWebPForDirectory(storage_path('app/public'));
        
        echo "\n✅ Versions WebP créées !\n";
    }
    
    /**
     * Créer des versions WebP pour un répertoire
     */
    private function createWebPForDirectory(string $directory)
    {
        if (!is_dir($directory)) {
            return;
        }
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $this->isImage($file->getPathname())) {
                $this->createWebP($file->getPathname());
            }
        }
    }
    
    /**
     * Créer une version WebP d'une image
     */
    private function createWebP(string $filepath)
    {
        $extension = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        
        // Ne pas créer de WebP pour les images déjà en WebP
        if ($extension === 'webp') {
            return;
        }
        
        try {
            $webpPath = str_replace('.' . $extension, '.webp', $filepath);
            
            // Vérifier si le WebP existe déjà
            if (file_exists($webpPath)) {
                return;
            }
            
            $image = $this->manager->read($filepath);
            $image->toWebp(85)->save($webpPath);
            
            echo "✅ WebP créé: " . basename($webpPath) . "\n";
            
        } catch (Exception $e) {
            echo "❌ Erreur WebP pour " . basename($filepath) . ": " . $e->getMessage() . "\n";
        }
    }
}

// Exécution du script
if (php_sapi_name() === 'cli') {
    $optimizer = new ImageOptimizer();
    
    echo "CSAR - Optimiseur d'images\n";
    echo "========================\n\n";
    
    // Optimiser les images existantes
    $optimizer->optimizeAll();
    
    echo "\n";
    
    // Créer les versions WebP
    $optimizer->createWebPVersions();
    
    echo "\n🎉 Optimisation terminée avec succès !\n";
}



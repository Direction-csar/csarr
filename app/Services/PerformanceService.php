<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PerformanceService
{
    private $imageManager;
    
    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }
    
    /**
     * Optimiser une image
     */
    public function optimizeImage(string $path, array $options = []): bool
    {
        try {
            $defaultOptions = [
                'quality' => config('performance.images.quality', 85),
                'max_width' => config('performance.images.max_width', 1920),
                'format' => null, // null = garder le format original
            ];
            
            $options = array_merge($defaultOptions, $options);
            
            if (!Storage::exists($path)) {
                return false;
            }
            
            $image = $this->imageManager->read(Storage::path($path));
            
            // Redimensionner si nécessaire
            if ($image->width() > $options['max_width']) {
                $image->scaleDown(width: $options['max_width']);
            }
            
            // Optimiser selon le format
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image->toJpeg($options['quality']);
                    break;
                case 'png':
                    $image->toPng(8);
                    break;
                case 'webp':
                    $image->toWebp($options['quality']);
                    break;
            }
            
            // Sauvegarder
            $image->save(Storage::path($path));
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Erreur optimisation image: ' . $e->getMessage(), [
                'path' => $path,
                'options' => $options
            ]);
            
            return false;
        }
    }
    
    /**
     * Créer une version WebP d'une image
     */
    public function createWebPVersion(string $path): ?string
    {
        try {
            if (!config('performance.images.webp_enabled', true)) {
                return null;
            }
            
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            
            if ($extension === 'webp') {
                return $path;
            }
            
            $webpPath = str_replace('.' . $extension, '.webp', $path);
            
            if (Storage::exists($webpPath)) {
                return $webpPath;
            }
            
            $image = $this->imageManager->read(Storage::path($path));
            $image->toWebp(config('performance.images.quality', 85));
            $image->save(Storage::path($webpPath));
            
            return $webpPath;
            
        } catch (\Exception $e) {
            Log::error('Erreur création WebP: ' . $e->getMessage(), [
                'path' => $path
            ]);
            
            return null;
        }
    }
    
    /**
     * Mettre en cache une requête
     */
    public function cacheQuery(string $key, callable $callback, int $ttl = null): mixed
    {
        if (!config('performance.cache.enabled', true)) {
            return $callback();
        }
        
        $ttl = $ttl ?? config('performance.cache.ttl', 3600);
        $cacheKey = config('performance.cache.prefix', 'csar_perf') . ':' . $key;
        
        return Cache::remember($cacheKey, $ttl, $callback);
    }
    
    /**
     * Mettre en cache les résultats de recherche
     */
    public function cacheSearchResults(string $query, callable $callback): mixed
    {
        if (!config('performance.search.cache_results', true)) {
            return $callback();
        }
        
        $key = 'search:' . md5($query);
        $ttl = config('performance.search.cache_ttl', 1800);
        
        return $this->cacheQuery($key, $callback, $ttl);
    }
    
    /**
     * Optimiser le HTML
     */
    public function optimizeHtml(string $html): string
    {
        // Supprimer les commentaires HTML
        $html = preg_replace('/<!--(?!\s*(?:\[if [^]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);
        
        // Supprimer les espaces multiples
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Supprimer les espaces autour des balises
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Supprimer les espaces en début et fin de ligne
        $html = preg_replace('/^\s+|\s+$/m', '', $html);
        
        return trim($html);
    }
    
    /**
     * Générer des URLs CDN
     */
    public function getCdnUrl(string $path): string
    {
        if (!config('performance.cdn.enabled', false)) {
            return asset($path);
        }
        
        $cdnUrl = rtrim(config('performance.cdn.url', ''), '/');
        $assetPath = ltrim($path, '/');
        
        return $cdnUrl . '/' . $assetPath;
    }
    
    /**
     * Vérifier si une ressource doit être servie via CDN
     */
    public function shouldUseCdn(string $path): bool
    {
        if (!config('performance.cdn.enabled', false)) {
            return false;
        }
        
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            return config('performance.cdn.assets.images', true);
        }
        
        if (in_array($extension, ['css'])) {
            return config('performance.cdn.assets.css', true);
        }
        
        if (in_array($extension, ['js'])) {
            return config('performance.cdn.assets.js', true);
        }
        
        return false;
    }
    
    /**
     * Mesurer les performances d'une requête
     */
    public function measureQuery(string $name, callable $callback): mixed
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        $result = $callback();
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $executionTime = ($endTime - $startTime) * 1000; // en millisecondes
        $memoryUsed = $endMemory - $startMemory;
        
        // Log si la requête est lente
        $slowThreshold = config('performance.monitoring.slow_request_threshold', 2000);
        if ($executionTime > $slowThreshold) {
            Log::warning('Requête lente détectée', [
                'name' => $name,
                'execution_time' => $executionTime . 'ms',
                'memory_used' => $this->formatBytes($memoryUsed)
            ]);
        }
        
        return $result;
    }
    
    /**
     * Formater les bytes
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
     * Nettoyer le cache de performance
     */
    public function clearPerformanceCache(): bool
    {
        try {
            $prefix = config('performance.cache.prefix', 'csar_perf');
            
            // Supprimer toutes les clés de cache de performance
            $keys = Cache::getRedis()->keys($prefix . ':*');
            
            if (!empty($keys)) {
                Cache::getRedis()->del($keys);
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Erreur nettoyage cache performance: ' . $e->getMessage());
            return false;
        }
    }
}



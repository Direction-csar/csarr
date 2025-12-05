<?php

namespace App\Helpers;

class VideoHelper
{
    /**
     * Convertit une URL YouTube en URL d'embed
     */
    public static function convertToEmbedUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        // Si c'est déjà une URL d'embed, la retourner telle quelle
        if (strpos($url, '/embed/') !== false) {
            return $url;
        }

        // Extraire l'ID de la vidéo selon différents formats
        $videoId = null;

        // Format: https://www.youtube.com/watch?v=VIDEO_ID
        if (preg_match('/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Format: https://youtu.be/VIDEO_ID
        elseif (preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
        // Format: https://www.youtube.com/embed/VIDEO_ID
        elseif (preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }

        if ($videoId) {
            return "https://www.youtube.com/embed/{$videoId}";
        }

        // Si on ne peut pas convertir, retourner l'URL originale
        return $url;
    }

    /**
     * Extrait l'ID de la vidéo YouTube
     */
    public static function extractVideoId($url)
    {
        if (empty($url)) {
            return null;
        }

        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
            '/youtu\.be\/([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Génère une miniature YouTube
     */
    public static function getThumbnailUrl($url, $quality = 'maxresdefault')
    {
        $videoId = self::extractVideoId($url);
        
        if ($videoId) {
            return "https://img.youtube.com/vi/{$videoId}/{$quality}.jpg";
        }

        return null;
    }

    /**
     * Vérifie si l'URL est une vidéo YouTube valide
     */
    public static function isYouTubeUrl($url)
    {
        return strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false;
    }
}










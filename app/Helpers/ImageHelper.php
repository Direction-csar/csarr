<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Résout le chemin d'une image en fonction de son type et de sa localisation
     *
     * @param string|null $imagePath Le chemin de l'image stocké en base de données
     * @return string L'URL complète de l'image
     */
    public static function resolveImageUrl($imagePath)
    {
        if (empty($imagePath)) {
            return asset('images/logos/LOGO CSAR vectoriel-01.png');
        }

        // Si c'est déjà une URL complète (http/https), la retourner telle quelle
        if (preg_match('/^https?:\/\//i', $imagePath)) {
            return $imagePath;
        }

        // Si le chemin commence par 'storage/', utiliser asset() directement
        if (strpos($imagePath, 'storage/') === 0) {
            return asset($imagePath);
        }

        // Si le chemin commence par 'images/', utiliser asset() directement
        if (strpos($imagePath, 'images/') === 0) {
            return asset($imagePath);
        }

        // Vérifier l'existence du fichier dans différents répertoires
        if (file_exists(public_path('storage/' . $imagePath))) {
            return asset('storage/' . $imagePath);
        }

        if (file_exists(public_path('images/' . $imagePath))) {
            return asset('images/' . $imagePath);
        }

        if (file_exists(public_path('images/bloc/' . $imagePath))) {
            return asset('images/bloc/' . $imagePath);
        }

        // Par défaut, essayer avec storage/
        return asset('storage/' . $imagePath);
    }

    /**
     * Génère le code HTML pour afficher une image avec fallback
     *
     * @param string|null $imagePath Le chemin de l'image
     * @param string $alt Le texte alternatif
     * @param string $class Les classes CSS
     * @param array $attributes Attributs HTML supplémentaires
     * @return string Le code HTML de l'image
     */
    public static function imageTag($imagePath, $alt = '', $class = '', $attributes = [])
    {
        $imageUrl = self::resolveImageUrl($imagePath);
        $fallbackUrl = asset('images/logos/LOGO CSAR vectoriel-01.png');
        
        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }
        
        return '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($alt) . '" class="' . $class . '" onerror="this.src=\'' . $fallbackUrl . '\'"' . $attrString . '>';
    }
}





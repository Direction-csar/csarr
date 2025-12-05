<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ChiffreCle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ChiffresClesController extends Controller
{
    /**
     * Récupérer les chiffres clés pour les pages publiques
     */
    public function getChiffresCles()
    {
        try {
            // Vérifier si la table existe
            if (!Schema::hasTable('chiffres_cles')) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }
            
            // Essayer de récupérer les chiffres clés, avec fallback en cas d'erreur
            try {
                $chiffresCles = ChiffreCle::safeGetActifs()
                    ->map(function ($chiffre) {
                        return [
                            'id' => $chiffre->id,
                            'icone' => $chiffre->icone,
                            'titre' => $chiffre->titre,
                            'valeur' => $chiffre->valeur,
                            'description' => $chiffre->description,
                            'couleur' => $chiffre->couleur_complete,
                            'ordre' => $chiffre->ordre
                        ];
                    });
            } catch (\Exception $e) {
                // En cas d'erreur, retourner un tableau vide
                $chiffresCles = collect();
            }
            
            return response()->json([
                'success' => true,
                'data' => $chiffresCles
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des chiffres clés.'
            ], 500);
        }
    }

    /**
     * Récupérer les chiffres clés pour l'affichage dans les vues
     */
    public static function getChiffresClesForView()
    {
        try {
            // Vérifier si la table existe
            if (!Schema::hasTable('chiffres_cles')) {
                return collect();
            }
            
            // Essayer de récupérer les chiffres clés, avec fallback en cas d'erreur
            try {
                return ChiffreCle::safeGetActifs();
            } catch (\Exception $e) {
                // En cas d'erreur, retourner une collection vide
                return collect();
            }
        } catch (\Exception $e) {
            return collect(); // Retourner une collection vide en cas d'erreur
        }
    }
}

<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ChiffreCle;
use Illuminate\Http\Request;

class ChiffresClesController extends Controller
{
    /**
     * Récupérer les chiffres clés pour les pages publiques
     */
    public function getChiffresCles()
    {
        try {
            $chiffresCles = ChiffreCle::actifs()
                ->ordered()
                ->get()
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
            return ChiffreCle::actifs()
                ->ordered()
                ->get();
        } catch (\Exception $e) {
            return collect(); // Retourner une collection vide en cas d'erreur
        }
    }
}

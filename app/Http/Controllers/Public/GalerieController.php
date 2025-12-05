<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GalerieController extends Controller
{
    /**
     * Afficher la galerie publique
     */
    public function index(Request $request)
    {
        try {
            $images = \App\Models\GalleryImage::where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();

            // Appliquer les filtres
            if ($request->filled('categorie')) {
                $images = $images->where('category', $request->categorie);
            }
            if ($request->filled('search')) {
                $search = strtolower($request->search);
                $images = $images->filter(function($image) use ($search) {
                    return str_contains(strtolower($image->title), $search) ||
                           str_contains(strtolower($image->description), $search);
                });
            }

            // Statistiques
            $stats = [
                'total' => $images->count(),
                'actif' => $images->where('status', 'active')->count(),
                'inactif' => $images->where('status', 'inactive')->count(),
                'taille_totale' => $images->sum('file_size'),
                'par_categorie' => $images->groupBy('category')->map->count()->toArray(),
                'derniere_mise_a_jour' => Carbon::now()
            ];

            Log::info('Accès à la galerie publique', [
                'ip' => request()->ip(),
                'filters' => $request->only(['categorie', 'search'])
            ]);

            return view('public.galerie.index', compact('images', 'stats'));

        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de la galerie publique', [
                'error' => $e->getMessage(),
                'ip' => request()->ip()
            ]);

            return redirect()->back()->with('error', 'Erreur lors du chargement de la galerie.');
        }
    }

    /**
     * API pour récupérer les statistiques de la galerie
     */
    public function getStats()
    {
        try {
            $images = \App\Models\GalleryImage::all();
            
            $stats = [
                'total' => $images->count(),
                'actif' => $images->where('status', 'active')->count(),
                'inactif' => $images->where('status', 'inactive')->count(),
                'taille_totale' => $images->sum('file_size'),
                'par_categorie' => $images->groupBy('category')->map->count()->toArray(),
                'derniere_mise_a_jour' => Carbon::now()->toISOString(),
                'status' => 'success'
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            Log::error('Erreur API statistiques galerie publique', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }
}
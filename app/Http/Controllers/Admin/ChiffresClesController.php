<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChiffreCle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChiffresClesController extends Controller
{
    /**
     * Afficher la liste des chiffres clés
     */
    public function index()
    {
        try {
            $chiffresCles = ChiffreCle::ordered()->get();
            
            return view('admin.chiffres-cles.index', compact('chiffresCles'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des chiffres clés', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors du chargement des chiffres clés.');
        }
    }

    /**
     * Afficher le formulaire d'édition d'un chiffre clé
     */
    public function edit($id)
    {
        try {
            $chiffreCle = ChiffreCle::findOrFail($id);
            
            return view('admin.chiffres-cles.edit', compact('chiffreCle'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du chiffre clé', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.chiffres-cles.index')
                ->with('error', 'Chiffre clé non trouvé.');
        }
    }

    /**
     * Mettre à jour un chiffre clé
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'valeur' => 'required|string|max:255',
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'icone' => 'required|string|max:255',
            'couleur' => 'required|string|max:7',
            'ordre' => 'required|integer|min:1',
            'statut' => 'required|in:Actif,Inactif'
        ]);

        try {
            DB::beginTransaction();
            
            $chiffreCle = ChiffreCle::findOrFail($id);
            
            $chiffreCle->update([
                'valeur' => $request->valeur,
                'titre' => $request->titre,
                'description' => $request->description,
                'icone' => $request->icone,
                'couleur' => $request->couleur,
                'ordre' => $request->ordre,
                'statut' => $request->statut
            ]);
            
            DB::commit();
            
            Log::info('Chiffre clé mis à jour', [
                'id' => $id,
                'titre' => $chiffreCle->titre,
                'valeur' => $request->valeur,
                'user' => auth()->user()->name ?? 'System'
            ]);
            
            return redirect()->route('admin.chiffres-cles.index')
                ->with('success', 'Chiffre clé mis à jour avec succès !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la mise à jour du chiffre clé', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du chiffre clé.');
        }
    }

    /**
     * Mettre à jour plusieurs chiffres clés en lot
     */
    public function updateBatch(Request $request)
    {
        $request->validate([
            'chiffres' => 'required|array',
            'chiffres.*.id' => 'required|exists:chiffres_cles,id',
            'chiffres.*.valeur' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();
            
            $updated = 0;
            foreach ($request->chiffres as $chiffreData) {
                $chiffreCle = ChiffreCle::find($chiffreData['id']);
                if ($chiffreCle) {
                    $chiffreCle->update(['valeur' => $chiffreData['valeur']]);
                    $updated++;
                }
            }
            
            DB::commit();
            
            Log::info('Mise à jour en lot des chiffres clés', [
                'updated_count' => $updated,
                'user' => auth()->user()->name ?? 'System'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "{$updated} chiffres clés mis à jour avec succès !"
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la mise à jour en lot', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour en lot.'
            ], 500);
        }
    }

    /**
     * Basculer le statut d'un chiffre clé
     */
    public function toggleStatus($id)
    {
        try {
            $chiffreCle = ChiffreCle::findOrFail($id);
            $chiffreCle->basculerStatut();
            
            $status = $chiffreCle->statut === 'Actif' ? 'activé' : 'désactivé';
            
            Log::info('Statut du chiffre clé basculé', [
                'id' => $id,
                'titre' => $chiffreCle->titre,
                'nouveau_statut' => $chiffreCle->statut,
                'user' => auth()->user()->name ?? 'System'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Chiffre clé {$status} avec succès.",
                'statut' => $chiffreCle->statut
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du basculement du statut', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du basculement du statut.'
            ], 500);
        }
    }

    /**
     * Réinitialiser tous les chiffres clés
     */
    public function reset()
    {
        try {
            DB::beginTransaction();
            
            // Réinitialiser tous les chiffres clés à leurs valeurs par défaut
            $defaultValues = [
                1 => '137', // Agents mobilisés
                2 => '71',  // Entrepôts de stockage
                3 => '79',  // Capacité en tonnes
                4 => '14',  // Régions couvertes
                5 => '50',  // Années d'expérience
                6 => '15598', // Demandes traitées
                7 => '94.5%'  // Taux de satisfaction
            ];
            
            $updated = 0;
            foreach ($defaultValues as $id => $defaultValue) {
                $chiffreCle = ChiffreCle::find($id);
                if ($chiffreCle) {
                    $chiffreCle->update(['valeur' => $defaultValue]);
                    $updated++;
                }
            }
            
            DB::commit();
            
            Log::info('Réinitialisation des chiffres clés', [
                'updated_count' => $updated,
                'user' => auth()->user()->name ?? 'System'
            ]);
            
            return redirect()->route('admin.chiffres-cles.index')
                ->with('success', "{$updated} chiffres clés réinitialisés avec succès !");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la réinitialisation', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->with('error', 'Erreur lors de la réinitialisation des chiffres clés.');
        }
    }

    /**
     * API pour récupérer les chiffres clés (pour les pages publiques)
     */
    public function api()
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
            Log::error('Erreur lors de la récupération API des chiffres clés', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des chiffres clés.'
            ], 500);
        }
    }
}
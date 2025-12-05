<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AboutStatisticsController extends Controller
{
    /**
     * Afficher la liste des statistiques
     */
    public function index()
    {
        $statistics = AboutStatistic::orderBy('order')->get();
        return view('admin.about-statistics.index', compact('statistics'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.about-statistics.create');
    }

    /**
     * Enregistrer une nouvelle statistique
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
            'order' => 'required|integer|min:0'
        ]);

        try {
            AboutStatistic::create($request->all());
            
            Log::info('Nouvelle statistique créée', [
                'title' => $request->title,
                'value' => $request->value,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.about-statistics.index')
                ->with('success', 'Statistique créée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la statistique', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return back()->withInput()
                ->with('error', 'Erreur lors de la création de la statistique.');
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(AboutStatistic $aboutStatistic)
    {
        return view('admin.about-statistics.edit', compact('aboutStatistic'));
    }

    /**
     * Mettre à jour une statistique
     */
    public function update(Request $request, AboutStatistic $aboutStatistic)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
            'order' => 'required|integer|min:0'
        ]);

        try {
            $aboutStatistic->update($request->all());
            
            Log::info('Statistique mise à jour', [
                'id' => $aboutStatistic->id,
                'title' => $request->title,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.about-statistics.index')
                ->with('success', 'Statistique mise à jour avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la statistique', [
                'error' => $e->getMessage(),
                'id' => $aboutStatistic->id,
                'user_id' => auth()->id()
            ]);

            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour de la statistique.');
        }
    }

    /**
     * Supprimer une statistique
     */
    public function destroy(AboutStatistic $aboutStatistic)
    {
        try {
            $title = $aboutStatistic->title;
            $aboutStatistic->delete();
            
            Log::info('Statistique supprimée', [
                'title' => $title,
                'user_id' => auth()->id()
            ]);

            return redirect()->route('admin.about-statistics.index')
                ->with('success', 'Statistique supprimée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la statistique', [
                'error' => $e->getMessage(),
                'id' => $aboutStatistic->id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erreur lors de la suppression de la statistique.');
        }
    }

    /**
     * Toggle l'état actif d'une statistique
     */
    public function toggle(AboutStatistic $aboutStatistic)
    {
        try {
            $aboutStatistic->update(['is_active' => !$aboutStatistic->is_active]);
            
            $status = $aboutStatistic->is_active ? 'activée' : 'désactivée';
            
            Log::info('Statistique ' . $status, [
                'id' => $aboutStatistic->id,
                'title' => $aboutStatistic->title,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Statistique {$status} avec succès.",
                'is_active' => $aboutStatistic->is_active
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du toggle de la statistique', [
                'error' => $e->getMessage(),
                'id' => $aboutStatistic->id,
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification de la statistique.'
            ], 500);
        }
    }
}


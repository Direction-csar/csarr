<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Statistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ContentController extends Controller
{
    /**
     * Afficher la liste du contenu et des statistiques
     */
    public function index(Request $request)
    {
        try {
            // Statistiques du contenu
            $stats = $this->getContentStats();
            
            // Statistiques dynamiques pour la page À propos
            $aboutStats = Statistics::active()->forSection('about')->ordered()->get();
            
            // TODO: Implémenter la vraie logique de récupération depuis la base de données
            $content = collect(); // Collection vide en attendant l'implémentation
            
            return view('admin.content.index', compact('content', 'stats', 'aboutStats'));
            
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return view('admin.content.index', [
                'content' => collect(),
                'stats' => $this->getDefaultStats(),
                'aboutStats' => collect()
            ]);
        }
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.content.create');
    }
    
    /**
     * Enregistrer un nouveau contenu
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|in:page,article,announcement',
                'category' => 'nullable|in:general,news,announcements',
                'status' => 'required|in:published,draft,scheduled',
                'content' => 'required|string',
                'slug' => 'nullable|string|max:255',
                'publish_date' => 'nullable|date'
            ]);
            
            // TODO: Implémenter la vraie logique de sauvegarde en base de données
            // Pour l'instant, on retourne une erreur car il n'y a pas encore de table de contenu
            return response()->json([
                'success' => false,
                'message' => 'Fonctionnalité de contenu non encore implémentée. Veuillez créer une table de contenu d\'abord.'
            ], 501);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@store', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du contenu'
            ], 500);
        }
    }
    
    /**
     * Afficher les détails d'un contenu
     */
    public function show($id)
    {
        try {
            // TODO: Implémenter la vraie récupération depuis la base de données
            return redirect()->route('admin.content.index')
                ->with('error', 'Fonctionnalité de contenu non encore implémentée');
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@show', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'content_id' => $id
            ]);
            
            return redirect()->route('admin.content.index')
                ->with('error', 'Contenu non trouvé');
        }
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        try {
            // TODO: Implémenter la vraie récupération depuis la base de données
            return redirect()->route('admin.content.index')
                ->with('error', 'Fonctionnalité de contenu non encore implémentée');
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@edit', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'content_id' => $id
            ]);
            
            return redirect()->route('admin.content.index')
                ->with('error', 'Contenu non trouvé');
        }
    }
    
    /**
     * Mettre à jour un contenu
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|in:page,article,announcement',
                'category' => 'nullable|in:general,news,announcements',
                'status' => 'required|in:published,draft,scheduled',
                'content' => 'required|string',
                'slug' => 'nullable|string|max:255',
                'publish_date' => 'nullable|date'
            ]);
            
            // TODO: Implémenter la vraie logique de mise à jour en base de données
            return response()->json([
                'success' => false,
                'message' => 'Fonctionnalité de contenu non encore implémentée. Veuillez créer une table de contenu d\'abord.'
            ], 501);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@update', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'content_id' => $id,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du contenu'
            ], 500);
        }
    }
    
    /**
     * Supprimer un contenu
     */
    public function destroy($id)
    {
        try {
            // TODO: Implémenter la vraie logique de suppression en base de données
            return response()->json([
                'success' => false,
                'message' => 'Fonctionnalité de contenu non encore implémentée. Veuillez créer une table de contenu d\'abord.'
            ], 501);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@destroy', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'content_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du contenu'
            ], 500);
        }
    }
    
    /**
     * Publier/Dépublier un contenu
     */
    public function toggleStatus($id)
    {
        try {
            // TODO: Implémenter la vraie logique de changement de statut en base de données
            return response()->json([
                'success' => false,
                'message' => 'Fonctionnalité de contenu non encore implémentée. Veuillez créer une table de contenu d\'abord.'
            ], 501);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@toggleStatus', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'content_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }
    
    /**
     * Aperçu du site
     */
    public function preview()
    {
        try {
            // Redirection vers l'aperçu du site
            return redirect('/preview');
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@preview', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('admin.content.index')
                ->with('error', 'Erreur lors de l\'ouverture de l\'aperçu');
        }
    }
    
    /**
     * Obtenir les statistiques du contenu
     */
    private function getContentStats()
    {
        try {
            // Récupérer les vraies statistiques depuis la base de données
            $totalContent = 0;
            $publishedContent = 0;
            $draftContent = 0;
            $scheduledContent = 0;

            // Si vous avez une table de contenu, utilisez-la ici
            // Pour l'instant, on retourne des zéros car il n'y a pas encore de table de contenu
            // Vous pouvez ajouter une table 'contents' plus tard si nécessaire

            return [
                'total_pages' => $totalContent,
                'published_content' => $publishedContent,
                'draft_content' => $draftContent,
                'scheduled_content' => $scheduledContent
            ];
        } catch (\Exception $e) {
            Log::error('Erreur dans getContentStats', ['error' => $e->getMessage()]);
            return $this->getDefaultStats();
        }
    }
    
    /**
     * Statistiques par défaut
     */
    private function getDefaultStats()
    {
        return [
            'total_pages' => 0,
            'published_content' => 0,
            'draft_content' => 0,
            'scheduled_content' => 0
        ];
    }
    
    /**
     * Générer un slug à partir du titre
     */
    private function generateSlug($title)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }
    
    /**
     * Afficher la gestion des statistiques
     */
    public function statistics()
    {
        try {
            $statistics = Statistics::active()->forSection('about')->ordered()->get();
            
            return view('admin.content.statistics', compact('statistics'));
            
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@statistics', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('admin.content.index')
                ->with('error', 'Erreur lors du chargement des statistiques');
        }
    }
    
    /**
     * Mettre à jour une statistique
     */
    public function updateStatistic(Request $request, $id)
    {
        try {
            $request->validate([
                'value' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'icon' => 'nullable|string|max:50',
                'color' => 'nullable|string|max:7',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'notes' => 'nullable|string'
            ]);
            
            $statistic = Statistics::findOrFail($id);
            
            $statistic->update([
                'value' => $request->value,
                'description' => $request->description,
                'title' => $request->title,
                'icon' => $request->icon,
                'color' => $request->color,
                'order' => $request->order ?? $statistic->order,
                'is_active' => $request->has('is_active'),
                'notes' => $request->notes
            ]);
            
            $this->createNotification(
                'Statistique mise à jour',
                "La statistique '{$statistic->title}' a été mise à jour avec succès.",
                'success'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Statistique mise à jour avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@updateStatistic', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'statistic_id' => $id,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la statistique'
            ], 500);
        }
    }
    
    /**
     * Créer une nouvelle statistique
     */
    public function createStatistic(Request $request)
    {
        try {
            $request->validate([
                'key' => 'required|string|max:255|unique:statistics,key',
                'value' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'icon' => 'nullable|string|max:50',
                'color' => 'nullable|string|max:7',
                'order' => 'nullable|integer|min:0',
                'section' => 'required|string|max:50',
                'notes' => 'nullable|string'
            ]);
            
            $statistic = Statistics::create([
                'key' => $request->key,
                'value' => $request->value,
                'description' => $request->description,
                'title' => $request->title,
                'icon' => $request->icon,
                'color' => $request->color ?? '#22c55e',
                'order' => $request->order ?? 0,
                'section' => $request->section,
                'is_active' => true,
                'notes' => $request->notes
            ]);
            
            $this->createNotification(
                'Nouvelle statistique créée',
                "La statistique '{$statistic->title}' a été créée avec succès.",
                'success'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Statistique créée avec succès',
                'statistic' => $statistic
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@createStatistic', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la statistique'
            ], 500);
        }
    }
    
    /**
     * Supprimer une statistique
     */
    public function deleteStatistic($id)
    {
        try {
            $statistic = Statistics::findOrFail($id);
            $title = $statistic->title;
            
            $statistic->delete();
            
            $this->createNotification(
                'Statistique supprimée',
                "La statistique '{$title}' a été supprimée avec succès.",
                'warning'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Statistique supprimée avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans ContentController@deleteStatistic', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'statistic_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la statistique'
            ], 500);
        }
    }
    
    /**
     * Créer une notification
     */
    private function createNotification($title, $message, $type = 'info')
    {
        try {
            if (class_exists('App\Models\Notification')) {
                \App\Models\Notification::create([
                    'type' => $type,
                    'title' => $title,
                    'message' => $message,
                    'user_id' => auth()->id(),
                    'read' => false
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de notification', [
                'error' => $e->getMessage()
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class NewsController extends Controller
{
    /**
     * Afficher la liste des actualités
     */
    public function index(Request $request)
    {
        try {
            // Statistiques
            $stats = $this->getNewsStats();
            
            // Filtres
            $query = News::query();
            
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('content', 'like', '%' . $request->search . '%')
                      ->orWhere('author', 'like', '%' . $request->search . '%');
                });
            }
            
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }
            
            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);
            
            // Pagination
            $news = $query->paginate(15);
            
            return view('admin.news.index', compact('news', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Erreur dans NewsController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return view('admin.news.index', [
                'news' => collect(),
                'stats' => $this->getDefaultStats()
            ]);
        }
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.news.create');
    }
    
    /**
     * Enregistrer une nouvelle actualité
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'required|in:general,urgent,announcement,event',
                'author' => 'required|string|max:255',
                'status' => 'required|in:published,draft,scheduled',
                'excerpt' => 'nullable|string|max:500',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'publish_date' => 'nullable|date',
                'featured' => 'boolean',
                'allow_comments' => 'boolean'
            ]);
            
            // Traitement de l'image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('news', 'public');
            }
            
            // Création de l'actualité
            $news = News::create([
                'title' => $request->title,
                'category' => $request->category,
                'author' => $request->author,
                'status' => $request->status,
                'excerpt' => $request->excerpt,
                'content' => $request->content,
                'image' => $imagePath,
                'publish_date' => $request->publish_date ? Carbon::parse($request->publish_date) : now(),
                'featured' => $request->boolean('featured'),
                'allow_comments' => $request->boolean('allow_comments'),
                'views' => 0
            ]);
            
            // Déclencher l'événement si l'actualité est publiée
            if ($request->status === 'published') {
                event(new \App\Events\CommunicationPublished($news));
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Actualité créée avec succès',
                'news' => $news
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans NewsController@store', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'actualité'
            ], 500);
        }
    }
    
    /**
     * Afficher les détails d'une actualité
     */
    public function show($id)
    {
        try {
            $news = News::findOrFail($id);
            return view('admin.news.show', compact('news'));
        } catch (\Exception $e) {
            Log::error('Erreur dans NewsController@show', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'news_id' => $id
            ]);
            
            return redirect()->route('admin.news.index')
                ->with('error', 'Actualité non trouvée');
        }
    }
    
    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        try {
            $news = News::findOrFail($id);
            return view('admin.news.edit', compact('news'));
        } catch (\Exception $e) {
            Log::error('Erreur dans NewsController@edit', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'news_id' => $id
            ]);
            
            return redirect()->route('admin.news.index')
                ->with('error', 'Actualité non trouvée');
        }
    }
    
    /**
     * Mettre à jour une actualité
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'required|in:general,urgent,announcement,event',
                'author' => 'required|string|max:255',
                'status' => 'required|in:published,draft,scheduled',
                'excerpt' => 'nullable|string|max:500',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'publish_date' => 'nullable|date',
                'featured' => 'boolean',
                'allow_comments' => 'boolean'
            ]);
            
            $news = News::findOrFail($id);
            
            // Traitement de l'image
            $imagePath = $news->image;
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image
                if ($news->image) {
                    Storage::disk('public')->delete($news->image);
                }
                $imagePath = $request->file('image')->store('news', 'public');
            }
            
            // Mise à jour
            $news->update([
                'title' => $request->title,
                'category' => $request->category,
                'author' => $request->author,
                'status' => $request->status,
                'excerpt' => $request->excerpt,
                'content' => $request->content,
                'image' => $imagePath,
                'publish_date' => $request->publish_date ? Carbon::parse($request->publish_date) : $news->publish_date,
                'featured' => $request->boolean('featured'),
                'allow_comments' => $request->boolean('allow_comments')
            ]);
            
            // Créer une notification
            $this->createNotification(
                'Actualité modifiée',
                "L'actualité '{$news['title']}' a été mise à jour",
                'info'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Actualité mise à jour avec succès',
                'news' => $news
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans NewsController@update', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'news_id' => $id,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'actualité'
            ], 500);
        }
    }
    
    /**
     * Supprimer une actualité
     */
    public function destroy($id)
    {
        try {
            $news = News::findOrFail($id);
            $newsTitle = $news->title;
            
            // Supprimer l'image associée
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            
            // Supprimer l'actualité
            $news->delete();
            
            // Créer une notification
            $this->createNotification(
                'Actualité supprimée',
                "L'actualité '{$newsTitle}' a été supprimée",
                'warning'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Actualité supprimée avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans NewsController@destroy', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'news_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'actualité'
            ], 500);
        }
    }
    
    /**
     * Publier/Dépublier une actualité
     */
    public function toggleStatus($id)
    {
        try {
            $news = News::findOrFail($id);
            $newStatus = $news->status === 'published' ? 'draft' : 'published';
            $statusText = $newStatus === 'published' ? 'publiée' : 'dépubliée';
            
            $news->update(['status' => $newStatus]);
            
            // Créer une notification
            $this->createNotification(
                'Statut modifié',
                "L'actualité '{$news->title}' a été {$statusText}",
                'info'
            );
            
            return response()->json([
                'success' => true,
                'message' => "Actualité {$statusText} avec succès",
                'status' => $newStatus
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans NewsController@toggleStatus', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'news_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }
    
    /**
     * Mettre à la une/Retirer de la une
     */
    public function toggleFeatured($id)
    {
        try {
            $news = News::findOrFail($id);
            $featured = !$news->featured;
            $featuredText = $featured ? 'mise à la une' : 'retirée de la une';
            
            $news->update(['featured' => $featured]);
            
            // Créer une notification
            $this->createNotification(
                'Statut modifié',
                "L'actualité '{$news->title}' a été {$featuredText}",
                'info'
            );
            
            return response()->json([
                'success' => true,
                'message' => "Actualité {$featuredText} avec succès",
                'featured' => $featured
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans NewsController@toggleFeatured', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'news_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut'
            ], 500);
        }
    }
    
    /**
     * Aperçu public
     */
    public function preview()
    {
        try {
            // Redirection vers l'aperçu public
            return redirect('/news');
        } catch (\Exception $e) {
            Log::error('Erreur dans NewsController@preview', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('admin.news.index')
                ->with('error', 'Erreur lors de l\'ouverture de l\'aperçu');
        }
    }
    
    /**
     * Obtenir les statistiques des actualités
     */
    private function getNewsStats()
    {
        try {
            return [
                'total_news' => News::count(),
                'published_news' => News::where('status', 'published')->count(),
                'draft_news' => News::where('status', 'draft')->count(),
                'featured_news' => News::where('featured', true)->count()
            ];
        } catch (\Exception $e) {
            return $this->getDefaultStats();
        }
    }
    
    /**
     * Statistiques par défaut
     */
    private function getDefaultStats()
    {
        return [
            'total_news' => 0,
            'published_news' => 0,
            'draft_news' => 0,
            'featured_news' => 0
        ];
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

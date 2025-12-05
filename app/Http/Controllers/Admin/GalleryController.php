<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GalleryController extends Controller
{
    /**
     * Afficher la galerie
     */
    public function index(Request $request)
    {
        try {
            // Statistiques
            $stats = $this->getGalleryStats();
            
            // Filtres
            $query = collect(); // Simulation pour l'instant
            
            if ($request->filled('search')) {
                // Logique de recherche
            }
            
            if ($request->filled('album')) {
                // Filtre par album
            }
            
            if ($request->filled('type')) {
                // Filtre par type
            }
            
            if ($request->filled('size')) {
                // Filtre par taille
            }
            
            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            // Pagination simulée
            $media = $query->paginate(20);
            
            return view('admin.gallery.index', compact('media', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Erreur dans GalleryController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return view('admin.gallery.index', [
                'media' => collect(),
                'stats' => $this->getDefaultStats()
            ]);
        }
    }
    
    /**
     * Uploader des images
     */
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'images' => 'required|array|min:1',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
                'album' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'tags' => 'nullable|string|max:500',
                'optimize' => 'boolean'
            ]);
            
            $uploadedFiles = [];
            
            foreach ($request->file('images') as $file) {
                // Simulation d'upload (à remplacer par une vraie logique)
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = 'gallery/' . ($request->album ?? 'general') . '/' . $filename;
                
                $media = [
                    'id' => rand(1000, 9999),
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'album' => $request->album ?? 'general',
                    'description' => $request->description,
                    'tags' => $request->tags ? explode(',', $request->tags) : [],
                    'optimized' => $request->boolean('optimize'),
                    'views' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                
                $uploadedFiles[] = $media;
            }
            
            // Créer une notification
            $this->createNotification(
                'Images uploadées',
                count($uploadedFiles) . ' image(s) uploadée(s) avec succès',
                'success'
            );
            
            return response()->json([
                'success' => true,
                'message' => count($uploadedFiles) . ' image(s) uploadée(s) avec succès',
                'files' => $uploadedFiles
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans GalleryController@upload', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload des images'
            ], 500);
        }
    }
    
    /**
     * Créer un nouvel album
     */
    public function createAlbum(Request $request)
    {
        try {
            $request->validate([
                'album_name' => 'required|string|max:255',
                'album_description' => 'nullable|string|max:1000',
                'album_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'album_public' => 'boolean'
            ]);
            
            // Simulation de création d'album
            $album = [
                'id' => rand(1000, 9999),
                'name' => $request->album_name,
                'description' => $request->album_description,
                'cover' => $request->hasFile('album_cover') ? $request->file('album_cover')->store('albums') : null,
                'public' => $request->boolean('album_public'),
                'image_count' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Créer une notification
            $this->createNotification(
                'Nouvel album créé',
                "L'album '{$album['name']}' a été créé avec succès",
                'info'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Album créé avec succès',
                'album' => $album
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans GalleryController@createAlbum', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'album'
            ], 500);
        }
    }
    
    /**
     * Afficher les détails d'un média
     */
    public function show($id)
    {
        try {
            // Simulation de récupération
            $media = [
                'id' => $id,
                'filename' => 'exemple.jpg',
                'original_name' => 'exemple.jpg',
                'path' => 'gallery/general/exemple.jpg',
                'size' => 1024000,
                'mime_type' => 'image/jpeg',
                'album' => 'general',
                'description' => 'Description de l\'image...',
                'tags' => ['tag1', 'tag2'],
                'views' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            return view('admin.gallery.show', compact('media'));
        } catch (\Exception $e) {
            Log::error('Erreur dans GalleryController@show', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'media_id' => $id
            ]);
            
            return redirect()->route('admin.gallery.index')
                ->with('error', 'Média non trouvé');
        }
    }
    
    /**
     * Mettre à jour un média
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'description' => 'nullable|string|max:1000',
                'tags' => 'nullable|string|max:500',
                'album' => 'nullable|string|max:255'
            ]);
            
            // Simulation de mise à jour
            $media = [
                'id' => $id,
                'description' => $request->description,
                'tags' => $request->tags ? explode(',', $request->tags) : [],
                'album' => $request->album,
                'updated_at' => now()
            ];
            
            // Créer une notification
            $this->createNotification(
                'Média modifié',
                "Le média a été mis à jour",
                'info'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Média mis à jour avec succès',
                'media' => $media
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans GalleryController@update', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'media_id' => $id,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du média'
            ], 500);
        }
    }
    
    /**
     * Supprimer un média
     */
    public function destroy($id)
    {
        try {
            // Simulation de suppression
            $mediaName = 'Média supprimé';
            
            // Créer une notification
            $this->createNotification(
                'Média supprimé',
                "Le média '{$mediaName}' a été supprimé",
                'warning'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Média supprimé avec succès'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans GalleryController@destroy', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'media_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du média'
            ], 500);
        }
    }
    
    /**
     * Télécharger un média
     */
    public function download($id)
    {
        try {
            // Simulation de téléchargement
            $media = [
                'id' => $id,
                'filename' => 'exemple.jpg',
                'path' => 'gallery/general/exemple.jpg'
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Téléchargement démarré',
                'download_url' => '/download/' . $id
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans GalleryController@download', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'media_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du téléchargement'
            ], 500);
        }
    }
    
    /**
     * Déplacer des médias
     */
    public function move(Request $request)
    {
        try {
            $request->validate([
                'media_ids' => 'required|array|min:1',
                'media_ids.*' => 'integer',
                'target_album' => 'required|string|max:255'
            ]);
            
            $movedCount = count($request->media_ids);
            
            // Créer une notification
            $this->createNotification(
                'Médias déplacés',
                "{$movedCount} média(s) déplacé(s) vers l'album '{$request->target_album}'",
                'info'
            );
            
            return response()->json([
                'success' => true,
                'message' => "{$movedCount} média(s) déplacé(s) avec succès"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans GalleryController@move', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du déplacement des médias'
            ], 500);
        }
    }
    
    /**
     * Optimiser les images
     */
    public function optimize(Request $request)
    {
        try {
            $request->validate([
                'media_ids' => 'required|array|min:1',
                'media_ids.*' => 'integer'
            ]);
            
            $optimizedCount = count($request->media_ids);
            
            // Créer une notification
            $this->createNotification(
                'Images optimisées',
                "{$optimizedCount} image(s) optimisée(s) avec succès",
                'success'
            );
            
            return response()->json([
                'success' => true,
                'message' => "{$optimizedCount} image(s) optimisée(s) avec succès"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans GalleryController@optimize', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'optimisation des images'
            ], 500);
        }
    }
    
    /**
     * Obtenir les statistiques de la galerie
     */
    private function getGalleryStats()
    {
        try {
            return [
                'total_images' => 0,
                'total_albums' => 0,
                'storage_used' => 0, // en MB
                'total_views' => 0
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
            'total_images' => 0,
            'total_albums' => 0,
            'storage_used' => 0,
            'total_views' => 0
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
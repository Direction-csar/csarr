<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\News;
use App\Models\PublicContent;
use App\Models\Personnel;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Effectuer une recherche globale
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $results = collect();
        
        // Recherche dans les actualités
        $news = News::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->select('id', 'title', 'content', 'created_at')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'news',
                    'title' => $item->title,
                    'excerpt' => $this->extractExcerpt($item->content, 100),
                    'url' => route('news.show', $item->id),
                    'date' => $item->created_at->format('d/m/Y')
                ];
            });
        
        $results = $results->merge($news);
        
        // Recherche dans le contenu public
        $content = PublicContent::where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->select('id', 'title', 'content', 'type')
            ->limit(3)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'content',
                    'title' => $item->title,
                    'excerpt' => $this->extractExcerpt($item->content, 100),
                    'url' => route('about'),
                    'category' => $item->type
                ];
            });
        
        $results = $results->merge($content);
        
        // Recherche dans le personnel
        $personnel = Personnel::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('nom', 'LIKE', "%{$query}%")
                  ->orWhere('prenom', 'LIKE', "%{$query}%")
                  ->orWhere('fonction', 'LIKE', "%{$query}%");
            })
            ->select('id', 'nom', 'prenom', 'fonction', 'photo')
            ->limit(3)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'personnel',
                    'title' => $item->nom . ' ' . $item->prenom,
                    'excerpt' => $item->fonction,
                    'url' => route('about'),
                    'image' => $item->photo ? asset('storage/' . $item->photo) : null
                ];
            });
        
        $results = $results->merge($personnel);
        
        // Trier par pertinence (simplifié)
        $results = $results->sortByDesc(function($item) use ($query) {
            $titleScore = stripos($item['title'], $query) !== false ? 10 : 0;
            $excerptScore = stripos($item['excerpt'], $query) !== false ? 5 : 0;
            return $titleScore + $excerptScore;
        });
        
        return response()->json($results->values()->toArray());
    }
    
    /**
     * Extraire un extrait de texte
     */
    private function extractExcerpt(string $content, int $length = 100): string
    {
        // Supprimer les balises HTML
        $content = strip_tags($content);
        
        // Nettoyer le texte
        $content = preg_replace('/\s+/', ' ', $content);
        $content = trim($content);
        
        if (strlen($content) <= $length) {
            return $content;
        }
        
        return substr($content, 0, $length) . '...';
    }
    
    /**
     * Recherche avancée
     */
    public function advancedSearch(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $results = collect();
        
        // Recherche dans les actualités avec filtres
        if ($type === 'all' || $type === 'news') {
            $newsQuery = News::where('is_published', true)
                ->where(function($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('content', 'LIKE', "%{$query}%");
                });
            
            if ($dateFrom) {
                $newsQuery->whereDate('created_at', '>=', $dateFrom);
            }
            
            if ($dateTo) {
                $newsQuery->whereDate('created_at', '<=', $dateTo);
            }
            
            $news = $newsQuery->select('id', 'title', 'content', 'created_at', 'image')
                ->limit(10)
                ->get()
                ->map(function($item) {
                    return [
                        'type' => 'news',
                        'title' => $item->title,
                        'excerpt' => $this->extractExcerpt($item->content, 150),
                        'url' => route('news.show', $item->id),
                        'date' => $item->created_at->format('d/m/Y'),
                        'image' => $item->image ? asset('storage/' . $item->image) : null
                    ];
                });
            
            $results = $results->merge($news);
        }
        
        // Recherche dans les documents
        if ($type === 'all' || $type === 'documents') {
            $documents = DB::table('hr_documents')
                ->where('is_active', true)
                ->where(function($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%");
                })
                ->select('id', 'title', 'description', 'file_path', 'created_at')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'type' => 'document',
                        'title' => $item->title,
                        'excerpt' => $this->extractExcerpt($item->description ?? '', 100),
                        'url' => route('admin.hr.documents.show', $item->id),
                        'date' => \Carbon\Carbon::parse($item->created_at)->format('d/m/Y'),
                        'file_type' => pathinfo($item->file_path, PATHINFO_EXTENSION)
                    ];
                });
            
            $results = $results->merge($documents);
        }
        
        return response()->json($results->values()->toArray());
    }
}



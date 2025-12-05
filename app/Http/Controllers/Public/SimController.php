<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SimReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SimController extends Controller
{
    /**
     * Afficher les rapports SIM publics
     */
    public function index(Request $request)
    {
        // Vérifier si la table existe avant de faire la requête
        if (!\Schema::hasTable('sim_reports')) {
            $reports = collect([]);
            return view('public.sim.index', compact('reports'));
        }
        
        $query = SimReport::public();

        // Filtres
        if ($request->filled('report_type')) {
            $query->byType($request->report_type);
        }

        if ($request->filled('region')) {
            $query->byRegion($request->region);
        }

        if ($request->filled('market_sector')) {
            $query->bySector($request->market_sector);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $reports = $query->with('generator')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Dernières actualités (si disponibles)
        try {
            $latestNews = \App\Models\News::where('is_published', true)
                ->orderBy('published_at', 'desc')
                ->take(2)
                ->get();
        } catch (\Throwable $e) {
            $latestNews = collect();
        }

        return view('public.sim.index', compact('reports', 'latestNews'));
    }

    /**
     * Afficher un rapport SIM public
     */
    public function show(SimReport $simReport)
    {
        // Vérifier que le rapport est public
        if (!$simReport->isPublic()) {
            abort(404);
        }

        $simReport->incrementViewCount();
        
        return view('public.sim.show', compact('simReport'));
    }

    /**
     * Télécharger un rapport SIM public
     */
    public function download(SimReport $simReport)
    {
        // Vérifier que le rapport est public
        if (!$simReport->isPublic()) {
            abort(404);
        }

        $simReport->incrementDownloadCount();

        // Si un PDF est disponible, proposer le téléchargement du fichier
        if ($simReport->document_file) {
            $path = storage_path('app/public/' . $simReport->document_file);
            if (file_exists($path)) {
                return response()->download($path, basename($path), [
                    'Content-Type' => 'application/pdf'
                ]);
            }
        }

        // Sinon, fallback: générer un contenu texte
        $content = $this->generateReportContent($simReport);
        $filename = 'rapport_sim_' . $simReport->id . '_' . now()->format('Y-m-d') . '.txt';
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Générer le contenu du rapport pour téléchargement
     */
    protected function generateReportContent(SimReport $simReport)
    {
        $content = "RAPPORT SIM - SURVEILLANCE DES INDICATEURS DE MARCHÉ\n";
        $content .= "=" . str_repeat("=", 50) . "\n\n";
        
        $content .= "Titre: {$simReport->title}\n";
        $content .= "Type: {$simReport->report_type_label}\n";
        $content .= "Période: {$simReport->formatted_period}\n";
        $content .= "Région: " . ($simReport->region ?: 'Toutes') . "\n";
        $content .= "Secteur: {$simReport->sector_label}\n";
        $content .= "Publié le: {$simReport->published_at->format('d/m/Y H:i')}\n";
        $content .= "Généré par: " . ($simReport->generator ? $simReport->generator->name : 'Système') . "\n\n";
        
        if ($simReport->description) {
            $content .= "DESCRIPTION\n";
            $content .= "-" . str_repeat("-", 20) . "\n";
            $content .= $simReport->description . "\n\n";
        }
        
        if ($simReport->summary) {
            $content .= "RÉSUMÉ\n";
            $content .= "-" . str_repeat("-", 20) . "\n";
            $content .= $simReport->summary . "\n\n";
        }
        
        if ($simReport->recommendations) {
            $content .= "RECOMMANDATIONS\n";
            $content .= "-" . str_repeat("-", 20) . "\n";
            $content .= $simReport->recommendations . "\n\n";
        }
        
        $content .= "---\n";
        $content .= "Rapport généré automatiquement par la plateforme CSAR\n";
        $content .= "Date de génération: " . now()->format('d/m/Y H:i:s') . "\n";
        
        return $content;
    }

    /**
     * Afficher la page des magasins de stockage
     */
    public function magasins()
    {
        // Récupérer les entrepôts actifs
        $warehouses = \App\Models\Warehouse::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($warehouse) {
                return [
                    'id' => $warehouse->id,
                    'name' => $warehouse->name,
                    'address' => $warehouse->address,
                    'lat' => $warehouse->latitude,
                    'lng' => $warehouse->longitude,
                    'capacity' => $warehouse->capacity,
                    'type' => 'warehouse'
                ];
            });

        // Statistiques générales
        $stats = [
            'total_warehouses' => $warehouses->count(),
            'total_capacity' => $warehouses->sum('capacity'),
            'regions_covered' => 14,
            'availability' => '24/7'
        ];

        return view('public.sim.magasins', compact('warehouses', 'stats'));
    }

    /**
     * Afficher la page des approvisionnements
     */
    public function supply()
    {
        return view('public.sim.supply');
    }

    /**
     * Afficher la page régionale
     */
    public function regional()
    {
        return view('public.sim.regional');
    }

    /**
     * Afficher la page des distributions
     */
    public function distributions()
    {
        return view('public.sim.distributions');
    }

    /**
     * Afficher la page des opérations
     */
    public function operations()
    {
        return view('public.sim.operations');
    }
}
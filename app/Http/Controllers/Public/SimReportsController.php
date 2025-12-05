<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SimReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SimReportsController extends Controller
{
    /**
     * Afficher la page des rapports SIM publics
     */
    public function index(Request $request)
    {
        try {
            // Vérifier si la table existe
            if (!Schema::hasTable('sim_reports')) {
                $reports = collect([]);
                return view('public.sim-reports', compact('reports'));
            }
            
            // Récupérer les rapports publics directement sans mapping
            $reports = SimReport::where('is_public', true)
                ->where('status', 'published')
                ->orderBy('published_at', 'desc')
                ->get();
            
            return view('public.sim-reports', compact('reports'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur dans SimReportsController@index', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return view('public.sim-reports', ['reports' => collect([])]);
        }
    }
    
    /**
     * Afficher un rapport SIM spécifique
     */
    public function show($id)
    {
        try {
            $report = SimReport::where('id', $id)
                              ->where('is_public', true)
                              ->where('status', 'published')
                              ->firstOrFail();
            
            // Incrémenter le compteur de vues
            $report->incrementViews();
            
            return view('public.sim-report-detail', compact('report'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur dans SimReportsController@show', [
                'error' => $e->getMessage(),
                'report_id' => $id
            ]);
            
            return redirect()->route('sim-reports.index')
                           ->with('error', 'Rapport non trouvé');
        }
    }
    
    /**
     * Télécharger un rapport SIM
     */
    public function download($id)
    {
        try {
            $report = SimReport::where('id', $id)
                              ->where('is_public', true)
                              ->where('status', 'published')
                              ->firstOrFail();
            
            if (!$report->document_file) {
                return redirect()->back()->with('error', 'Document non disponible');
            }
            
            $filePath = storage_path('app/public/' . $report->document_file);
            
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'Fichier non trouvé');
            }
            
            // Incrémenter le compteur de téléchargements
            $report->incrementDownloads();
            
            // Déterminer l'extension du fichier
            $extension = $this->getFileExtension($report->document_file);
            $filename = $this->sanitizeFilename($report->title) . '.' . $extension;
            
            return response()->download($filePath, $filename);
            
        } catch (\Exception $e) {
            \Log::error('Erreur dans SimReportsController@download', [
                'error' => $e->getMessage(),
                'report_id' => $id
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors du téléchargement');
        }
    }
    
    /**
     * Obtenir l'extension du fichier
     */
    private function getFileExtension($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        // Mapper les extensions communes
        $extensionMap = [
            'pdf' => 'pdf',
            'ppt' => 'ppt',
            'pptx' => 'pptx',
            'doc' => 'doc',
            'docx' => 'docx',
            'xls' => 'xls',
            'xlsx' => 'xlsx'
        ];
        
        return $extensionMap[$extension] ?? 'pdf';
    }
    
    /**
     * Nettoyer le nom de fichier
     */
    private function sanitizeFilename($filename)
    {
        // Supprimer les caractères spéciaux et remplacer les espaces par des underscores
        $filename = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $filename);
        $filename = preg_replace('/_+/', '_', $filename);
        $filename = trim($filename, '_');
        
        return $filename ?: 'document';
    }
}

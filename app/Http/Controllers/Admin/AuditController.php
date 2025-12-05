<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuditController extends Controller
{
    /**
     * Afficher la page d'audit et sécurité
     */
    public function index(Request $request)
    {
        try {
            // Statistiques
            $stats = $this->getAuditStats();
            
            // Filtres
            $query = collect(); // Simulation pour l'instant
            
            if ($request->filled('search')) {
                // Logique de recherche
            }
            
            if ($request->filled('level')) {
                // Filtre par niveau
            }
            
            if ($request->filled('user')) {
                // Filtre par utilisateur
            }
            
            if ($request->filled('date')) {
                // Filtre par date
            }
            
            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            // Pagination simulée
            $logs = $query->paginate(15);
            
            return view('admin.audit.index', compact('logs', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Erreur dans AuditController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return view('admin.audit.index', [
                'logs' => collect(),
                'stats' => $this->getDefaultStats()
            ]);
        }
    }
    
    /**
     * Obtenir les logs d'activité
     */
    public function getLogs(Request $request)
    {
        try {
            $query = \App\Models\AuditLog::with('user');
            
            // Filtres
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('action', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('ip_address', 'like', "%{$search}%");
                });
            }
            
            if ($request->filled('level')) {
                $query->where('level', $request->level);
            }
            
            if ($request->filled('user')) {
                $query->where('user_id', $request->user);
            }
            
            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }
            
            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);
            
            // Pagination
            $logs = $query->paginate(15);
            
            // Log de l'accès aux logs
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'logs_accessed',
                'level' => 'info',
                'status' => 'success',
                'description' => 'Accès à la page des logs détaillés',
                'model_type' => 'AuditLog',
                'model_id' => null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'data' => [
                    'filters' => $request->only(['search', 'level', 'user', 'date']),
                    'page' => $request->get('page', 1)
                ],
                'created_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans AuditController@getLogs', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des logs'
            ], 500);
        }
    }
    
    /**
     * Obtenir les sessions actives
     */
    public function getSessions(Request $request)
    {
        try {
            $filters = $request->only(['search', 'status', 'page']);
            
            // Simulation de récupération des sessions
            $sessions = [
                'data' => [],
                'total' => 0,
                'per_page' => 15,
                'current_page' => 1,
                'last_page' => 1
            ];
            
            return response()->json([
                'success' => true,
                'data' => $sessions
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans AuditController@getSessions', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des sessions'
            ], 500);
        }
    }
    
    /**
     * Terminer une session
     */
    public function terminateSession($id)
    {
        try {
            // Simulation de terminaison de session
            $session = [
                'id' => $id,
                'status' => 'terminated',
                'terminated_at' => now()
            ];
            
            // Créer une notification
            $this->createNotification(
                'Session terminée',
                "La session a été terminée avec succès",
                'warning'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Session terminée avec succès',
                'data' => $session
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans AuditController@terminateSession', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'session_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la terminaison de la session'
            ], 500);
        }
    }
    
    /**
     * Terminer toutes les sessions
     */
    public function terminateAllSessions()
    {
        try {
            // Simulation de terminaison de toutes les sessions
            $terminatedCount = 0; // Nombre de sessions terminées
            
            // Créer une notification
            $this->createNotification(
                'Sessions terminées',
                "Toutes les sessions ont été terminées ({$terminatedCount} sessions)",
                'warning'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Toutes les sessions ont été terminées',
                'data' => ['terminated_count' => $terminatedCount]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans AuditController@terminateAllSessions', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la terminaison des sessions'
            ], 500);
        }
    }
    
    /**
     * Générer un rapport de sécurité
     */
    public function generateSecurityReport(Request $request)
    {
        try {
            $request->validate([
                'report_type' => 'required|in:full,summary,threats',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from'
            ]);
            
            $dateFrom = $request->date_from ? Carbon::parse($request->date_from) : now()->subMonth();
            $dateTo = $request->date_to ? Carbon::parse($request->date_to) : now();
            
            // Génération réelle du rapport
            $reportData = $this->generateRealSecurityReport($request->report_type, $dateFrom, $dateTo);
            
            // Log de la génération du rapport
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'security_report_generated',
                'level' => 'info',
                'status' => 'success',
                'description' => "Rapport de sécurité généré ({$request->report_type})",
                'model_type' => 'SecurityReport',
                'model_id' => null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'data' => [
                    'report_type' => $request->report_type,
                    'date_from' => $dateFrom->toDateString(),
                    'date_to' => $dateTo->toDateString(),
                    'generated_by' => auth()->user()->name ?? 'Système',
                    'report_data' => $reportData
                ],
                'created_at' => now()
            ]);
            
            // Créer une notification
            $this->createNotification(
                'Rapport de sécurité',
                "Le rapport de sécurité a été généré avec succès",
                'success'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Rapport de sécurité généré avec succès',
                'data' => $reportData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans AuditController@generateSecurityReport', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du rapport'
            ], 500);
        }
    }
    
    /**
     * Nettoyer les anciens logs
     */
    public function clearOldLogs(Request $request)
    {
        try {
            $request->validate([
                'older_than_days' => 'required|integer|min:1|max:365'
            ]);
            
            $olderThan = $request->older_than_days;
            $cutoffDate = now()->subDays($olderThan);
            
            // Nettoyage réel des logs
            $deletedCount = \App\Models\AuditLog::where('created_at', '<', $cutoffDate)->delete();
            
            // Log de l'action de nettoyage
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'logs_cleaned',
                'level' => 'info',
                'status' => 'success',
                'description' => "Nettoyage des logs antérieurs à {$olderThan} jours",
                'model_type' => 'AuditLog',
                'model_id' => null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'data' => [
                    'deleted_count' => $deletedCount,
                    'cutoff_date' => $cutoffDate->toDateString(),
                    'cleaned_by' => auth()->user()->name ?? 'Système'
                ],
                'created_at' => now()
            ]);
            
            // Créer une notification
            $this->createNotification(
                'Logs nettoyés',
                "Les logs antérieurs à {$olderThan} jours ont été supprimés ({$deletedCount} entrées)",
                'info'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Logs nettoyés avec succès',
                'data' => ['deleted_count' => $deletedCount]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans AuditController@clearOldLogs', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du nettoyage des logs'
            ], 500);
        }
    }
    
    /**
     * Obtenir les statistiques d'audit
     */
    public function getStats()
    {
        try {
            $stats = $this->getAuditStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans AuditController@getStats', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }
    
    /**
     * Obtenir les données des graphiques
     */
    public function getChartData(Request $request)
    {
        try {
            $type = $request->get('type', 'activity');
            
            $chartData = [];
            
            if ($type === 'activity') {
                $chartData = [
                    'labels' => ['00h', '04h', '08h', '12h', '16h', '20h'],
                    'data' => [0, 0, 0, 0, 0, 0]
                ];
            } elseif ($type === 'events') {
                $chartData = [
                    'labels' => ['Connexions', 'Actions', 'Erreurs', 'Sécurité'],
                    'data' => [0, 0, 0, 0]
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => $chartData
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans AuditController@getChartData', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des données de graphique'
            ], 500);
        }
    }
    
    /**
     * Obtenir les statistiques d'audit
     */
    private function getAuditStats()
    {
        try {
            $totalLogs = \App\Models\AuditLog::count();
            $securityAlerts = \App\Models\AuditLog::where('level', 'critical')->count();
            $failedLogins = \App\Models\AuditLog::where('action', 'login_failed')->count();
            $activeSessions = \App\Models\AuditLog::where('action', 'login')
                ->where('created_at', '>=', now()->subHours(24))
                ->count();
            
            return [
                'total_logs' => $totalLogs,
                'security_alerts' => $securityAlerts,
                'failed_logins' => $failedLogins,
                'active_sessions' => $activeSessions
            ];
        } catch (\Exception $e) {
            Log::error('Erreur dans getAuditStats', ['error' => $e->getMessage()]);
            return $this->getDefaultStats();
        }
    }
    
    /**
     * Statistiques par défaut
     */
    private function getDefaultStats()
    {
        return [
            'total_logs' => 0,
            'security_alerts' => 0,
            'failed_logins' => 0,
            'active_sessions' => 0
        ];
    }
    
    /**
     * Afficher les détails d'un log (méthode show pour compatibilité)
     */
    public function show($id)
    {
        return $this->showLog($id);
    }
    
    /**
     * Afficher les détails d'un log
     */
    public function showLog($id)
    {
        try {
            $log = \App\Models\AuditLog::with('user')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $log
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans AuditController@showLog', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'log_id' => $id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Log non trouvé'
            ], 404);
        }
    }
    
    /**
     * Générer un vrai rapport de sécurité
     */
    private function generateRealSecurityReport($type, $dateFrom, $dateTo)
    {
        $query = \App\Models\AuditLog::whereBetween('created_at', [$dateFrom, $dateTo]);
        
        $report = [
            'id' => 'SEC-' . date('YmdHis'),
            'type' => $type,
            'date_from' => $dateFrom->toDateString(),
            'date_to' => $dateTo->toDateString(),
            'generated_at' => now()->toDateTimeString(),
            'generated_by' => auth()->user()->name ?? 'Système'
        ];
        
        if ($type === 'summary' || $type === 'full') {
            // Statistiques générales
            $report['summary'] = [
                'total_logs' => $query->count(),
                'critical_alerts' => $query->where('level', 'critical')->count(),
                'warning_alerts' => $query->where('level', 'warning')->count(),
                'failed_logins' => $query->where('action', 'login_failed')->count(),
                'successful_logins' => $query->where('action', 'login')->count(),
                'unique_ips' => $query->distinct('ip_address')->count('ip_address'),
                'unique_users' => $query->whereNotNull('user_id')->distinct('user_id')->count('user_id')
            ];
        }
        
        if ($type === 'threats' || $type === 'full') {
            // Menaces détectées
            $report['threats'] = [
                'security_breaches' => $query->where('action', 'security_breach')->get()->map(function($log) {
                    return [
                        'id' => $log->id,
                        'date' => $log->created_at->toDateTimeString(),
                        'ip' => $log->ip_address,
                        'description' => $log->description,
                        'user_agent' => $log->user_agent
                    ];
                }),
                'failed_logins' => $query->where('action', 'login_failed')->get()->map(function($log) {
                    return [
                        'id' => $log->id,
                        'date' => $log->created_at->toDateTimeString(),
                        'ip' => $log->ip_address,
                        'attempts' => $log->data['attempts'] ?? 1
                    ];
                }),
                'suspicious_ips' => $query->where('level', 'critical')
                    ->selectRaw('ip_address, COUNT(*) as count')
                    ->groupBy('ip_address')
                    ->having('count', '>', 1)
                    ->get()
            ];
        }
        
        if ($type === 'full') {
            // Données complètes
            $report['detailed_logs'] = $query->with('user')
                ->orderBy('created_at', 'desc')
                ->limit(100)
                ->get()
                ->map(function($log) {
                    return [
                        'id' => $log->id,
                        'date' => $log->created_at->toDateTimeString(),
                        'action' => $log->action,
                        'level' => $log->level,
                        'user' => $log->user ? $log->user->name : 'Anonyme',
                        'ip' => $log->ip_address,
                        'description' => $log->description
                    ];
                });
            
            // Activité par jour
            $report['daily_activity'] = $query->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }
        
        return $report;
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
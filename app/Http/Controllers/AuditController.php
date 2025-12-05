<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditController extends Controller
{
    /**
     * Afficher les logs d'audit
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Seuls les admins et DG peuvent voir tous les logs
        if (!in_array($user->role, ['admin', 'dg'])) {
            abort(403, 'Accès non autorisé.');
        }

        $query = AuditLog::query();

        // Filtres
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Statistiques
        $stats = $this->getAuditStats($request);

        // Utilisateurs pour le filtre
        $users = User::select('id', 'name')->get();

        return view('audit.index', compact('logs', 'stats', 'users'));
    }

    /**
     * Afficher les détails d'un log
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'dg'])) {
            abort(403, 'Accès non autorisé.');
        }

        $log = AuditLog::with('user')->findOrFail($id);

        return view('audit.show', compact('log'));
    }

    /**
     * Exporter les logs d'audit
     */
    public function export(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'dg'])) {
            abort(403, 'Accès non autorisé.');
        }

        $query = AuditLog::query();

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->with('user')->get();

        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // BOM pour UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // En-têtes
            fputcsv($file, [
                'ID', 'Utilisateur', 'Action', 'Modèle', 'ID Modèle', 
                'Anciennes Valeurs', 'Nouvelles Valeurs', 'IP', 'User Agent', 'Date'
            ], ';');
            
            // Données
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'N/A',
                    $log->action,
                    $log->model_type,
                    $log->model_id,
                    $log->old_values ? json_encode($log->old_values) : '',
                    $log->new_values ? json_encode($log->new_values) : '',
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at->format('d/m/Y H:i:s')
                ], ';');
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Obtenir les statistiques d'audit
     */
    private function getAuditStats($request)
    {
        $query = AuditLog::query();

        // Appliquer les mêmes filtres
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return [
            'total_logs' => $query->count(),
            'today_logs' => $query->whereDate('created_at', today())->count(),
            'this_week_logs' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_logs' => $query->whereMonth('created_at', now()->month)->count(),
            'top_actions' => $query->select('action', DB::raw('count(*) as count'))
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'top_users' => $query->join('users', 'audit_logs.user_id', '=', 'users.id')
                ->select('users.name', DB::raw('count(*) as count'))
                ->groupBy('users.id', 'users.name')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'top_models' => $query->select('model_type', DB::raw('count(*) as count'))
                ->whereNotNull('model_type')
                ->groupBy('model_type')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    /**
     * Nettoyer les anciens logs
     */
    public function cleanup(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }

        $days = $request->get('days', 90);
        $deleted = AuditLog::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', "{$deleted} logs d'audit supprimés (plus anciens que {$days} jours).");
    }

    /**
     * Obtenir les logs en temps réel (API)
     */
    public function realtime(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'dg'])) {
            abort(403, 'Accès non autorisé.');
        }

        $lastId = $request->get('last_id', 0);
        
        $logs = AuditLog::where('id', '>', $lastId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'logs' => $logs,
            'last_id' => $logs->max('id') ?? $lastId
        ]);
    }

    /**
     * Rechercher dans les logs
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'dg'])) {
            abort(403, 'Accès non autorisé.');
        }

        $query = $request->get('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $logs = AuditLog::where(function ($q) use ($query) {
            $q->where('action', 'like', "%{$query}%")
              ->orWhere('model_type', 'like', "%{$query}%")
              ->orWhere('ip_address', 'like', "%{$query}%")
              ->orWhereHas('user', function ($userQuery) use ($query) {
                  $userQuery->where('name', 'like', "%{$query}%")
                           ->orWhere('email', 'like', "%{$query}%");
              });
        })
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->limit(20)
        ->get();

        return response()->json($logs);
    }
}

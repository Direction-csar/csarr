<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseCleanupController extends Controller
{
    /**
     * Afficher la page de nettoyage de la base de données
     */
    public function index()
    {
        try {
            // Statistiques actuelles
            $stats = [
                'total_demandes' => PublicRequest::count(),
                'test_demandes' => $this->countTestData('demandes'),
                'total_users' => User::count(),
                'test_users' => $this->countTestData('users'),
                'total_notifications' => Notification::count(),
                'test_notifications' => $this->countTestData('notifications'),
            ];

            return view('admin.database-cleanup.index', compact('stats'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de la page de nettoyage', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors du chargement de la page de nettoyage.');
        }
    }

    /**
     * Nettoyer les données de test
     */
    public function cleanup(Request $request)
    {
        try {
            DB::beginTransaction();

            $cleaned = [];

            // Nettoyer les demandes de test
            if ($request->has('cleanup_demandes')) {
                $testDemandes = PublicRequest::where('full_name', 'like', '%test%')
                    ->orWhere('full_name', 'like', '%Test%')
                    ->orWhere('email', 'like', '%test%')
                    ->orWhere('email', 'like', '%example%')
                    ->orWhere('description', 'like', '%test%')
                    ->orWhere('tracking_code', 'like', '%TEST%')
                    ->get();

                $cleaned['demandes'] = $testDemandes->count();
                foreach ($testDemandes as $demande) {
                    $demande->delete();
                }
            }

            // Nettoyer les utilisateurs de test
            if ($request->has('cleanup_users')) {
                $testUsers = User::where('name', 'like', '%test%')
                    ->orWhere('name', 'like', '%Test%')
                    ->orWhere('email', 'like', '%test%')
                    ->orWhere('email', 'like', '%example%')
                    ->where('role', '!=', 'admin') // Ne pas supprimer les admins
                    ->get();

                $cleaned['users'] = $testUsers->count();
                foreach ($testUsers as $user) {
                    $user->delete();
                }
            }

            // Nettoyer les notifications de test
            if ($request->has('cleanup_notifications')) {
                $testNotifications = Notification::where('title', 'like', '%test%')
                    ->orWhere('message', 'like', '%test%')
                    ->orWhere('type', 'like', '%test%')
                    ->get();

                $cleaned['notifications'] = $testNotifications->count();
                foreach ($testNotifications as $notification) {
                    $notification->delete();
                }
            }

            DB::commit();

            $message = "Nettoyage terminé avec succès ! ";
            foreach ($cleaned as $type => $count) {
                $message .= ucfirst($type) . ": {$count} éléments supprimés. ";
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du nettoyage de la base de données', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors du nettoyage de la base de données.');
        }
    }

    /**
     * Vérifier la connexion à la base de données
     */
    public function checkConnection()
    {
        try {
            DB::connection()->getPdo();
            
            $stats = [
                'connection' => 'OK',
                'database' => DB::connection()->getDatabaseName(),
                'total_demandes' => PublicRequest::count(),
                'total_users' => User::count(),
                'total_notifications' => Notification::count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Compter les données de test
     */
    private function countTestData($type)
    {
        switch ($type) {
            case 'demandes':
                return PublicRequest::where('full_name', 'like', '%test%')
                    ->orWhere('full_name', 'like', '%Test%')
                    ->orWhere('email', 'like', '%test%')
                    ->orWhere('email', 'like', '%example%')
                    ->orWhere('description', 'like', '%test%')
                    ->orWhere('tracking_code', 'like', '%TEST%')
                    ->count();
                    
            case 'users':
                return User::where('name', 'like', '%test%')
                    ->orWhere('name', 'like', '%Test%')
                    ->orWhere('email', 'like', '%test%')
                    ->orWhere('email', 'like', '%example%')
                    ->where('role', '!=', 'admin')
                    ->count();
                    
            case 'notifications':
                return Notification::where('title', 'like', '%test%')
                    ->orWhere('message', 'like', '%test%')
                    ->orWhere('type', 'like', '%test%')
                    ->count();
                    
            default:
                return 0;
        }
    }
}

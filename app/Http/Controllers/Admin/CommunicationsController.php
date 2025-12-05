<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CommunicationsController extends Controller
{
    /**
     * Afficher la page des communications
     */
    public function index()
    {
        try {
            // Statistiques des communications
            $stats = $this->getCommunicationStats();
            
            // Messages récents
            $recentMessages = Message::orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Notifications récentes
            $recentNotifications = Notification::orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Campagnes newsletter récentes
            $recentNewsletters = DB::table('newsletters')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Abonnés newsletter récents
            $recentSubscribers = DB::table('newsletter_subscribers')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Logs d'audit des communications
            $auditLogs = DB::table('audit_logs')
                ->where('action', 'LIKE', '%communication%')
                ->orWhere('action', 'LIKE', '%message%')
                ->orWhere('action', 'LIKE', '%newsletter%')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            Log::info('Accès à la page Communications', [
                'user_id' => auth()->id(),
                'timestamp' => Carbon::now()
            ]);

            return view('admin.communications.index', compact(
                'stats',
                'recentMessages',
                'recentNotifications',
                'recentNewsletters',
                'recentSubscribers',
                'auditLogs'
            ));
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de la page Communications', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors du chargement de la page Communications.');
        }
    }

    /**
     * Obtenir les statistiques des communications
     */
    private function getCommunicationStats()
    {
        try {
            return [
                // Messages
                'total_messages' => Message::count(),
                'unread_messages' => Message::where('lu', false)->count(),
                'today_messages' => Message::whereDate('created_at', today())->count(),
                'week_messages' => Message::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                
                // Notifications
                'total_notifications' => Notification::count(),
                'unread_notifications' => Notification::where('read', false)->count(),
                'today_notifications' => Notification::whereDate('created_at', today())->count(),
                'week_notifications' => Notification::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                
                // Newsletter - Campagnes
                'total_newsletters' => DB::table('newsletters')->count(),
                'sent_newsletters' => DB::table('newsletters')->where('status', 'sent')->count(),
                'pending_newsletters' => DB::table('newsletters')->whereIn('status', ['draft', 'pending', 'scheduled'])->count(),
                'today_newsletters' => DB::table('newsletters')->whereDate('created_at', today())->count(),
                
                // Newsletter - Abonnés
                'total_subscribers' => DB::table('newsletter_subscribers')->count(),
                'active_subscribers' => DB::table('newsletter_subscribers')->where('status', 'subscribed')->count(),
                'today_subscribers' => DB::table('newsletter_subscribers')->whereDate('created_at', today())->count(),
                'week_subscribers' => DB::table('newsletter_subscribers')
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count(),
                
                // Statistiques globales
                'total_communications' => Message::count() + Notification::count() + DB::table('newsletters')->count(),
                'today_communications' => Message::whereDate('created_at', today())->count() 
                    + Notification::whereDate('created_at', today())->count()
                    + DB::table('newsletters')->whereDate('created_at', today())->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Erreur dans getCommunicationStats', ['error' => $e->getMessage()]);
            return [
                'total_messages' => 0,
                'unread_messages' => 0,
                'today_messages' => 0,
                'week_messages' => 0,
                'total_notifications' => 0,
                'unread_notifications' => 0,
                'today_notifications' => 0,
                'week_notifications' => 0,
                'total_newsletters' => 0,
                'sent_newsletters' => 0,
                'pending_newsletters' => 0,
                'today_newsletters' => 0,
                'total_subscribers' => 0,
                'active_subscribers' => 0,
                'today_subscribers' => 0,
                'week_subscribers' => 0,
                'total_communications' => 0,
                'today_communications' => 0,
            ];
        }
    }

    /**
     * Obtenir les statistiques en temps réel (AJAX)
     */
    public function realtimeStats()
    {
        try {
            $stats = $this->getCommunicationStats();
            
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'timestamp' => Carbon::now()->toIso8601String()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans realtimeStats', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }
}


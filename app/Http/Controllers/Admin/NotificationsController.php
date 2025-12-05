<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * Afficher la liste complète des notifications
     */
    public function index()
    {
        try {
            $notifications = Notification::orderBy('created_at', 'desc')
                ->paginate(20);
            
            $stats = Notification::getStats();
            
            return view('admin.notifications.index', compact('notifications', 'stats'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des notifications', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors du chargement des notifications.');
        }
    }

    /**
     * Afficher une notification spécifique
     */
    public function show($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            
            // Marquer comme lue automatiquement
            if (!$notification->read) {
                $notification->markAsRead();
            }
            
            return view('admin.notifications.show', compact('notification'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage de la notification', [
                'notification_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.notifications.index')
                ->with('error', 'Notification introuvable.');
        }
    }

    /**
     * API - Récupérer les notifications récentes (pour le dropdown)
     */
    public function getNotifications(Request $request)
    {
        try {
            $limit = $request->input('limit', 10);
            
            $notifications = Notification::orderBy('created_at', 'desc')
                ->take($limit)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'icon' => $notification->icon ?? Notification::getDefaultIcon($notification->type),
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'read' => $notification->read,
                        'time_ago' => $notification->time_ago,
                        'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                        'action_url' => $notification->action_url ?? $this->getNotificationUrl($notification)
                    ];
                });

            $unreadCount = Notification::where('read', false)->count();

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'count' => $unreadCount  // Pour compatibilité
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des notifications', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des notifications',
                'count' => 0,
                'notifications' => []
            ], 500);
        }
    }

    /**
     * API - Marquer une notification comme lue
     */
    public function markAsRead(Request $request, $id = null)
    {
        try {
            $notificationId = $id ?? $request->input('id');
            
            $notification = Notification::findOrFail($notificationId);
            $notification->markAsRead();
            
            $unreadCount = Notification::where('read', false)->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marquée comme lue',
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la marque de notification comme lue', [
                'notification_id' => $id ?? $request->input('id'),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * API - Marquer une notification comme non lue
     */
    public function markAsUnread(Request $request, $id = null)
    {
        try {
            $notificationId = $id ?? $request->input('id');
            
            $notification = Notification::findOrFail($notificationId);
            $notification->markAsUnread();
            
            $unreadCount = Notification::where('read', false)->count();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marquée comme non lue',
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la marque de notification comme non lue', [
                'notification_id' => $id ?? $request->input('id'),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * API - Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        try {
            Notification::markAllAsRead();
            
            return response()->json([
                'success' => true,
                'message' => 'Toutes les notifications ont été marquées comme lues',
                'unread_count' => 0
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la marque de toutes les notifications comme lues', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * API - Obtenir le nombre de notifications non lues
     */
    public function getUnreadCount()
    {
        try {
            $unreadCount = Notification::where('read', false)->count();
            
            return response()->json([
                'success' => true,
                'count' => $unreadCount,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du nombre de notifications non lues', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'count' => 0
            ], 500);
        }
    }

    /**
     * Supprimer une notification
     */
    public function destroy($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();
            
            // Si c'est une requête AJAX
            if (request()->ajax()) {
                $unreadCount = Notification::where('read', false)->count();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Notification supprimée avec succès',
                    'unread_count' => $unreadCount
                ]);
            }
            
            return redirect()->back()->with('success', 'Notification supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de notification', [
                'notification_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Générer l'URL de redirection pour une notification
     */
    private function getNotificationUrl($notification)
    {
        // Si une URL est déjà définie, l'utiliser
        if ($notification->action_url) {
            return $notification->action_url;
        }

        // Analyser le type ou le message pour déterminer l'URL
        $type = $notification->type;
        $message = strtolower($notification->message);
        
        if ($type === 'demande' || str_contains($message, 'demande')) {
            return route('admin.demandes.index');
        } elseif ($type === 'message' || str_contains($message, 'message') || str_contains($message, 'contact')) {
            return route('admin.messages.index');
        } elseif ($type === 'newsletter' || str_contains($message, 'newsletter') || str_contains($message, 'inscription')) {
            return route('admin.newsletter.index');
        } elseif ($type === 'communication' || str_contains($message, 'communication')) {
            return route('admin.communications.index');
        }
        
        return route('admin.dashboard');
    }

    /**
     * Créer une notification manuellement (pour tests ou cas spéciaux)
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'type' => 'required|in:info,success,warning,error,demande,message,newsletter,communication'
            ]);

            Notification::createNotification(
                $request->title,
                $request->message,
                $request->type,
                $request->input('data', []),
                null,
                $request->input('icon'),
                $request->input('action_url')
            );

            return redirect()->route('admin.notifications.index')
                ->with('success', 'Notification créée avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de notification', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de la création de la notification.');
        }
    }
}


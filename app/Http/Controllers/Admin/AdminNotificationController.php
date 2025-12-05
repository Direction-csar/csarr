<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminNotificationController extends Controller
{
    /**
     * API pour récupérer les notifications (pour la cloche)
     */
    public function getNotifications()
    {
        try {
            $notifications = Notification::orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'read' => $notification->read,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'url' => $this->getNotificationUrl($notification)
                    ];
                });

            $unreadCount = Notification::where('is_read', false)->count();

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'count' => $unreadCount  // Ajout pour compatibilité avec le layout
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des notifications', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des notifications',
                'count' => 0
            ], 500);
        }
    }

    /**
     * Générer l'URL de redirection pour une notification
     */
    private function getNotificationUrl($notification)
    {
        // Analyser le message pour déterminer l'URL de redirection
        if (str_contains($notification->message, 'message de contact')) {
            return route('admin.messages.index');
        } elseif (str_contains($notification->message, 'demande')) {
            return route('admin.requests.index');
        } elseif (str_contains($notification->message, 'newsletter')) {
            return route('admin.newsletter.index');
        }
        
        return route('admin.dashboard');
    }

    /**
     * Afficher une notification spécifique
     */
    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
        
        return view('admin.notifications.show', compact('notification'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead(Request $request)
    {
        try {
            $notification = Notification::findOrFail($request->id);
            $notification->markAsRead();
            
            $unreadCount = Notification::where('is_read', false)->count();
            
            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la marque de notification comme lue', [
                'notification_id' => $request->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        try {
            Notification::where('is_read', false)->update(['is_read' => true, 'read_at' => now()]);
            
            return response()->json([
                'success' => true,
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
     * Supprimer une notification
     */
    public function destroy($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();
            
            return redirect()->back()->with('success', 'Notification supprimée avec succès.');
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de notification', [
                'notification_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de la suppression.');
        }
    }
}

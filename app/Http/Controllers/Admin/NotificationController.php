<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Récupérer les notifications depuis la base de données
        $notifications = Notification::orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('id');
        
        $notification = Notification::findOrFail($notificationId);
        $notification->markAsRead();
        
        $unreadCount = Notification::where('is_read', false)->count();
        
        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount
        ]);
    }

    public function markAllAsRead()
    {
        Notification::markAllAsRead();
        
        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $unreadCount = Notification::where('is_read', false)->count();
        
        return response()->json(['count' => $unreadCount]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error'
        ]);

        Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type
        ]);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification créée avec succès');
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification supprimée avec succès');
    }
}




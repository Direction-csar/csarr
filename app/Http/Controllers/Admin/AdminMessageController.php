<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminMessageController extends Controller
{
    /**
     * Afficher tous les messages
     */
    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Afficher un message spécifique
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);
        $message->markAsRead();
        
        return view('admin.messages.show', compact('message'));
    }

    /**
     * Marquer un message comme lu
     */
    public function markAsRead(Request $request)
    {
        try {
            $message = Message::findOrFail($request->id);
            $message->markAsRead();
            
            $unreadCount = Message::where('lu', false)->count();
            
            return response()->json([
                'success' => true,
                'unread_count' => $unreadCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la marque de message comme lu', [
                'message_id' => $request->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Marquer tous les messages comme lus
     */
    public function markAllAsRead()
    {
        try {
            Message::where('lu', false)->update(['lu' => true, 'lu_at' => now()]);
            
            return response()->json([
                'success' => true,
                'unread_count' => 0
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la marque de tous les messages comme lus', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Répondre à un message
     */
    public function reply(Request $request, $id)
    {
        try {
            $request->validate([
                'reponse' => 'required|string|max:1000'
            ]);

            $message = Message::findOrFail($id);
            $message->reply($request->reponse);
            
            return redirect()->back()->with('success', 'Réponse envoyée avec succès.');
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de réponse', [
                'message_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de la réponse.');
        }
    }

    /**
     * Supprimer un message
     */
    public function destroy($id)
    {
        try {
            $message = Message::findOrFail($id);
            $message->delete();
            
            return redirect()->back()->with('success', 'Message supprimé avec succès.');
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de message', [
                'message_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors de la suppression.');
        }
    }
}



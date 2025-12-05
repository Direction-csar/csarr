<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Message;
use App\Models\Notification;

class CommunicationController extends Controller
{
    /**
     * Afficher la page de communication
     */
    public function index(Request $request)
    {
        try {
            // Statistiques réelles
            $stats = $this->getCommunicationStats();
            
            // Récupérer les messages de la base de données
            $query = Message::query();
            
            // Recherche
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('sujet', 'like', "%{$search}%")
                      ->orWhere('contenu', 'like', "%{$search}%")
                      ->orWhere('expediteur', 'like', "%{$search}%");
                });
            }
            
            // Filtre par statut (lu/non lu)
            if ($request->filled('status')) {
                if ($request->status === 'read') {
                    $query->where('lu', true);
                } elseif ($request->status === 'unread') {
                    $query->where('lu', false);
                }
            }
            
            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);
            
            // Pagination
            $communications = $query->paginate(15);
            
            return view('admin.communication.index', compact('communications', 'stats'));
            
        } catch (\Exception $e) {
            Log::error('Erreur dans CommunicationController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return view('admin.communication.index', [
                'communications' => collect(),
                'stats' => $this->getDefaultStats()
            ]);
        }
    }
    
    /**
     * Envoyer un message
     */
    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'recipient' => 'required|string|max:255',
                'channel' => 'required|in:email,sms,notification',
                'subject' => 'required|string|max:255',
                'message_content' => 'required|string',
                'urgent' => 'boolean',
                'schedule' => 'boolean',
                'schedule_date' => 'nullable|date|after:now'
            ]);
            
            // Simulation d'envoi de message
            $message = [
                'id' => rand(1000, 9999),
                'recipient' => $request->recipient,
                'channel' => $request->channel,
                'subject' => $request->subject,
                'content' => $request->message_content,
                'urgent' => $request->boolean('urgent'),
                'scheduled' => $request->boolean('schedule'),
                'schedule_date' => $request->schedule_date ? Carbon::parse($request->schedule_date) : null,
                'status' => $request->boolean('schedule') ? 'scheduled' : 'sent',
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Créer une notification
            $this->createNotification(
                'Message envoyé',
                "Un message a été envoyé via {$message['channel']}",
                'success'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Message envoyé avec succès',
                'data' => $message
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans CommunicationController@sendMessage', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du message'
            ], 500);
        }
    }
    
    /**
     * Créer un canal de communication
     */
    public function createChannel(Request $request)
    {
        try {
            $request->validate([
                'channel_name' => 'required|string|max:255',
                'channel_type' => 'required|in:email,sms,notification,webhook',
                'channel_description' => 'nullable|string|max:1000',
                'channel_active' => 'boolean'
            ]);
            
            // Simulation de création de canal
            $channel = [
                'id' => rand(1000, 9999),
                'name' => $request->channel_name,
                'type' => $request->channel_type,
                'description' => $request->channel_description,
                'active' => $request->boolean('channel_active'),
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Créer une notification
            $this->createNotification(
                'Canal créé',
                "Le canal '{$channel['name']}' a été créé avec succès",
                'info'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Canal créé avec succès',
                'data' => $channel
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans CommunicationController@createChannel', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du canal'
            ], 500);
        }
    }
    
    /**
     * Créer un modèle de message
     */
    public function createTemplate(Request $request)
    {
        try {
            $request->validate([
                'template_name' => 'required|string|max:255',
                'template_subject' => 'required|string|max:255',
                'template_content' => 'required|string',
                'template_category' => 'required|in:general,notification,alert,welcome'
            ]);
            
            // Simulation de création de modèle
            $template = [
                'id' => rand(1000, 9999),
                'name' => $request->template_name,
                'subject' => $request->template_subject,
                'content' => $request->template_content,
                'category' => $request->template_category,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Créer une notification
            $this->createNotification(
                'Modèle créé',
                "Le modèle '{$template['name']}' a été créé avec succès",
                'info'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Modèle créé avec succès',
                'data' => $template
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans CommunicationController@createTemplate', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du modèle'
            ], 500);
        }
    }
    
    /**
     * Envoyer une diffusion
     */
    public function sendBroadcast(Request $request)
    {
        try {
            $request->validate([
                'recipients' => 'required|array|min:1',
                'recipients.*' => 'string|max:255',
                'channel' => 'required|in:email,sms,notification',
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
                'urgent' => 'boolean'
            ]);
            
            $recipientCount = count($request->recipients);
            
            // Simulation de diffusion
            $broadcast = [
                'id' => rand(1000, 9999),
                'recipients' => $request->recipients,
                'channel' => $request->channel,
                'subject' => $request->subject,
                'content' => $request->content,
                'urgent' => $request->boolean('urgent'),
                'status' => 'sent',
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Créer une notification
            $this->createNotification(
                'Diffusion envoyée',
                "Une diffusion a été envoyée à {$recipientCount} destinataire(s)",
                'success'
            );
            
            return response()->json([
                'success' => true,
                'message' => "Diffusion envoyée à {$recipientCount} destinataire(s)",
                'data' => $broadcast
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans CommunicationController@sendBroadcast', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de la diffusion'
            ], 500);
        }
    }
    
    /**
     * Obtenir les statistiques de communication
     */
    public function getStats()
    {
        try {
            $stats = $this->getCommunicationStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans CommunicationController@getStats', [
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
     * Obtenir les analytiques
     */
    public function getAnalytics(Request $request)
    {
        try {
            $period = $request->get('period', 'month');
            
            // Simulation d'analytiques
            $analytics = [
                'messages_by_month' => [
                    'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
                    'data' => [0, 0, 0, 0, 0, 0]
                ],
                'channels_usage' => [
                    'labels' => ['Email', 'SMS', 'Notification'],
                    'data' => [0, 0, 0]
                ],
                'delivery_rates' => [
                    'delivered' => 0,
                    'failed' => 0,
                    'pending' => 0
                ]
            ];
            
            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur dans CommunicationController@getAnalytics', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des analytiques'
            ], 500);
        }
    }
    
    /**
     * Obtenir les statistiques de communication
     */
    private function getCommunicationStats()
    {
        try {
            return [
                'total_messages' => Message::count(),
                'unread_messages' => Message::where('lu', false)->count(),
                'read_messages' => Message::where('lu', true)->count(),
                'pending_messages' => Message::where('lu', false)->count(),
                'total_notifications' => Notification::count(),
                'unread_notifications' => Notification::where('read', false)->count(),
                'active_channels' => 3 // Email, SMS, Notification
            ];
        } catch (\Exception $e) {
            return $this->getDefaultStats();
        }
    }
    
    /**
     * Statistiques par défaut
     */
    private function getDefaultStats()
    {
        return [
            'total_messages' => 0,
            'unread_messages' => 0,
            'read_messages' => 0,
            'pending_messages' => 0,
            'total_notifications' => 0,
            'unread_notifications' => 0,
            'active_channels' => 0
        ];
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
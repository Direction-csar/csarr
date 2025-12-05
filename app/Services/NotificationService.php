<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Créer une notification pour une nouvelle demande (Admin + DG)
     */
    public static function notifyNewRequest($request)
    {
        // Récupérer tous les utilisateurs Admin et DG
        $adminUsers = \App\Models\User::whereIn('role', ['admin', 'dg'])->get();
        
        $notifications = [];
        
        foreach ($adminUsers as $user) {
            $notification = Notification::create([
                'title' => 'Nouvelle demande reçue',
                'message' => "Une nouvelle demande de type '{$request->type}' a été soumise par {$request->full_name} depuis {$request->region}. Code de suivi: {$request->tracking_code}",
                'type' => 'success',
                'icon' => 'file-text',
                'data' => [
                    'request_id' => $request->id, 
                    'tracking_code' => $request->tracking_code,
                    'request_type' => $request->type,
                    'requester_name' => $request->full_name,
                    'region' => $request->region
                ],
                'user_id' => $user->id,
                'action_url' => $user->role === 'admin' 
                    ? route('admin.demandes.show', $request->id)
                    : route('dg.demandes.show', $request->id),
                'read' => false
            ]);
            
            $notifications[] = $notification;
        }
        
        return $notifications;
    }

    /**
     * Créer une notification pour un nouveau message de contact
     */
    public static function notifyNewContactMessage($contact)
    {
        return Notification::createNotification(
            'Nouveau message de contact',
            "Un nouveau message de contact a été reçu de {$contact->full_name} concernant: {$contact->subject}",
            'info',
            ['contact_id' => $contact->id, 'email' => $contact->email]
        );
    }

    /**
     * Créer une notification pour une nouvelle inscription newsletter
     */
    public static function notifyNewsletterSubscription($email)
    {
        return Notification::createNotification(
            'Nouvelle inscription newsletter',
            "Un nouvel abonné s'est inscrit à la newsletter: {$email}",
            'info',
            ['email' => $email]
        );
    }

    /**
     * Créer une notification pour un nouveau message
     */
    public static function notifyNewMessage($message)
    {
        return Notification::createNotification(
            'Nouveau message reçu',
            "Un nouveau message a été reçu de {$message->expediteur} concernant: {$message->sujet}",
            'info',
            ['message_id' => $message->id]
        );
    }

    /**
     * Créer une notification système
     */
    public static function notifySystem($title, $message, $type = 'info', $data = [])
    {
        return Notification::createNotification($title, $message, $type, $data);
    }

    /**
     * Créer une notification de maintenance
     */
    public static function notifyMaintenance($title, $message, $startTime = null, $endTime = null)
    {
        $data = [];
        if ($startTime) $data['start_time'] = $startTime;
        if ($endTime) $data['end_time'] = $endTime;

        return Notification::createNotification($title, $message, 'warning', $data);
    }

    /**
     * Créer une notification d'erreur système
     */
    public static function notifySystemError($title, $message, $errorData = [])
    {
        return Notification::createNotification($title, $message, 'error', $errorData);
    }

    /**
     * Créer une notification de succès
     */
    public static function notifySuccess($title, $message, $data = [])
    {
        return Notification::createNotification($title, $message, 'success', $data);
    }

    /**
     * Notifier tous les utilisateurs Admin
     */
    public static function notifyAdmins($title, $message, $type = 'info', $data = [], $actionUrl = null)
    {
        $adminUsers = \App\Models\User::where('role', 'admin')->get();
        $notifications = [];
        
        foreach ($adminUsers as $user) {
            $notification = Notification::create([
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'icon' => static::getDefaultIcon($type),
                'data' => $data,
                'user_id' => $user->id,
                'action_url' => $actionUrl,
                'read' => false
            ]);
            
            $notifications[] = $notification;
        }
        
        return $notifications;
    }

    /**
     * Notifier tous les utilisateurs DG
     */
    public static function notifyDGs($title, $message, $type = 'info', $data = [], $actionUrl = null)
    {
        $dgUsers = \App\Models\User::where('role', 'dg')->get();
        $notifications = [];
        
        foreach ($dgUsers as $user) {
            $notification = Notification::create([
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'icon' => static::getDefaultIcon($type),
                'data' => $data,
                'user_id' => $user->id,
                'action_url' => $actionUrl,
                'read' => false
            ]);
            
            $notifications[] = $notification;
        }
        
        return $notifications;
    }

    /**
     * Notifier Admin et DG ensemble
     */
    public static function notifyAdminAndDG($title, $message, $type = 'info', $data = [], $actionUrl = null)
    {
        $adminAndDgUsers = \App\Models\User::whereIn('role', ['admin', 'dg'])->get();
        $notifications = [];
        
        foreach ($adminAndDgUsers as $user) {
            $notification = Notification::create([
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'icon' => static::getDefaultIcon($type),
                'data' => $data,
                'user_id' => $user->id,
                'action_url' => $actionUrl,
                'read' => false
            ]);
            
            $notifications[] = $notification;
        }
        
        return $notifications;
    }

    /**
     * Obtenir l'icône par défaut selon le type
     */
    private static function getDefaultIcon($type)
    {
        $icons = [
            'success' => 'check-circle',
            'error' => 'x-circle',
            'warning' => 'alert-triangle',
            'info' => 'info',
            'demande' => 'file-text',
            'message' => 'mail',
            'newsletter' => 'send',
            'communication' => 'megaphone'
        ];

        return $icons[$type] ?? 'bell';
    }
}
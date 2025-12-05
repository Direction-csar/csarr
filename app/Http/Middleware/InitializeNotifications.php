<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InitializeNotifications
{
    public function handle(Request $request, Closure $next)
    {
        // Initialiser les notifications si elles n'existent pas
        if (!Session::has('notifications')) {
            $notifications = [
                [
                    'id' => 1,
                    'type' => 'warning',
                    'icon' => 'exclamation-triangle',
                    'message' => 'Stock faible détecté dans l\'entrepôt de Dakar',
                    'read' => false,
                    'created_at' => now()->subMinutes(30)
                ],
                [
                    'id' => 2,
                    'type' => 'info',
                    'icon' => 'info-circle',
                    'message' => '5 nouvelles demandes en attente de traitement',
                    'read' => false,
                    'created_at' => now()->subMinutes(15)
                ],
                [
                    'id' => 3,
                    'type' => 'success',
                    'icon' => 'check-circle',
                    'message' => 'Mise à jour du système terminée avec succès',
                    'read' => false,
                    'created_at' => now()->subMinutes(5)
                ]
            ];
            Session::put('notifications', $notifications);
        }

        // Initialiser les messages si ils n'existent pas
        if (!Session::has('messages')) {
            $messages = [
                [
                    'id' => 1,
                    'from' => 'Mamadou Diallo',
                    'subject' => 'Demande d\'approvisionnement',
                    'content' => 'Bonjour, j\'ai besoin d\'aide pour...',
                    'read' => false,
                    'created_at' => now()->subMinutes(45)
                ],
                [
                    'id' => 2,
                    'from' => 'Fatou Sall',
                    'subject' => 'Rapport mensuel',
                    'content' => 'Voici le rapport du mois...',
                    'read' => false,
                    'created_at' => now()->subMinutes(20)
                ]
            ];
            Session::put('messages', $messages);
        }

        return $next($request);
    }
}




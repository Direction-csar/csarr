<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminEmailService
{
    /**
     * Envoyer une notification email pour un nouveau message de contact
     */
    public function sendContactMessageNotification($contact)
    {
        try {
            $adminEmails = $this->getAdminEmails();
            
            foreach ($adminEmails as $email) {
                Mail::send('emails.admin.contact-notification', [
                    'contact' => $contact,
                    'adminEmail' => $email
                ], function ($message) use ($email, $contact) {
                    $message->to($email)
                        ->subject('🔔 Nouveau message de contact reçu - CSAR Platform')
                        ->priority(1); // Haute priorité
                });
            }
            
            Log::info('Email de notification de contact envoyé', [
                'contact_id' => $contact->id,
                'admin_emails' => $adminEmails
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur envoi email notification contact', [
                'error' => $e->getMessage(),
                'contact_id' => $contact->id ?? null
            ]);
        }
    }

    /**
     * Envoyer une notification email pour une nouvelle demande
     */
    public function sendRequestNotification($request)
    {
        try {
            $adminEmails = $this->getAdminEmails();
            
            foreach ($adminEmails as $email) {
                Mail::send('emails.admin.request-notification', [
                    'request' => $request,
                    'adminEmail' => $email
                ], function ($message) use ($email, $request) {
                    $message->to($email)
                        ->subject('🚨 Nouvelle demande d\'aide reçue - CSAR Platform')
                        ->priority(1); // Haute priorité
                });
            }
            
            Log::info('Email de notification de demande envoyé', [
                'request_id' => $request->id,
                'admin_emails' => $adminEmails
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur envoi email notification demande', [
                'error' => $e->getMessage(),
                'request_id' => $request->id ?? null
            ]);
        }
    }

    /**
     * Envoyer une notification email pour un nouvel abonnement newsletter
     */
    public function sendNewsletterSubscriptionNotification($subscriber)
    {
        try {
            $adminEmails = $this->getAdminEmails();
            
            foreach ($adminEmails as $email) {
                Mail::send('emails.admin.newsletter-notification', [
                    'subscriber' => $subscriber,
                    'adminEmail' => $email
                ], function ($message) use ($email, $subscriber) {
                    $message->to($email)
                        ->subject('📧 Nouvel abonnement newsletter - CSAR Platform')
                        ->priority(2); // Priorité normale
                });
            }
            
            Log::info('Email de notification newsletter envoyé', [
                'subscriber_id' => $subscriber->id,
                'admin_emails' => $adminEmails
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur envoi email notification newsletter', [
                'error' => $e->getMessage(),
                'subscriber_id' => $subscriber->id ?? null
            ]);
        }
    }

    /**
     * Obtenir les emails des administrateurs
     */
    private function getAdminEmails()
    {
        try {
            // Récupérer les emails des utilisateurs avec le rôle admin
            $adminEmails = \App\Models\User::where('role', 'admin')
                ->whereNotNull('email')
                ->pluck('email')
                ->toArray();
            
            // Ajouter des emails par défaut si aucun admin trouvé
            if (empty($adminEmails)) {
                $adminEmails = [
                    'admin@csar.sn',
                    'dg@csar.sn'
                ];
            }
            
            return $adminEmails;
            
        } catch (\Exception $e) {
            Log::error('Erreur récupération emails admin', [
                'error' => $e->getMessage()
            ]);
            
            // Emails de fallback
            return [
                'admin@csar.sn',
                'dg@csar.sn'
            ];
        }
    }

    /**
     * Envoyer un email de confirmation à l'utilisateur
     */
    public function sendUserConfirmation($userEmail, $type, $data = [])
    {
        try {
            $subject = match($type) {
                'contact' => '✅ Votre message a bien été transmis - CSAR',
                'request' => '✅ Votre demande a bien été enregistrée - CSAR',
                'newsletter' => '✅ Abonnement newsletter confirmé - CSAR',
                default => '✅ Confirmation - CSAR Platform'
            };

            Mail::send('emails.user.confirmation', [
                'type' => $type,
                'data' => $data,
                'userEmail' => $userEmail
            ], function ($message) use ($userEmail, $subject) {
                $message->to($userEmail)
                    ->subject($subject)
                    ->priority(3); // Priorité basse
            });
            
            Log::info('Email de confirmation utilisateur envoyé', [
                'user_email' => $userEmail,
                'type' => $type
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur envoi email confirmation utilisateur', [
                'error' => $e->getMessage(),
                'user_email' => $userEmail,
                'type' => $type
            ]);
        }
    }
}

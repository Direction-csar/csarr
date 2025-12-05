<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use App\Jobs\SendSmsJob;
use Illuminate\Support\Facades\Log;

class QueueService
{
    /**
     * Ajouter un email à la queue
     */
    public static function queueEmail($emailClass, $recipient, $data = [], $delay = 0)
    {
        try {
            $job = new SendEmailJob($emailClass, $recipient, $data);
            
            if ($delay > 0) {
                $job->delay(now()->addSeconds($delay));
            }
            
            dispatch($job);
            
            Log::info('Email ajouté à la queue', [
                'recipient' => $recipient,
                'email_class' => $emailClass,
                'delay' => $delay
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout de l\'email à la queue', [
                'recipient' => $recipient,
                'email_class' => $emailClass,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Ajouter un SMS à la queue
     */
    public static function queueSms($phoneNumber, $message, $type = 'notification', $delay = 0)
    {
        try {
            $job = new SendSmsJob($phoneNumber, $message, $type);
            
            if ($delay > 0) {
                $job->delay(now()->addSeconds($delay));
            }
            
            dispatch($job);
            
            Log::info('SMS ajouté à la queue', [
                'phone' => $phoneNumber,
                'type' => $type,
                'delay' => $delay
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du SMS à la queue', [
                'phone' => $phoneNumber,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Envoyer un email de confirmation de demande
     */
    public static function queueRequestConfirmation($requestData)
    {
        return self::queueEmail(
            \App\Mail\RequestConfirmation::class,
            $requestData['email'],
            $requestData
        );
    }

    /**
     * Envoyer une notification interne pour nouvelle demande
     */
    public static function queueRequestNotification($requestData)
    {
        $adminEmails = self::getAdminEmails();
        
        foreach ($adminEmails as $email) {
            self::queueEmail(
                \App\Mail\RequestNotification::class,
                $email,
                $requestData
            );
        }
    }

    /**
     * Envoyer un email de confirmation de contact
     */
    public static function queueContactConfirmation($contactData)
    {
        return self::queueEmail(
            \App\Mail\ContactConfirmation::class,
            $contactData['email'],
            $contactData
        );
    }

    /**
     * Envoyer une notification interne pour nouveau contact
     */
    public static function queueContactNotification($contactData)
    {
        $adminEmails = self::getAdminEmails();
        
        foreach ($adminEmails as $email) {
            self::queueEmail(
                \App\Mail\ContactNotification::class,
                $email,
                $contactData
            );
        }
    }

    /**
     * Envoyer un email de bienvenue newsletter
     */
    public static function queueNewsletterWelcome($email)
    {
        return self::queueEmail(
            \App\Mail\NewsletterWelcome::class,
            $email,
            ['email' => $email]
        );
    }

    /**
     * Envoyer un SMS de confirmation de demande
     */
    public static function queueRequestSms($phoneNumber, $trackingCode, $type = 'aide')
    {
        $message = "Votre demande CSAR ({$type}) a été reçue. Code de suivi: {$trackingCode}. Nous vous contacterons bientôt.";
        
        return self::queueSms($phoneNumber, $message, 'request_confirmation');
    }

    /**
     * Envoyer un SMS de mise à jour de statut
     */
    public static function queueStatusUpdateSms($phoneNumber, $trackingCode, $status)
    {
        $statusText = self::getStatusText($status);
        $message = "Votre demande CSAR ({$trackingCode}) est maintenant: {$statusText}";
        
        return self::queueSms($phoneNumber, $message, 'status_update');
    }

    /**
     * Obtenir les emails des administrateurs
     */
    private static function getAdminEmails()
    {
        return \App\Models\User::whereIn('role', ['admin', 'dg'])
            ->where('status', 'active')
            ->pluck('email')
            ->toArray();
    }

    /**
     * Obtenir le texte du statut
     */
    private static function getStatusText($status)
    {
        $statusTexts = [
            'pending' => 'En attente',
            'processing' => 'En cours de traitement',
            'completed' => 'Terminée',
            'cancelled' => 'Annulée'
        ];
        
        return $statusTexts[$status] ?? 'Inconnu';
    }

    /**
     * Traiter les emails en lot
     */
    public static function processBatchEmails($emails)
    {
        foreach ($emails as $emailData) {
            self::queueEmail(
                $emailData['class'],
                $emailData['recipient'],
                $emailData['data'],
                $emailData['delay'] ?? 0
            );
        }
    }

    /**
     * Traiter les SMS en lot
     */
    public static function processBatchSms($smsList)
    {
        foreach ($smsList as $smsData) {
            self::queueSms(
                $smsData['phone'],
                $smsData['message'],
                $smsData['type'] ?? 'notification',
                $smsData['delay'] ?? 0
            );
        }
    }
}

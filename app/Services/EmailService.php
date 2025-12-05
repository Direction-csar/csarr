<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\ContactConfirmation;
use App\Mail\ContactNotification;
use App\Mail\RequestConfirmation;
use App\Mail\RequestNotification;
use App\Mail\NewsletterWelcome;
use App\Mail\UserAccountCreated;
use App\Mail\PasswordResetConfirmation;

class EmailService
{
    /**
     * Envoyer un email de confirmation de contact
     */
    public function sendContactConfirmation($contactData)
    {
        try {
            Mail::to($contactData['email'])->send(new ContactConfirmation($contactData));
            Log::info('Email de confirmation de contact envoyé', ['email' => $contactData['email']]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email confirmation contact', [
                'email' => $contactData['email'],
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Envoyer une notification interne pour nouveau contact
     */
    public function sendContactNotification($contactData)
    {
        try {
            Mail::to('contact@csar.sn')->send(new ContactNotification($contactData));
            Log::info('Notification interne contact envoyée', ['email' => $contactData['email']]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi notification contact', [
                'email' => $contactData['email'],
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Envoyer un email de confirmation de demande
     */
    public function sendRequestConfirmation($requestData)
    {
        try {
            Mail::to($requestData['email'])->send(new RequestConfirmation($requestData));
            Log::info('Email de confirmation de demande envoyé', ['email' => $requestData['email']]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email confirmation demande', [
                'email' => $requestData['email'],
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Envoyer une notification interne pour nouvelle demande
     */
    public function sendRequestNotification($requestData)
    {
        try {
            // Envoyer à l'admin et aux responsables concernés
            $adminEmails = ['admin@csar.sn', 'dg@csar.sn'];
            foreach ($adminEmails as $email) {
                Mail::to($email)->send(new RequestNotification($requestData));
            }
            Log::info('Notification interne demande envoyée', ['email' => $requestData['email']]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi notification demande', [
                'email' => $requestData['email'],
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Envoyer un email de bienvenue newsletter
     */
    public function sendNewsletterWelcome($subscriberData)
    {
        try {
            Mail::to($subscriberData['email'])->send(new NewsletterWelcome($subscriberData));
            Log::info('Email de bienvenue newsletter envoyé', ['email' => $subscriberData['email']]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email newsletter', [
                'email' => $subscriberData['email'],
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Envoyer un email de création de compte utilisateur
     */
    public function sendUserAccountCreated($userData, $password)
    {
        try {
            Mail::to($userData['email'])->send(new UserAccountCreated($userData, $password));
            Log::info('Email de création de compte envoyé', ['email' => $userData['email']]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email création compte', [
                'email' => $userData['email'],
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Envoyer un email de confirmation de réinitialisation de mot de passe
     */
    public function sendPasswordResetConfirmation($userData)
    {
        try {
            Mail::to($userData['email'])->send(new PasswordResetConfirmation($userData));
            Log::info('Email de confirmation reset mot de passe envoyé', ['email' => $userData['email']]);
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur envoi email reset mot de passe', [
                'email' => $userData['email'],
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}


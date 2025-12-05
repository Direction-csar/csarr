<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NewsletterSubscriptionController extends Controller
{
    /**
     * S'abonner à la newsletter
     */
    public function subscribe(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:newsletter_subscribers,email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.unique' => 'Cette adresse email est déjà abonnée à notre newsletter.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Créer l'abonnement
            $subscriber = NewsletterSubscriber::create([
                'email' => $request->email,
                'name' => $request->name,
                'status' => 'active',
                'subscribed_at' => now(),
                'source' => 'website_footer',
                'preferences' => [
                    'frequency' => 'weekly',
                    'categories' => ['news', 'reports', 'events']
                ]
            ]);

            // Envoyer un email de notification à l'admin
            $this->sendAdminNotification($subscriber);

            // Envoyer un email de confirmation à l'abonné
            $this->sendConfirmationEmail($subscriber);

            return response()->json([
                'success' => true,
                'message' => 'Inscription réussie ! Merci pour votre abonnement à la newsletter du CSAR.'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'abonnement à la newsletter: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de votre inscription. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Se désabonner de la newsletter
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:newsletter_subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Adresse email invalide.'
            ], 422);
        }

        try {
            $subscriber = NewsletterSubscriber::where('email', $request->email)->first();
            
            if ($subscriber) {
                $subscriber->unsubscribe();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Vous avez été désabonné de notre newsletter.'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Adresse email non trouvée.'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Erreur lors du désabonnement: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Envoyer une notification à l'admin
     */
    private function sendAdminNotification($subscriber)
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@csar.sn');
            
            Mail::send('emails.newsletter.admin-notification', [
                'subscriber' => $subscriber,
                'adminEmail' => $adminEmail
            ], function ($message) use ($adminEmail, $subscriber) {
                $message->to($adminEmail)
                        ->subject('Nouvel abonnement à la newsletter CSAR')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error('Erreur envoi notification admin: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer un email de confirmation à l'abonné
     */
    private function sendConfirmationEmail($subscriber)
    {
        try {
            Mail::send('emails.newsletter.welcome', [
                'subscriber' => $subscriber
            ], function ($message) use ($subscriber) {
                $message->to($subscriber->email)
                        ->subject('Bienvenue dans la newsletter du CSAR')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

        } catch (\Exception $e) {
            Log::error('Erreur envoi email confirmation: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques des abonnés (pour l'admin)
     */
    public function getStats()
    {
        try {
            $stats = NewsletterSubscriber::getStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur récupération stats newsletter: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques.'
            ], 500);
        }
    }
}

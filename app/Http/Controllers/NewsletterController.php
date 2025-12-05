<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Enregistrer un nouvel abonnement à la newsletter
     */
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $email = $request->input('email');

            // Vérifier si l'email existe déjà
            $existingSubscriber = \App\Models\NewsletterSubscriber::where('email', $email)->first();

            if ($existingSubscriber) {
                if ($existingSubscriber->status === 'active') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cette adresse email est déjà abonnée à notre newsletter.'
                    ], 409);
                } else {
                    // Réactiver l'abonnement
                    $existingSubscriber->update([
                        'status' => 'active',
                        'subscribed_at' => now(),
                        'unsubscribed_at' => null
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Merci ! Votre abonnement à la newsletter du CSAR a bien été réactivé.'
                    ]);
                }
            }

            // Vérifier les doublons
            if (\App\Services\SecurityService::checkDuplicateNewsletter($email)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Un abonnement a déjà été effectué récemment. Veuillez attendre avant de vous réabonner.'
                ], 409);
            }

            // Générer le hash de duplication
            $duplicateHash = \App\Services\SecurityService::generateDuplicateHash($email);

            // Créer un nouvel abonnement dans la table NewsletterSubscriber
            $subscriber = \App\Models\NewsletterSubscriber::create([
                'email' => $email,
                'status' => 'active',
                'subscribed_at' => now(),
                'source' => 'website',
                'duplicate_hash' => $duplicateHash
            ]);

            // Journaliser la création de l'abonnement
            \App\Services\SecurityService::logAudit('newsletter_subscription', 'NewsletterSubscriber', $subscriber->id, [
                'email' => $email,
                'duplicate_hash' => $duplicateHash
            ]);

            // Créer une notification automatique pour l'admin
            try {
                \App\Models\Notification::create([
                    'type' => 'info',
                    'title' => 'Nouvel abonnement newsletter',
                    'message' => "Un nouvel abonné s'est inscrit à la newsletter: {$email}",
                    'user_id' => null, // Notification globale pour tous les admins
                    'read' => false
                ]);
            } catch (\Exception $e) {
                // Log l'erreur mais ne pas faire échouer le processus
                \Log::error('Erreur création notification newsletter: ' . $e->getMessage());
            }

            // Envoyer un email de notification aux administrateurs
            try {
                $emailService = new \App\Services\AdminEmailService();
                $emailService->sendNewsletterSubscriptionNotification($subscriber);
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email notification newsletter: ' . $e->getMessage());
            }

            // Envoyer un email de confirmation à l'utilisateur
            try {
                $emailService = new \App\Services\AdminEmailService();
                $emailService->sendUserConfirmation($email, 'newsletter');
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email confirmation newsletter: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Merci ! Votre abonnement à la newsletter du CSAR a bien été enregistré.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Désabonner un utilisateur
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Adresse email invalide.'
            ], 422);
        }

        try {
            $email = $request->input('email');

            $updated = DB::table('newsletters')
                ->where('email', $email)
                ->where('is_active', 1)
                ->update([
                    'is_active' => 0,
                    'unsubscribed_at' => now(),
                    'updated_at' => now()
                ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vous avez été désabonné de notre newsletter.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Adresse email non trouvée ou déjà désabonnée.'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du désabonnement.'
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des abonnés
     */
    public function stats()
    {
        try {
            $stats = [
                'total_subscribers' => DB::table('newsletters')->where('is_active', 1)->count(),
                'total_unsubscribed' => DB::table('newsletters')->where('is_active', 0)->count(),
                'subscribers_this_month' => DB::table('newsletters')
                    ->where('is_active', 1)
                    ->where('subscribed_at', '>=', now()->startOfMonth())
                    ->count(),
                'subscribers_this_week' => DB::table('newsletters')
                    ->where('is_active', 1)
                    ->where('subscribed_at', '>=', now()->startOfWeek())
                    ->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques.'
            ], 500);
        }
    }
}
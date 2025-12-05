<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.max' => 'L\'adresse email ne doit pas dépasser 255 caractères.'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $email = $request->email;

            // Vérifier si l'email existe déjà
            $existingSubscriber = NewsletterSubscriber::where('email', $email)->first();

            if ($existingSubscriber) {
                if ($existingSubscriber->status === 'active') {
                    $message = 'Cette adresse email est déjà inscrite à notre newsletter.';
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => $message], 422);
                    }
                    return back()->with('error', $message);
                } else {
                    // Réactiver l'inscription
                    $existingSubscriber->update([
                        'status' => 'active',
                        'subscribed_at' => now(),
                        'unsubscribed_at' => null
                    ]);
                    $message = 'Vous êtes abonné avec succès à notre newsletter.';
                    if ($request->expectsJson()) {
                        return response()->json(['success' => true, 'message' => $message]);
                    }
                    return back()->with('success', $message);
                }
            }

            // Créer une nouvelle inscription
            $subscriber = NewsletterSubscriber::create([
                'email' => $email,
                'status' => 'active',
                'subscribed_at' => now(),
                'source' => 'website'
            ]);

            // Créer une notification pour l'admin
            try {
                \App\Models\Notification::create([
                    'type' => 'info',
                    'title' => 'Nouvel abonnement newsletter',
                    'message' => "Un nouvel abonné s'est inscrit à la newsletter: {$email}",
                    'user_id' => null,
                    'read' => false
                ]);
            } catch (\Exception $e) {
                \Log::error('Erreur création notification newsletter: ' . $e->getMessage());
            }

            $message = 'Vous êtes abonné avec succès à notre newsletter.';
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Erreur abonnement newsletter: ' . $e->getMessage());
            
            $message = 'Une erreur est survenue lors de l\'abonnement. Veuillez réessayer.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ], [
            'email.required' => 'L\'adresse email est requise.',
            'email.email' => 'L\'adresse email doit être valide.'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator);
        }

        try {
            $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

            if (!$subscriber) {
                $message = 'Cette adresse email n\'est pas inscrite à notre newsletter.';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return back()->with('error', $message);
            }

            $subscriber->update([
                'status' => 'unsubscribed',
                'unsubscribed_at' => now()
            ]);

            $message = 'Vous avez été désinscrit de notre newsletter.';
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return back()->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Erreur désabonnement newsletter: ' . $e->getMessage());
            
            $message = 'Une erreur est survenue lors du désabonnement. Veuillez réessayer.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    public function checkSubscription(Request $request)
    {
        $email = $request->query('email');
        
        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Adresse email requise'
            ], 400);
        }

        $subscription = NewsletterSubscriber::where('email', $email)->first();
        
        return response()->json([
            'success' => true,
            'is_subscribed' => $subscription ? ($subscription->status === 'active') : false,
            'subscribed_at' => $subscription ? $subscription->subscribed_at : null
        ]);
    }

    /**
     * Afficher la page de désinscription
     */
    public function unsubscribePage()
    {
        return view('newsletter.unsubscribe');
    }
}

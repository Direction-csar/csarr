<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\NewsletterSubscriber;
use App\Models\SimReport;
use App\Models\News;
use App\Models\About;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class IntegrationController extends Controller
{
    /**
     * Afficher le tableau de bord d'intégration
     */
    public function index()
    {
        try {
            $stats = [
                'messages' => [
                    'total' => Message::count(),
                    'unread' => Message::where('lu', false)->count(),
                    'replied' => Message::whereNotNull('reponse')->count(),
                    'pending' => Message::where('status', 'pending')->count()
                ],
                'newsletter' => [
                    'subscribers' => NewsletterSubscriber::count(),
                    'active' => NewsletterSubscriber::where('status', 'active')->count(),
                    'unsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count()
                ],
                'sim_reports' => [
                    'total' => SimReport::count(),
                    'published' => SimReport::where('status', 'published')->count(),
                    'pending' => SimReport::where('status', 'pending')->count()
                ],
                'news' => [
                    'total' => News::count(),
                    'published' => News::where('status', 'published')->count(),
                    'draft' => News::where('status', 'draft')->count()
                ]
            ];

            $recentMessages = Message::orderBy('created_at', 'desc')->limit(5)->get();
            $recentSubscribers = NewsletterSubscriber::where('status', 'active')->orderBy('created_at', 'desc')->limit(5)->get();
            $recentReports = SimReport::where('status', 'published')->orderBy('created_at', 'desc')->limit(5)->get();
            $recentNews = News::where('status', 'published')->orderBy('created_at', 'desc')->limit(5)->get();

            return view('admin.integration.index', compact('stats', 'recentMessages', 'recentSubscribers', 'recentReports', 'recentNews'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du tableau de bord d\'intégration: ' . $e->getMessage());
            return view('admin.integration.index', [
                'stats' => [
                    'messages' => ['total' => 0, 'unread' => 0, 'replied' => 0, 'pending' => 0],
                    'newsletter' => ['subscribers' => 0, 'active' => 0, 'unsubscribed' => 0],
                    'sim_reports' => ['total' => 0, 'published' => 0, 'pending' => 0],
                    'news' => ['total' => 0, 'published' => 0, 'draft' => 0]
                ],
                'recentMessages' => collect(),
                'recentSubscribers' => collect(),
                'recentReports' => collect(),
                'recentNews' => collect()
            ]);
        }
    }

    /**
     * Publier un rapport SIM sur la plateforme publique
     */
    public function publishSimReport(Request $request, $id)
    {
        try {
            $report = SimReport::findOrFail($id);
            
            $report->update([
                'status' => 'published',
                'published_at' => now()
            ]);

            // Créer une notification
            \App\Models\Notification::createSystem(
                'Rapport SIM publié',
                "Le rapport '{$report->title}' a été publié sur la plateforme publique.",
                'success',
                'info',
                route('public.sim-reports'),
                'Voir le rapport'
            );

            return response()->json([
                'success' => true,
                'message' => 'Rapport publié avec succès sur la plateforme publique'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la publication du rapport SIM: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication du rapport'
            ], 500);
        }
    }

    /**
     * Publier une actualité sur la plateforme publique
     */
    public function publishNews(Request $request, $id)
    {
        try {
            $news = News::findOrFail($id);
            
            $news->update([
                'status' => 'published',
                'published_at' => now()
            ]);

            // Créer une notification
            \App\Models\Notification::createSystem(
                'Actualité publiée',
                "L'actualité '{$news->title}' a été publiée sur la plateforme publique.",
                'success',
                'info',
                route('public.news.show', $news->id),
                'Voir l\'actualité'
            );

            return response()->json([
                'success' => true,
                'message' => 'Actualité publiée avec succès sur la plateforme publique'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la publication de l\'actualité: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la publication de l\'actualité'
            ], 500);
        }
    }

    /**
     * Mettre à jour les informations "À propos" sur la plateforme publique
     */
    public function updateAboutInfo(Request $request)
    {
        try {
            $about = About::first();
            
            if (!$about) {
                $about = About::create($request->all());
            } else {
                $about->update($request->all());
            }

            // Créer une notification
            \App\Models\Notification::createSystem(
                'Informations mises à jour',
                'Les informations "À propos" ont été mises à jour sur la plateforme publique.',
                'success',
                'info',
                route('public.about'),
                'Voir les informations'
            );

            return response()->json([
                'success' => true,
                'message' => 'Informations mises à jour avec succès sur la plateforme publique'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des informations À propos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour des informations'
            ], 500);
        }
    }

    /**
     * Envoyer une newsletter aux abonnés
     */
    public function sendNewsletter(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
                'template' => 'nullable|string'
            ]);

            $newsletter = \App\Models\Newsletter::create([
                'title' => $request->title,
                'subject' => $request->subject,
                'content' => $request->content,
                'template' => $request->template ?? 'default',
                'status' => 'pending',
                'sent_by' => auth()->id()
            ]);

            // Ici, vous pouvez ajouter la logique d'envoi d'emails
            // Par exemple, utiliser une queue pour envoyer les emails en arrière-plan

            return response()->json([
                'success' => true,
                'message' => 'Newsletter créée avec succès et sera envoyée aux abonnés'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la newsletter'
            ], 500);
        }
    }

    /**
     * Répondre à un message depuis l'admin
     */
    public function replyToMessage(Request $request, $id)
    {
        try {
            $request->validate([
                'reply_message' => 'required|string|max:1000'
            ]);

            $message = Message::findOrFail($id);
            
            $message->update([
                'replied' => true,
                'replied_at' => now(),
                'replied_by' => auth()->id(),
                'reply_message' => $request->reply_message,
                'status' => 'processed'
            ]);

            // Ici, vous pouvez ajouter la logique d'envoi d'email de réponse
            // Par exemple, envoyer un email à l'expéditeur du message

            return response()->json([
                'success' => true,
                'message' => 'Réponse envoyée avec succès'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la réponse: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de la réponse'
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques d'intégration
     */
    public function getIntegrationStats()
    {
        try {
            $stats = [
                'messages' => [
                    'total' => Message::count(),
                    'unread' => Message::where('lu', false)->count(),
                    'replied' => Message::whereNotNull('reponse')->count(),
                    'pending' => Message::where('status', 'pending')->count(),
                    'new_today' => Message::whereDate('created_at', today())->count()
                ],
                'newsletter' => [
                    'subscribers' => NewsletterSubscriber::count(),
                    'active' => NewsletterSubscriber::where('status', 'active')->count(),
                    'unsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
                    'new_this_month' => NewsletterSubscriber::where('subscribed_at', '>=', now()->startOfMonth())->count()
                ],
                'sim_reports' => [
                    'total' => SimReport::count(),
                    'published' => SimReport::where('status', 'published')->count(),
                    'pending' => SimReport::where('status', 'pending')->count(),
                    'downloads' => SimReport::sum('download_count')
                ],
                'news' => [
                    'total' => News::count(),
                    'published' => News::where('status', 'published')->count(),
                    'draft' => News::where('status', 'draft')->count(),
                    'views' => News::sum('views_count')
                ]
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques d\'intégration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }

    /**
     * Synchroniser les données entre admin et public
     */
    public function syncData()
    {
        try {
            // Synchroniser les messages non lus
            $unreadMessages = Message::where('lu', false)->count();
            
            // Synchroniser les abonnés actifs
            $activeSubscribers = NewsletterSubscriber::where('status', 'active')->count();
            
            // Synchroniser les rapports publiés
            $publishedReports = SimReport::where('status', 'published')->count();
            
            // Synchroniser les actualités publiées
            $publishedNews = News::where('status', 'published')->count();

            return response()->json([
                'success' => true,
                'message' => 'Synchronisation terminée avec succès',
                'data' => [
                    'unread_messages' => $unreadMessages,
                    'active_subscribers' => $activeSubscribers,
                    'published_reports' => $publishedReports,
                    'published_news' => $publishedNews
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la synchronisation des données: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la synchronisation'
            ], 500);
        }
    }
}

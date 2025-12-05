<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\NewsletterSubscriber;
use App\Models\SimReport;
use App\Models\News;
use App\Models\About;
use App\Models\Personnel;
use App\Models\Warehouse;
use App\Models\Demande;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    /**
     * Afficher la page d'accueil publique
     */
    public function index()
    {
        try {
            $stats = [
                'total_staff' => Personnel::count(),
                'total_warehouses' => Warehouse::count(),
                'beneficiaries' => PublicRequest::count(),
                'founded_year' => 2010
            ];

            $recentNews = News::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            $recentReports = SimReport::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            return view('public.index', compact('stats', 'recentNews', 'recentReports'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de la page d\'accueil publique: ' . $e->getMessage());
            return view('public.index', [
                'stats' => ['total_staff' => 0, 'total_warehouses' => 0, 'beneficiaries' => 0, 'founded_year' => 2010],
                'recentNews' => collect(),
                'recentReports' => collect()
            ]);
        }
    }

    /**
     * Afficher la page À propos
     */
    public function about()
    {
        try {
            $about = About::first();
            $stats = [
                'founded_year' => 2010,
                'total_staff' => Personnel::count(),
                'total_warehouses' => Warehouse::count(),
                'beneficiaries' => Demande::count()
            ];

            return view('public.about', compact('about', 'stats'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de la page À propos: ' . $e->getMessage());
            return view('public.about', [
                'about' => null,
                'stats' => ['founded_year' => 2010, 'total_staff' => 0, 'total_warehouses' => 0, 'beneficiaries' => 0]
            ]);
        }
    }

    /**
     * Afficher les actualités
     */
    public function news()
    {
        try {
            $news = News::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->paginate(6);

            return view('public.news', compact('news'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des actualités: ' . $e->getMessage());
            return view('public.news', ['news' => collect()]);
        }
    }

    /**
     * Afficher un article d'actualité
     */
    public function showNews($id)
    {
        try {
            $news = News::where('id', $id)
                ->where('status', 'published')
                ->firstOrFail();

            $relatedNews = News::where('status', 'published')
                ->where('id', '!=', $id)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            return view('public.news.show', compact('news', 'relatedNews'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement de l\'actualité: ' . $e->getMessage());
            return redirect()->route('public.news')->with('error', 'Actualité non trouvée');
        }
    }

    /**
     * Afficher les rapports SIM
     */
    public function simReports()
    {
        try {
            $reports = SimReport::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->paginate(6);

            return view('public.sim-reports', compact('reports'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des rapports SIM: ' . $e->getMessage());
            return view('public.sim-reports', ['reports' => collect()]);
        }
    }

    /**
     * Télécharger un rapport SIM
     */
    public function downloadSimReport($id)
    {
        try {
            $report = SimReport::where('id', $id)
                ->where('status', 'published')
                ->firstOrFail();

            if (!$report->document_file) {
                return redirect()->back()->with('error', 'Document non disponible');
            }

            $filePath = storage_path('app/public/' . $report->document_file);
            
            if (!file_exists($filePath)) {
                return redirect()->back()->with('error', 'Fichier non trouvé');
            }

            return response()->download($filePath, $report->title . '.pdf');
        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement du rapport: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du téléchargement');
        }
    }

    /**
     * Afficher le formulaire de contact
     */
    public function contact()
    {
        return view('public.contact');
    }

    /**
     * Envoyer un message de contact
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'phone' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Message::create([
                'expediteur' => $request->name,
                'email_expediteur' => $request->email,
                'sujet' => $request->subject,
                'contenu' => $request->message,
                'telephone_expediteur' => $request->phone,
                'lu' => false,
                'reponse' => null
            ]);

            return redirect()->back()->with('success', 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi du message: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi du message. Veuillez réessayer.');
        }
    }

    /**
     * S'abonner à la newsletter
     */
    public function subscribeNewsletter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email invalide ou déjà inscrit'
            ], 400);
        }

        try {
            NewsletterSubscriber::create([
                'email' => $request->email,
                'name' => $request->name,
                'status' => 'active',
                'subscribed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inscription à la newsletter réussie !'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'inscription à la newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'inscription'
            ], 500);
        }
    }

    /**
     * Se désabonner de la newsletter
     */
    public function unsubscribeNewsletter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Email invalide'
            ], 400);
        }

        try {
            $subscriber = NewsletterSubscriber::where('email', $request->email)->first();
            
            if ($subscriber) {
                $subscriber->update(['status' => 'unsubscribed']);
                return response()->json([
                    'success' => true,
                    'message' => 'Désabonnement réussi'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Email non trouvé'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du désabonnement: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du désabonnement'
            ], 500);
        }
    }
}

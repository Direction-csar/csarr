<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Models\HRDocument;
use App\Models\SalarySlip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Créer un profil Personnel temporaire avec toutes les propriétés nécessaires
     */
    private function createTemporaryPersonnel($user)
    {
        $personnel = new \stdClass();
        $personnel->id = null;
        $personnel->prenoms_nom = $user->name;
        $personnel->email = $user->email;
        $personnel->matricule = 'TEMP-' . $user->id;
        $personnel->poste_actuel = 'Agent';
        $personnel->direction_service = 'CSAR';
        $personnel->date_recrutement_csar = $user->created_at;
        $personnel->statut = 'Contractuel';
        $personnel->statut_validation = 'valide';
        $personnel->contact_telephonique = $user->phone ?? 'Non renseigné';
        $personnel->adresse_complete = $user->address ?? 'Non renseignée';
        $personnel->date_naissance = null;
        $personnel->lieu_naissance = 'Non renseigné';
        $personnel->nationalite = 'Sénégalaise';
        $personnel->sexe = 'Non spécifié';
        $personnel->situation_matrimoniale = 'Non spécifié';
        $personnel->nombre_enfants = 0;
        $personnel->groupe_sanguin = 'Non spécifié';
        $personnel->photo_personnelle = null;
        
        return $personnel;
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('agent.login')->with('error', "Session expirée. Veuillez vous reconnecter.");
        }
        
        // Vérifier que l'utilisateur a un email
        if (!$user->email) {
            return redirect()->route('agent.login')->with('error', "Profil utilisateur incomplet. Veuillez contacter l'administrateur.");
        }
        
        // Récupérer le profil personnel de l'agent
        $personnel = Personnel::where('email', $user->email)->first();
        
        // Si aucun profil personnel n'est trouvé, continuer avec des valeurs par défaut
        if (!$personnel) {
            $personnel = $this->createTemporaryPersonnel($user);
        }
        
        // Message de bienvenue personnalisé
        $welcomeMessage = $this->getWelcomeMessage($personnel);
        
        // Résumé du profil
        $profileSummary = [
            'poste_actuel' => $personnel->poste_actuel,
            'direction_service' => $personnel->direction_service,
            'date_recrutement' => $personnel->date_recrutement_csar ? Carbon::parse($personnel->date_recrutement_csar)->format('d/m/Y') : 'Non spécifiée',
            'contrat_type' => $personnel->statut,
            'contrat_statut' => $personnel->statut_validation === 'valide' ? 'Actif' : 'En attente de validation'
        ];
        
        // Statistiques personnelles
        $stats = [
            'documents_total' => $personnel->id ? HRDocument::where('personnel_id', $personnel->id)->count() : 0,
            'bulletins_total' => $personnel->id ? SalarySlip::where('personnel_id', $personnel->id)->count() : 0,
            'bulletins_ce_mois' => $personnel->id ? SalarySlip::where('personnel_id', $personnel->id)
                ->whereYear('periode_debut', now()->year)
                ->whereMonth('periode_debut', now()->month)
                ->count() : 0,
            'documents_recents' => $personnel->id ? HRDocument::where('personnel_id', $personnel->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->count() : 0
        ];
        
        // Documents récents
        $recentDocuments = $personnel->id ? HRDocument::where('personnel_id', $personnel->id)
            ->latest()
            ->take(5)
            ->get() : collect();
        
        // Bulletins récents
        $recentSalarySlips = $personnel->id ? SalarySlip::where('personnel_id', $personnel->id)
            ->latest()
            ->take(5)
            ->get() : collect();
        
        // Notifications
        $notifications = $this->getNotifications($personnel);
        
        return view('agent.dashboard', compact(
            'user',
            'personnel',
            'welcomeMessage',
            'profileSummary',
            'stats',
            'recentDocuments',
            'recentSalarySlips',
            'notifications'
        ));
    }
    
    /**
     * Générer un message de bienvenue personnalisé
     */
    private function getWelcomeMessage($personnel)
    {
        $hour = now()->hour;
        $greeting = '';
        
        if ($hour < 12) {
            $greeting = 'Bonjour';
        } elseif ($hour < 18) {
            $greeting = 'Bon après-midi';
        } else {
            $greeting = 'Bonsoir';
        }
        
        $firstName = explode(' ', $personnel->prenoms_nom)[0] ?? $personnel->prenoms_nom;
        
        return [
            'greeting' => $greeting,
            'name' => $firstName,
            'full_name' => $personnel->prenoms_nom,
            'poste' => $personnel->poste_actuel,
            'direction' => $personnel->direction_service
        ];
    }
    
    /**
     * Récupérer les notifications pour l'agent
     */
    private function getNotifications($personnel)
    {
        $notifications = [];
        
        // Vérifier les documents expirés ou expirant bientôt
        $expiringDocuments = HRDocument::where('personnel_id', $personnel->id)
            ->where('date_emission', '<=', now()->subMonths(11))
            ->get();
        
        foreach ($expiringDocuments as $document) {
            $notifications[] = [
                'type' => 'warning',
                'title' => 'Document expirant',
                'message' => "Le document '{$document->file_name}' expire bientôt",
                'time' => 'Maintenant',
                'icon' => 'fas fa-exclamation-triangle'
            ];
        }
        
        // Vérifier les nouveaux documents
        $newDocuments = HRDocument::where('personnel_id', $personnel->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->get();
        
        foreach ($newDocuments as $document) {
            $notifications[] = [
                'type' => 'info',
                'title' => 'Nouveau document',
                'message' => "Un nouveau document '{$document->file_name}' a été ajouté",
                'time' => $document->created_at ? $document->created_at->diffForHumans() : 'Date inconnue',
                'icon' => 'fas fa-file-alt'
            ];
        }
        
        // Vérifier les bulletins de salaire du mois
        $currentMonthSlip = SalarySlip::where('personnel_id', $personnel->id)
            ->whereYear('periode_debut', now()->year)
            ->whereMonth('periode_debut', now()->month)
            ->first();
        
        if ($currentMonthSlip) {
            $notifications[] = [
                'type' => 'success',
                'title' => 'Bulletin disponible',
                'message' => "Votre bulletin de salaire de " . now()->format('F Y') . " est disponible",
                'time' => $currentMonthSlip->created_at ? $currentMonthSlip->created_at->diffForHumans() : 'Date inconnue',
                'icon' => 'fas fa-file-invoice-dollar'
            ];
        }
        
        return $notifications;
    }
}

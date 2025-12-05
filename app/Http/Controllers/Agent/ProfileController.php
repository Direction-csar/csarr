<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PDF;

class ProfileController extends Controller
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
        $personnel->tranche_age = 'Non spécifiée';
        $personnel->numero_cni = 'Non renseigné';
        $personnel->formations_professionnelles = 'Non renseignées';
        $personnel->diplome_academique = 'Non renseigné';
        $personnel->autres_diplomes_certifications = 'Aucun';
        $personnel->logiciels_maitrises = [];
        $personnel->langues_parlees = [];
        $personnel->autres_aptitudes = 'Non renseignées';
        $personnel->aspirations_professionnelles = 'Non renseignées';
        $personnel->interet_nouvelles_responsabilites = 'Non spécifié';
        $personnel->taille_vetements = 'Non spécifiée';
        $personnel->contact_urgence_nom = 'Non renseigné';
        $personnel->contact_urgence_telephone = 'Non renseigné';
        $personnel->contact_urgence_lien_parente = 'Non spécifié';
        $personnel->observations_personnelles = 'Profil temporaire - Veuillez contacter l\'administrateur';
        $personnel->date_validation = $user->created_at;
        $personnel->valide_par = null;
        $personnel->commentaire_validation = 'Profil temporaire';
        
        return $personnel;
    }

    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Utilisateur non authentifié');
            }
            
            // Récupérer le profil personnel de l'agent
            $personnel = Personnel::where('email', $user->email)->first();
            
            if (!$personnel) {
                // Créer un profil temporaire avec les données utilisateur
                $personnel = $this->createTemporaryPersonnel($user);
            }
            
            return view('agent.profile', compact('personnel', 'user'));
            
        } catch (\Exception $e) {
            \Log::error('Erreur lors du chargement du profil agent', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()->with('error', 'Erreur lors du chargement du profil. Veuillez réessayer.');
        }
    }
    
    /**
     * Afficher le formulaire de modification du profil
     */
    public function edit()
    {
        $user = Auth::user();
        $personnel = Personnel::where('email', $user->email)->first();
        
        if (!$personnel) {
            $personnel = $this->createTemporaryPersonnel($user);
        }
        
        return view('agent.profile.edit', compact('personnel', 'user'));
    }
    
    /**
     * Mettre à jour le profil de l'agent
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $user = Auth::user();
        
        // Mettre à jour les informations de base
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone
        ]);
        
        // Gérer l'upload de photo
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('agent-photos', 'public');
            $user->update(['photo' => $photoPath]);
        }
        
        // Mettre à jour le personnel si l'email a changé
        $personnel = Personnel::where('email', $user->getOriginal('email'))->first();
        if ($personnel && $request->email !== $user->getOriginal('email')) {
            $personnel->update(['email' => $request->email]);
        }
        
        return redirect()->route('agent.profile')->with('success', 'Profil mis à jour avec succès');
    }
    
    /**
     * Afficher le formulaire de changement de mot de passe
     */
    public function changePassword()
    {
        return view('agent.profile.change-password');
    }
    
    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect']);
        }
        
        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('agent.profile')->with('success', 'Mot de passe mis à jour avec succès');
    }
    
    /**
     * Télécharger la fiche agent en PDF
     */
    public function downloadPdf()
    {
        $user = Auth::user();
        $personnel = Personnel::where('email', $user->email)->first();
        
        if (!$personnel) {
            return back()->with('error', 'Profil personnel non trouvé');
        }
        
        $pdf = PDF::loadView('agent.profile.pdf', compact('personnel'));
        
        return $pdf->download('fiche-agent-' . $personnel->matricule . '.pdf');
    }
    
    /**
     * Afficher les informations détaillées du profil
     */
    public function show()
    {
        $user = Auth::user();
        $personnel = Personnel::where('email', $user->email)->first();
        
        if (!$personnel) {
            return back()->with('error', 'Profil personnel non trouvé. Veuillez contacter l\'administrateur pour créer votre profil.');
        }
        
        return view('agent.profile.show', compact('personnel', 'user'));
    }
}

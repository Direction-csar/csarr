<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\DemandeUnifiee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DemandeController extends Controller
{
    public function index()
    {
        try {
            $demandes = DemandeUnifiee::latest()->paginate(5); // Réduire pour éviter la coupure
            
            $stats = [
                "total" => DemandeUnifiee::count(),
                "en_attente" => DemandeUnifiee::where("statut", "en_attente")->count(),
                "approuvees" => DemandeUnifiee::where("statut", "approuvee")->count(),
                "rejetees" => DemandeUnifiee::where("statut", "rejetee")->count(),
            ];
            
            return view("dg.demandes.index", compact("demandes", "stats"));
            
        } catch (\Exception $e) {
            Log::error("Erreur lors du chargement des demandes DG", [
                "error" => $e->getMessage()
            ]);
            
            return redirect()->back()->with("error", "Erreur lors du chargement des demandes");
        }
    }
    
    public function show($id)
    {
        try {
            $demande = DemandeUnifiee::findOrFail($id);
            return view("dg.demandes.show", compact("demande"));
            
        } catch (\Exception $e) {
            return redirect()->back()->with("error", "Demande non trouvée");
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $demande = DemandeUnifiee::findOrFail($id);
            
            $request->validate([
                "statut" => "required|in:en_attente,en_cours,approuvee,rejetee,terminee",
                "commentaire_admin" => "nullable|string|max:1000"
            ]);
            
            $demande->update([
                "statut" => $request->statut,
                "commentaire_admin" => $request->commentaire_admin,
                "traite_par" => auth()->id(),
                "date_traitement" => now()
            ]);
            
            Log::info("Demande mise à jour par DG", [
                "demande_id" => $id,
                "statut" => $request->statut,
                "dg_id" => auth()->id()
            ]);
            
            return redirect()->back()->with("success", "Demande mise à jour avec succès");
            
        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise à jour de la demande DG", [
                "demande_id" => $id,
                "error" => $e->getMessage()
            ]);
            
            return redirect()->back()->with("error", "Erreur lors de la mise à jour");
        }
    }
}
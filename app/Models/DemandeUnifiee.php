<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeUnifiee extends Model
{
    use HasFactory;
    
    protected $table = "demandes_unifiees";
    
    protected $fillable = [
        "tracking_code",
        "nom",
        "prenom", 
        "email",
        "telephone",
        "type_demande",
        "objet",
        "description",
        "adresse",
        "region",
        "urgence",
        "statut",
        "commentaire_admin",
        "traite_par",
        "date_traitement",
        "pj",
        "consentement",
        "ip_address",
        "user_agent"
    ];
    
    protected $casts = [
        "date_traitement" => "datetime",
        "consentement" => "boolean"
    ];
    
    public function traitePar()
    {
        return $this->belongsTo(User::class, "traite_par");
    }
    
    public function getFullNameAttribute()
    {
        return $this->nom . " " . $this->prenom;
    }
    
    public function getStatutBadgeAttribute()
    {
        $badges = [
            "en_attente" => "warning",
            "en_cours" => "info", 
            "approuvee" => "success",
            "rejetee" => "danger",
            "terminee" => "secondary"
        ];
        
        return $badges[$this->statut] ?? "secondary";
    }
}
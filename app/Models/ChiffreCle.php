<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiffreCle extends Model
{
    use HasFactory;

    protected $table = 'chiffres_cles';

    protected $fillable = [
        'icone',
        'titre',
        'valeur',
        'description',
        'couleur',
        'statut',
        'ordre'
    ];

    protected $casts = [
        'ordre' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope pour récupérer seulement les chiffres clés actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'Actif');
    }

    /**
     * Scope pour trier par ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre');
    }

    /**
     * Accessor pour obtenir le statut en français
     */
    public function getStatutFrancaisAttribute()
    {
        return $this->statut === 'Actif' ? 'Actif' : 'Inactif';
    }

    /**
     * Accessor pour obtenir la couleur avec # si nécessaire
     */
    public function getCouleurCompleteAttribute()
    {
        return str_starts_with($this->couleur, '#') ? $this->couleur : '#' . $this->couleur;
    }

    /**
     * Méthode pour activer un chiffre clé
     */
    public function activer()
    {
        $this->update(['statut' => 'Actif']);
    }

    /**
     * Méthode pour désactiver un chiffre clé
     */
    public function desactiver()
    {
        $this->update(['statut' => 'Inactif']);
    }

    /**
     * Méthode pour basculer le statut
     */
    public function basculerStatut()
    {
        $this->update(['statut' => $this->statut === 'Actif' ? 'Inactif' : 'Actif']);
    }
}

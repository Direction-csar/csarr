<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ChiffreCle extends Model
{
    use HasFactory;

    protected $table = 'chiffres_cles';
    
    /**
     * Vérifier si la table existe avant d'utiliser le modèle
     */
    public static function tableExists()
    {
        try {
            return Schema::hasTable('chiffres_cles');
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Méthode statique sécurisée pour récupérer les chiffres clés actifs
     * Retourne une collection vide si la table n'existe pas
     */
    public static function safeGetActifs()
    {
        try {
            if (!self::tableExists()) {
                return collect();
            }
            return self::actifs()->ordered()->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

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
        try {
            if (!self::tableExists()) {
                return $query->whereRaw('1 = 0'); // Retourne une requête vide
            }
            return $query->where('statut', 'Actif');
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une requête vide
            return $query->whereRaw('1 = 0');
        }
    }

    /**
     * Scope pour trier par ordre
     */
    public function scopeOrdered($query)
    {
        try {
            if (!self::tableExists()) {
                return $query; // Retourne la requête telle quelle
            }
            return $query->orderBy('ordre');
        } catch (\Exception $e) {
            // En cas d'erreur, retourner la requête telle quelle
            return $query;
        }
    }
    
    /**
     * Override de la méthode newQuery pour vérifier la table avant de créer une requête
     */
    public function newQuery()
    {
        try {
            if (!self::tableExists()) {
                // Créer une requête qui ne fera jamais de requête SQL en utilisant une condition impossible
                $query = parent::newQuery();
                // Utiliser whereRaw avec une condition toujours fausse pour éviter toute requête SQL
                return $query->whereRaw('1 = 0');
            }
            return parent::newQuery();
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une requête vide
            try {
                $query = parent::newQuery();
                return $query->whereRaw('1 = 0');
            } catch (\Exception $e2) {
                // Si même ça échoue, retourner une requête basique
                return parent::newQuery();
            }
        }
    }
    
    /**
     * Override de la méthode getTable pour éviter les erreurs si la table n'existe pas
     */
    public function getTable()
    {
        try {
            if (!self::tableExists()) {
                // Retourner un nom de table qui n'existe pas pour éviter les requêtes
                return 'chiffres_cles_empty';
            }
            return parent::getTable();
        } catch (\Exception $e) {
            return parent::getTable();
        }
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

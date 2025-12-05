<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'region',
        'telephone',
        'email',
        'responsable',
        'telephone_responsable',
        'email_responsable',
        'type',
        'statut',
        'capacite',
        'capacite_max',
        'capacite_utilisee',
        'unite_capacite',
        'date_creation',
        'description',
        'latitude',
        'longitude'
    ];

    protected $table = 'entrepots';

    protected $casts = [
        'capacite_max' => 'decimal:2',
        'capacite_utilisee' => 'decimal:2',
        'date_creation' => 'date'
    ];

    // Relations
    public function demandes()
    {
        return $this->hasMany(PublicRequest::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockMovementsDestination()
    {
        return $this->hasMany(StockMovement::class, 'entrepot_destination_id');
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    // Accessors
    public function getNomCompletAttribute()
    {
        return $this->nom . ' - ' . $this->ville;
    }

    public function getCapaciteFormateeAttribute()
    {
        return number_format($this->capacite_max, 0, ',', ' ') . ' m²';
    }
    
    public function getTauxOccupationAttribute()
    {
        if ($this->capacite_max > 0) {
            return round(($this->capacite_utilisee / $this->capacite_max) * 100, 2);
        }
        return 0;
    }
}

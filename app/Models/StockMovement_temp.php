<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'reference_number',
        'type',
        'produit',
        'quantite',
        'unite',
        'prix_unitaire',
        'total',
        'entrepot_id',
        'responsable',
        'motif',
        'date_mouvement',
        'fournisseur',
        'numero_facture',
        'destinataire',
        'numero_bon',
        'entrepot_destination_id',
        'numero_transfert',
        'raison_ajustement'
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'total' => 'decimal:2',
        'date_mouvement' => 'datetime'
    ];

    /**
     * Relation avec l'entrepôt
     */
    public function entrepot()
    {
        return $this->belongsTo(Warehouse::class, 'entrepot_id');
    }

    /**
     * Relation avec l'entrepôt de destination
     */
    public function entrepotDestination()
    {
        return $this->belongsTo(Warehouse::class, 'entrepot_destination_id');
    }

    /**
     * Relation avec le produit
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'produit', 'name');
    }

    /**
     * Scope pour filtrer par entrepôt
     */
    public function scopeForEntrepot($query, $entrepotId)
    {
        return $query->where('entrepot_id', $entrepotId);
    }

    /**
     * Scope pour filtrer par type de mouvement
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeInPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_mouvement', [$startDate, $endDate]);
    }

    /**
     * Scope pour filtrer par produit
     */
    public function scopeForProduit($query, $produit)
    {
        return $query->where('produit', $produit);
    }

    // Accessors
    public function getQuantiteFormateeAttribute()
    {
        return number_format($this->quantite, 0, ',', ' ') . ' ' . $this->unite;
    }

    public function getTotalFormateAttribute()
    {
        return number_format($this->total, 0, ',', ' ') . ' FCFA';
    }

    public function getTypeFormateAttribute()
    {
        return ucfirst($this->type);
    }
}


